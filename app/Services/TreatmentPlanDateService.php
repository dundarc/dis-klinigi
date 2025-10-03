<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\TreatmentPlanItem;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class TreatmentPlanDateService
{
    /**
     * Sync dates when an encounter is completed
     */
    public function syncDatesOnEncounterCompletion(Encounter $encounter): void
    {
        $actualDate = $encounter->started_at ?? $encounter->arrived_at ?? now();

        // Handle walk-in encounters (no appointment)
        if (!$encounter->appointment_id) {
            $this->handleWalkInEncounter($encounter, $actualDate);
            return;
        }

        // Handle scheduled encounters
        $this->handleScheduledEncounter($encounter, $actualDate);
    }

    /**
     * Handle walk-in encounters (no appointment)
     */
    private function handleWalkInEncounter(Encounter $encounter, $actualDate): void
    {
        // Create or update appointment for walk-in encounter
        $appointment = $this->createOrUpdateAppointmentForWalkIn($encounter, $actualDate);

        // Update treatment plan items
        foreach ($encounter->treatmentPlanItems as $item) {
            $item->update([
                'appointment_id' => $appointment->id,
                'actual_date' => $actualDate,
            ]);
        }

        // Log walk-in completion
        $this->logWalkInCompletion($encounter, $appointment, $actualDate);
    }

    /**
     * Handle scheduled encounters (with appointment)
     */
    private function handleScheduledEncounter(Encounter $encounter, $actualDate): void
    {
        $appointment = $encounter->appointment;
        $plannedDate = $appointment->start_at;

        // Check if appointment needs rescheduling
        if (!$plannedDate->isSameDay($actualDate)) {
            $this->rescheduleAppointment($appointment, $actualDate);
            $this->logRescheduledAppointment($encounter, $appointment, $plannedDate, $actualDate);
        }

        // Update treatment plan items
        foreach ($encounter->treatmentPlanItems as $item) {
            $item->update(['actual_date' => $actualDate]);
        }
    }

    /**
     * Reschedule appointment to match actual completion date
     */
    private function rescheduleAppointment(Appointment $appointment, $actualDate): void
    {
        $oldStartAt = $appointment->start_at;

        $appointment->update([
            'start_at' => $actualDate,
            'end_at' => $actualDate->copy()->addMinutes(30),
            'rescheduled_from' => $oldStartAt,
            'status' => \App\Enums\AppointmentStatus::COMPLETED,
        ]);
    }

    /**
     * Create or update appointment for walk-in encounter
     */
    public function createOrUpdateAppointmentForWalkIn(Encounter $encounter, $actualDate): Appointment
    {
        $endDate = $encounter->ended_at ?? $actualDate->copy()->addMinutes(30);

        // If encounter already has an appointment, update it
        if ($encounter->appointment_id) {
            $appointment = $encounter->appointment;
            $appointment->update([
                'start_at' => $actualDate,
                'end_at' => $endDate,
                'status' => \App\Enums\AppointmentStatus::COMPLETED,
                'notes' => ($appointment->notes ? $appointment->notes . ' | ' : '') . 'Randevusuz işlem tamamlandı - tarih eşitlendi.',
            ]);
            return $appointment;
        }

        // Create new appointment for walk-in encounter
        $appointment = Appointment::create([
            'patient_id' => $encounter->patient_id,
            'dentist_id' => $encounter->dentist_id,
            'start_at' => $actualDate,
            'end_at' => $endDate,
            'status' => \App\Enums\AppointmentStatus::COMPLETED,
            'notes' => 'Otomatik olarak randevusuz işlem tamamlanmasından oluşturuldu.',
        ]);

        $encounter->update(['appointment_id' => $appointment->id]);

        return $appointment;
    }

    /**
     * Log walk-in completion with ActivityLog
     */
    private function logWalkInCompletion(Encounter $encounter, Appointment $appointment, $actualDate): void
    {
        $treatmentNames = $encounter->treatmentPlanItems->map(function ($item) {
            return $item->treatment?->name ?? 'Tedavi Silinmiş';
        })->join(', ');

        $message = "Randevusuz işlem {$treatmentNames} {$actualDate->format('d.m.Y H:i')} tarihinde tamamlandı, appointment tarihi ziyaret tarihi ile eşitlendi.";

        // Laravel Log
        Log::info('Randevusuz işlem ziyaret tarihinde tamamlandı', [
            'encounter_id' => $encounter->id,
            'appointment_id' => $appointment->id,
            'actual_date' => $actualDate->toISOString(),
            'treatment_count' => $encounter->treatmentPlanItems->count(),
        ]);

        // ActivityLog
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'walk_in_completed',
            'model_type' => 'App\Models\Encounter',
            'model_id' => $encounter->id,
            'description' => $message,
            'old_values' => null,
            'new_values' => [
                'appointment_id' => $appointment->id,
                'actual_date' => $actualDate->toISOString(),
                'treatment_items' => $encounter->treatmentPlanItems->pluck('id')->toArray(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Log treatment plan item updates
        foreach ($encounter->treatmentPlanItems as $item) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'treatment_plan_item_walk_in_completed',
                'model_type' => 'App\Models\TreatmentPlanItem',
                'model_id' => $item->id,
                'description' => "Tedavi plan öğesi randevusuz işlem ile tamamlandı: " . ($item->treatment ? $item->treatment->name : 'Tedavi Silinmiş'),
                'old_values' => [
                    'appointment_id' => $item->getOriginal('appointment_id'),
                    'actual_date' => $item->getOriginal('actual_date'),
                ],
                'new_values' => [
                    'appointment_id' => $appointment->id,
                    'actual_date' => $actualDate->toISOString(),
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }

    /**
     * Log rescheduled appointment with ActivityLog
     */
    private function logRescheduledAppointment(Encounter $encounter, Appointment $appointment, $plannedDate, $actualDate): void
    {
        $isEarly = $actualDate->isBefore($plannedDate);
        $timingText = $isEarly ? 'erken' : 'geç';

        $message = "Planlanan: {$plannedDate->format('d.m.Y H:i')}, Gerçekleşen: {$actualDate->format('d.m.Y H:i')} ({$timingText} tamamlandı)";

        // Laravel Log
        Log::info('Planlanan: ' . $plannedDate->format('d.m.Y H:i') . ', Gerçekleşen: ' . $actualDate->format('d.m.Y H:i'), [
            'encounter_id' => $encounter->id,
            'appointment_id' => $appointment->id,
            'planned_date' => $plannedDate->toISOString(),
            'actual_date' => $actualDate->toISOString(),
            'is_early' => $isEarly,
        ]);

        // ActivityLog
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'appointment_rescheduled',
            'model_type' => 'App\Models\Appointment',
            'model_id' => $appointment->id,
            'description' => $message,
            'old_values' => [
                'start_at' => $plannedDate->toISOString(),
            ],
            'new_values' => [
                'start_at' => $actualDate->toISOString(),
                'rescheduled_from' => $plannedDate->toISOString(),
                'encounter_id' => $encounter->id,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Log treatment plan item updates for rescheduled appointment
        foreach ($encounter->treatmentPlanItems as $item) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'treatment_plan_item_rescheduled',
                'model_type' => 'App\Models\TreatmentPlanItem',
                'model_id' => $item->id,
                'description' => "Tedavi plan öğesi randevusu yeniden planlandı: " . ($item->treatment ? $item->treatment->name : 'Tedavi Silinmiş'),
                'old_values' => [
                    'appointment_start_at' => $plannedDate->toISOString(),
                ],
                'new_values' => [
                    'appointment_start_at' => $actualDate->toISOString(),
                    'actual_date' => $actualDate->toISOString(),
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }

    /**
     * Get display information for planned vs actual dates
     */
    public function getDateDisplayInfo(TreatmentPlanItem $item): array
    {
        $plannedDate = $item->appointment?->start_at;
        $actualDate = $item->actual_date;

        if (!$plannedDate) {
            return [
                'planned' => null,
                'actual' => $actualDate?->format('d.m.Y H:i'),
                'is_rescheduled' => false,
                'is_early' => false,
                'is_late' => false,
            ];
        }

        $isRescheduled = $actualDate && !$plannedDate->isSameDay($actualDate);

        return [
            'planned' => $plannedDate->format('d.m.Y H:i'),
            'actual' => $actualDate?->format('d.m.Y H:i'),
            'is_rescheduled' => $isRescheduled,
            'is_early' => $isRescheduled && $actualDate->isBefore($plannedDate),
            'is_late' => $isRescheduled && $actualDate->isAfter($plannedDate),
        ];
    }
}
