<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TreatmentPlanItem>
 */
class TreatmentPlanItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'treatment_plan_id' => \App\Models\TreatmentPlan::factory(),
            'treatment_id' => \App\Models\Treatment::factory(),
            'tooth_number' => $this->faker->optional(0.7)->numberBetween(11, 48),
            'appointment_id' => null, // Will be set when appointment is created
            'estimated_price' => $this->faker->randomFloat(2, 50, 2000),
            'status' => \App\Enums\TreatmentPlanItemStatus::PLANNED,
        ];
    }
}
