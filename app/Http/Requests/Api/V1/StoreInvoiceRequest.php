<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Invoice;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Invoice::class);
    }

    /**
     * Gelen isteği doğrulamadan ÖNCE hazırlar.
     * Bu, string halindeki ID'leri integer'a çevirmek için en doğru yerdir.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('treatment_ids')) {
            $this->merge([
                'treatment_ids' => array_map('intval', $this->treatment_ids)
            ]);
        }
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
            'issue_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'treatment_ids' => ['required', 'array', 'min:1'],
            'treatment_ids.*' => ['integer', 'exists:patient_treatments,id'],
        ];
    }
}

