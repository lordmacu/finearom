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
        'address',
        'city',
        'billing_closure',
        'commercial_conditions',
        'proforma_invoice',
        'payment_method',
        'payment_day',
        'status',
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
