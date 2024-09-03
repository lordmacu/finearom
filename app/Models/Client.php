<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Client extends Model
{
    protected $fillable = [
        'client_name',
        'nit',
        'client_type',
        'payment_type',
        'email',
        'executive',
        'executive_email',          // Agregado
        'address',                  // Debe coincidir con 'delivery_address'
        'city',                     // Debe coincidir con 'delivery_city'
        'billing_closure',
        'commercial_terms',         // Cambiado de 'commercial_conditions' para coincidir con la inserción
        'proforma_invoice',
        'payment_method',
        'payment_day',
        'status',
        'dispatch_confirmation_contact',
        'accounting_contact',
        'accounting_contact_email',
        'dispatch_confirmation_email',
        'registration_address',
        'registration_city',
        'phone',
        'shipping_notes',
        'user_id',                // Agregado para coincidir con la inserción
        'trm',                      // Agregado para coincidir con la inserción
        'commercial_conditions'
    ];
    

    public function observation()
    {
        return $this->hasOne(ClientObservation::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function branchOffices()
    {
        return $this->hasMany(BranchOffice::class);
    }

    
}
