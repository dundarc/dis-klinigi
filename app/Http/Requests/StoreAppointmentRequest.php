<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\AppointmentStatus;
use Illuminate\Validation\Rules\Enum;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Appointment::class);
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'dentist_id' => ['required', 'exists:users,id'],
            'start_at' => ['required', 'date_format:Y-m-d H:i:s'],
            'end_at' => ['required', 'date_format:Y-m-d H:i:s', 'after:start_at'],
            'status' => ['sometimes', new Enum(AppointmentStatus::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
