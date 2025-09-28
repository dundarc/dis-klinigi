<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTreatmentPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Or add specific authorization logic, e.g., auth()->user()->isDentist()
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.treatment_id' => 'required|exists:treatments,id',
            'items.*.tooth_number' => 'nullable|string|max:255',
            'items.*.appointment_date' => 'nullable|date',
            'items.*.estimated_price' => 'required|numeric|min:0',
        ];
    }
}