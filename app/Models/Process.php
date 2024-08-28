<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{

    protected $fillable = [
        'name',
        'email',
        'process_type',
    ];

    public const PROCESSES = [
        'orden_de_compra' => 'Orden de Compra',
        'confirmacion_despacho' => 'Confirmación Despacho',
        'pedido' => 'Pedido',
    ];
}
