<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;
use App\Enums\InvoiceStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 500, 8000);
        $vatRate = 0.20; // %20 KDV varsayalım
        $vatTotal = $subtotal * $vatRate;
        $grandTotal = $subtotal + $vatTotal;

        return [
            'patient_id' => Patient::factory(), // Eğer belirtilmezse yeni bir hasta oluşturur
            'invoice_no' => 'FAT-' . fake()->unique()->numberBetween(20250001, 20259999),
            'issue_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'subtotal' => $subtotal,
            'vat_total' => $vatTotal,
            'discount_total' => 0,
            'grand_total' => $grandTotal,
            'status' => fake()->randomElement(InvoiceStatus::class),
            'notes' => fake()->boolean(20) ? fake()->sentence() : null,
            'insurance_coverage_amount' => 0, // Varsayılan
        ];
    }
}