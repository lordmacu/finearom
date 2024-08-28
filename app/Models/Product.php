<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'product_name',
        'price',
        'client_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function purchaseOrders()
    {
        return $this->belongsToMany(PurchaseOrder::class, 'purchase_order_product', 'product_id', 'purchase_order_id')
            ->withPivot('price'); // Si quieres guardar el precio en la tabla pivote
    }

    public function branchOffice()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id', 'id')
            ->withPivot('branch_office_id'); // assuming branch_office_id is in the pivot table
    } 
}
