<?php
namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'fullName' => $this->first_name . ' ' . $this->last_name,
            'birthDate' => $this->birth_date->format('d.m.Y'),
            'gender' => $this->gender->value,
            'phonePrimary' => $this->phone_primary,
        ];
    }
}