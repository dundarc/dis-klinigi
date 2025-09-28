<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\EncounterStatus;
use Illuminate\Validation\Rules\Enum;
use App\Services\NotificationService;
use App\Http\Requests\Api\V1\AssignAndProcessEncounterRequest;
use App\Http\Requests\Api\V1\StoreEncounterRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EncounterController extends Controller
{
    use AuthorizesRequests;

    /**
     * Controller'a NotificationService'i enjekte ediyoruz.
     */
    public function __construct(
        protected NotificationService $notificationService,
        protected \App\Services\TreatmentPlanService $treatmentPlanService
    ) {
    }

    /**
     * Bir vakanın durumunu günceller (örn: done, cancelled).
     */
    public function updateStatus(Request $request, Encounter $encounter)
    {
        // Yetki kontrolü (Policy oluşturulursa eklenebilir)
        // $this->authorize('update', $encounter);

        $validated = $request->validate([
            'status' => ['required', new Enum(EncounterStatus::class)],
        ]);
        
        $encounter->update($validated);

        return response()->json([
            'message' => 'Vaka durumu güncellendi.',
            'status' => $encounter->status->value
        ]);
    }
    
    /**
     * Bir vakaya sadece hekim atar.
     */
    public function assignDoctor(Request $request, Encounter $encounter)
    {
         // Yetki kontrolü (Policy oluşturulursa eklenebilir)
         // $this->authorize('update', $encounter);

         $validated = $request->validate([
            'dentist_id' => 'required|exists:users,id',
        ]);

        $encounter->update($validated);
 

        return response()->json(['message' => 'Hekim atandı.']);
    }

    public function store(StoreEncounterRequest $request)
    {
        $validated = $request->validated();

        $encounter = Encounter::create([
            'patient_id' => $validated['patient_id'],
            'dentist_id' => $validated['dentist_id'] ?? null,
            'type' => $validated['type'],
            'triage_level' => $validated['triage_level'],
            'notes' => $validated['notes'] ?? null,
            'arrived_at' => now(),
            'status' => EncounterStatus::WAITING,
        ]);

        if (! empty($validated['dentist_id'])) {
            $this->notificationService->createNotification(
                $encounter->dentist,
                'Yeni Acil Hasta Kaydı',
                "{$encounter->patient->first_name} {$encounter->patient->last_name} isimli hasta acil kaydına eklendi."
            );
        }

        return response()->json([
            'message' => 'Acil/randevusuz hasta kaydı oluşturuldu.',
            'encounter' => $encounter->load(['patient', 'dentist']),
        ], 201);
    }

    /**
     * Bir vakaya hekim atar ve durumunu "İşlemde" olarak günceller.
     */
    public function assignAndProcess(AssignAndProcessEncounterRequest $request, Encounter $encounter)
    {
        $validated = $request->validated();
        $dentist = User::find($validated['dentist_id']);
        $patient = $encounter->patient;

        $encounter->update([
            'dentist_id' => $dentist->id,
            'status' => EncounterStatus::IN_SERVICE,
            'started_at' => now(),
        ]);

        // Atanan hekime bildirim gönder
        $this->notificationService->createNotification(
            $dentist,
            'Yeni Hasta Yönlendirmesi',
            "{$patient->first_name} {$patient->last_name} isimli hasta size yönlendirildi."
        );

        return response()->json([
            'message' => 'Hasta başarıyla hekime atandı ve işleme alındı.',
            'encounter' => $encounter->fresh()->load('dentist'), // Güncel veriyi döndür
        ]);
    }

    /**
     * Get treatment plan items for an encounter
     */
    public function getTreatmentPlanItems(Encounter $encounter)
    {
        try {
            $patientId = $encounter->patient_id;

            // Get unscheduled treatment plan items (not linked to any appointment)
            $unscheduledTreatmentPlanItems = \App\Models\TreatmentPlanItem::with(['treatment', 'treatmentPlan'])
                ->whereHas('treatmentPlan', function ($query) use ($patientId) {
                    $query->where('patient_id', $patientId);
                })
                ->whereNull('appointment_id')
                ->whereIn('status', [\App\Enums\TreatmentPlanItemStatus::PLANNED, \App\Enums\TreatmentPlanItemStatus::IN_PROGRESS, \App\Enums\TreatmentPlanItemStatus::CANCELLED])
                ->orderBy('created_at')
                ->get();

            // Get scheduled treatment plan items (linked to future appointments)
            $scheduledTreatmentPlanItems = \App\Models\TreatmentPlanItem::with(['treatment', 'treatmentPlan', 'appointment.dentist'])
                ->whereHas('treatmentPlan', function ($query) use ($patientId) {
                    $query->where('patient_id', $patientId);
                })
                ->whereHas('appointment', function ($query) {
                    $query->where('start_at', '>', now());
                })
                ->whereIn('treatment_plan_items.status', [\App\Enums\TreatmentPlanItemStatus::PLANNED, \App\Enums\TreatmentPlanItemStatus::IN_PROGRESS])
                ->join('appointments', 'treatment_plan_items.appointment_id', '=', 'appointments.id')
                ->orderBy('appointments.start_at')
                ->select('treatment_plan_items.*') // Only select treatment plan item fields
                ->get();

            // Get appointment treatment plan items (for the current encounter's appointment if any)
            $appointmentTreatmentPlanItems = collect();
            if ($encounter->appointment_id) {
                $appointmentTreatmentPlanItems = \App\Models\TreatmentPlanItem::with(['treatment', 'treatmentPlan', 'appointment'])
                    ->where('appointment_id', $encounter->appointment_id)
                    ->whereIn('status', [\App\Enums\TreatmentPlanItemStatus::PLANNED, \App\Enums\TreatmentPlanItemStatus::IN_PROGRESS])
                    ->get();
            }

            return response()->json([
                'unscheduled' => $unscheduledTreatmentPlanItems,
                'scheduled' => $scheduledTreatmentPlanItems,
                'appointment' => $appointmentTreatmentPlanItems,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error getting treatment plan items for encounter', [
                'encounter_id' => $encounter->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to load treatment plan items',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Auto-save applied treatments for an encounter
     */
    public function autoSaveTreatments(Request $request, Encounter $encounter)
    {
        $validated = $request->validate([
            'treatments' => 'array',
            'treatments.*.treatment_id' => 'nullable|exists:treatments,id',
            'treatments.*.tooth_number' => 'nullable|string',
            'treatments.*.unit_price' => 'nullable|numeric|min:0',
            'treatments.*.treatment_plan_item_id' => 'nullable|exists:treatment_plan_items,id',
            'treatments.*.treatment_plan_item_status' => 'nullable|in:done,cancelled',
        ]);

        try {
            // Only process regular treatments during auto-save, skip treatment plan items
            // Treatment plan items should only be processed during final form submission
            foreach ($validated['treatments'] as $treatmentData) {
                // Skip treatment plan items during auto-save to prevent duplicate links
                if (!empty($treatmentData['treatment_plan_item_id'])) {
                    \Illuminate\Support\Facades\Log::info('Skipping treatment plan item during auto-save', [
                        'encounter_id' => $encounter->id,
                        'treatment_plan_item_id' => $treatmentData['treatment_plan_item_id'],
                        'user_id' => auth()->id(),
                    ]);
                    continue;
                }
                
                // Only handle regular new treatments during auto-save
                if (!empty($treatmentData['treatment_id'])) {
                    // Check if this treatment already exists to prevent duplicates
                    $existingTreatment = $encounter->treatments()
                        ->where('treatment_id', $treatmentData['treatment_id'])
                        ->where('tooth_number', $treatmentData['tooth_number'] ?? null)
                        ->where('unit_price', $treatmentData['unit_price'] ?? 0)
                        ->first();
                        
                    if (!$existingTreatment) {
                        $encounter->treatments()->create([
                            'patient_id' => $encounter->patient_id,
                            'dentist_id' => $encounter->dentist_id,
                            'treatment_id' => $treatmentData['treatment_id'],
                            'tooth_number' => $treatmentData['tooth_number'] ?? null,
                            'unit_price' => $treatmentData['unit_price'] ?? 0,
                            'vat' => \App\Models\Treatment::find($treatmentData['treatment_id'])->default_vat ?? 20,
                            'status' => \App\Enums\PatientTreatmentStatus::PENDING,
                            'performed_at' => null, // Will be set when encounter is completed
                            'notes' => 'Auto-saved during encounter',
                            'display_treatment_name' => \App\Models\Treatment::find($treatmentData['treatment_id'])?->name ?? 'Unknown Treatment',
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Treatments auto-saved successfully.'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Auto-save failed', [
                'encounter_id' => $encounter->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to auto-save treatments: ' . $e->getMessage()
            ], 500);
        }
    }
}