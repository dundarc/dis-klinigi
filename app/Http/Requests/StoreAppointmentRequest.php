<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\AppointmentStatus;
use Illuminate\Validation\Rules\Enum;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Appointment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'dentist_id' => ['required', 'exists:users,id'],
            // DÜZELTME: 'date_format' kuralını, 'datetime-local' formatını da kabul eden
            // daha esnek olan 'date' kuralı ile değiştiriyoruz.
            'start_at' => ['required', 'date', 'after:now'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'status' => ['sometimes', new Enum(AppointmentStatus::class)],
            'notes' => ['nullable', 'string'],
            'treatment_plan_items' => ['nullable', 'array'],
            'treatment_plan_items.*' => ['exists:treatment_plan_items,id'],
        ];
    }
}
