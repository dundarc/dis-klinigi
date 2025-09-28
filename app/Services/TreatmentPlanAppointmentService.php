<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\TreatmentPlanItem;
use App\Enums\AppointmentStatus;
use App\Enums\TreatmentPlanItemStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TreatmentPlanAppointmentService
{
    /**
     * Get pending treatment plan items for a patient that can be linked to appointments
     */
    public function getPendingTreatmentPlanItems(int $patientId): \Illuminate\Database\Eloquent\Collection
    {
        return TreatmentPlanItem::with(['treatment', 'treatmentPlan', 'appointment'])
            ->whereHas('treatmentPlan', function ($query) use ($patientId) {
                $query->where('patient_id', $patientId);
            })
            ->whereIn('status', [TreatmentPlanItemStatus::PLANNED, TreatmentPlanItemStatus::IN_PROGRESS])
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Link treatment plan items to a new appointment and handle conflicts
     */
    public function linkItemsToAppointment(array $itemIds, Appointment $appointment): array
    {
        $results = [
            'linked_items' => [],
            'cancelled_appointments' => [],
            'adjusted_appointments' => [],
        ];

        DB::transaction(function () use ($itemIds, $appointment, &$results) {
            foreach ($itemIds as $itemId) {
                $item = TreatmentPlanItem::find($itemId);
                if (!$item) continue;

                // Check if this item was already linked to another appointment
                if ($item->appointment_id && $item->appointment_id !== $appointment->id) {
                    $oldAppointment = $item->appointment;

                    // Remove this item from the old appointment
                    $item->update(['appointment_id' => null]);

                    // Check if the old appointment has any remaining items
                    $remainingItems = TreatmentPlanItem::where('appointment_id', $oldAppointment->id)->count();

                    if ($remainingItems === 0) {
                        // Cancel the old appointment
                        $oldAppointment->update([
                            'status' => AppointmentStatus::CANCELLED,
                            'notes' => ($oldAppointment->notes ? $oldAppointment->notes . ' | ' : '') . 'Tedavi öğeleri başka randevuya taşındı - otomatik iptal edildi'
                        ]);

                        $results['cancelled_appointments'][] = [
                            'appointment_id' => $oldAppointment->id,
                            'date' => $oldAppointment->start_at->format('d.m.Y H:i'),
                            'reason' => 'Tüm tedavi öğeleri başka randevuya taşındı'
                        ];

                        Log::info('Appointment cancelled due to treatment plan item reassignment', [
                            'old_appointment_id' => $oldAppointment->id,
                            'new_appointment_id' => $appointment->id,
                            'treatment_plan_item_id' => $itemId,
                            'user_id' => auth()->id(),
                        ]);
                    } else {
                        // Just log that the appointment was adjusted
                        $results['adjusted_appointments'][] = [
                            'appointment_id' => $oldAppointment->id,
                            'date' => $oldAppointment->start_at->format('d.m.Y H:i'),
                            'remaining_items' => $remainingItems
                        ];

                        Log::info('Appointment adjusted due to treatment plan item reassignment', [
                            'appointment_id' => $oldAppointment->id,
                            'new_appointment_id' => $appointment->id,
                            'treatment_plan_item_id' => $itemId,
                            'remaining_items' => $remainingItems,
                            'user_id' => auth()->id(),
                        ]);
                    }
                }

                // Link the item to the new appointment
                $item->update(['appointment_id' => $appointment->id]);

                $results['linked_items'][] = [
                    'item_id' => $itemId,
                    'treatment_name' => $item->treatment->name,
                    'tooth_number' => $item->tooth_number,
                ];

                Log::info('Treatment plan item linked to appointment', [
                    'treatment_plan_item_id' => $itemId,
                    'appointment_id' => $appointment->id,
                    'user_id' => auth()->id(),
                ]);
            }
        });

        return $results;
    }

    /**
     * Check for appointment conflicts when linking items
     */
    public function checkAppointmentConflicts(array $itemIds, \Carbon\Carbon $newAppointmentDate): array
    {
        $conflicts = [];

        foreach ($itemIds as $itemId) {
            $item = TreatmentPlanItem::with('appointment')->find($itemId);
            if (!$item || !$item->appointment) continue;

            $existingAppointment = $item->appointment;

            // Check if the existing appointment conflicts with the new one
            if ($existingAppointment->start_at->isSameDay($newAppointmentDate)) {
                $conflicts[] = [
                    'item_id' => $itemId,
                    'treatment_name' => $item->treatment->name,
                    'existing_appointment' => [
                        'id' => $existingAppointment->id,
                        'date' => $existingAppointment->start_at->format('d.m.Y H:i'),
                        'dentist' => $existingAppointment->dentist->name,
                    ],
                    'conflict_type' => 'same_day'
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Clean up appointments that have no remaining treatment plan items
     */
    public function cleanupEmptyAppointments(): int
    {
        $count = 0;
        
        // Find appointments that have no treatment plan items linked
        $emptyAppointments = Appointment::whereDoesntHave('treatmentPlanItems')
            ->where('status', '!=', AppointmentStatus::CANCELLED)
            ->where('created_at', '>=', now()->subDays(30)) // Only recent appointments
            ->get();

        foreach ($emptyAppointments as $appointment) {
            // Check if this appointment was created from a treatment plan
            if (str_contains($appointment->notes ?? '', 'Tedavi planından oluşturuldu')) {
                $appointment->update([
                    'status' => AppointmentStatus::CANCELLED,
                    'notes' => ($appointment->notes ? $appointment->notes . ' | ' : '') . 'Otomatik iptal - tedavi kalemleri kaldırıldı'
                ]);
                
                Log::info('Empty appointment cancelled automatically', [
                    'appointment_id' => $appointment->id,
                    'original_notes' => $appointment->getOriginal('notes'),
                ]);
                
                $count++;
            }
        }

        return $count;
    }
}