<?php

namespace App\Mail\Automatic;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientCheckinNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $patientName,
        public readonly string $dentistName,
        public readonly string $appointmentTime,
        public readonly string $clinicName
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Hasta Check-in Bildirimi')
            ->view('mail.automatic.patient-checkin', [
                'patientName'     => $this->patientName,
                'dentistName'     => $this->dentistName,
                'appointmentTime' => $this->appointmentTime,
                'clinicName'      => $this->clinicName,
            ]);
    }
}
