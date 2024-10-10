<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\PurchaseOrdersImport;
use App\Mail\PurchaseOrderMail;
use App\Mail\PurchaseOrderStatusChangedMail;
use Illuminate\Support\Facades\Auth;

use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\BranchOffice;
use App\Models\Client;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Rules\UniqueOrderConsecutiveForClient;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use Maatwebsite\Excel\Facades\Excel;


use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\EventListener\MessageListener;
use App\Listeners\MessageIdListener;
use App\Mail\PurchaseOrderMailDespacho;
use App\Models\PurchaseOrderProduct;
use Symfony\Component\Mailer\Event\MessageEvent;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

use App\Models\Process;
use Symfony\Component\Mime\Address;

use Illuminate\Support\Facades\Cache;


class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $exchange = $this->getExchangeRate();

        // Obtén los filtros del request
        $clientId = $request->input('client_id');
        $creationDateRange = $request->input('creation_date');
        $deliveryDateRange = $request->input('delivery_date');
        $orderConsecutive = $request->input('order_consecutive');
        $status = $request->input('status');
        $sortBy = $request->input('sort_by', 'id'); // Campo por defecto para ordenar
        $sortOrder = $request->input('sort_order', 'desc'); // Orden por defecto

        // Construye la consulta
        $query = PurchaseOrder::with('branchOffice.client', 'products');

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        if ($creationDateRange) {
            $dates = explode(' - ', $creationDateRange);
            if (count($dates) === 2) {
                $query->whereBetween('order_creation_date', [date('Y-m-d', strtotime($dates[0])), date('Y-m-d', strtotime($dates[1]))]);
            }
        }

        if ($deliveryDateRange) {
            $dates = explode(' - ', $deliveryDateRange);
            if (count($dates) === 2) {
                $query->whereBetween('required_delivery_date', [date('Y-m-d', strtotime($dates[0])), date('Y-m-d', strtotime($dates[1]))]);
            }
        }

        if ($orderConsecutive) {
            $query->where('order_consecutive', 'like', '%' . $orderConsecutive . '%');
        }

        if ($status) {
            $query->where('status', $status);
        }

        // Agrega la subconsulta para calcular el total
        $query->selectRaw('purchase_orders.*, (
            SELECT SUM(products.price * purchase_order_product.quantity)
            FROM purchase_order_product
            JOIN products ON products.id = purchase_order_product.product_id
            WHERE purchase_order_product.purchase_order_id = purchase_orders.id
        ) as total_sum');

        // Ordenar por el total si es solicitado
        if ($sortBy === 'total') {
            $query->orderBy('total_sum', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $purchaseOrders = $query->paginate(50); // Paginación de 10 elementos por página
        $clients = Client::all(); // Para poblar el dropdown de clientes

        return view('admin.purchase_order.index', compact('purchaseOrders', 'clients', 'sortBy', 'sortOrder'));
    }

    private function getExchangeRate()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $cacheFile = "exchange_rate_{$currentDate}.json";

        // Verifica si el valor ya está en el almacenamiento
        if (Storage::disk('local')->exists($cacheFile)) {
            $cachedData = json_decode(Storage::disk('local')->get($cacheFile), true);
            if ($cachedData['date'] === $currentDate) {
                return $cachedData['exchangeRate'];
            }
        }

        // Si no existe en el almacenamiento, haz la solicitud a la API
        $client = new HttpClient();
        $url = "https://openexchangerates.org/api/time-series.json?app_id=f2884227901c482194f0fbfe7fa77c63&start={$currentDate}&end={$currentDate}&symbols=USD&base=COP";

        try {
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody()->getContents(), true);
            $exchangeRate = $data['rates'][$currentDate]['USD'];

            // Guardar en caché
            Storage::disk('local')->put($cacheFile, json_encode(['exchangeRate' => $exchangeRate, 'date' => $currentDate]));

            return $exchangeRate;
        } catch (\Exception $e) {
            return null; // o algún valor predeterminado o manejar el error según tus necesidades
        }
    }

    public function create()
    {
        $clients = Client::all();
        $exchange = $this->getExchangeRate();

        return view('admin.purchase_order.create', compact('clients', 'exchange'));
    }

    /**
     * Delete the attachment of a purchase order.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAttachment($id)
    {
        try {
            $purchaseOrder = PurchaseOrder::findOrFail($id);

            if ($purchaseOrder->attachment) {
                // Delete the file from the storage
                Storage::disk('public')->delete($purchaseOrder->attachment);

                // Remove the attachment path from the database
                $purchaseOrder->attachment = null;
                $purchaseOrder->save();
            }

            return response()->json([
                'message' => 'Attachment deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete attachment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function import(Request $request){
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx',
        ]);

        Excel::import(new PurchaseOrdersImport, $request->file('excel_file'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'required_delivery_date' => 'required|date',
                'trm' => 'required',
                'observations' => 'nullable|string',
                'contact' => 'required|string|max:255',
                'phone' => 'required|string',
                'status' => 'required|in:pending,processing,completed,cancelled',
                'products' => 'required|array',
                'products.*.product_id' => 'required|exists:products,id',
                'attachment' => 'nullable|file|mimes:pdf|max:2048',
                'products.*.branch_office_id' => 'required',
                'products.*.quantity' => 'required|integer|min:1',
            ]);

            $currentDate = Carbon::now()->format('Y-m-d');
            $formattedDeliveryDate = Carbon::createFromFormat('d-m-Y', $request->required_delivery_date)->format('Y-m-d');

            $purchaseOrderData = $request->only(
                'client_id',
                'branch_office_id',
                'required_delivery_date',
                'order_consecutive',
                'observations',
                'contact',
                'phone',
                'status',
                'trm'
            );

            $purchaseOrderData['required_delivery_date'] = $formattedDeliveryDate; // Update formatted delivery date


            $filePath = null;  // Initialize the filePath as null

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filePath = $file->store('attachments', 'public');
                $purchaseOrderData['attachment'] = $filePath;
            }

            $purchaseOrderData['order_creation_date'] = $currentDate;

            if ($request->get('trm_initial') != $request->get('trm')) {
                $purchaseOrderData['trm_updated_at'] = $currentDate;
            }

            $purchaseOrder = PurchaseOrder::create($purchaseOrderData);
            $purchaseOrder->order_consecutive = $purchaseOrder->id . '-' . $purchaseOrder->order_consecutive;
            $purchaseOrder->save();
            foreach ($request->products as $product) {
                $purchaseOrder->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'branch_office_id' => $product['branch_office_id'],
                ]);
            }

            $pdfContent = $this->generatePdfOrder($purchaseOrder);


            $clientEmail = $purchaseOrder->client->email;

         //   $clientEmail = 'yocristiangarciaco@gmail.com'; //demo


            $executiveEmail = $purchaseOrder->client->executive_email;
            $coordinator = 'monica.castano@finearom.com';

            $ccEmails = Process::where('process_type', 'pedido')
                ->pluck('email')
                ->toArray();
            
            $ccEmails = array_merge($ccEmails, [$executiveEmail, $coordinator]);

           /* Mail::to(explode(',', $clientEmail))
                ->cc($ccEmails)
                ->send(new PurchaseOrderMail($purchaseOrder, $pdfContent));*/

            $ccEmails = Process::where('process_type', 'orden_de_compra')
                ->pluck('email')
                ->toArray();
            $ccEmails = array_merge($ccEmails, [$executiveEmail, $coordinator]);

           /* Mail::to(explode(',', $executiveEmail))
                ->cc($ccEmails)
                ->send(new PurchaseOrderMailDespacho($purchaseOrder, $filePath));*/

            return response()->json([
                'message' => 'Purchase Order created successfully.',
                'purchase_order_id' => $purchaseOrder->id,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating Purchase Order: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while creating the purchase order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getClientBranchOffices($clientId)
    {
        $branchOffices = BranchOffice::where('client_id', $clientId)->get();
        return response()->json($branchOffices);
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $clients = Client::all();
        $branchOffices = BranchOffice::where('client_id', $purchaseOrder->client_id)->get();
        $products = Product::where('client_id', $purchaseOrder->client_id)->get();
        $purchaseOrderProducts = PurchaseOrderProduct::where('purchase_order_id', $purchaseOrder->id)->get();
        $exchange = $this->getExchangeRate();
        $client = Client::find($purchaseOrder->client_id);
        $title = "editar";

        return view('admin.purchase_order.edit', compact('title', 'purchaseOrderProducts', 'purchaseOrder', 'clients', 'branchOffices', 'products', 'exchange', 'client'));
    }


    public function updateStatus(Request $request, $id)
    {


        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $oldStatus = $purchaseOrder->status;

        $purchaseOrder->status = $request->input('status');

        // Solo procesar los datos adicionales si el estado es 'completed' o 'parcial'
        if (in_array($purchaseOrder->status, ['completed', 'parcial'])) {
            $purchaseOrder->invoice_number = $request->input('invoice_number');
            $purchaseOrder->dispatch_date = $request->input('dispatch_date');
            $purchaseOrder->tracking_number = $request->input('tracking_number');
           // $purchaseOrder->observations_extra = $request->input('observations');
    
            $newObservation = $request->input('observations');
            $currentObservations = $purchaseOrder->observations_extra ?? '';
        
            $userName = Auth::user()->name;
    
            // Agregar separador, timestamp y nombre de usuario
            $separator = "\n---\n";
            $timestamp = now()->format('Y-m-d H:i:s');
        
            // Construir la nueva entrada de observación
            $observationEntry = $separator . $timestamp . " - " . $userName . "\n" . $newObservation;
        
            // Concatenar la nueva observación con las existentes
            $purchaseOrder->observations_extra = $observationEntry . $currentObservations;

            $this->sendStatusChangedEmailsCompleteAndPartial($purchaseOrder);
        } else {
            $this->sendStatusChangedEmails($purchaseOrder, $oldStatus);
        }

        $purchaseOrder->save();

        return redirect()->route('admin.purchase_orders.index')->with('message', 'Estado de la orden actualizado exitosamente.');
    }


    public function addObservation(Request $request)
    {

        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'new_observation' => 'required|string',
        ]);
    
        $purchaseOrder = PurchaseOrder::findOrFail($request->input('purchase_order_id'));
    
        $newObservation = $request->input('new_observation');
        $currentObservations = $purchaseOrder->observations_extra ?? '';
    
        $userName = Auth::user()->name;

        // Agregar separador, timestamp y nombre de usuario
        $separator = "\n---\n";
        $timestamp = now()->format('Y-m-d H:i:s');
    
        // Construir la nueva entrada de observación
        $observationEntry = $separator . $timestamp . " - " . $userName . "\n" . $newObservation;
    
        // Concatenar la nueva observación con las existentes
        $purchaseOrder->observations_extra = $observationEntry . $currentObservations;
    
        $purchaseOrder->save();
    
        return redirect()->back()->with('success', 'Observación agregada exitosamente.');
    }
    

    public function sendStatusChangedEmailsCompleteAndPartial(PurchaseOrder $purchaseOrder)
    {
        $clientEmail = $purchaseOrder->client->email;
        //$clientEmail = 'yocristiangarciaco@gmail.com'; //demo

        // Recuperar el `message_id` desde el storage
        $messageId = $purchaseOrder->message_id;

        $transport = Transport::fromDsn(env('MAILER_DSN'));
        $mailer = new Mailer($transport);

        $ccEmails = Process::where('process_type', 'pedido')
            ->pluck('email')
            ->toArray();

        $ccEmailsString = implode(',', $ccEmails);

        $fromEmail = env('MAIL_USERNAME_FACTURACION');

        $executiveEmail = $purchaseOrder->client->executive_email;

        $ccEmailsString = $executiveEmail . ',' . $ccEmailsString;

        $email = (new Email())
            ->from($fromEmail)
            ->to($clientEmail)
            ->cc($ccEmailsString)
            ->subject('Re: Orden de Compra - ' . $purchaseOrder->order_consecutive)
            ->html(view('emails.purchase_order_email_complete_partial', ['purchaseOrder' => $purchaseOrder])->render());

        if ($messageId) {
            $email->getHeaders()->addTextHeader('In-Reply-To', '<' . $purchaseOrder->message_id . '>');
            $email->getHeaders()->addTextHeader('References', '<' . $purchaseOrder->message_id . '>');
        }

      //  $mailer->send($email);
    }


    public function sendStatusChangedEmails(PurchaseOrder $purchaseOrder)
    {

        $messageId = $purchaseOrder->message_id;


        $transport = Transport::fromDsn(env('MAILER_DSN'));
        $mailer = new Mailer($transport);
        $fromEmail = env('MAIL_USERNAME_FACTURACION');

        $ccEmails = Process::where('process_type', 'pedido')
            ->pluck('email')
            ->toArray();
        $clientEmail = $purchaseOrder->client->email;
     //   $clientEmail = 'yocristiangarciaco@gmail.com'; //demo

        $ccEmailsString = implode(', ', $ccEmails);
        $executiveEmail = $purchaseOrder->client->executive_email;

        $ccEmailsString = $executiveEmail . ', ' . $ccEmailsString;
        $ccAddresses = array_map(function($email) {
            return new Address($email);
        }, $ccEmails); 
        
        // Si el correo del ejecutivo es válido, añadirlo al array
        if (filter_var($executiveEmail, FILTER_VALIDATE_EMAIL)) {
            array_unshift($ccAddresses, new Address($executiveEmail)); // Añadir al inicio del array
        }
        
        

        $email = (new Email())
            ->from($fromEmail)
            ->cc(...$ccAddresses)  // Desempaquetar el array para pasarlo como argumentos separados
            ->to($clientEmail)
            ->subject('Re: Orden de Compra - ' . $purchaseOrder->order_consecutive)
            ->html(view('emails.purchase_order_status_changed_plain', ['purchaseOrder' => $purchaseOrder])->render());

        if ($messageId) {
            $email->getHeaders()->addTextHeader('In-Reply-To', '<' . $purchaseOrder->message_id . '>');
            $email->getHeaders()->addTextHeader('References', '<' . $purchaseOrder->message_id . '>');
        }

        // Enviar el mensaje
    //    $mailer->send($email);
    }

    public function update(Request $request, $purchaseOrderId)
    {
        try {
            $purchaseOrder = PurchaseOrder::find($purchaseOrderId);

            $request->validate([
                'required_delivery_date' => 'required|date',
                'trm' => 'required',
                'order_consecutive' => [
                    'required',
                    'string',
                    new UniqueOrderConsecutiveForClient($request->client_id, $purchaseOrder->id),
                ],
                'observations' => 'nullable|string',
                'status' => 'required|in:pending,processing,completed,cancelled', // Validación del estado
                'products' => 'required|array',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'contact' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'attachment' => 'nullable|file|mimes:pdf|max:2048',
            ]);


            $purchaseOrder->update([
                'required_delivery_date' => $request->required_delivery_date,
                'trm' => $request->trm,
                'order_consecutive' => $request->order_consecutive,
                'contact' => $request->contact,
                'phone' => $request->phone,
                'observations' => $request->observations,
                'status' => $request->status,
            ]);

            // Handle the attachment file
            if ($request->hasFile('attachment')) {
                // Delete the old attachment if it exists
                if ($purchaseOrder->attachment) {
                    Storage::disk('public')->delete($purchaseOrder->attachment);
                }

                // Store the new attachment
                $file = $request->file('attachment');
                $filePath = $file->store('attachments', 'public');
                $purchaseOrder->update(['attachment' => $filePath]);
            }


            $purchaseOrder->products()->detach();

            foreach ($request->products as $product) {
                $purchaseOrder->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'branch_office_id' => $product['branch_office_id'],
                ]);
            }

            return response()->json([
                'message' => 'Purchase Order created successfully.',
                'purchase_order_id' => $purchaseOrder->id,
            ], 201);
        } catch (ValidationException $e) {
            // Handle validation exception
            return response()->json([
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Error creating Purchase Order: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while creating the purchase order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();

        return redirect()->route('admin.purchase_orders.index')->with('message', 'Purchase Order deleted successfully.');
    }

    public function getClientProducts($clientId)
    {
        // Definir un key único para la cache basada en el ID del cliente
        $cacheKey = "client_products_{$clientId}";

        // Cachear la respuesta indefinidamente
        $products = Cache::rememberForever($cacheKey, function () use ($clientId) {
            return Product::where('client_id', $clientId)->get();
        });

        return response()->json($products);
    }
    public function show(PurchaseOrder $purchaseOrder)
    {
        $exchange = $this->getExchangeRate();

        return view('admin.purchase_order.show', compact('purchaseOrder', 'exchange'));
    }

    public function generatePdfOrder($purchaseOrder)
    {
        $exchange = $this->getExchangeRate();

        $subtotal = $purchaseOrder->products->sum(function ($product) use ($exchange) {
            return $product->price * $product->pivot->quantity;
        });
        $subtotal = $subtotal / $purchaseOrder->trm;

        $iva = $subtotal * 0.19;
        $reteica = $subtotal * 0.00966;
        $total = $subtotal + $iva + $reteica;

        $pdf = PDF::loadView('admin.purchase_order.pdf', compact('purchaseOrder', 'exchange', 'subtotal', 'iva', 'reteica', 'total'));

        return $pdf->output();
    }

    public function showPdf($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        // Generar el contenido del PDF
        $pdfContent = $this->generatePdfOrder($purchaseOrder);

        // Mostrar el PDF en el navegador
        return response($pdfContent)->header('Content-Type', 'application/pdf');
    }

    public function storeClientPurchase(Request $request)
    {
        $request->validate([
            'branch_office_id' => 'required|exists:branch_offices,id',
            'required_delivery_date' => 'required|date|after:today',
            'order_consecutive' => 'required|string|unique:purchase_orders,order_consecutive',
            'delivery_address' => 'required|string',
            'observations' => 'nullable|string',
            'status' => 'required|in:active,inactive,processing', // Validación del estado
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $purchaseOrder = new PurchaseOrder();
        $purchaseOrder->client_id = $request->user()->client->id;
        $purchaseOrder->branch_office_id = $request->branch_office_id;
        $purchaseOrder->order_creation_date = now(); // Setting the order creation date to the current date
        $purchaseOrder->required_delivery_date = $request->required_delivery_date;
        $purchaseOrder->order_consecutive = $request->order_consecutive;
        $purchaseOrder->delivery_address = $request->delivery_address;
        $purchaseOrder->observations = $request->observations;
        $purchaseOrder->status = $request->status; // Guardar el estado
        $purchaseOrder->save();

        foreach ($request->products as $product) {
            $purchaseOrder->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
                'price' => Product::find($product['product_id'])->price, // Assuming you have a price field in the products table
            ]);
        }

        return redirect()->route('purchase_order.dashboard')->with('message', 'Orden de compra creada exitosamente.');
    }
}
