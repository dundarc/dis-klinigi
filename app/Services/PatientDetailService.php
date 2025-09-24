<?php

namespace App\Services;

use App\Models\Patient;
use App\Repositories\PatientRepository;
use Carbon\Carbon;

class PatientDetailService
{
    public function __construct(private readonly PatientRepository $patientRepository)
    {
    }

    public function buildDetail(Patient $patient): array
    {
        $now = Carbon::now();

        $upcomingAppointments = $this->patientRepository->getUpcomingAppointments($patient, $now);
        $encounters = $this->patientRepository->getEncountersWithDetails($patient);

        return [
            'upcomingAppointments' => $upcomingAppointments,
            'encounters' => $encounters,
        ];
    }
}