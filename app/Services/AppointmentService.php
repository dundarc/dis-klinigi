<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class AppointmentService
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getAppointments(string $start, string $end, ?array $dentistIds, ?array $statuses): Collection
    {
        return $this->appointmentRepository->getAppointmentsByDateRange(
            Carbon::parse($start),
            Carbon::parse($end),
            $dentistIds,
            $statuses
        );
    }

    public function createAppointment(array $data): Appointment
    {
        $this->checkForConflict($data['dentist_id'], $data['start_at'], $data['end_at']);
        return $this->appointmentRepository->create($data);
    }

    public function updateAppointment(Appointment $appointment, array $data): bool
    {
        $this->checkForConflict(
            $data['dentist_id'] ?? $appointment->dentist_id,
            $data['start_at'] ?? $appointment->start_at,
            $data['end_at'] ?? $appointment->end_at,
            $appointment->id
        );
        return $this->appointmentRepository->update($appointment, $data);
    }

    public function deleteAppointment(Appointment $appointment): ?bool
    {
        return $this->appointmentRepository->delete($appointment);
    }

    protected function checkForConflict(int $dentistId, string $start, string $end, ?int $exceptId = null): void
    {
        if ($this->appointmentRepository->hasConflict(
            $dentistId, Carbon::parse($start), Carbon::parse($end), $exceptId
        )) {
            throw ValidationException::withMessages([
                'start_at' => 'Seçilen zaman aralığında bu hekim için başka bir randevu bulunmaktadır.'
            ]);
        }
    }
}
