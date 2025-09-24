<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InvoiceStatus;
use Illuminate\Validation\Rules\Enum;

class UpdateInvoiceDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Yetki kontrolü rotada ve controller'da yapıldığı için true dönebiliriz.
        return $this->user()->can('accessAccountingFeatures');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(InvoiceStatus::class)],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'paid_at' => ['nullable', 'required_if:status,paid', 'date'],
            'due_date' => ['nullable', 'required_if:status,vadeli', 'date'],
            'notes' => ['nullable', 'string'],
            'insurance_coverage_amount' => ['nullable', 'numeric', 'min:0'],
            'taksit_sayisi' => ['nullable', 'required_if:status,taksitlendirildi', 'integer', 'min:2'],
            'ilk_odeme_gunu' => ['nullable', 'required_if:status,taksitlendirildi', 'date'],
        ];
    }
}

    