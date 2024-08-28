<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    protected $fillable = [
        'name',
        'nit',
        'client_id',
        'headquarters',
        'contact',
        'billing_contact',
        'delivery_address',
        'delivery_city',
        'billing_address',
        'billing_city',
        'phone',
        'shipping_observations',
        'general_observations',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
