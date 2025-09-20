<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\AppointmentStatus;
use App\Models\Patient; // Bu satırı ekleyin
use App\Models\User;    // Bu satırı ekleyin

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        $startAt = fake()->dateTimeBetween('-1 month', '+2 months');
        $endAt = (clone $startAt)->modify('+' . fake()->randomElement([30, 60, 90]) . ' minutes');

        return [
            // Eğer testte özel olarak verilmezse, factory yeni bir hasta ve hekim oluştursun.
            'patient_id' => Patient::factory(),
            'dentist_id' => User::factory()->dentist(),

            'start_at' => $startAt,
            'end_at' => $endAt,
            'status' => fake()->randomElement(AppointmentStatus::class),
            'notes' => fake()->boolean(25) ? fake()->sentence() : null,
        ];
    }
}