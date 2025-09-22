<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Bu format, FullCalendar'ın doğrudan anlayacağı standart bir yapıdır.
        return [
            // FullCalendar'ın ana alanları
            'id'      => $this->id,
            'title'   => $this->patient->first_name . ' ' . $this->patient->last_name,
            'start'   => $this->start_at->toIso8601String(), // ISO8601 formatı en güvenilir yöntemdir
            'end'     => $this->end_at->toIso8601String(),

            // Renklendirme, modal doldurma ve diğer mantıklar için kullanılacak özel veriler.
            // Tüm özel verileri 'extendedProps' içine grupluyoruz.
            'extendedProps' => [
                'status'  => $this->status->value,
                'notes'   => $this->notes,
                'patient' => [ // Modal'da kullanmak için temel hasta bilgileri
                    'id' => $this->patient->id,
                    'name' => $this->patient->first_name . ' ' . $this->patient->last_name,
                ],
                'dentist' => [ // Renklendirme ve modal için hekim bilgileri
                    'id' => $this->dentist->id,
                    'name' => $this->dentist->name,
                ]
            ]
        ];
    }
}

