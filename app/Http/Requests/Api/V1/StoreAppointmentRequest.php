<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Appointment; // 1. Bu 'use' ifadesinin burada olduğundan emin olun.

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 2. Kodu daha okunaklı hale getirmek için tam yolu siliyoruz.
        return $this->user()->can('create', Appointment::class);
    }

    public function rules(): array
    {
        // ... (rules metodu daha önceki adımlarda düzelttiğimiz gibi kalacak)
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'dentist_id' => ['required', 'exists:users,id'],
            'start_at' => ['required', 'date_format:Y-m-d H:i:s', 'after:now'],
            'notes' => ['nullable', 'string'],
            'end_at' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:start_at',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $isConflict = Appointment::where('dentist_id', $this->dentist_id)
                        ->where(function ($query) use ($value) {
                            $query->where('start_at', '<', $value)
                                  ->where('end_at', '>', $this->start_at);
                        })->exists();

                    if ($isConflict) {
                        $fail('Seçilen zaman aralığında bu hekim için başka bir randevu bulunmaktadır.');
                    }
                },
            ],
        ];
    }
}