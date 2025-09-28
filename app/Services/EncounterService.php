<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Enums\EncounterStatus;
use App\Enums\EncounterType;
use App\Enums\TreatmentPlanItemStatus;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\Treatment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EncounterService
{
    public function ensureEncounterForAppointment(Appointment $appointment): ?Encounter
    {
        if ($appointment->status !== AppointmentStatus::COMPLETED) {
            return null;
        }

        if ($appointment->encounter) {
            return $appointment->encounter;
        }

        return DB::transaction(function () use ($appointment) {
            return Encounter::create([
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'dentist_id' => $appointment->dentist_id,
                'type' => EncounterType::SCHEDULED,
                'status' => EncounterStatus::DONE,
                'arrived_at' => $appointment->start_at,
                'started_at' => $appointment->start_at,
                'ended_at' => $appointment->end_at ?? $appointment->start_at->copy()->addMinutes(30),
                'notes' => 'Otomatik olarak randevu tamamlanmasından oluşturuldu.',
            ]);
        });
    }

    /**
     * Update encounter with treatments and handle treatment plan items
     */
    public function updateEncounterWithTreatments(Encounter $encounter, array $validated): array
    {
        try {
            return DB::transaction(function () use ($encounter, $validated) {
                // Check policy for completing encounter
                if ($validated['status'] === EncounterStatus::DONE->value) {
                    // This should be handled by authorization in controller
                }

                // Update encounter basic info
                $encounter->update([
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? $encounter->notes,
                    'started_at' => $validated['status'] === EncounterStatus::IN_SERVICE->value && !$encounter->started_at ? now() : $encounter->started_at,
                    'ended_at' => $validated['status'] === EncounterStatus::DONE->value && !$encounter->ended_at ? now() : $encounter->ended_at,
                ]);

                // Process treatments
                if (!empty($validated['treatments'])) {
                    $this->processTreatments($encounter, $validated['treatments'], $validated['status']);
                }

                // Handle prescriptions
                if (!empty($validated['prescription_text'])) {
                    $encounter->prescriptions()->create([
                        'patient_id' => $encounter->patient_id,
                        'dentist_id' => $encounter->dentist_id,
                        'text' => $validated['prescription_text'],
                    ]);
                }

                return ['success' => true, 'message' => 'Encounter updated successfully'];
            });
        } catch (\Exception $e) {
            Log::error('Failed to update encounter with treatments', [
                'encounter_id' => $encounter->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return ['success' => false, 'message' => 'Failed to update encounter: ' . $e->getMessage()];
        }
    }

    /**
     * Process treatments for an encounter
     */
    private function processTreatments(Encounter $encounter, array $treatments, string $encounterStatus): void
    {
        foreach ($treatments as $treatmentData) {
            // Handle treatment plan item operations
            if (!empty($treatmentData['treatment_plan_item_id'])) {
                $this->handleTreatmentPlanItem($encounter, $treatmentData, $encounterStatus);
            }
            // Handle regular new treatments
            elseif (!empty($treatmentData['treatment_id'])) {
                $this->createRegularTreatment($encounter, $treatmentData);
            }
        }
    }

    /**
     * Handle treatment plan item operations (complete/apply)
     */
    private function handleTreatmentPlanItem(Encounter $encounter, array $treatmentData, string $encounterStatus): void
    {
        $planItem = \App\Models\TreatmentPlanItem::find($treatmentData['treatment_plan_item_id']);
        if (!$planItem) {
            return;
        }

        // Apply treatment from plan item
        if (!empty($treatmentData['treatment_id'])) {
            // Check if this treatment already exists to prevent duplicates
            $existingTreatment = $encounter->treatments()
                ->where('treatment_id', $treatmentData['treatment_id'])
                ->where('tooth_number', $treatmentData['tooth_number'] ?? $planItem->tooth_number)
                ->where('unit_price', $treatmentData['unit_price'] ?? $planItem->estimated_price)
                ->where('treatment_plan_item_id', $planItem->id) // More specific check
                ->first();
                
            if (!$existingTreatment) {
                // Ensure we're using the correct treatment name from the plan item's relationship
                $treatmentName = $planItem->treatment ? $planItem->treatment->name : 'Unknown Treatment';
                
                // Create patient treatment record
                $patientTreatment = $encounter->treatments()->create([
                    'patient_id' => $encounter->patient_id,
                    'dentist_id' => $encounter->dentist_id,
                    'treatment_id' => $planItem->treatment_id, // Use the treatment_id from the plan item
                    'tooth_number' => $treatmentData['tooth_number'] ?? $planItem->tooth_number,
                    'unit_price' => $treatmentData['unit_price'] ?? $planItem->estimated_price,
                    'vat' => $planItem->treatment->default_vat ?? 20,
                    'status' => \App\Enums\PatientTreatmentStatus::DONE,
                    'performed_at' => now(),
                    'notes' => 'Tedavi planı öğesinden oluşturuldu',
                    'display_treatment_name' => $treatmentName, // Use the name from plan item relationship
                    'treatment_plan_item_id' => $planItem->id, // Always link back to treatment plan item
                ]);
                
                Log::info('Treatment created from treatment plan item (EncounterService)', [
                    'encounter_id' => $encounter->id,
                    'treatment_plan_item_id' => $planItem->id,
                    'patient_treatment_id' => $patientTreatment->id,
                    'treatment_name' => $treatmentName,
                    'stored_display_name' => $treatmentName,
                    'plan_item_treatment_id' => $planItem->treatment_id,
                    'actual_treatment_id_used' => $planItem->treatment_id,
                    'user_id' => auth()->id(),
                ]);
            } else {
                Log::info('Treatment from treatment plan item already exists, skipping duplicate', [
                    'encounter_id' => $encounter->id,
                    'treatment_plan_item_id' => $planItem->id,
                    'existing_treatment_id' => $existingTreatment->id,
                    'user_id' => auth()->id(),
                ]);
            }

            // Always link the treatment plan item to the encounter (for tracking)
            $existingLink = $encounter->treatmentPlanItems()->where('treatment_plan_item_id', $planItem->id)->exists();
            
            if (!$existingLink) {
                $encounter->treatmentPlanItems()->attach($planItem->id, [
                    'price' => $treatmentData['unit_price'] ?? $planItem->estimated_price,
                    'notes' => 'Applied during encounter',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                Log::info('Treatment plan item linked to encounter', [
                    'encounter_id' => $encounter->id,
                    'treatment_plan_item_id' => $planItem->id,
                    'price' => $treatmentData['unit_price'] ?? $planItem->estimated_price,
                    'user_id' => auth()->id(),
                ]);
            } else {
                Log::info('Treatment plan item already linked to encounter, skipping duplicate', [
                    'encounter_id' => $encounter->id,
                    'treatment_plan_item_id' => $planItem->id,
                    'user_id' => auth()->id(),
                ]);
            }

            // Mark treatment plan item as done only if encounter is completed
            if ($encounterStatus === EncounterStatus::DONE->value) {
                $planItem->changeStatus(
                    TreatmentPlanItemStatus::DONE,
                    auth()->user(),
                    'Completed during encounter #' . $encounter->id,
                    ['encounter_id' => $encounter->id]
                );

                // Cancel scheduled appointment if this was early application
                if ($treatmentData['is_scheduled'] ?? false) {
                    $this->cancelScheduledAppointment($planItem);
                }
            }
        }
    }

    /**
     * Create a regular treatment (not from treatment plan)
     */
    private function createRegularTreatment(Encounter $encounter, array $treatmentData): void
    {
        $encounter->treatments()->create([
            'patient_id' => $encounter->patient_id,
            'dentist_id' => $encounter->dentist_id,
            'treatment_id' => $treatmentData['treatment_id'],
            'tooth_number' => $treatmentData['tooth_number'] ?? null,
            'unit_price' => $treatmentData['unit_price'] ?? 0,
            'vat' => Treatment::find($treatmentData['treatment_id'])->default_vat ?? 20,
            'status' => \App\Enums\PatientTreatmentStatus::DONE,
            'performed_at' => now(),
            'display_treatment_name' => Treatment::find($treatmentData['treatment_id'])->name ?? 'Unknown Treatment',
        ]);
    }

    /**
     * Cancel scheduled appointment for early treatment application
     */
    private function cancelScheduledAppointment(\App\Models\TreatmentPlanItem $planItem): void
    {
        if ($planItem->appointment_id) {
            $appointment = $planItem->appointment;
            if ($appointment) {
                $appointment->update([
                    'status' => AppointmentStatus::CANCELLED,
                    'notes' => ($appointment->notes ? $appointment->notes . ' | ' : '') . 'Erken tedavi uygulandı - otomatik iptal edildi'
                ]);

                Log::info('Appointment cancelled due to early treatment application', [
                    'appointment_id' => $appointment->id,
                    'treatment_plan_item_id' => $planItem->id,
                    'user_id' => auth()->id(),
                ]);
            }
        }
    }
}