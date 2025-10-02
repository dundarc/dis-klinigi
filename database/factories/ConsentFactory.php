<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consent>
 */
class ConsentFactory extends Factory
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
            'version' => '1.0',
            'status' => \App\Enums\ConsentStatus::ACTIVE,
            'accepted_at' => now(),
            'ip_address' => '127.0.0.1',
            'user_agent' => 'TestAgent',
            'snapshot' => ['test' => 'data'],
            'hash' => hash('sha256', json_encode(['test' => 'data'])),
        ];
    }
}
