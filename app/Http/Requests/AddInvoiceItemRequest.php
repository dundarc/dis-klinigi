<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class AddInvoiceItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(UserRole::ADMIN) || $this->user()->hasRole(UserRole::ACCOUNTANT);
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'patient_treatment_id' => ['nullable', 'exists:patient_treatments,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'Açıklama alanı zorunludur.',
            'quantity.required' => 'Miktar alanı zorunludur.',
            'quantity.integer' => 'Miktar tam sayı olmalıdır.',
            'quantity.min' => 'Miktar en az 1 olmalıdır.',
            'unit_price.required' => 'Birim fiyat alanı zorunludur.',
            'unit_price.numeric' => 'Birim fiyat sayı olmalıdır.',
            'unit_price.min' => 'Birim fiyat 0\'dan büyük olmalıdır.',
            'vat_rate.required' => 'KDV oranı zorunludur.',
            'vat_rate.numeric' => 'KDV oranı sayı olmalıdır.',
            'vat_rate.min' => 'KDV oranı 0\'dan küçük olamaz.',
            'vat_rate.max' => 'KDV oranı 100\'den büyük olamaz.',
            'patient_treatment_id.exists' => 'Seçilen tedavi geçersiz.',
        ];
    }
}