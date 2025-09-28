<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTreatmentPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Or add specific authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'dentist_id' => 'sometimes|required|exists:users,id',
            'notes' => 'nullable|string',
            'status' => 'sometimes|required|in:draft,active,completed,cancelled',
            'items' => 'sometimes|array',
            'items.*.id' => 'nullable|exists:treatment_plan_items,id', // For existing items
            'items.*.treatment_id' => 'required|exists:treatments,id',
            'items.*.tooth_number' => 'nullable|string|max:255',
            'items.*.appointment_date' => 'nullable|date',
            'items.*.estimated_price' => 'required|numeric|min:0',
            'new_items' => 'sometimes|array',
            'new_items.*.treatment_id' => 'required|exists:treatments,id',
            'new_items.*.tooth_number' => 'nullable|string|max:255',
            'new_items.*.appointment_date' => 'nullable|date',
            'new_items.*.estimated_price' => 'required|numeric|min:0',
            'deleted_items' => 'sometimes|array',
            'deleted_items.*' => 'exists:treatment_plan_items,id',
        ];
    }
}