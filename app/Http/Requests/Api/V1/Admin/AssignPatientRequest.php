<?php
namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRole;
use Illuminate\Validation\Rule;

class AssignPatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('accessAdminFeatures');
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => ['required', Rule::exists('users', 'id')->where('role', UserRole::DENTIST)],
            'type' => 'required|in:appointment,walk_in',
            'start_at' => 'required_if:type,appointment|date_format:Y-m-d H:i:s',
            'end_at' => 'required_if:type,appointment|date_format:Y-m-d H:i:s|after:start_at',
        ];
    }
}