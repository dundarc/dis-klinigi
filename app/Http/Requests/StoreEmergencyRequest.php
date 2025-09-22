<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRole;
use App\Enums\TriageLevel;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreEmergencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Yetki kontrolü için EncounterPolicy'deki 'createEmergency' metodunu kullan
        return $this->user()->can('createEmergency', \App\Models\Encounter::class);
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
            'triage_level' => ['required', new Enum(TriageLevel::class)],
            'dentist_id' => [
                'required',
                // Seçilen kullanıcının var olduğunu ve rolünün hekim olduğunu doğrula
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role', UserRole::DENTIST);
                })
            ],
            'notes' => ['nullable', 'string'],
        ];
    }
}