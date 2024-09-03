<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    protected $fillable = [
        'name',
        'nit',
        'client_id',
        'delivery_city',
        'billing_address',
        'delivery_address',
        'general_observations',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
