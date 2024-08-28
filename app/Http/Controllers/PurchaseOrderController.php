<?php

namespace App\Http\Controllers;

use App\Models\BranchOffice;
use App\Models\Client;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Storage;
use TCPDF;

class PurchaseOrderController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('can:view purchase order dashboard')->only('showDashboard');
        $this->middleware('can:create purchase order')->only('storeClientPurchase');
        $this->middleware('can:view purchase order history')->only('showHistory');
        $this->middleware('can:edit purchase order')->only(['edit', 'updateClientPurchase']);
        $this->middleware('can:delete purchase order')->only('destroy');
    }
 
    public function showDashboard(Request $request)
    {
        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->first();
        $branchOffices = BranchOffice::where('client_id', $client->id)->get();
        $products = Product::where('client_id', $client->id)->get();

        $exchange = $this->getExchangeRate();
        
        return view('purchase_order.dashboard', compact('client', 'branchOffices', 'products', 'exchange'));
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

    public function storeClientPurchase(Request $request)
    {
        $request->validate([
            'branch_office_id' => 'required|exists:branch_offices,id',
            'required_delivery_date' => 'required|date|after:today',
            'order_consecutive' => 'required|string|unique:purchase_orders,order_consecutive',
            'delivery_address' => 'required|string',
            'observations' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->first();

        $purchaseOrder = new PurchaseOrder();
        $purchaseOrder->client_id = $client->id;
        $purchaseOrder->branch_office_id = $request->branch_office_id;
        $purchaseOrder->order_creation_date = now();
        $purchaseOrder->required_delivery_date = $request->required_delivery_date;
        $purchaseOrder->order_consecutive = $request->order_consecutive;
        $purchaseOrder->delivery_address = $request->delivery_address;
        $purchaseOrder->observations = $request->observations;
        $purchaseOrder->save();

        foreach ($request->products as $product) {
            $purchaseOrder->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
                'price' => Product::find($product['product_id'])->price,
            ]);
        }

        $pdf = Pdf::loadView('admin.purchase_order.pdf', compact('purchaseOrder'));
        return $pdf->download('invoice.pdf');
        return redirect()->route('purchase_order.dashboard')->with('message', 'Orden de compra creada exitosamente.');
    }

    public function showHistory(Request $request)
    {
        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->first();
        $purchaseOrders = PurchaseOrder::where('client_id', $client->id)->get();
        $hora = date("H:i:s");
        $currentRouteName = \Route::currentRouteName();
        $search = $request->input('search');
        $paginate = $request->input('paginate', 5);

        $purchaseOrders = PurchaseOrder::with('client')
            ->whereHas('client', function ($query) use ($search) {
                $query->where('client_name', 'like', "%{$search}%");
            })
            ->orWhere('order_consecutive', 'like', "%{$search}%")
            ->paginate($paginate);

        return view('purchase_order.history', compact('purchaseOrders', 'hora', 'currentRouteName', 'search', 'paginate'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->first();
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $branchOffices = BranchOffice::where('client_id', $client->id)->get();
        $products = Product::where('client_id', $client->id)->get();
        $exchange = $this->getExchangeRate();

        return view('purchase_order.edit', compact('purchaseOrder', 'branchOffices', 'products', 'client','exchange'));
    }

    public function updateClientPurchase(Request $request, $id)
    {
        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->first();
        $purchaseOrder = PurchaseOrder::where('client_id', $client->id)->findOrFail($id);

        $request->validate([
            'branch_office_id' => 'required|exists:branch_offices,id',
            'required_delivery_date' => 'required|date|after:today',
            'order_consecutive' => 'required|string|unique:purchase_orders,order_consecutive,' . $purchaseOrder->id,
            'delivery_address' => 'required|string',
            'observations' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $purchaseOrder->update([
            'branch_office_id' => $request->branch_office_id,
            'required_delivery_date' => $request->required_delivery_date,
            'order_consecutive' => $request->order_consecutive,
            'delivery_address' => $request->delivery_address,
            'observations' => $request->observations,
        ]);

        $purchaseOrder->products()->detach();

        foreach ($request->products as $product) {
            $purchaseOrder->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
            ]);
        }

        return redirect()->route('purchase_order.history')->with('message', 'Orden de compra actualizada exitosamente.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->first();
        $purchaseOrder = PurchaseOrder::with(['client', 'branchOffice', 'products'])->findOrFail($id);

        return view('purchase_order.show', compact('purchaseOrder', 'client'));
    }
}
