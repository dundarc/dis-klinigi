<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Patient::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'size:11', 'regex:/^[0-9]+$/'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'phone_primary' => ['required', 'string', 'max:20'],
            'phone_secondary' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_text' => ['nullable', 'string', 'max:1000'],
            'has_private_insurance' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'Ad',
            'last_name' => 'Soyad',
            'national_id' => 'T.C. Kimlik Numarası',
            'birth_date' => 'Doğum Tarihi',
            'gender' => 'Cinsiyet',
            'phone_primary' => 'Telefon (Birincil)',
            'phone_secondary' => 'Telefon (İkincil)',
            'email' => 'E-posta Adresi',
            'address_text' => 'Adres',
            'has_private_insurance' => 'Özel Sağlık Sigortası',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'national_id.size' => 'T.C. Kimlik Numarası 11 haneli olmalıdır.',
            'national_id.regex' => 'T.C. Kimlik Numarası sadece rakamlardan oluşmalıdır.',
            'birth_date.before' => 'Doğum tarihi bugün\'den önce olmalıdır.',
            'gender.in' => 'Geçersiz cinsiyet seçimi.',
        ];
    }
}
