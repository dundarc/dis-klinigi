<?php

namespace App\Mail\Automatic;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmergencyPatientNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $patientName,
        public readonly string $dentistName,
        public readonly string $registrationTime,
        public readonly string $clinicName,
        public readonly ?string $triageLevel = null
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Acil Hasta Bildirimi')
            ->view('mail.automatic.emergency-patient', [
                'patientName' => $this->patientName,
                'dentistName' => $this->dentistName,
                'registrationTime' => $this->registrationTime,
                'clinicName' => $this->clinicName,
                'triageLevel' => $this->triageLevel,
            ]);
    }
}
