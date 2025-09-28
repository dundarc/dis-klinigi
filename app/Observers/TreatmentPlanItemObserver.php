<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\TreatmentPlanItemAppointmentAction;
use App\Enums\TreatmentPlanItemStatus;
use App\Models\TreatmentPlanItem;
use App\Services\TreatmentPlanService;
use Illuminate\Support\Facades\Log;

class TreatmentPlanItemObserver
{
    public function __construct(
        private TreatmentPlanService $treatmentPlanService
    ) {}

    /**
     * Handle the TreatmentPlanItem "created" event.
     */
    public function created(TreatmentPlanItem $treatmentPlanItem): void
    {
        // Log creation in history
        $treatmentPlanItem->histories()->create([
            'old_status' => null,
            'new_status' => $treatmentPlanItem->status->value,
            'user_id' => auth()->id(),
            'notes' => 'Treatment plan item created',
            'metadata' => ['treatment_plan_id' => $treatmentPlanItem->treatment_plan_id],
        ]);

        Log::info('Treatment plan item created', [
            'treatment_plan_item_id' => $treatmentPlanItem->id,
            'treatment_plan_id' => $treatmentPlanItem->treatment_plan_id,
            'status' => $treatmentPlanItem->status->value,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Handle the TreatmentPlanItem "updated" event.
     */
    public function updated(TreatmentPlanItem $treatmentPlanItem): void
    {
        // Handle status changes
        if ($treatmentPlanItem->isDirty('status')) {
            $oldStatus = $treatmentPlanItem->getOriginal('status');
            $newStatus = $treatmentPlanItem->status;

            Log::info('Treatment plan item status changed', [
                'treatment_plan_item_id' => $treatmentPlanItem->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus->value,
                'user_id' => auth()->id(),
            ]);

            // Ensure encounter linkage when marked as DONE
            if ($newStatus === TreatmentPlanItemStatus::DONE) {
                $this->treatmentPlanService->ensureEncounterLinkageForItem($treatmentPlanItem);
                
                // Also create a PatientTreatment record if not already exists
                $this->createPatientTreatmentRecord($treatmentPlanItem);
            }

            // Update treatment plan total cost if cancelled
            if ($newStatus === TreatmentPlanItemStatus::CANCELLED) {
                $this->updateTreatmentPlanTotalCost($treatmentPlanItem);
            }
        }

        // Handle appointment changes
        if ($treatmentPlanItem->isDirty('appointment_id')) {
            $oldAppointmentId = $treatmentPlanItem->getOriginal('appointment_id');
            $newAppointmentId = $treatmentPlanItem->appointment_id;

            Log::info('Treatment plan item appointment changed', [
                'treatment_plan_item_id' => $treatmentPlanItem->id,
                'old_appointment_id' => $oldAppointmentId,
                'new_appointment_id' => $newAppointmentId,
                'user_id' => auth()->id(),
            ]);

            // Log appointment history for both old and new appointments
            if ($oldAppointmentId) {
                \App\Models\TreatmentPlanItemAppointment::create([
                    'treatment_plan_item_id' => $treatmentPlanItem->id,
                    'appointment_id' => $oldAppointmentId,
                    'action' => TreatmentPlanItemAppointmentAction::REMOVED,
                    'notes' => 'Item moved to different appointment',
                    'user_id' => auth()->id(),
                ]);
            }

            if ($newAppointmentId) {
                \App\Models\TreatmentPlanItemAppointment::create([
                    'treatment_plan_item_id' => $treatmentPlanItem->id,
                    'appointment_id' => $newAppointmentId,
                    'action' => TreatmentPlanItemAppointmentAction::PLANNED,
                    'notes' => 'Item assigned to appointment',
                    'user_id' => auth()->id(),
                ]);
            }
        }
    }

    /**
     * Handle the TreatmentPlanItem "deleting" event.
     */
    public function deleting(TreatmentPlanItem $treatmentPlanItem): void
    {
        Log::info('Treatment plan item being deleted', [
            'treatment_plan_item_id' => $treatmentPlanItem->id,
            'treatment_plan_id' => $treatmentPlanItem->treatment_plan_id,
            'status' => $treatmentPlanItem->status->value,
            'user_id' => auth()->id(),
        ]);

        // Detach from encounters
        $treatmentPlanItem->encounters()->detach();

        // Log appointment history if linked to appointment
        if ($treatmentPlanItem->appointment_id) {
            \App\Models\TreatmentPlanItemAppointment::create([
                'treatment_plan_item_id' => $treatmentPlanItem->id,
                'appointment_id' => $treatmentPlanItem->appointment_id,
                'action' => TreatmentPlanItemAppointmentAction::REMOVED,
                'notes' => 'Item deleted from treatment plan',
                'user_id' => auth()->id(),
            ]);
        }
    }

    /**
     * Create a PatientTreatment record for a completed treatment plan item
     */
    private function createPatientTreatmentRecord(TreatmentPlanItem $treatmentPlanItem): void
    {
        // First check if there's already a patient treatment linked to this treatment plan item
        $existingLinkedTreatment = \App\Models\PatientTreatment::where('treatment_plan_item_id', $treatmentPlanItem->id)
            ->first();
            
        if ($existingLinkedTreatment) {
            Log::info('Patient treatment already exists for treatment plan item, skipping duplicate creation', [
                'treatment_plan_item_id' => $treatmentPlanItem->id,
                'existing_patient_treatment_id' => $existingLinkedTreatment->id,
                'user_id' => auth()->id(),
            ]);
            return;
        }
        
        // Also check if there's a recent patient treatment that might be for the same item
        // (but not yet linked due to timing issues)
        $existingRecord = \App\Models\PatientTreatment::where('treatment_id', $treatmentPlanItem->treatment_id)
            ->where('patient_id', $treatmentPlanItem->treatmentPlan->patient_id)
            ->where('tooth_number', $treatmentPlanItem->tooth_number)
            ->where('unit_price', $treatmentPlanItem->estimated_price)
            ->where('performed_at', '>=', now()->subMinutes(10)) // Within last 10 minutes
            ->orderBy('created_at', 'desc')
            ->first();

        if ($existingRecord) {
            Log::info('Recent patient treatment found that likely corresponds to this treatment plan item, linking instead of creating new', [
                'treatment_plan_item_id' => $treatmentPlanItem->id,
                'existing_patient_treatment_id' => $existingRecord->id,
                'user_id' => auth()->id(),
            ]);
            
            // Link the existing treatment to this treatment plan item
            $existingRecord->update(['treatment_plan_item_id' => $treatmentPlanItem->id]);
            return;
        }

        // Only create a new patient treatment if none exists
        // Find or create an encounter for this treatment
        $encounter = $this->findOrCreateEncounterForItem($treatmentPlanItem);
        
        if ($encounter) {
            // Create the patient treatment record
            $patientTreatment = $encounter->treatments()->create([
                'patient_id' => $treatmentPlanItem->treatmentPlan->patient_id,
                'dentist_id' => $treatmentPlanItem->treatmentPlan->dentist_id,
                'treatment_id' => $treatmentPlanItem->treatment_id,
                'treatment_plan_item_id' => $treatmentPlanItem->id, // Always link back
                'tooth_number' => $treatmentPlanItem->tooth_number,
                'unit_price' => $treatmentPlanItem->estimated_price,
                'vat' => $treatmentPlanItem->treatment->default_vat ?? 20,
                'status' => \App\Enums\PatientTreatmentStatus::DONE,
                'performed_at' => now(),
                'notes' => 'Tedavi planı öğesinden otomatik oluşturuldu',
                'display_treatment_name' => $treatmentPlanItem->treatment->name,
            ]);

            Log::info('Patient treatment record created from treatment plan item (Observer)', [
                'treatment_plan_item_id' => $treatmentPlanItem->id,
                'patient_treatment_id' => $patientTreatment->id,
                'encounter_id' => $encounter->id,
                'stored_display_name' => $treatmentPlanItem->treatment->name,
                'plan_item_treatment_id' => $treatmentPlanItem->treatment_id,
                'user_id' => auth()->id(),
            ]);
        }
    }

    /**
     * Find or create an encounter for a treatment plan item
     */
    private function findOrCreateEncounterForItem(TreatmentPlanItem $treatmentPlanItem): ?\App\Models\Encounter
    {
        // First try to find an existing encounter linked to this item
        $encounter = $treatmentPlanItem->encounters()->first();
        
        if ($encounter) {
            return $encounter;
        }

        // Try to find encounter through appointment
        if ($treatmentPlanItem->appointment && $treatmentPlanItem->appointment->encounter) {
            return $treatmentPlanItem->appointment->encounter;
        }

        // Try to find a recent encounter for this patient
        $encounter = \App\Models\Encounter::where('patient_id', $treatmentPlanItem->treatmentPlan->patient_id)
            ->whereDate('created_at', today())
            ->whereIn('status', [\App\Enums\EncounterStatus::IN_SERVICE, \App\Enums\EncounterStatus::DONE])
            ->latest()
            ->first();

        if ($encounter) {
            return $encounter;
        }

        // Create a new encounter as last resort
        return \App\Models\Encounter::create([
            'patient_id' => $treatmentPlanItem->treatmentPlan->patient_id,
            'appointment_id' => $treatmentPlanItem->appointment_id,
            'dentist_id' => $treatmentPlanItem->treatmentPlan->dentist_id,
            'type' => \App\Enums\EncounterType::WALK_IN,
            'status' => \App\Enums\EncounterStatus::DONE,
            'arrived_at' => now(),
            'started_at' => now(),
            'ended_at' => now(),
            'notes' => 'Otomatik olarak tedavi kalemi tamamlanmasından oluşturuldu.',
        ]);
    }

    /**
     * Update treatment plan total cost when an item is cancelled
     */
    private function updateTreatmentPlanTotalCost(TreatmentPlanItem $treatmentPlanItem): void
    {
        $plan = $treatmentPlanItem->treatmentPlan;
        if ($plan) {
            $activeItemsCost = $plan->items()
                ->where('status', '!=', TreatmentPlanItemStatus::CANCELLED)
                ->sum('estimated_price');

            $plan->update(['total_estimated_cost' => $activeItemsCost]);
            
            Log::info('Treatment plan total cost updated after item cancellation', [
                'treatment_plan_id' => $plan->id,
                'new_total' => $activeItemsCost,
                'cancelled_item_id' => $treatmentPlanItem->id,
            ]);
        }
    }
}
