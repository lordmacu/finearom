<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\Hash; // Import the Hash facade
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Create the client
        $client = new Client([
            'client_name' => $row['client_name'],
            'nit' => $row['nit'],
            'client_type' => $row['client_type'],
            'payment_type' => $row['payment_type'],
            'email' => $row['email'],
        ]);

        $client->save();

        // Create the user associated with the client
        $user = User::create([
            'name' => $row['client_name'],
            'email' => $row['email'],
            'password' => Hash::make('password'), // Assign a default password or generate a random one
        ]);

        $user->assignRole('order-creator'); // Assign the "order creator" role to the user

        return $client;
    }
}
