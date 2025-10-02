<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Gender;
use Illuminate\Validation\Rules\Enum;

class StorePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Yetki kontrolü için PatientPolicy'deki 'create' metodunu kullanıyoruz.
        return $this->user()->can('create', \App\Models\Patient::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:11', 'unique:patients,national_id'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', new Enum(Gender::class)],
            'phone_primary' => ['required', 'string', 'max:20'],
            'phone_secondary' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_text' => ['nullable', 'string'],
            'tax_office' => ['nullable', 'string', 'max:255'],
            'emergency_contact_person' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'medications_used' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'has_private_insurance' => ['sometimes', 'boolean'],
        ];
    }
}
