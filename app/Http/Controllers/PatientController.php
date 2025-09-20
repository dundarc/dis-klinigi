<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\XRay;
use App\Models\Treatment;

use App\Models\Dentist;



class PatientController extends Controller
{
    use AuthorizesRequests;


    public function storeTreatment(Request $request, Patient $patient)
{
    $this->authorize('update', $patient);

    $validated = $request->validate([
    'treatment_id' => 'required|exists:treatments,id',
    'dentist_id' => 'required|exists:users,id',
    'performed_at' => 'required|date',
    'unit_price' => 'required|numeric|min:0',
    'vat' => 'required|numeric|min:0|max:100',
    'notes' => 'nullable|string',
]);

$patient->treatments()->create([
    'treatment_id' => $validated['treatment_id'],
    'dentist_id' => $validated['dentist_id'],
    'performed_at' => $validated['performed_at'],
    'unit_price' => $validated['unit_price'],
    'vat' => $validated['vat'],
    'notes' => $validated['notes'],
]);
    return redirect()->route('patients.show', $patient)->with('success', 'Tedavi başarıyla eklendi.');
}

    public function storeXRay(Request $request, Patient $patient)
{
    $this->authorize('update', $patient);

    $validated = $request->validate([
        'image' => 'required|image|max:2048',
    ]);

    $path = $request->file('image')->store('xrays', 'public');

    $patient->xrays()->create([
        'uploader_id' => auth()->id(),
        'name' => $request->file('image')->getClientOriginalName(),
        'path' => $path,
    ]);

    return back()->with('success', 'Röntgen başarıyla yüklendi.');
}

public function createTreatment(Patient $patient)
{
    $this->authorize('update', $patient);

    $treatments = Treatment::all();
    $dentists = User::where('role', UserRole::DENTIST)->get(); // veya ayrı Dentist modeli varsa Dentist::all();

    return view('patients.treatments.create', compact('patient', 'treatments', 'dentists'));
}



    public function index(Request $request)
    {
        $this->authorize('viewAny', Patient::class);

        $user = $request->user();
        $filters = $this->extractFilters($request);
        $activeStatuses = AppointmentStatus::activeForListing();
        $now = Carbon::now();
        $weekAhead = $now->copy()->addDays(7);

        $baseQuery = $this->baseQueryForUser($user);

        $patientsQuery = $this->applyFilters(clone $baseQuery, $filters, $activeStatuses, $now, $weekAhead);
        $this->applySorting($patientsQuery, $filters['sort']);

        $patients = $patientsQuery
            ->withCount('appointments')
            ->with(['latestAppointment.dentist', 'upcomingAppointment.dentist'])
            ->paginate(15)
            ->withQueryString();

        $stats = $this->buildStats(clone $baseQuery, $activeStatuses, $now, $weekAhead);

        return view('patients.index', [
            'patients' => $patients,
            'stats' => $stats,
            'filters' => $filters,
        ]);
    }

    public function show(Patient $patient)
{
    $this->authorize('view', $patient);

    $patient->load([
        'treatments.treatment',
        'treatments.dentist',
        'xrays'
    ]);

    return view('patients.show', compact('patient'));
}




    protected function extractFilters(Request $request): array
    {
        return [
            'search' => trim((string) $request->input('search', '')),
            'insurance' => $request->input('insurance'),
            'kvkk' => $request->input('kvkk'),
            'upcoming' => $request->input('upcoming'),
            'sort' => $request->input('sort', 'recent'),
        ];
    }

    protected function baseQueryForUser(User $user): Builder
    {
        $query = Patient::query();

        if ($user->role === UserRole::DENTIST) {
            $query->whereHas('appointments', fn (Builder $builder) => $builder->where('dentist_id', $user->id));
        }

        return $query;
    }

    protected function applyFilters(Builder $query, array $filters, array $activeStatuses, Carbon $now, Carbon $weekAhead): Builder
    {
        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function (Builder $builder) use ($search) {
                $builder->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"])
                    ->orWhere('national_id', 'like', "%{$search}%")
                    ->orWhere('phone_primary', 'like', "%{$search}%")
                    ->orWhere('phone_secondary', 'like', "%{$search}%");
            });
        }

        if ($filters['insurance'] === 'private') {
            $query->where('has_private_insurance', true);
        } elseif ($filters['insurance'] === 'none') {
            $query->where(function (Builder $builder) {
                $builder->where('has_private_insurance', false)
                    ->orWhereNull('has_private_insurance');
            });
        }

        if ($filters['kvkk'] === 'signed') {
            $query->whereNotNull('consent_kvkk_at');
        } elseif ($filters['kvkk'] === 'missing') {
            $query->whereNull('consent_kvkk_at');
        }

        if ($filters['upcoming'] === 'next7') {
            $query->whereHas('appointments', function (Builder $builder) use ($now, $weekAhead, $activeStatuses) {
                $builder->whereBetween('start_at', [$now, $weekAhead])
                    ->whereIn('status', $activeStatuses);
            });
        } elseif ($filters['upcoming'] === 'none') {
            $query->whereDoesntHave('appointments', function (Builder $builder) use ($now, $activeStatuses) {
                $builder->where('start_at', '>=', $now)
                    ->whereIn('status', $activeStatuses);
            });
        }

        return $query;
    }

    protected function applySorting(Builder $query, string $sort): void
    {
        match ($sort) {
            'name' => $query->orderBy('last_name')->orderBy('first_name'),
            'oldest' => $query->orderBy('created_at'),
            default => $query->orderByDesc('created_at'),
        };
    }

    protected function buildStats(Builder $query, array $activeStatuses, Carbon $now, Carbon $weekAhead): array
    {
        return [
            'total' => (clone $query)->count(),
            'newThisMonth' => (clone $query)
                ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now])
                ->count(),
            'upcomingAppointments' => (clone $query)
                ->whereHas('appointments', function (Builder $builder) use ($now, $weekAhead, $activeStatuses) {
                    $builder->whereBetween('start_at', [$now, $weekAhead])
                        ->whereIn('status', $activeStatuses);
                })
                ->count(),
            'missingKvkk' => (clone $query)
                ->whereNull('consent_kvkk_at')
                ->count(),
        ];
    }

    
}
