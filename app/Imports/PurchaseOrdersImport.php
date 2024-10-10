<?php

namespace App\Imports;

use App\Models\BranchOffice;
use App\Models\Client;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Jobs\BackupDatabaseJob; // Importar el Job

class PurchaseOrdersImport implements ToCollection, WithHeadingRow
{
    private array $missingProducts = [];
    private array $createdOrders = [];
    private array $createdProducts = [];
    private array $requiredColumns = ['orden_de_compra', 'nit', 'fecha_de_despacho', 'fecha_de_solicitud', 'codigo', 'cantidad'];

    public function collection(Collection $rows)
    {
        dispatch_sync(new BackupDatabaseJob()); // Despachar el Job sincrónicamente
        // Validar que todas las columnas requeridas estén presentes
        $missingColumns = array_diff($this->requiredColumns, array_keys($rows->first()->toArray()));
        if (!empty($missingColumns)) {
            echo "<p style='color:red;'>Faltan las siguientes columnas importantes en el Excel: " . implode(', ', $missingColumns) . "</p>";
            return;
        }

        $orders = $rows->groupBy('orden_de_compra');

        foreach ($orders as $orderConsecutive => $orderRows) {
            try {
                // Procesar los datos y crear la orden de compra
                $this->createOrUpdatePurchaseOrder($orderRows, $orderConsecutive);
            } catch (ValidationException $e) {
                dd($e);
                // Manejar errores de validación
                Log::error('Error de validación en la importación: ' . json_encode($e->errors()));
            } catch (\Exception $e) {
                dd("aqui ", $e);
                // Manejar otros errores
                Log::error('Error al crear la orden de compra: ' . $e->getMessage());
            }
        }

        // Reportar productos faltantes al final del proceso
        if (!empty($this->missingProducts)) {
            Log::warning('Productos no encontrados durante la importación: ' . implode(', ', $this->missingProducts));
        }

        
        echo '<a href="/admin/purchase_orders">volver a la página anterior</a>';

        // Mostrar las órdenes creadas y sus productos en formato de tabla
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Orden de Compra</th><th>Cliente ID</th><th>Fecha de Entrega</th><th>Productos</th><th>Productos Creados</th><th>Acción</th></tr>";
        foreach ($this->createdOrders as $order) {
            echo "<tr>";
            echo "<td>{$order['order_consecutive']}</td>";
            echo "<td>{$order['client_id']}</td>";
            echo "<td>{$order['required_delivery_date']}</td>";
            echo "<td>";
            if (!empty($order['products'])) {
                echo "<table border='0' style='border-collapse: collapse;'>";
                foreach ($order['products'] as $product) {
                    echo "<tr><td>producto: {$product['name']}</td> <td>precio: {$product['price']}</td> <td>Cantidad: {$product['quantity']}<br></td></tr>";
                }
                echo "</table>";
            } else {
                echo "No existen productos";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($order['created_products'])) {
                foreach ($order['created_products'] as $createdProduct) {
                    echo "{$createdProduct}<br>";
                }
            } else {
                echo "No hay productos creados";
            }
            echo "</td>";
            echo "<td>{$order['action']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    private function transformDate($value, $format = 'Y-m-d')
    {
        try {
            if (is_numeric($value)) {
                // Si es numérico, convertir desde formato de fecha de Excel
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format($format);
            } else {
                // Intentar parsear la cadena utilizando Carbon
                return Carbon::createFromFormat('Y-m-d', $value)->format($format);
            }
        } catch (\Exception $e) {
            dd($e);
            // Manejar el error o retornar null si no se puede convertir
            return null;
        }
    }

    private function createOrUpdatePurchaseOrder(Collection $orderRows, $orderConsecutive)
    {
        $firstRow = $orderRows->first()->toArray();
        $client = Client::where('nit', $firstRow['nit'])->first();
        $client_id = $client->id;

        $formattedDeliveryDate = $this->transformDate($firstRow['fecha_de_despacho']);
        $orderCreation = $this->transformDate($firstRow['fecha_de_solicitud']);

        // Buscar si ya existe una orden de compra con el mismo consecutivo interno y orden de compra (usando LIKE)
        $existingOrder = PurchaseOrder::where('order_consecutive', 'like', '%' . $firstRow['orden_de_compra'])->first();
        $action = 'Creado';
        if ($existingOrder) {
            // Eliminar la orden de compra existente
            $existingOrder->products()->detach();
            $existingOrder->delete();
            $action = 'Modificado';
        }

        $status=$firstRow['estado'];
        $statusDb="processing";

        if($status == 'cancelado'){
            $statusDb = "cancelled";
        }
        
        if($status == 'completado'){
            $statusDb = "completed";
        }
        
        if($status == 'pendiente'){
            $statusDb = "pending";
        }
        
        //'pending','processing','completed','cancelled','parcial_status'
        // Crear una nueva orden de compra
        $purchaseOrderData = [
            'client_id' => $client_id,
            'required_delivery_date' => $formattedDeliveryDate,
            'observations' => $firstRow['observaciones'] ?? null,
            'contact' => $client->accounting_contact,
            'phone' => $client->phone,
            'status' => $statusDb,
            'trm' => $firstRow['trm'] ,
            'order_creation_date' => $orderCreation,
            'order_consecutive' => $firstRow['orden_de_compra'],
        ];
  
        $purchaseOrder = PurchaseOrder::create($purchaseOrderData);
        $purchaseOrder->order_consecutive = $purchaseOrder->id . '-' . $purchaseOrder->order_consecutive;

        $products = [];
        $missingProducts = [];
        $createdProducts = [];

        foreach ($orderRows as $row) {
            $row = $row->toArray();

            $product = Product::where('code', $row['nit'].$row['codigo'])->first();

            if (!$product) {
                // Crear el producto si no existe
                $product = Product::create([
                    'code' => $row['nit'].$row['codigo'],
                    'product_name' => $row['nombre_producto'] ?? 'Producto Desconocido',
                    'price' => $row['precio_unitario'] ?? 0,
                    'client_id' => $client_id,
                ]);
                $createdProducts[] = $row['nit'].$row['codigo'];
            }

            $product_id = $product->id;
            $product_name = $product->product_name;
            $price = $row['precio_unitario'] ?? 0;
            $quantity = $row['cantidad'];
            $branch_office = BranchOffice::where('client_id', $client_id)->first();

            if ($branch_office) {
                $purchaseOrder->products()->attach($product_id, [
                    'quantity' => $quantity,
                    'branch_office_id' => $branch_office->id,
                ]);

                $products[] = [
                    'name' => $product_name,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }
        }

        // Guardar la orden creada y sus productos para informar al final
        $this->createdOrders[] = [
            'order_consecutive' => $purchaseOrder->order_consecutive,
            'client_id' => $client_id,
            'required_delivery_date' => $formattedDeliveryDate,
            'products' => $products,
            'missing_products' => $missingProducts,
            'created_products' => $createdProducts,
            'action' => $action,
        ];
    }
}
