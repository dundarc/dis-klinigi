<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\EncounterType;
use App\Enums\TriageLevel;
use App\Enums\EncounterStatus;

class EncounterFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Seeder içinde patient_id ve dentist_id atanacak
            'type' => EncounterType::WALK_IN, // Default
            'arrived_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'status' => EncounterStatus::WAITING,
        ];
    }

    public function emergency(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => EncounterType::EMERGENCY,
            'triage_level' => fake()->randomElement(TriageLevel::class),
        ]);
    }
    
    public function walkIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => EncounterType::WALK_IN,
            'triage_level' => TriageLevel::GREEN,
        ]);
    }
}