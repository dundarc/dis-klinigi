<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Enums\UserRole;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            
            'phone' => fake('tr_TR')->phoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => UserRole::DENTIST,
            
        ];
    }
    
    /**
     * Indicate that the model's email address should be unverified.
     * BU METODU EKLEYİN
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    // Rol bazlı state'ler (bunlar zaten vardı)
    public function admin(): static { return $this->state(fn (array $attributes) => ['role' => UserRole::ADMIN]); }
    public function dentist(): static { return $this->state(fn (array $attributes) => ['role' => UserRole::DENTIST]); }
    public function assistant(): static { return $this->state(fn (array $attributes) => ['role' => UserRole::ASSISTANT]); }
    public function receptionist(): static { return $this->state(fn (array $attributes) => ['role' => UserRole::RECEPTIONIST]); }
}