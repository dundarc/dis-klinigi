<?php

namespace App\Repositories;

use App\Enums\AppointmentStatus;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PatientRepository
{
    public function getUpcomingAppointments(Patient $patient, Carbon $from): Collection
    {
        return $patient->appointments()
            ->with('dentist:id,name')
            ->where('start_at', '>=', $from)
            ->whereNotIn('status', [AppointmentStatus::CANCELLED->value, AppointmentStatus::NO_SHOW->value])
            ->orderBy('start_at')
            ->get();
    }

    public function getEncountersWithDetails(Patient $patient): Collection
    {
        return $patient->encounters()
            ->with([
                'dentist:id,name',
                'appointment:id,start_at,dentist_id',
                'treatments' => function ($query) {
                    $query->with(['treatment:id,name', 'dentist:id,name'])
                        ->orderByDesc('performed_at');
                },
                'prescriptions' => function ($query) {
                    $query->with('dentist:id,name')
                        ->orderByDesc('created_at');
                },
                'files' => function ($query) {
                    $query->with('uploader:id,name')
                        ->orderByDesc('created_at');
                },
            ])
            ->orderByDesc('arrived_at')
            ->orderByDesc('created_at')
            ->get();
    }
}