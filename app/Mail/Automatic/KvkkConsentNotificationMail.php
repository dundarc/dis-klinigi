<?php

namespace App\Mail\Automatic;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KvkkConsentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $patientName,
        public readonly string $consentDate,
        public readonly string $clinicName,
        public readonly ?string $recipientName = null
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('KVKK Onay Bildirimi')
            ->view('mail.automatic.kvkk-consent', [
                'patientName'   => $this->patientName,
                'consentDate'   => $this->consentDate,
                'clinicName'    => $this->clinicName,
                'recipientName' => $this->recipientName,
            ]);
    }
}
