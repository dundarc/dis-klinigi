<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRole;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Düzenlenen kullanıcının ID'sini al
        $userId = $this->route('user')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            // 'unique' kuralı, mevcut kullanıcının kendisini göz ardı etmeli
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'role' => ['required', new Enum(UserRole::class)],
            'is_active' => ['required', 'boolean'],
            // Şifre alanı sadece doluysa doğrulanır.
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];
    }
}
