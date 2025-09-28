<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Services\EncounterService;

class AppointmentObserver
{
    public function __construct(
        private EncounterService $encounterService
    ) {}

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        if ($appointment->isDirty('status') &&
            $appointment->status === AppointmentStatus::COMPLETED) {
            $this->encounterService->ensureEncounterForAppointment($appointment);
        }
    }

    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        // Handle any logic needed when appointment is created
    }

    /**
     * Handle the Appointment "deleted" event.
     */
    public function deleted(Appointment $appointment): void
    {
        // Handle cleanup if needed
    }

    /**
     * Handle the Appointment "restored" event.
     */
    public function restored(Appointment $appointment): void
    {
        // Handle restoration if needed
    }

    /**
     * Handle the Appointment "force deleted" event.
     */
    public function forceDeleted(Appointment $appointment): void
    {
        // Handle force deletion if needed
    }
}