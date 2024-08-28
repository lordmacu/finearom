<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class PurchaseOrderMailDespacho extends Mailable
{
    use Queueable, SerializesModels;

    public $purchaseOrder;
    public $pdfAttachment; // New property to hold the PDF attachment path

    /**
     * Create a new message instance.
     *
     * @param $purchaseOrder
     * @param $pdf
     */
    public function __construct($purchaseOrder, $pdfAttachment)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->pdfAttachment = $pdfAttachment; // Set the attachment path

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pedido - ' . $this->purchaseOrder->client->client_name . ' - ' . $this->purchaseOrder->client->nit . ' - ' . $this->purchaseOrder->order_consecutive
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.purchase_order_email',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function build()
    {
            $email = $this->view('emails.purchase_order_email')
            ->with('purchaseOrder', $this->purchaseOrder)
            ->subject('Pedido - ' . $this->purchaseOrder->client->client_name . ' - ' . $this->purchaseOrder->client->nit . ' - ' . $this->purchaseOrder->order_consecutive);

        // Attach the PDF if it exists
        if ($this->pdfAttachment) {
            $fullPath = Storage::disk('public')->path($this->pdfAttachment);

            $email->attach($fullPath, [
                'as' => 'adjunto.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
}
