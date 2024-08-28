<?php

namespace App\Mail;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchaseOrder;
    public $messageId;

    /**
     * Create a new message instance.
     */
    public function __construct(PurchaseOrder $purchaseOrder, string $messageId = null)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->messageId = $messageId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.purchase_order_status_changed_plain')
                      ->subject('Re: Orden de Compra - '.$this->purchaseOrder->order_consecutive)
                      ->with([
                          'purchaseOrder' => $this->purchaseOrder,
                      ]);

        // Set the Reply-To header if messageId is provided
        if ($this->messageId) {
            $email->replyTo($this->messageId);
        }

        // Add custom headers
        return $email->withSwiftMessage(function ($swiftMessage) {
            $headers = $swiftMessage->getHeaders();
            $headers->addTextHeader('In-Reply-To', $this->messageId);
            $headers->addTextHeader('References', $this->messageId);
        });
    }
}
