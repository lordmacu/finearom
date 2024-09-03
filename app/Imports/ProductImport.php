<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    protected $errors = []; // Array para almacenar errores

    public function model(array $row)
    {
        try {
            if ($row['estado'] != "INACTIVA" && $row['rev'] != "delete") {
                // Buscar el cliente por NIT
                $client = Client::where('nit', $row['nit'])->first();
    
                // Si no se encuentra el cliente, lanzar una excepción
                if (!$client) {
                    throw new \Exception($row['nit']);
                }
    
                // Verificar si el producto ya existe por código
                $existingProduct = Product::where('code', $row['nit_cod'])->first();
    
                if (!$existingProduct) {
                    // Crear el producto asociado al cliente si no existe
                    Product::create([
                        'code' => $row['nit_cod'],
                        'product_name' => $row['referencia_codigo'],
                        'price' => $row['price'] ?? 0,
                        'client_id' => $client->id,
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Si ocurre un error, añadirlo al array de errores
            $this->errors[] = ['product' => $row['referencia_codigo'], 'nit_cod' => $row['nit_cod'], 'nit' => $row['nit'], 'error' => $e->getMessage()];
        }
    }
    

    // Método para obtener los errores
    public function getErrors()
    {
        return $this->errors;
    }
}
