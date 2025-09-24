<?php

namespace App\Http\Requests;

use App\Models\Prescription;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\Prescription $prescription */
        $prescription = $this->route('prescription');

        return $this->user()->can('update', $prescription);
    }

    public function rules(): array
    {
        return [
            'text' => ['sometimes', 'required', 'string', 'max:5000'],
            'encounter_id' => ['sometimes', 'nullable', 'integer', 'exists:encounters,id'],
        ];
    }
}