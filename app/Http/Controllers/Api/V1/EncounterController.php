<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\EncounterStatus;
use Illuminate\Validation\Rules\Enum;
use App\Services\NotificationService;
use App\Http\Requests\Api\V1\AssignAndProcessEncounterRequest;
use App\Http\Requests\Api\V1\StoreEncounterRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EncounterController extends Controller
{
    use AuthorizesRequests;

    /**
     * Controller'a NotificationService'i enjekte ediyoruz.
     */
    public function __construct(protected NotificationService $notificationService)
    {
    }

    /**
     * Bir vakanın durumunu günceller (örn: done, cancelled).
     */
    public function updateStatus(Request $request, Encounter $encounter)
    {
        // Yetki kontrolü (Policy oluşturulursa eklenebilir)
        // $this->authorize('update', $encounter);

        $validated = $request->validate([
            'status' => ['required', new Enum(EncounterStatus::class)],
        ]);
        
        $encounter->update($validated);

        return response()->json([
            'message' => 'Vaka durumu güncellendi.',
            'status' => $encounter->status->value
        ]);
    }
    
    /**
     * Bir vakaya sadece hekim atar.
     */
    public function assignDoctor(Request $request, Encounter $encounter)
    {
         // Yetki kontrolü (Policy oluşturulursa eklenebilir)
         // $this->authorize('update', $encounter);

         $validated = $request->validate([
            'dentist_id' => 'required|exists:users,id',
        ]);

        $encounter->update($validated);

        return response()->json(['message' => 'Hekim atandı.']);
    }

    public function store(StoreEncounterRequest $request)
    {
        $validated = $request->validated();

        $encounter = Encounter::create([
            'patient_id' => $validated['patient_id'],
            'dentist_id' => $validated['dentist_id'] ?? null,
            'type' => $validated['type'],
            'triage_level' => $validated['triage_level'],
            'notes' => $validated['notes'] ?? null,
            'arrived_at' => now(),
            'status' => EncounterStatus::WAITING,
        ]);

        if (! empty($validated['dentist_id'])) {
            $this->notificationService->createNotification(
                $encounter->dentist,
                'Yeni Acil Hasta Kaydı',
                "{$encounter->patient->first_name} {$encounter->patient->last_name} isimli hasta acil kaydına eklendi."
            );
        }

        return response()->json([
            'message' => 'Acil/randevusuz hasta kaydı oluşturuldu.',
            'encounter' => $encounter->load(['patient', 'dentist']),
        ], 201);
    }

    /**
     * Bir vakaya hekim atar ve durumunu "İşlemde" olarak günceller.
     */
    public function assignAndProcess(AssignAndProcessEncounterRequest $request, Encounter $encounter)
    {
        $validated = $request->validated();
        $dentist = User::find($validated['dentist_id']);
        $patient = $encounter->patient;

        $encounter->update([
            'dentist_id' => $dentist->id,
            'status' => EncounterStatus::IN_SERVICE,
            'started_at' => now(),
        ]);

        // Atanan hekime bildirim gönder
        $this->notificationService->createNotification(
            $dentist,
            'Yeni Hasta Yönlendirmesi',
            "{$patient->first_name} {$patient->last_name} isimli hasta size yönlendirildi."
        );

        return response()->json([
            'message' => 'Hasta başarıyla hekime atandı ve işleme alındı.',
            'encounter' => $encounter->fresh()->load('dentist'), // Güncel veriyi döndür
        ]);
    }
}