<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\Gender;

class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'national_id' => fake()->unique()->numerify('###########'),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-3 years')->format('Y-m-d'),
            'gender' => fake()->randomElement(Gender::class),
            'phone_primary' => fake('tr_TR')->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'address_text' => fake('tr_TR')->address(),
            'consent_kvkk_at' => now(),
        ];
    }
}