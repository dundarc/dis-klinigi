<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Gender;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('patient'));
    }

    /**
     * Veri doğrulamadan ÖNCE çalışır ve gelen isteği manipüle eder.
     * Bu, checkbox gibi alanları işlemek için en doğru yerdir.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // Formdan 'has_private_insurance' alanı gelmiyorsa (işaretli değilse)
            // değerini false (0) olarak ayarla. Geliyorsa true (1) yap.
            'has_private_insurance' => $this->boolean('has_private_insurance'),
            
        ]);
    }

    public function rules(): array
    {
        $patientId = $this->route('patient')->id;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:11', Rule::unique('patients')->ignore($patientId)],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', new Enum(Gender::class)],
            'phone_primary' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('patients')->ignore($patientId)],
            'address_text' => ['nullable', 'string'],
            
            // Hazırlanan bu alanların sadece boolean (true/false) olmasını bekle.
            'has_private_insurance' => ['required', 'boolean'],
        ];
    }
}
