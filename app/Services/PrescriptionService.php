<?php

namespace App\Services;

use App\Models\Encounter;
use App\Models\Patient;
use App\Models\Prescription;
use App\Repositories\PrescriptionRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;

class PrescriptionService
{
    public function __construct(private readonly PrescriptionRepository $prescriptionRepository)
    {
    }

    public function create(array $data, Authenticatable $dentist): Prescription
    {
        $patient = Patient::findOrFail($data['patient_id']);
        $encounterId = $data['encounter_id'] ?? null;

        $encounterId = $this->validateEncounterOwnership($encounterId, $patient->id);

        return $this->prescriptionRepository->create([
            'patient_id' => $patient->id,
            'encounter_id' => $encounterId,
            'dentist_id' => $dentist->getAuthIdentifier(),
            'text' => $data['text'],
        ], ['dentist:id,name']);
    }

    public function update(Prescription $prescription, array $data): Prescription
    {
        $updatePayload = [];

        if (array_key_exists('text', $data)) {
            $updatePayload['text'] = $data['text'];
        }

        if (array_key_exists('encounter_id', $data)) {
            $encounterId = $data['encounter_id'];
            $updatePayload['encounter_id'] = $this->validateEncounterOwnership($encounterId, $prescription->patient_id);
        }

        if (empty($updatePayload)) {
            return $prescription->load('dentist:id,name');
        }

        return $this->prescriptionRepository->update($prescription, $updatePayload, ['dentist:id,name']);
    }

    public function delete(Prescription $prescription): void
    {
        $this->prescriptionRepository->delete($prescription);
    }

    public function createOrUpdateForEncounter(Encounter $encounter, array $data, Authenticatable $dentist): Prescription
    {
        $prescription = $encounter->prescriptions()->first();

        if ($prescription) {
            // Update existing prescription
            return $this->update($prescription, [
                'text' => $data['content'],
            ]);
        } else {
            // Create new prescription
            return $this->create([
                'patient_id' => $encounter->patient_id,
                'encounter_id' => $encounter->id,
                'text' => $data['content'],
            ], $dentist);
        }
    }

    private function validateEncounterOwnership(?int $encounterId, int $patientId): ?int
    {
        if (! $encounterId) {
            return null;
        }

        $encounter = Encounter::where('id', $encounterId)
            ->where('patient_id', $patientId)
            ->first();

        if (! $encounter) {
            throw ValidationException::withMessages([
                'encounter_id' => __('Bu reçete için seçilen ziyaret geçersiz.'),
            ]);
        }

        return $encounter->id;
    }
}