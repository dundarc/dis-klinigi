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
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WaitingRoomController extends Controller
{
    use AuthorizesRequests;

    /**
     * Bekleme OdasÄ± ana sayfasÄ±nÄ± gÃ¶sterir.
     */
    public function index()
    {
        $doctorEncounters = collect();
        $user = auth()->user();

        if ($user && $user->role === UserRole::DENTIST) {
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

        $pendingAppointments = Appointment::with(['patient:id,first_name,last_name', 'dentist:id,name'])
            ->whereDate('start_at', $today)
            ->whereIn('status', [AppointmentStatus::SCHEDULED, AppointmentStatus::CONFIRMED])
            ->orderBy('start_at')
            ->get();

        $checkedInEncounters = Encounter::with(['patient:id,first_name,last_name', 'dentist:id,name'])
            ->where('type', EncounterType::SCHEDULED)
            ->whereIn('status', [EncounterStatus::WAITING, EncounterStatus::IN_SERVICE])
            ->whereDate('arrived_at', $today)
            ->orderBy('arrived_at')
            ->orderBy('created_at')
            ->get();

        $emergencyEncounters = Encounter::with(['patient:id,first_name,last_name', 'dentist:id,name'])
            ->whereIn('type', [EncounterType::EMERGENCY, EncounterType::WALK_IN])
            ->where('status', EncounterStatus::WAITING)
            ->whereDate('arrived_at', $today)
            ->orderByRaw("CASE triage_level WHEN 'red' THEN 1 WHEN 'yellow' THEN 2 WHEN 'green' THEN 3 ELSE 4 END")
            ->orderBy('arrived_at')
            ->get();

        $completedEncounters = Encounter::with(['patient:id,first_name,last_name', 'dentist:id,name'])
            ->where('status', EncounterStatus::DONE)
            ->whereDate('ended_at', $today)
            ->latest('ended_at')
            ->get();

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
        $waitingEncounters = Encounter::with(['patient', 'dentist', 'appointment'])
            // DÃœZELTME: Sadece 'bekliyor' deÄŸil, 'iÅŸlemde' olanlarÄ± da dahil ederek
            // tamamlanmÄ±ÅŸ veya iptal edilmiÅŸ olanlarÄ± bu listeden Ã§Ä±karÄ±yoruz.
            ->whereIn('status', [EncounterStatus::WAITING, EncounterStatus::IN_SERVICE])
            ->where('type', EncounterType::SCHEDULED)
            ->orderBy('arrived_at', 'asc')
            ->get();
            
        return view('waiting-room.appointments', compact('waitingEncounters'));
    }

    /**
     * Bekleme odasÄ± Ã¼zerinden yeni randevu oluÅŸturma formunu gÃ¶sterir.
     */
    public function createAppointment()
    {
        $this->authorize('create', Appointment::class);

        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $dentists = User::where('role', UserRole::DENTIST)->orderBy('name')->get(['id', 'name']);

        return view('waiting-room.appointments_create', compact('patients', 'dentists'));
    }

    /**
     * Bekleme odasÄ± formundan gelen yeni randevuyu kaydeder.
     */
    public function storeAppointment(StoreAppointmentRequest $request)
    {
        Appointment::create($request->validated());
        return redirect()->route('appointments.today')->with('success', 'Randevu baÅŸarÄ±yla oluÅŸturuldu.');
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
        $emergencyEncounters = Encounter::with(['patient', 'dentist'])
            ->where('status', EncounterStatus::WAITING)
            ->whereIn('type', ['emergency', 'walk_in'])
            ->orderByRaw("CASE triage_level WHEN 'red' THEN 1 WHEN 'yellow' THEN 2 WHEN 'green' THEN 3 ELSE 4 END ASC")
            ->orderBy('arrived_at', 'asc')
            ->get();
            
        return view('waiting-room.emergency', compact('emergencyEncounters'));
    }
    
    /**
     * Yeni acil hasta kayÄ±t formunu gÃ¶sterir.
     */
    public function createEmergency()
    {
        $this->authorize('createEmergency', Encounter::class);
        
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'national_id']);
        $dentists = User::where('role', UserRole::DENTIST)->orderBy('name')->get(['id', 'name']);
        
        return view('waiting-room.emergency_create', compact('patients', 'dentists'));
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

        $statuses = EncounterStatus::cases();
        $treatments = Treatment::orderBy('name')->get();
        $fileTypes = FileType::cases();

        return view('waiting-room.action', compact('encounter', 'statuses', 'treatments', 'fileTypes'));
    }

    /**
     * Ziyaret (encounter) bilgilerini, tedavileri ve reÃ§eteleri gÃ¼nceller.
     */
    public function updateAction(Request $request, Encounter $encounter)
    {
        $this->authorize('update', $encounter);
        $validated = $request->validate([
            'status' => ['required', new Enum(EncounterStatus::class)],
            'notes' => ['nullable', 'string'],
            'treatments' => ['nullable', 'array'],
            'treatments.*.treatment_id' => ['required_with:treatments', 'exists:treatments,id'],
            'treatments.*.tooth_number' => ['nullable', 'integer'],
            'treatments.*.unit_price' => ['required_with:treatments', 'numeric'],
            'prescription_text' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($encounter, $validated, $request) {
            $encounter->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? $encounter->notes,
                'started_at' => $validated['status'] === EncounterStatus::IN_SERVICE->value && !$encounter->started_at ? now() : $encounter->started_at,
                'ended_at' => $validated['status'] === EncounterStatus::DONE->value && !$encounter->ended_at ? now() : $encounter->ended_at,
            ]);

            if (!empty($validated['treatments'])) {
                foreach ($validated['treatments'] as $treatmentData) {
                    $encounter->treatments()->create($treatmentData + [
                        'patient_id' => $encounter->patient_id,
                        'dentist_id' => $encounter->dentist_id,
                        'vat' => Treatment::find($treatmentData['treatment_id'])->default_vat ?? 20,
                        'status' => 'done',
                        'performed_at' => now(),
                    ]);
                }
            }

            if (!empty($validated['prescription_text'])) {
                $encounter->prescriptions()->create([
                    'patient_id' => $encounter->patient_id,
                    'dentist_id' => $encounter->dentist_id,
                    'text' => $validated['prescription_text'],
                ]);
            }
        });

        return redirect()->route('waiting-room.index')->with('success', 'Ziyaret kaydÄ± baÅŸarÄ±yla gÃ¼ncellendi.');
    }

    /**
     * O gÃ¼n tamamlanmÄ±ÅŸ iÅŸlemlerin listesini gÃ¶sterir.
     */
    public function completed()
    {
        $completedEncounters = Encounter::with(['patient', 'dentist'])
            ->where('status', EncounterStatus::DONE)
            ->whereDate('ended_at', today())
            ->latest('ended_at')
            ->get();
            
        return view('waiting-room.completed', compact('completedEncounters'));
    }
}

