<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\EncounterStatus;
use App\Enums\EncounterType;
use App\Enums\UserRole;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientNotesRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Services\PatientDetailService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly PatientDetailService $patientDetailService)
    {
    }

    /**
     * Hastaları listeler ve arama işlevselliği sağlar.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Patient::class);

        $user = auth()->user();
        $search = $request->input('search');

        $query = Patient::query();

        if ($user->role === UserRole::DENTIST) {
            $query->whereHas('appointments', function ($q) use ($user) {
                $q->where('dentist_id', $user->id);
            });
        }

        $query->when($search, function ($q, $search) {
            $q->where(function ($subQuery) use ($search) {
                $subQuery->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_primary', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%");
            });
        });

        $patients = $query->latest()->paginate(20)->withQueryString();

        return view('patients.index', compact('patients', 'search'));
    }

    /**
     * Yeni hasta oluşturma formunu gösterir.
     */
    public function create()
    {
        $this->authorize('create', Patient::class);

        return view('patients.create');
    }

    /**
     * Yeni oluşturulan hastayı kaydeder.
     */
    public function store(StorePatientRequest $request)
    {
        $validatedData = $request->validated();
        $patient = new Patient($validatedData);
        $patient->has_private_insurance = $request->boolean('has_private_insurance');
        $patient->consent_kvkk_at = $request->boolean('consent_kvkk') ? now() : null;
        $patient->save();

        return redirect()->route('patients.index')->with('success', 'Hasta başarıyla eklendi.');
    }

    /**
     * Hastanın tüm detaylarını ve ilişkili verilerini görüntüler.
     */
    public function show(Patient $patient)
    {
        $this->authorize('view', $patient);

        $detail = $this->patientDetailService->buildDetail($patient);

        $age = $patient->birth_date ? $patient->birth_date->age : null;

        $appointmentStatusLabels = [];
        foreach (AppointmentStatus::cases() as $status) {
            $appointmentStatusLabels[$status->value] = method_exists($status, 'label')
                ? $status->label()
                : ucfirst(str_replace('_', ' ', $status->value));
        }

        $appointmentStatusStyles = [
            AppointmentStatus::SCHEDULED->value => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-100',
            AppointmentStatus::CONFIRMED->value => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-100',
            AppointmentStatus::CHECKED_IN->value => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-100',
            AppointmentStatus::IN_SERVICE->value => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-100',
            AppointmentStatus::COMPLETED->value => 'bg-slate-100 text-slate-700 dark:bg-slate-800/60 dark:text-slate-100',
            AppointmentStatus::CANCELLED->value => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-100',
            AppointmentStatus::NO_SHOW->value => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-100',
        ];

        $encounterTypeLabels = [
            EncounterType::SCHEDULED->value => __('patient.encounter_type.scheduled'),
            EncounterType::EMERGENCY->value => __('patient.encounter_type.emergency'),
            EncounterType::WALK_IN->value => __('patient.encounter_type.walk_in'),
        ];

        $encounterStatusLabels = [
            EncounterStatus::WAITING->value => __('patient.encounter_status.waiting'),
            EncounterStatus::IN_SERVICE->value => __('patient.encounter_status.in_service'),
            EncounterStatus::DONE->value => __('patient.encounter_status.done'),
            EncounterStatus::CANCELLED->value => __('patient.encounter_status.cancelled'),
        ];

        return view('patients.show', [
            'patient' => $patient,
            'age' => $age,
            'upcomingAppointments' => $detail['upcomingAppointments'],
            'encounters' => $detail['encounters'],
            'appointmentStatusLabels' => $appointmentStatusLabels,
            'appointmentStatusStyles' => $appointmentStatusStyles,
            'encounterTypeLabels' => $encounterTypeLabels,
            'encounterStatusLabels' => $encounterStatusLabels,
        ]);
    }

    /**
     * Hasta düzenleme formunu gösterir.
     */
    public function edit(Patient $patient)
    {
        $this->authorize('update', $patient);

        return view('patients.edit', compact('patient'));
    }

    /**
     * Ana hasta bilgilerini günceller.
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $validatedData = $request->validated();
        $updateData = $validatedData;
        $updateData['consent_kvkk_at'] = $validatedData['consent_kvkk'] ? ($patient->consent_kvkk_at ?? now()) : null;
        unset($updateData['consent_kvkk']);
        $patient->update($updateData);

        return redirect()->route('patients.show', $patient)->with('success', 'Hasta bilgileri başarıyla güncellendi.');
    }

    /**
     * Hastanın sadece notlar bölümünü gunceller.
     */
    public function updateNotes(UpdatePatientNotesRequest $request, Patient $patient)
    {
        $this->authorize('update', $patient);
        $patient->update($request->validated());

        return redirect()->route('patients.show', $patient)->with('success', 'Hasta notları başarıyla güncellendi.');
    }

    /**
     * Hastayı arşivler (Soft Delete).
     */
    public function destroy(Patient $patient)
    {
        $this->authorize('delete', $patient);
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Hasta başarıyla arşivlendi.');
    }

    /**
     * API: Bir hastanın faturalanmamış ve tamamlanmış tedavilerini döndürür.
     */
    public function getUninvoicedTreatments(Patient $patient)
    {
        $this->authorize('view', $patient);
        $treatments = $patient->treatments()
            ->where('status', 'done')
            ->whereDoesntHave('invoiceItem')
            ->with('treatment')
            ->get();

        return response()->json($treatments);
    }
}