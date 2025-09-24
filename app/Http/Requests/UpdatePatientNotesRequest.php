<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientNotesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Yetki kontrolü, kullanıcının bu hastayı güncelleyip güncelleyemeyeceğini
        // PatientPolicy üzerinden kontrol eder.
        return $this->user()->can('update', $this->route('patient'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Bu form sadece notlar ve ilaçlar alanını güncellediği için
        // sadece bu alanların kurallarını içerir.
        return [
            'notes' => ['nullable', 'string'],
            'medications_used' => ['nullable', 'string'],
        ];
    }
}
