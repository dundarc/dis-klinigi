<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InvoiceStatus;
use Illuminate\Validation\Rules\Enum;

class UpdateInvoiceDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Yetki kontrolü, kullanıcının muhasebe özelliklerine erişip erişemediğini kontrol eder.
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
            // Faturanın genel durumu ile ilgili mevcut kurallar
            'status' => ['required', new Enum(InvoiceStatus::class)],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'insurance_coverage_amount' => ['nullable', 'numeric', 'min:0'],
            'taksit_sayisi' => ['nullable', 'required_if:status,taksitlendirildi', 'integer', 'min:2'],
            'ilk_odeme_gunu' => ['nullable', 'required_if:status,taksitlendirildi', 'date'],

            // YENİ EKLENEN KURALLAR: Fatura kalemleri için doğrulama
            // 'items' adında en az bir eleman içeren bir dizi olmalıdır.
            'items' => ['sometimes', 'array', 'min:1'],
            // Dizinin içindeki her bir eleman için aşağıdaki kurallar geçerlidir:
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.vat' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }
}

