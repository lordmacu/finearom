<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Storage;
use App\Models\PurchaseOrder;
use Log;
use Symfony\Component\Mime\Header\TagHeader;

class EmailSentListener
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Mail\Events\MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        // Obtén el message_id del mensaje enviado
        $messageId = $event->sent->getMessageId();
        $headers = $event->message->getHeaders();

        $tagHeader = $headers->get('x-tag');

        if ($tagHeader) {
            $purchaseOrderId = $tagHeader->getValue();

            $headers = $event->message->getHeaders();

            $purchaseOrder = PurchaseOrder::find($purchaseOrderId);

            if ($purchaseOrder) {
                $purchaseOrder->message_id = $messageId;
                $purchaseOrder->save();
            }
        }
    }
}
