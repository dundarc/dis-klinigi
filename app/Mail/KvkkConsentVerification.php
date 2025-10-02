<?php

namespace App\Mail;

use App\Models\Consent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KvkkConsentVerification extends Mailable
{
    use Queueable, SerializesModels;

    public Consent $consent;
    public string $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Consent $consent, string $verificationUrl)
    {
        $this->consent = $consent;
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[KLİNİK ADI] - KVKK Açık Rıza Onayı',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.kvkk-consent-verification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}