<?php

namespace App\Http\Requests;

use App\Models\PatientTreatment;
use Illuminate\Foundation\Http\FormRequest;

class StorePatientTreatmentRequest extends FormRequest
{
    /**
     * Kullanıcının bu isteği yapmaya yetkisi var mı?
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', PatientTreatment::class);
    }

    /**
     * İsteğe uygulanacak doğrulama kuralları.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
     public function rules(): array
    {
        return [
            'treatment_id' => ['required', 'exists:treatments,id'],
            'dentist_id' => ['required', 'exists:users,id'],
            'performed_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'invoice_amount' => ['nullable', 'numeric'],
            'xray_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }
}