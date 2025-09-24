<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\FileType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePatientFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\File::class);
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,dicom', 'max:10240'],
            'type' => ['required', new Enum(FileType::class)],
            'notes' => ['nullable', 'string'],
            'encounter_id' => ['nullable', 'integer', 'exists:encounters,id'],
        ];
    }
}