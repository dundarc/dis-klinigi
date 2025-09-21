<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePatientRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function search(Request $request)
    {
        $user = $request->user();

        if (! $user->can('viewAny', Patient::class) && ! $user->can('create', Patient::class)) {
            abort(403);
        }

        $term = trim((string) $request->input('query', ''));

        if (mb_strlen($term) < 2) {
            return response()->json(['data' => []]);
        }

        $patients = Patient::query()
            ->where(function ($query) use ($term) {
                $normalized = Str::lower($term);
                $query->whereRaw('LOWER(first_name) LIKE ?', ["%{$normalized}%"])
                    ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$normalized}%"])
                    ->orWhereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", ["%{$normalized}%"])
                    ->orWhere('national_id', 'like', "%{$term}%")
                    ->orWhere('phone_primary', 'like', "%{$term}%")
                    ->orWhere('phone_secondary', 'like', "%{$term}%");
            })
            ->orderBy('first_name')
            ->limit(10)
            ->get()
            ->map(fn (Patient $patient) => [
                'id' => $patient->id,
                'full_name' => $patient->full_name,
                'national_id' => $patient->national_id,
                'phone' => $patient->phone_primary ?? $patient->phone_secondary,
            ]);

        return response()->json(['data' => $patients]);
    }

    public function store(StorePatientRequest $request)
    {
        $patient = Patient::create($request->validated());

        return response()->json([
            'message' => 'Hasta kaydı başarıyla oluşturuldu.',
            'patient' => [
                'id' => $patient->id,
                'full_name' => $patient->full_name,
                'national_id' => $patient->national_id,
                'phone' => $patient->phone_primary ?? $patient->phone_secondary,
            ],
        ], 201);
    }
}
