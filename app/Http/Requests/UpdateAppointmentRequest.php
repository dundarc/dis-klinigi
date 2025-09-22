<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\AppointmentStatus;
use Illuminate\Validation\Rules\Enum;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('appointment'));
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['sometimes', 'required', 'exists:patients,id'],
            'dentist_id' => ['sometimes', 'required', 'exists:users,id'],
            'start_at' => ['sometimes', 'required', 'date_format:Y-m-d H:i:s'],
            'end_at' => ['sometimes', 'required', 'date_format:Y-m-d H:i:s', 'after:start_at'],
            'status' => ['sometimes', 'required', new Enum(AppointmentStatus::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
