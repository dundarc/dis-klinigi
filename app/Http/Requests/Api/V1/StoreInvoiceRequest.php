<?php
namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Invoice::class);
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'issue_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.patient_treatment_id' => 'nullable|exists:patient_treatments,id',
            'items.*.description' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.vat' => 'required|numeric|between:0,100',
        ];
    }
}