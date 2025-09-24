<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'title' => $this->patient->first_name.' '.$this->patient->last_name,
            'start' => $this->start_at->toIso8601String(),
            'end'   => $this->end_at->toIso8601String(),
            'extendedProps' => [
                'status' => $this->status->value,
                'notes'  => $this->notes,
                'patient' => [
                    'id'   => $this->patient->id,
                    'name' => $this->patient->first_name.' '.$this->patient->last_name,
                ],
                'dentist' => [
                    'id'   => $this->dentist->id,
                    'name' => $this->dentist->name,
                ],
            ],
        ];
    }
}