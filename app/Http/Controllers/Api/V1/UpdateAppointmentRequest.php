<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route model binding ile gelen randevuyu alıp policy'de kontrol ediyoruz
        $appointment = $this->route('appointment');
        return $this->user()->can('update', $appointment);
    }

    public function rules(): array
    {
        // Güncelleme için kurallar daha esnek olabilir. Şimdilik temel kuralları ekleyelim.
        // Çakışma kontrolü gibi daha karmaşık kurallar buraya eklenebilir.
        return [
            'patient_id' => 'sometimes|required|exists:patients,id',
            'dentist_id' => 'sometimes|required|exists:users,id',
            'start_at' => 'sometimes|required|date_format:Y-m-d H:i:s',
            'end_at' => 'sometimes|required|date_format:Y-m-d H:i:s|after:start_at',
            'notes' => 'nullable|string',
        ];
    }
}