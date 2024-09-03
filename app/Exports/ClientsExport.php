<?php 
namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsExport implements FromCollection, WithHeadings
{
    protected $maxBranchOffices = 0;

    public function __construct()
    {
        // Determine the maximum number of branch offices any client has
        $this->maxBranchOffices = Client::with('branchOffices')
            ->get()
            ->map(function ($client) {
                return $client->branchOffices->count();
            })->max();
    }

    public function collection()
    {
        $clients = Client::with('branchOffices')->get();

        // Map the client data to include branch office details
        return $clients->map(function ($client) {
            $clientData = [
                'client_name' => $client->client_name,
                'nit' => $client->nit,
                'client_type' => $client->client_type,
                'payment_method' => $client->payment_method,
                'email' => $client->email,
                'executive' => $client->executive,
                'executive_email' => $client->executive_email,
                'address' => $client->address,
                'city' => $client->registration_city,
                'billing_closure' => $client->billing_closure,
                'commercial_terms' => $client->commercial_terms,
                'proforma_invoice' => $client->proforma_invoice,
                'payment_day' => $client->payment_day,
                'status' => $client->status,
                'dispatch_confirmation_contact' => $client->dispatch_confirmation_contact,
                'accounting_contact' => $client->accounting_contact,
                'accounting_contact_email' => $client->accounting_contact_email,
                'dispatch_confirmation_email' => $client->dispatch_confirmation_email,
                'registration_address' => $client->registration_address,
                'registration_city' => $client->registration_city,
                'phone' => $client->phone,
                'shipping_notes' => $client->shipping_notes,
                'user_id' => $client->user_id,
                'trm' => $client->trm,
                'commercial_conditions' => $client->commercial_conditions,
                'operation' => '',
            ];

            return $clientData;
        });
    }

    public function headings(): array
    {
        $headings = [
            'Client Name',
            'NIT',
            'Client Type',
            'Payment Method',
            'Email',
            'Executive',
            'Executive Email',
            'Address',
            'City',
            'Billing Closure',
            'Commercial Terms',
            'Proforma Invoice',
            'Payment Day',
            'Status',
            'Dispatch Confirmation Contact',
            'Accounting Contact',
            'Accounting Contact Email',
            'Dispatch Confirmation Email',
            'Registration Address',
            'Registration City',
            'Phone',
            'Shipping Notes',
            'User ID',
            'TRM',
            'Commercial Conditions',
            'Operation',
        ];

        return $headings;
    }
}
