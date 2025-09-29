<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(UserRole::ADMIN) || $this->user()->hasRole(UserRole::ACCOUNTANT);
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'vat' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'Açıklama alanı zorunludur.',
            'qty.required' => 'Miktar alanı zorunludur.',
            'qty.integer' => 'Miktar tam sayı olmalıdır.',
            'qty.min' => 'Miktar en az 1 olmalıdır.',
            'unit_price.required' => 'Birim fiyat alanı zorunludur.',
            'unit_price.numeric' => 'Birim fiyat sayı olmalıdır.',
            'unit_price.min' => 'Birim fiyat 0\'dan büyük olmalıdır.',
            'vat.required' => 'KDV oranı zorunludur.',
            'vat.numeric' => 'KDV oranı sayı olmalıdır.',
            'vat.min' => 'KDV oranı 0\'dan küçük olamaz.',
            'vat.max' => 'KDV oranı 100\'den büyük olamaz.',
        ];
    }
}