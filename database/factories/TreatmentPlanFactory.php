<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TreatmentPlan>
 */
class TreatmentPlanFactory extends Factory
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
            'dentist_id' => \App\Models\User::factory(),
            'notes' => $this->faker->optional()->sentence(),
            'total_estimated_cost' => $this->faker->randomFloat(2, 100, 5000),
            'status' => \App\Enums\TreatmentPlanStatus::ACTIVE,
        ];
    }
}
