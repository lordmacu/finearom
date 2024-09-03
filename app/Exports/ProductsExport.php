<?php 

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with('client:id,nit,client_name')
            ->get()
            ->map(function ($product) {
                return [
                    'code' => $product->code,
                    'product_name' => $product->product_name,
                    'price' => $product->price,
                    'client_nit' => $product->client->nit,
                    'client_name' => $product->client->client_name,
                    'operation' => '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Code',
            'Product Name',
            'Price',
            'Client NIT',
            'Client Name',
            'Operation'
        ];
    }
}
