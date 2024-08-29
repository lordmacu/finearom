<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'client_id',
        'branch_office_id',
        'order_creation_date',
        'required_delivery_date',
        'trm',
        'order_consecutive',
        'observations',
        'delivery_address',
        'status',
        'message_id',
        'delivery_city',
        'contact',
        'attachment',
        'phone',
        'invoice_number',        // Nuevo campo
        'dispatch_date',         // Nuevo campo
        'tracking_number',       // Nuevo campo
        'observations_extra',    // Nuevo campov
        'trm_updated_at'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    public function branchOffice()
    {
        return $this->belongsTo(BranchOffice::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_order_product', 'purchase_order_id', 'product_id')
            ->withPivot('quantity', 'price', 'branch_office_id'); // Incluyendo cantidad y precio en la tabla pivote
    }

    public function getBranchOfficeName(Product $product)
    {
        $branchOffice = BranchOffice::find($product->pivot->branch_office_id);
        return $branchOffice ? $branchOffice->name : 'N/A';
    }
}
