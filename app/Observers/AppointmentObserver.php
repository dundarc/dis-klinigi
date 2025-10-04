<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Services\AutomaticEmailService;
use App\Services\EncounterService;

class AppointmentObserver
{
    public function __construct(
        private readonly EncounterService $encounterService,
        private readonly AutomaticEmailService $automaticEmailService
    ) {
    }

    public function updated(Appointment $appointment): void
    {
        if ($appointment->wasChanged('status')) {
            if ($appointment->status === AppointmentStatus::COMPLETED) {
                // Ensure encounter record exists when appointment is marked as completed
                $this->encounterService->ensureEncounterForAppointment($appointment);
            }

            if ($appointment->status === AppointmentStatus::CHECKED_IN) {
                $this->automaticEmailService->sendPatientCheckin($appointment);
            }
        }
    }

    public function created(Appointment $appointment): void
    {
        // Handle any logic needed when appointment is created
    }

    public function deleted(Appointment $appointment): void
    {
        // Handle cleanup if needed
    }

    public function restored(Appointment $appointment): void
    {
        // Handle restoration if needed
    }

    public function forceDeleted(Appointment $appointment): void
    {
        // Handle force deletion if needed
    }
}
