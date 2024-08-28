<?php
namespace App\Listeners;

use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mailer\EventListener\MessageLoggerListener;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Storage;
use App\Models\PurchaseOrder;

class MessageIdListener
{
    private $purchaseOrder;

    public function __construct(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    public function onMessageSend(MessageEvent $event)
    {
        $message = $event->getMessage();
        dd('aqui');
        if ($message instanceof Email) {
            $headers = $message->getHeaders();
            dd($headers);
            $messageId = $headers->getHeaderBody('Message-ID');

            // Guardar el `message_id` en el storage
            Storage::put('latest_purchase_order_id', $this->purchaseOrder->id);
            Storage::put('latest_message_id', $messageId);

            // Guardar el `message_id` en la base de datos
            $this->purchaseOrder->message_id = $messageId;
            $this->purchaseOrder->save();
        }
    }
}
