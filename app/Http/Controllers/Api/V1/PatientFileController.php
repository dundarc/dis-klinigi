<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePatientFileRequest;
use App\Models\Encounter;
use App\Models\File;
use App\Models\Patient;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class PatientFileController extends Controller
{
    use AuthorizesRequests;

    public function store(StorePatientFileRequest $request, Patient $patient)
    {
        $encounterId = $request->input('encounter_id');

        if ($encounterId) {
            $encounter = Encounter::where('id', $encounterId)
                ->where('patient_id', $patient->id)
                ->firstOrFail();
        }

        $file = $request->file('file');
        $path = $file->store('patient_files/' . $patient->id, 'public');

        $patientFile = $patient->files()->create([
            'encounter_id' => $encounterId,
            'uploaded_by' => $request->user()->id,
            'type' => $request->input('type'),
            'notes' => $request->input('notes'),
            'file_path' => $path,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return response()->json($patientFile->load('uploader:id,name'), 201);
    }

    public function destroy(File $file)
    {
        $this->authorize('delete', $file);

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return response()->noContent();
    }
}