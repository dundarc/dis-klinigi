<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InvoiceStatus;
use Illuminate\Validation\Rules\Enum;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Yetki kontrolünü, genel bir Gate yerine,
        // InvoicePolicy'deki 'update' kuralını kullanarak yapıyoruz.
        // Admin'in "süper yetkisi" bu kuralı otomatik olarak geçecektir.
        return $this->user()->can('update', $this->route('invoice'));
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
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'taksit_sayisi' => ['nullable', 'required_if:status,taksitlendirildi', 'integer', 'min:2'],
            'ilk_odeme_gunu' => ['nullable', 'required_if:status,taksitlendirildi', 'date'],
        ];
    }
}

