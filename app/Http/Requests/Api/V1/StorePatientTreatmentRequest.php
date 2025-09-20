<?php
namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientTreatmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\PatientTreatment::class);
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'treatment_id' => 'required|exists:treatments,id',
            'tooth_number' => 'nullable|integer|between:11,48',
            'unit_price' => 'required|numeric|min:0',
            'vat' => 'required|numeric|between:0,100',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}