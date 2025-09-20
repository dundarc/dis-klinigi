<?php
namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRole;
use Illuminate\Validation\Rule;

class AssignAndProcessEncounterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Bu işlemi sadece Admin ve Resepsiyonist yapabilir
        return in_array($this->user()->role, [UserRole::ADMIN, UserRole::RECEPTIONIST]);
    }

    public function rules(): array
    {
        return [
            'dentist_id' => ['required', Rule::exists('users', 'id')->where('role', UserRole::DENTIST)],
        ];
    }
}