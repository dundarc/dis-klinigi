<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TreatmentPlanItem;
use App\Services\TreatmentPlanService;
use Illuminate\Http\Request;

class TreatmentPlanItemController extends Controller
{
    protected TreatmentPlanService $treatmentPlanService;

    public function __construct(TreatmentPlanService $treatmentPlanService)
    {
        $this->treatmentPlanService = $treatmentPlanService;
    }

    public function complete(TreatmentPlanItem $item)
    {
        try {
            // Find or create an encounter for this completion
            $encounter = $this->findRecentEncounterForItem($item);
            
            if ($encounter) {
                // Mark item as done with encounter linkage
                $this->treatmentPlanService->markItemAsDone($item->id, $encounter->id, auth()->user());
            } else {
                // Mark as done without specific encounter (will auto-create one)
                $item->changeStatus(
                    \App\Enums\TreatmentPlanItemStatus::DONE,
                    auth()->user(),
                    'Completed via API action',
                    ['api_completion' => true]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Treatment plan item marked as completed.',
                'item' => [
                    'id' => $item->id,
                    'status' => $item->fresh()->status->value,
                    'completed_at' => $item->fresh()->completed_at?->toISOString(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete treatment plan item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancel(TreatmentPlanItem $item)
    {
        try {
            // Remove from appointment if linked
            $appointmentId = $item->appointment_id;
            
            $item->changeStatus(
                \App\Enums\TreatmentPlanItemStatus::CANCELLED,
                auth()->user(),
                'Cancelled via API action',
                ['api_cancellation' => true]
            );

            // If item was linked to appointment, remove the link
            if ($appointmentId) {
                $item->update(['appointment_id' => null]);
                
                // Check if appointment has any remaining items
                $remainingItems = \App\Models\TreatmentPlanItem::where('appointment_id', $appointmentId)->count();
                
                if ($remainingItems === 0) {
                    // Cancel the appointment if no items remain
                    $appointment = \App\Models\Appointment::find($appointmentId);
                    if ($appointment) {
                        $appointment->update([
                            'status' => \App\Enums\AppointmentStatus::CANCELLED,
                            'notes' => ($appointment->notes ? $appointment->notes . ' | ' : '') . 'Tedavi kalemleri iptal edildi - otomatik iptal'
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Treatment plan item cancelled.',
                'item' => [
                    'id' => $item->id,
                    'status' => $item->fresh()->status->value,
                    'cancelled_at' => $item->fresh()->cancelled_at?->toISOString(),
                    'appointment_removed' => $appointmentId !== null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel treatment plan item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function start(TreatmentPlanItem $item)
    {
        try {
            $item->changeStatus(
                \App\Enums\TreatmentPlanItemStatus::IN_PROGRESS,
                auth()->user(),
                'Started via API action',
                ['api_start' => true]
            );

            return response()->json([
                'success' => true,
                'message' => 'Treatment plan item started.',
                'item' => [
                    'id' => $item->id,
                    'status' => $item->fresh()->status->value,
                    'started_at' => $item->fresh()->started_at?->toISOString(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start treatment plan item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Find a recent encounter for the treatment plan item
     */
    private function findRecentEncounterForItem(TreatmentPlanItem $item): ?\App\Models\Encounter
    {
        // Try appointment encounter first
        if ($item->appointment && $item->appointment->encounter) {
            return $item->appointment->encounter;
        }

        // Find recent encounter for this patient
        return \App\Models\Encounter::where('patient_id', $item->treatmentPlan->patient_id)
            ->whereDate('created_at', today())
            ->whereIn('status', [\App\Enums\EncounterStatus::IN_SERVICE, \App\Enums\EncounterStatus::DONE])
            ->latest()
            ->first();
    }
}