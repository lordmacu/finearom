<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Swift_Message;

class PurchaseOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchaseOrder;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @param $purchaseOrder
     * @param $pdf
     */
    public function __construct($purchaseOrder, $pdf)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->pdf = $pdf;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Orden de Compra - '.$this->purchaseOrder->order_consecutive,
            tags:[$this->purchaseOrder->id]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.purchase_order',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function build()
    {
        return $this->view('emails.purchase_order')
                    ->subject('Detalles de la Orden de Compra')->attachData($this->pdf, 'order.pdf', [
                        'mime' => 'application/pdf',
                    ]);
                 
    }

     /**
     * Build the message headers.
     *
     * @return $this
     */
    protected function buildHeaders()
    {
        parent::buildHeaders();

        $this->withSwiftMessage(function (Swift_Message $message) {
            $headers = $message->getHeaders();
            $headers->addTextHeader('X-Purchase-Order-ID', 4444);
        });

        return $this;
    }
}
