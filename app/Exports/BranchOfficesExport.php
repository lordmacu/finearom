<?php 

namespace App\Exports;

use App\Models\BranchOffice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BranchOfficesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BranchOffice::with('client:id,client_name') // Eager load client with specific columns
            ->get()
            ->map(function ($branchOffice) {
                return [
                    'id' => $branchOffice->id,
                    'nit' => $branchOffice->nit,
                    'delivery_address' => $branchOffice->delivery_address,
                    'delivery_city' => $branchOffice->delivery_city,
                    'client_name' => $branchOffice->client->client_name ?? '', // Safeguard against null client
                    'operation' => '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'NIT',
            'Delivery Address',
            'Delivery City',
            'Client Name',
            'Operation',
        ];
    }
}
