<?php

namespace App\Http\Requests;

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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // DÜZELTME: Bu form artık 'treatment_ids' değil,
        // hem 'prepare.blade.php'den hem de 'show.blade.php'den gönderilen
        // 'items' dizisini doğrular.
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'issue_date' => ['required', 'date'],
            'status' => ['required', 'in:draft,unpaid,vadeli'],
            'due_date' => ['required_if:status,vadeli', 'nullable', 'date', 'after:today'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'items.*.patient_treatment_id' => ['nullable', 'integer', 'exists:patient_treatments,id'],
        ];
    }
}

