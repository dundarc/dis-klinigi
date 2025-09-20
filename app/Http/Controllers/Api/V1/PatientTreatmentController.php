<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PatientTreatment;
use App\Http\Requests\Api\V1\StorePatientTreatmentRequest;

class PatientTreatmentController extends Controller
{
    public function store(StorePatientTreatmentRequest $request)
    {
        $validated = $request->validated();
        // İşlemi yapan hekim olarak mevcut kullanıcıyı ata
        $validated['dentist_id'] = auth()->id();
        $validated['status'] = 'done'; // İşlem yapıldığı için 'tamamlandı'
        $validated['performed_at'] = now();

        $patientTreatment = PatientTreatment::create($validated);
        return response()->json($patientTreatment, 201);
    }
}