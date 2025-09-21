<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\Gender;
use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Patient::class);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'digits:11', Rule::unique('patients', 'national_id')],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', new Enum(Gender::class)],
            'phone_primary' => ['required', 'string', 'max:20'],
            'phone_secondary' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_text' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}