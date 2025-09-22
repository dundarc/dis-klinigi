<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\File;
use App\Http\Requests\Api\V1\StorePatientFileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. Bu 'use' ifadesini ekleyin

class PatientFileController extends Controller
{
    use AuthorizesRequests; // 2. Bu özelliği sınıfa dahil edin

    public function store(StorePatientFileRequest $request, Patient $patient)
    {
        $file = $request->file('file');
        // Dosyayı 'storage/app/public/patient_files/{hasta_id}' klasörüne kaydet
        $path = $file->store('patient_files/' . $patient->id, 'public');

        $patientFile = $patient->files()->create([
            'uploaded_by' => auth()->id(),
            'type' => $request->input('type'),
            'notes' => $request->input('notes'),
            'file_path' => $path,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return response()->json($patientFile, 201);
    }

    /**
     * Yüklenmiş bir dosyayı siler.
     */
    public function destroy(File $file)
    {
        // Bu satır artık hata vermeyecektir
        $this->authorize('delete', $file);

        // Diskteki fiziksel dosyayı sil
        Storage::disk('public')->delete($file->file_path);

        // Veritabanındaki kaydı sil
        $file->delete();

        return response()->noContent();
    }
}

