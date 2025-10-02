<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KvkkAuditLog>
 */
class KvkkAuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => \App\Models\Patient::factory(),
            'user_id' => \App\Models\User::factory(),
            'action' => fake()->randomElement(['soft_delete', 'hard_delete', 'restore']),
            'ip_address' => fake()->ipv4(),
            'meta' => [
                'reason' => fake()->sentence(),
                'additional_info' => fake()->paragraph(),
            ],
        ];
    }
}
