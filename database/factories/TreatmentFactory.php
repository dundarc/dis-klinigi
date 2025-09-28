<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Treatment>
 */
class TreatmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->word(),
            'name' => $this->faker->words(2, true),
            'default_price' => $this->faker->randomFloat(2, 50, 2000),
            'default_vat' => $this->faker->randomFloat(2, 0, 0.25),
            'default_duration_min' => $this->faker->numberBetween(15, 120),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
