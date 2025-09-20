<?php
namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start' => $this->start_at->format('Y-m-d H:i:s'),
            'end' => $this->end_at->format('Y-m-d H:i:s'),
            'status' => $this->status->value,
            'notes' => $this->notes,
            // İlişkili verileri resource'ları kullanarak yüklüyoruz
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'dentist' => new UserResource($this->whenLoaded('dentist')),
        ];
    }
}