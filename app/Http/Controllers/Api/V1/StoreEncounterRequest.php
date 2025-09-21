<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\EncounterType;
use App\Enums\TriageLevel;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreEncounterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST], true);
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'dentist_id' => ['nullable', Rule::exists('users', 'id')->where('role', UserRole::DENTIST)],
            'type' => ['required', new Enum(EncounterType::class)],
            'triage_level' => ['required', new Enum(TriageLevel::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}