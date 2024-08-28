<?php

namespace App\Observers;

use App\Models\PurchaseOrder;

class PurchaseOrderObserver
{
    public function updating(PurchaseOrder $purchaseOrder)
    {
        // Verificar si el valor de 'trm' está cambiando
        if ($purchaseOrder->isDirty('trm')) {
            $purchaseOrder->trm_updated_at = now();
        }
    }
}
