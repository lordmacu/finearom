<?php 

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;
use App\Models\BranchOffice;
use Illuminate\Support\Facades\Hash;

class PurchaseOrderImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $cleaned_row = array_map(function ($value) {
            if (is_string($value)) {
                $value = trim($value);
                $value = preg_replace('/[\x{00A0}\x{200B}\x{FEFF}]/u', '', $value);
                $value = preg_replace('/[\r\n\t]/', ' ', $value);
                $value = preg_replace('/\s+/', ' ', $value);
                $value = strtolower($value);
            }
            return $value;
        }, $row);

        $client = Client::where('nit', $cleaned_row['nit'])->first();
        if(!$client){

            $client_name = ucwords($cleaned_row['cliente']);
            $executive = ucwords($cleaned_row['ejecutiva_de_cuenta']);
            $dispatch_confirmation_contact = ucwords($cleaned_row['contacto_confirmacion_despacho']);
            $accounting_contact = strtolower($cleaned_row['contacto_cartera_contabilidad']);
            $delivery_address = strtolower($cleaned_row['direccion_de_entrega_1']);
            $delivery_city = strtolower($cleaned_row['ciudad_entrega_1']);
            $registration_address = strtolower($cleaned_row['direccion_de_radicacion']);
            $registration_city = ucwords($cleaned_row['ciudad_radicacion']);
            $status = $cleaned_row['estado_cliente'] === 'activo' ? 'active' : 'inactive';

            if ($client_name) {

                $user = User::create([
                    'name' => $client_name,
                    'email' => $cleaned_row['correo_confirmacion_despachos'] ?? $cleaned_row['contacto_cartera_contabilidad'],
                    'password' => Hash::make($cleaned_row['nit']),
                ]);
                

            $user->assignRole('order-creator');

            $client = Client::create([
                'status' => $status,
                'user_id' => $user->id,
                'client_name' => $client_name,
                'nit' => $cleaned_row['nit'],
                'executive' => $executive,
                'accounting_contact_email' => $cleaned_row['contacto_cartera_contabilidad'],
                'executive_email' => $cleaned_row['correo_ejecutiva'],
                'supplier_location' => ucwords($cleaned_row['maquilador_sede']),
                'dispatch_confirmation_contact' => $dispatch_confirmation_contact,
                'accounting_contact' => $accounting_contact,
                'dispatch_confirmation_email' => $cleaned_row['correo_confirmacion_despachos'],
                'email' => $cleaned_row['correo_confirmacion_despachos'],
                'delivery_address' => $delivery_address,
                'delivery_city' => $delivery_city,
                'registration_address' => $registration_address,
                'registration_city' => $registration_city,
                'address' => $delivery_address,
                'city' => $delivery_city,
                'phone' => $cleaned_row['telefono'],
                'shipping_notes' => $cleaned_row['observaciones_de_envio'],
                'commercial_terms' => $cleaned_row['condiciones_comerciales'],
                'commercial_conditions' => $cleaned_row['condiciones_comerciales'],
                'billing_closure' => $cleaned_row['cierre_de_facturacion'],
                'trm' => $cleaned_row['trm'],
                'proforma_invoice' => $cleaned_row['factura_proforma'] === 'si' ? true : false,
                'payment_day' => $cleaned_row['plazo_credito'],
                'client_type' => 'none',
            ]);

            // Crear la sucursal principal con nombres en "Title Case"
            BranchOffice::create([
                'client_id' => $client->id,
                'nit' => $client->nit,
                'delivery_city' => $delivery_city,
                'delivery_address' => $delivery_address,
                'name' => $delivery_city,
            ]);

            // Crear sucursales adicionales si existen
            for ($i = 2; $i <= 8; $i++) {
                    $city_key = 'ciudad_' . $i;
                    $address_key = 'direccion_' . $i;

                if (!empty($cleaned_row[$city_key]) && !empty($cleaned_row[$address_key])) {
                    BranchOffice::create([
                        'client_id' => $client->id,
                        'nit' => $client->nit,
                        'delivery_city' => ucwords($cleaned_row[$city_key]),
                        'delivery_address' => ucwords($cleaned_row[$address_key]),
                        'name' => ucwords($cleaned_row[$city_key]),
                    ]);
                }
            }
        }
    }
}
}
