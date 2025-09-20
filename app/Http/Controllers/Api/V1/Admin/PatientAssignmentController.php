<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\AssignPatientRequest;
use App\Services\NotificationService;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\User;
use App\Models\Patient;

class PatientAssignmentController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function store(AssignPatientRequest $request)
    {
        $validated = $request->validated();
        $dentist = User::find($validated['dentist_id']);
        $patient = Patient::find($validated['patient_id']);

        if ($validated['type'] === 'appointment') {
            Appointment::create($validated);
            $body = "Admin, {$patient->first_name} isimli hasta için size yeni bir randevu oluşturdu.";
        } else { // walk_in
            Encounter::create([
                'patient_id' => $validated['patient_id'],
                'dentist_id' => $validated['dentist_id'],
                'type' => 'walk_in',
            ]);
            $body = "Admin, {$patient->first_name} isimli hastayı size yönlendirdi.";
        }
        
        // Hekime bildirim gönder
        $this->notificationService->createNotification(
            $dentist,
            'Yeni Hasta Ataması',
            $body
        );

        return response()->json(['message' => 'Hasta başarıyla hekime atandı.'], 201);
    }
}