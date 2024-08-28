<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientObservation extends Model
{
    protected $fillable = [
        'client_id',
        'requires_physical_invoice',
        'packaging_unit',
        'requires_appointment',
        'additional_observations',
        'is_in_free_zone',
        'billing_closure_date',
        'reteica',
        'retefuente',
        'reteiva',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
