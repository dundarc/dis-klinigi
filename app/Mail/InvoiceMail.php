<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invoice $invoice)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Faturanız - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
        );
    }

    public function attachments(): array
    {
        // Fatura PDF'ini oluştur ve e-postaya ekle
        $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $this->invoice]);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn () => $pdf->output(),
                'fatura-'.$this->invoice->invoice_no.'.pdf'
            )->withMime('application/pdf'),
        ];
    }
}