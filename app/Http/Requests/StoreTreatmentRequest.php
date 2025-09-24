<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTreatmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('accessAdminFeatures') ?? false;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('treatments', 'code')->whereNull('deleted_at')],
            'name' => ['required', 'string', 'max:255'],
            'default_price' => ['required', 'numeric', 'min:0'],
            'default_vat' => ['required', 'numeric', 'min:0', 'max:100'],
            'default_duration_min' => ['required', 'integer', 'min:5'],
            'description' => ['nullable', 'string'],
        ];
    }
}

