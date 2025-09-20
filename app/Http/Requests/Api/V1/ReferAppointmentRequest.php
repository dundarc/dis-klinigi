<?php
namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\UserRole;

class ReferAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('refer', $this->route('appointment'));
    }

    public function rules(): array
    {
        return [
            'referred_to_user_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', UserRole::DENTIST);
                }),
                // Kendisine sevk edememeli
                Rule::notIn([$this->user()->id]),
            ],
            'referral_notes' => 'nullable|string|max:1000',
        ];
    }
}