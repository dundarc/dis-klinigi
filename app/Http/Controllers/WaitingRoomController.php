<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\User;
use App\Models\Treatment;
use App\Models\Appointment;
use App\Enums\UserRole;
use App\Enums\EncounterStatus;
use App\Enums\EncounterType;
use App\Enums\AppointmentStatus;
use App\Enums\FileType;
use App\Http\Requests\StoreEmergencyRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Services\TreatmentPlanAppointmentService;
use App\Services\EncounterService;
use App\Services\TreatmentPlanDateService;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WaitingRoomController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly TreatmentPlanAppointmentService $treatmentPlanService,
        private readonly EncounterService $encounterService,
        private readonly TreatmentPlanDateService $dateService
    ) {
    }

    /**
      */
    public function index()
    {
        $doctorEncounters = collect();
        $user = auth()->user();
        $isDentist = $user && $user->role === UserRole::DENTIST;

        if ($isDentist) {
            $doctorEncounters = Encounter::with(['patient:id,first_name,last_name', 'appointment:id,start_at'])
                ->where('dentist_id', $user->id)
                ->whereIn('status', [EncounterStatus::WAITING, EncounterStatus::IN_SERVICE])
                ->whereIn('type', [EncounterType::SCHEDULED, EncounterType::EMERGENCY, EncounterType::WALK_IN])
                ->orderByRaw("CASE type WHEN 'emergency' THEN 1 WHEN 'walk_in' THEN 2 ELSE 3 END")
                ->orderBy('arrived_at')
                ->orderBy('created_at')
                ->get();
        }

        $today = today();

        // Filter appointments by dentist if user is a dentist
        $pendingAppointmentsQuery = Appointment::with(['patient:id,first_name,last_name', 'dentist:id,name'])
            ->whereDate('start_at', $today)
            ->whereIn('status', [AppointmentStatus::SCHEDULED, AppointmentStatus::CONFIRMED]);

        if ($isDentist) {
            $pendingAppointmentsQuery->where('dentist_id', $user->id);
        }

        $pendingAppointments = $pendingAppointmentsQuery->orderBy('start_at')->get();

        // Filter checked-in encounters by dentist if user is a dentist
        $checkedInEncountersQuery = Encounter::with(['patient:id,first_name,last_name', 'dentist:id,name'])
            ->where('type', EncounterType::SCHEDULED)
            ->whereIn('status', [EncounterStatus::WAITING, EncounterStatus::IN_SERVICE])
            ->whereDate('arrived_at', $today);

        if ($isDentist) {
            $checkedInEncountersQuery->where('dentist_id', $user->id);
        }

        $checkedInEncounters = $checkedInEncountersQuery->orderBy('arrived_at')->orderBy('created_at')->get();

        // Filter emergency encounters by dentist if user is a dentist
        $emergencyEncountersQuery = Encounter::with(['patient:id,first_name,last_name', 'dentist:id,name'])
            ->whereIn('type', [EncounterType::EMERGENCY, EncounterType::WALK_IN])
            ->where('status', EncounterStatus::WAITING)
            ->whereDate('arrived_at', $today);

        if ($isDentist) {
            $emergencyEncountersQuery->where('dentist_id', $user->id);
        }

        $emergencyEncounters = $emergencyEncountersQuery
            ->orderByRaw("CASE triage_level WHEN 'red' THEN 1 WHEN 'yellow' THEN 2 WHEN 'green' THEN 3 ELSE 4 END")
            ->orderBy('arrived_at')
            ->get();

        // Filter completed encounters by dentist if user is a dentist
        $completedEncountersQuery = Encounter::with(['patient:id,first_name,last_name', 'dentist:id,name'])
            ->where('status', EncounterStatus::DONE)
            ->whereDate('ended_at', $today);

        if ($isDentist) {
            $completedEncountersQuery->where('dentist_id', $user->id);
        }

        $completedEncounters = $completedEncountersQuery->latest('ended_at')->get();

        return view('waiting-room.index', [
            'doctorEncounters' => $doctorEncounters,
            'pendingAppointments' => $pendingAppointments,
            'checkedInEncounters' => $checkedInEncounters,
            'emergencyEncounters' => $emergencyEncounters,
            'completedEncounters' => $completedEncounters,
        ]);
    }

    /**
     * KliniÄŸe giriÅŸ yapmÄ±ÅŸ ve henÃ¼z tamamlanmamÄ±ÅŸ SADECE RANDEVULU hastalarÄ± listeler.
     */
    public function appointments()
    {
        $user = auth()->user();
        $isDentist = $user && $user->role === UserRole::DENTIST;

        $waitingEncountersQuery = Encounter::with(['patient', 'dentist', 'appointment'])
            // DÃœZELTME: Sadece 'bekliyor' deÄŸil, 'iÅŸlemde' olanlarÄ± da dahil ederek
            // tamamlanmÄ±ÅŸ veya iptal edilmiÅŸ olanlarÄ± bu listeden Ã§Ä±karÄ±yoruz.
            ->whereIn('status', [EncounterStatus::WAITING, EncounterStatus::IN_SERVICE])
            ->where('type', EncounterType::SCHEDULED);

        // Filter by dentist if user is a dentist
        if ($isDentist) {
            $waitingEncountersQuery->where('dentist_id', $user->id);
        }

        $waitingEncounters = $waitingEncountersQuery->orderBy('arrived_at', 'asc')->paginate(10);

        return view('waiting-room.appointments', compact('waitingEncounters'));
    }

    /**
     * Bekleme odasÄ± Ã¼zerinden yeni randevu oluÅŸturma formunu gÃ¶sterir.
     */
    public function createAppointment()
    {
        $this->authorize('create', Appointment::class);

        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);

        // Filter dentists based on user role - dentists can only see themselves
        $user = auth()->user();
        $dentistsQuery = User::where('role', UserRole::DENTIST)->orderBy('name');

        if ($user->role === UserRole::DENTIST) {
            $dentistsQuery->where('id', $user->id);
        }

        $dentists = $dentistsQuery->get(['id', 'name']);

        return view('waiting-room.appointments_create', compact('patients', 'dentists'));
    }

    /**
     * Get treatment plan items for a patient (AJAX endpoint)
     */
    public function getPatientTreatmentPlanItems(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $treatmentPlanItems = $this->treatmentPlanService->getPendingTreatmentPlanItems($request->patient_id);

        return response()->json([
            'items' => $treatmentPlanItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'treatment_name' => $item->treatment->name,
                    'tooth_number' => $item->tooth_number,
                    'estimated_price' => $item->estimated_price,
                    'status' => $item->status->value,
                    'status_label' => $item->status->label(),
                    'status_color' => $item->status->color(),
                    'treatment_plan_title' => $item->treatmentPlan->title,
                    'existing_appointment' => $item->appointment ? [
                        'id' => $item->appointment->id,
                        'date' => $item->appointment->start_at->format('d.m.Y H:i'),
                        'dentist' => $item->appointment->dentist->name,
                    ] : null,
                ];
            }),
        ]);
    }

    /**
     * Bekleme odasÄ± formundan gelen yeni randevuyu kaydeder.
     */
    public function storeAppointment(StoreAppointmentRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, &$appointment) {
            // Create the appointment
            $appointment = Appointment::create($validated);

            // Handle treatment plan items if any were selected
            if (!empty($validated['treatment_plan_items'])) {
                $results = $this->treatmentPlanService->linkItemsToAppointment(
                    $validated['treatment_plan_items'],
                    $appointment
                );

                // Log the results for audit purposes
                if (!empty($results['cancelled_appointments']) || !empty($results['adjusted_appointments'])) {
                    $appointment->update([
                        'notes' => ($appointment->notes ? $appointment->notes . ' | ' : '') .
                            'Otomatik randevu ayarlamaları yapıldı: ' .
                            count($results['cancelled_appointments']) . ' iptal, ' .
                            count($results['adjusted_appointments']) . ' düzenleme'
                    ]);
                }
            }
        });

        return redirect()->route('appointments.today')->with('success', 'Randevu oluşturuldu.');
    }

    /**
     * RandevularÄ± arama formunu ve sonuÃ§larÄ±nÄ± gÃ¶sterir.
     */
    public function searchAppointments(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);

        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $dentists = User::where('role', UserRole::DENTIST)->orderBy('name')->get(['id', 'name']);

        $query = Appointment::with(['patient', 'dentist', 'encounter']);

        if ($request->hasAny(['patient_id', 'dentist_id', 'start_date', 'end_date'])) {
            if ($request->filled('patient_id')) {
                $query->where('patient_id', $request->patient_id);
            }
            if ($request->filled('dentist_id')) {
                $query->where('dentist_id', $request->dentist_id);
            }
            if ($request->filled('start_date')) {
                $query->whereDate('start_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('start_at', '<=', $request->end_date);
            }
        } else {
            $query->whereRaw('1 = 0');
        }

        $appointments = $query->latest('start_at')->paginate(25)->withQueryString();

        return view('waiting-room.appointments_search', compact('appointments', 'patients', 'dentists'));
    }

    /**
     * Acil bekleyen hastalarÄ±n listesini gÃ¶sterir.
     */
    public function emergency()
    {
        $user = auth()->user();
        $isDentist = $user && $user->role === UserRole::DENTIST;

        $emergencyEncountersQuery = Encounter::with(['patient', 'dentist'])
            ->where('status', EncounterStatus::WAITING)
            ->whereIn('type', ['emergency', 'walk_in']);

        // Filter by dentist if user is a dentist
        if ($isDentist) {
            $emergencyEncountersQuery->where('dentist_id', $user->id);
        }

        $emergencyEncounters = $emergencyEncountersQuery
            ->orderByRaw("CASE triage_level WHEN 'red' THEN 1 WHEN 'yellow' THEN 2 WHEN 'green' THEN 3 ELSE 4 END ASC")
            ->orderBy('arrived_at', 'asc')
            ->paginate(10);

        return view('waiting-room.emergency', compact('emergencyEncounters'));
    }
    
    /**
     * Yeni acil hasta kayÄ±t formunu gÃ¶sterir.
     */
    public function createEmergency()
    {
        $this->authorize('createEmergency', Encounter::class);

        // Filter dentists based on user role - dentists can only see themselves
        $user = auth()->user();
        $dentistsQuery = User::where('role', UserRole::DENTIST)->orderBy('name');

        if ($user->role === UserRole::DENTIST) {
            $dentistsQuery->where('id', $user->id);
        }

        $dentists = $dentistsQuery->get(['id', 'name']);

        return view('waiting-room.emergency_create', compact('dentists'));
    }
    
    /**
     * Yeni acil hasta kaydÄ±nÄ± veritabanÄ±na ekler.
     */
    public function storeEmergency(StoreEmergencyRequest $request)
    {
        $validated = $request->validated();
        Encounter::create($validated + [
            'type' => 'emergency',
            'status' => EncounterStatus::WAITING,
            'arrived_at' => now(),
        ]);
        return redirect()->route('waiting-room.emergency')->with('success', 'Acil hasta kaydÄ± baÅŸarÄ±yla oluÅŸturuldu.');
    }

    /**
     * Belirli bir ziyaret (encounter) iÃ§in salt okunur gÃ¶rÃ¼ntÃ¼leme ekranÄ±nÄ± gÃ¶sterir.
     */
    public function show(Encounter $encounter)
    {
        $this->authorize('view', $encounter);

        $encounter->load([
            'patient',
            'dentist',
            'treatments.treatment',
            'treatments.dentist',
            'prescriptions.dentist',
            'files.uploader',
        ]);

        // Load treatment plan items specifically assigned to this appointment
        $appointmentTreatmentPlanItems = \App\Models\TreatmentPlanItem::with(['treatment', 'treatmentPlan', 'appointment'])
            ->where('appointment_id', $encounter->appointment_id)
            ->orderBy('created_at')
            ->get();

        // Load other treatment plan items for this patient (for reference)
        $otherTreatmentPlanItems = \App\Models\TreatmentPlanItem::with(['treatment', 'treatmentPlan', 'appointment'])
            ->whereHas('treatmentPlan', function ($query) use ($encounter) {
                $query->where('patient_id', $encounter->patient_id);
            })
            ->where(function ($query) use ($encounter) {
                $query->where('appointment_id', '!=', $encounter->appointment_id)
                      ->orWhereNull('appointment_id');
            })
            ->orderBy('appointment_id')
            ->get();

        // Separate scheduled and unscheduled items from other items
        $unscheduledTreatmentPlanItems = $otherTreatmentPlanItems->where('appointment_id', null);
        $scheduledTreatmentPlanItems = $otherTreatmentPlanItems->where('appointment_id', '!=', null);

        return view('waiting-room.show', compact('encounter', 'unscheduledTreatmentPlanItems', 'scheduledTreatmentPlanItems', 'appointmentTreatmentPlanItems'));
    }

    /**
     * Belirli bir ziyaret (encounter) iÃ§in iÅŸlem ekranÄ±nÄ± gÃ¶sterir.
     */
    public function action(Encounter $encounter)
    {
        $this->authorize('update', $encounter);

        $encounter->load([
            'patient',
            'dentist',
            'treatments.treatment',
            'treatments.dentist',
            'prescriptions.dentist',
            'files.uploader',
        ]);

        // Load treatment plan items specifically assigned to this appointment
        $appointmentTreatmentPlanItems = \App\Models\TreatmentPlanItem::with(['treatment', 'treatmentPlan', 'appointment'])
            ->where('appointment_id', $encounter->appointment_id)
            ->where('status', '!=', \App\Enums\TreatmentPlanItemStatus::DONE)
            ->orderBy('created_at')
            ->get();

        // Load other treatment plan items for this patient (for reference)
        // Include cancelled items that were previously assigned to this appointment
        $otherTreatmentPlanItems = \App\Models\TreatmentPlanItem::with(['treatment', 'treatmentPlan', 'appointment'])
            ->whereHas('treatmentPlan', function ($query) use ($encounter) {
                $query->where('patient_id', $encounter->patient_id);
            })
            ->where(function ($query) use ($encounter) {
                $query->where('appointment_id', '!=', $encounter->appointment_id)
                      ->orWhereNull('appointment_id');
            })
            ->where('status', '!=', \App\Enums\TreatmentPlanItemStatus::DONE)
            ->orderBy('appointment_id')
            ->get();

        // Separate scheduled and unscheduled items from other items
        $unscheduledTreatmentPlanItems = $otherTreatmentPlanItems->where('appointment_id', null);
        $scheduledTreatmentPlanItems = $otherTreatmentPlanItems->where('appointment_id', '!=', null);

        $statuses = EncounterStatus::cases();
        $treatments = Treatment::orderBy('name')->get();
        $fileTypes = FileType::cases();

        return view('waiting-room.action', compact('encounter', 'statuses', 'treatments', 'fileTypes', 'unscheduledTreatmentPlanItems', 'scheduledTreatmentPlanItems', 'appointmentTreatmentPlanItems'));
    }

    /**
     * Ziyaret (encounter) bilgilerini, tedavileri ve reÃ§eteleri gÃ¼nceller.
     */
    public function updateAction(Request $request, Encounter $encounter)
    {
        // Debug logging - logout sorununu araştırmak için
        \Illuminate\Support\Facades\Log::info('WR:updateAction', [
            'sid' => session()->getId(),
            'uid' => auth()->id(),
            'url' => request()->fullUrl(),
            'host' => request()->getHost(),
            'path' => request()->getPathInfo(),
            'cookies' => array_keys(request()->cookies->all()),
            'method' => $request->method(),
            'encounter_id' => $encounter->id,
            'user_agent' => $request->userAgent(),
            'csrf_token' => $request->header('X-CSRF-TOKEN'),
            'referer' => $request->header('referer'),
            'auto_complete_item_id' => $request->input('auto_complete_item_id'),
        ]);

        $this->authorize('update', $encounter);

        // Auto-complete treatment plan item if specified
        if ($request->filled('auto_complete_item_id')) {
            $autoCompleteItemId = $request->input('auto_complete_item_id');
            $treatmentPlanItem = \App\Models\TreatmentPlanItem::find($autoCompleteItemId);

            if ($treatmentPlanItem) {
                // Yetkilendirme kontrolü
                $this->authorize('complete', $treatmentPlanItem);

                // Treatment plan item'ı complete et ve actual_date'i güncelle
                $treatmentPlanItem->update([
                    'actual_date' => $encounter->started_at ?? $encounter->arrived_at ?? now(),
                ]);

                $treatmentPlanItem->changeStatus(
                    \App\Enums\TreatmentPlanItemStatus::DONE,
                    auth()->user(),
                    'Otomatik olarak ziyaret tamamlanmasından işaretlendi',
                    ['encounter_id' => $encounter->id, 'auto_completed' => true]
                );

                // ActivityLog kaydı
                \App\Models\ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'treatment_plan_item_auto_completed',
                    'model_type' => 'App\Models\TreatmentPlanItem',
                    'model_id' => $treatmentPlanItem->id,
                    'description' => "Tedavi plan öğesi otomatik olarak tamamlandı: " . ($treatmentPlanItem->treatment ? $treatmentPlanItem->treatment->name : 'Tedavi Silinmiş'),
                    'old_values' => [
                        'status' => $treatmentPlanItem->getOriginal('status'),
                        'actual_date' => $treatmentPlanItem->getOriginal('actual_date'),
                    ],
                    'new_values' => [
                        'status' => \App\Enums\TreatmentPlanItemStatus::DONE->value,
                        'actual_date' => $treatmentPlanItem->actual_date,
                        'encounter_id' => $encounter->id,
                        'auto_completed' => true,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        }

        $validated = $request->validate([
            'status' => ['nullable', new Enum(EncounterStatus::class)],
            'notes' => ['nullable', 'string'],
            'action' => ['required', 'in:save,complete'],
            'applied_treatments' => ['nullable', 'string'],
            'prescription_text' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // Handle action
            if ($request->action === 'complete') {
                $validated['status'] = EncounterStatus::DONE->value;

                // If this encounter is linked to an appointment, mark the appointment as completed
                if ($encounter->appointment_id) {
                    $encounter->appointment->update(['status' => AppointmentStatus::COMPLETED]);
                }

                // Sync dates when encounter is completed
                $this->dateService->syncDatesOnEncounterCompletion($encounter);
            } elseif (!$request->has('status') || empty($request->status)) {
                $validated['status'] = $encounter->status->value; // Keep current status
            }

            // Update encounter basic info
            $encounter->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? $encounter->notes,
                'started_at' => $validated['status'] === EncounterStatus::IN_SERVICE->value && !$encounter->started_at ? now() : $encounter->started_at,
                'ended_at' => $validated['status'] === EncounterStatus::DONE->value && !$encounter->ended_at ? now() : $encounter->ended_at,
            ]);

            // Decode applied treatments
            $appliedTreatments = [];
            if ($request->applied_treatments) {
                $appliedTreatments = json_decode($request->applied_treatments, true) ?? [];
            }

            // Date synchronization is handled by TreatmentPlanDateService

            // Process treatments
            if (!empty($appliedTreatments)) {
                foreach ($appliedTreatments as $treatmentData) {
                    // Handle treatment plan item operations
                    if (!empty($treatmentData['treatment_plan_item_id'])) {
                        $planItem = \App\Models\TreatmentPlanItem::find($treatmentData['treatment_plan_item_id']);
                        if ($planItem) {
                            // Check if patient treatment already exists for this item
                            $existingTreatment = $encounter->treatments()->where('treatment_plan_item_id', $planItem->id)->first();

                            if (!$existingTreatment) {
                                // Create patient treatment record
                                $patientTreatment = $encounter->treatments()->create([
                                    'patient_id' => $encounter->patient_id,
                                    'dentist_id' => $encounter->dentist_id,
                                    'treatment_id' => $planItem->treatment_id,
                                    'tooth_number' => $treatmentData['tooth_number'] ?? $planItem->tooth_number,
                                    'unit_price' => $treatmentData['unit_price'] ?? $planItem->estimated_price,
                                    'vat' => $planItem->treatment->default_vat ?? 20,
                                    'status' => \App\Enums\PatientTreatmentStatus::DONE,
                                    'performed_at' => now(),
                                    'notes' => 'Tedavi planı öğesinden oluşturuldu',
                                    'display_treatment_name' => $planItem->treatment ? $planItem->treatment->name : 'Unknown Treatment',
                                    'treatment_plan_item_id' => $planItem->id,
                                ]);

                                // Link treatment plan item to encounter
                                $encounter->treatmentPlanItems()->attach($planItem->id, [
                                    'price' => $treatmentData['unit_price'] ?? $planItem->estimated_price,
                                    'notes' => 'Applied during encounter',
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
    
                                // Mark treatment plan item as done
                                $planItem->changeStatus(
                                    \App\Enums\TreatmentPlanItemStatus::DONE,
                                    auth()->user(),
                                    'Completed during encounter #' . $encounter->id,
                                    ['encounter_id' => $encounter->id]
                                );
                            }
                        }
                    }
                    // Handle regular new treatments
                    elseif (!empty($treatmentData['treatment_id'])) {
                        $patientTreatment = $encounter->treatments()->create([
                            'patient_id' => $encounter->patient_id,
                            'dentist_id' => $encounter->dentist_id,
                            'treatment_id' => $treatmentData['treatment_id'],
                            'tooth_number' => $treatmentData['tooth_number'] ?? null,
                            'unit_price' => $treatmentData['unit_price'] ?? 0,
                            'vat' => \App\Models\Treatment::find($treatmentData['treatment_id'])->default_vat ?? 20,
                            'status' => \App\Enums\PatientTreatmentStatus::DONE,
                            'performed_at' => now(),
                            'display_treatment_name' => \App\Models\Treatment::find($treatmentData['treatment_id'])->name ?? 'Unknown Treatment',
                        ]);
                    }
                }
            }

            // Handle prescriptions
            if (!empty($validated['prescription_text'])) {
                $encounter->prescriptions()->create([
                    'patient_id' => $encounter->patient_id,
                    'dentist_id' => $encounter->dentist_id,
                    'text' => $validated['prescription_text'],
                ]);
            }

            DB::commit();

            // AJAX request ise JSON response döndür
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $request->action === 'complete' ? 'Ziyaret başarıyla tamamlandı.' : 'Ziyaret kaydi başarıyla güncellendi.',
                    'redirect_url' => $request->action === 'complete' ? route('waiting-room.show', $encounter) : null,
                ]);
            }

            // Normal request ise redirect yap
            if ($request->action === 'complete') {
                return redirect()->route('waiting-room.show', $encounter)->with('success', 'Ziyaret başarıyla tamamlandı.');
            }

            return redirect()->back()->with('success', 'Ziyaret kaydi başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Illuminate\Support\Facades\Log::error('WaitingRoom updateAction failed', [
                'encounter_id' => $encounter->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Güncelleme başarısız oldu: ' . $e->getMessage());
        }
    }

    /**
     * Randevusuz tedavi plan öğesini ziyarete bağlar (AJAX endpoint)
     */
    public function linkTreatmentPlanItemToEncounter(Request $request, Encounter $encounter)
    {
        $request->validate([
            'treatment_plan_item_id' => 'required|exists:treatment_plan_items,id',
        ]);

        $treatmentPlanItemId = $request->treatment_plan_item_id;
        $treatmentPlanItem = \App\Models\TreatmentPlanItem::findOrFail($treatmentPlanItemId);

        // Yetki kontrolü - Policy kullanarak kontrol et
        $this->authorize('update', $encounter);

        // Tedavi plan öğesi zaten bu encounter'a bağlı mı kontrol et
        $existingLink = $encounter->treatmentPlanItems()->where('treatment_plan_item_id', $treatmentPlanItemId)->exists();
        if ($existingLink) {
            return response()->json(['success' => false, 'message' => 'Bu tedavi öğesi zaten bu ziyarete bağlı.']);
        }

        DB::transaction(function () use ($encounter, $treatmentPlanItem) {
            // Appointment oluştur veya güncelle
            $appointment = $this->dateService->createOrUpdateAppointmentForWalkIn($encounter, $encounter->started_at ?? $encounter->arrived_at ?? now());

            // Treatment plan item'ı appointment'a bağla
            $treatmentPlanItem->update(['appointment_id' => $appointment->id]);

            // Treatment plan item'ı encounter'a bağla
            $encounter->treatmentPlanItems()->attach($treatmentPlanItem->id, [
                'price' => $treatmentPlanItem->estimated_price,
                'notes' => 'Ziyaret ile ilişkilendirildi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ActivityLog kaydı
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'treatment_plan_item_linked_to_encounter',
                'model_type' => 'App\Models\TreatmentPlanItem',
                'model_id' => $treatmentPlanItem->id,
                'description' => "Tedavi plan öğesi randevusuz işlem ile ziyarete bağlandı: " . ($treatmentPlanItem->treatment ? $treatmentPlanItem->treatment->name : 'Tedavi Silinmiş'),
                'old_values' => [
                    'appointment_id' => $treatmentPlanItem->getOriginal('appointment_id'),
                ],
                'new_values' => [
                    'appointment_id' => $appointment->id,
                    'encounter_id' => $encounter->id,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Tedavi plan öğesi başarıyla ziyarete bağlandı.',
            'redirect' => route('waiting-room.action', $encounter) // Sayfa yenileme için
        ]);
    }

    /**
     * Randevulu tedavi plan öğesini mevcut ziyarete bağlar (erken uygulama için)
     */
    public function linkScheduledTreatmentPlanItemToEncounter(Request $request, Encounter $encounter)
    {
        $request->validate([
            'treatment_plan_item_id' => 'required|exists:treatment_plan_items,id',
        ]);

        $treatmentPlanItemId = $request->treatment_plan_item_id;
        $treatmentPlanItem = \App\Models\TreatmentPlanItem::findOrFail($treatmentPlanItemId);

        // Yetki kontrolü - Policy kullanarak kontrol et
        $this->authorize('update', $encounter);

        // Tedavi plan öğesi zaten bu encounter'a bağlı mı kontrol et
        $existingLink = $encounter->treatmentPlanItems()->where('treatment_plan_item_id', $treatmentPlanItemId)->exists();
        if ($existingLink) {
            return response()->json(['success' => false, 'message' => 'Bu tedavi öğesi zaten bu ziyarete bağlı.']);
        }

        // Tedavi plan öğesinin randevusu var mı kontrol et
        if (!$treatmentPlanItem->appointment) {
            return response()->json(['success' => false, 'message' => 'Bu tedavi öğesinin randevusu bulunamadı.']);
        }

        DB::transaction(function () use ($encounter, $treatmentPlanItem) {
            $oldAppointment = $treatmentPlanItem->appointment;
            $oldAppointmentId = $treatmentPlanItem->appointment_id;

            // Eski randevuyu iptal et
            $oldAppointment->update([
                'status' => \App\Enums\AppointmentStatus::CANCELLED,
                'notes' => ($oldAppointment->notes ? $oldAppointment->notes . ' | ' : '') . 'Erken tedavi uygulandı - otomatik iptal edildi'
            ]);

            // Treatment plan item'ı güncelle
            $treatmentPlanItem->update([
                'appointment_id' => $encounter->appointment_id, // Mevcut encounter'ın appointment'ına bağla
                'actual_date' => $encounter->started_at ?? $encounter->arrived_at ?? now(), // Gerçek uygulama tarihi
            ]);

            // Treatment plan item'ı encounter'a bağla
            $encounter->treatmentPlanItems()->attach($treatmentPlanItem->id, [
                'price' => $treatmentPlanItem->estimated_price,
                'notes' => 'Erken tedavi uygulandı - randevu iptal edildi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ActivityLog kayıtları
            // Eski randevu için
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'appointment_cancelled_early_treatment',
                'model_type' => 'App\Models\Appointment',
                'model_id' => $oldAppointment->id,
                'description' => "Randevu erken tedavi nedeniyle iptal edildi: " . ($treatmentPlanItem->treatment ? $treatmentPlanItem->treatment->name : 'Tedavi Silinmiş'),
                'old_values' => [
                    'status' => $oldAppointment->getOriginal('status'),
                ],
                'new_values' => [
                    'status' => \App\Enums\AppointmentStatus::CANCELLED->value,
                    'cancelled_reason' => 'Erken tedavi uygulandı',
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Treatment plan item için
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'treatment_plan_item_early_applied',
                'model_type' => 'App\Models\TreatmentPlanItem',
                'model_id' => $treatmentPlanItem->id,
                'description' => "Tedavi plan öğesi erken uygulandı: " . ($treatmentPlanItem->treatment ? $treatmentPlanItem->treatment->name : 'Tedavi Silinmiş'),
                'old_values' => [
                    'appointment_id' => $oldAppointmentId,
                    'actual_date' => $treatmentPlanItem->getOriginal('actual_date'),
                ],
                'new_values' => [
                    'appointment_id' => $encounter->appointment_id,
                    'actual_date' => $treatmentPlanItem->actual_date,
                    'encounter_id' => $encounter->id,
                    'applied_early' => true,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Tedavi plan öğesi erken uygulandı ve randevu iptal edildi.',
            'redirect' => route('waiting-room.action', $encounter) // Sayfa yenileme için
        ]);
    }

    /**
     * O gÃ¼n tamamlanmÄ±ÅŸ iÅŸlemlerin listesini gÃ¶sterir.
     */
    public function completed()
    {
        $user = auth()->user();
        $isDentist = $user && $user->role === UserRole::DENTIST;

        $completedEncountersQuery = Encounter::with(['patient', 'dentist'])
            ->where('status', EncounterStatus::DONE)
            ->whereDate('ended_at', today());

        // Filter by dentist if user is a dentist
        if ($isDentist) {
            $completedEncountersQuery->where('dentist_id', $user->id);
        }

        $completedEncounters = $completedEncountersQuery->latest('ended_at')->paginate(10);

        return view('waiting-room.completed', compact('completedEncounters'));
    }
}

