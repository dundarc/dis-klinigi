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
            'uploader_id' => $request->user()->id,
            'type' => $request->input('type'),
            'filename' => basename($path),
            'original_filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'notes' => $request->input('notes'),
        ]);

        return response()->json($patientFile->load('uploader:id,name'), 201);
    }

    public function update(Request $request, File $file)
    {
        $this->authorize('update', $file);

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:' . implode(',', array_map(fn($case) => $case->value, \App\Enums\FileType::cases()))],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $file->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Dosya bilgileri güncellendi.',
            'file' => $file->load('uploader:id,name')
        ]);
    }

    public function destroy(File $file)
    {
        $this->authorize('delete', $file);

        // Soft delete - don't delete the actual file from storage
        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dosya başarıyla silindi.',
        ]);
    }
}