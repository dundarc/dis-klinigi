<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\User;
use App\Models\Treatment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreatmentPlanEditSimpleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user (dentist)
        $this->user = User::factory()->create([
            'role' => \App\Enums\UserRole::DENTIST,
        ]);

        // Create test patient
        $this->patient = Patient::factory()->create();
        
        // Create test treatment
        $this->treatment = Treatment::factory()->create(['name' => 'Filling', 'default_price' => 100.00]);
    }

    /** @test */
    public function edit_page_loads_successfully()
    {
        // Create treatment plan
        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);

        // Visit edit page
        $response = $this->actingAs($this->user)
            ->get(route('treatment-plans.edit', $plan));

        // Assert successful
        $response->assertStatus(200);
    }

    /** @test */
    public function simple_plan_update_works()
    {
        // Create treatment plan
        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'notes' => 'Original notes',
        ]);

        // Simple update data
        $updateData = [
            'dentist_id' => $this->user->id,
            'notes' => 'Updated notes',
            'items' => [],
            'deleted_items' => [],
        ];

        // Send PATCH request
        $response = $this->actingAs($this->user)
            ->patchJson(route('treatment-plans.update', $plan), $updateData);

        // Assert successful response
        $response->assertStatus(200);

        // Assert database changes
        $plan->refresh();
        $this->assertEquals('Updated notes', $plan->notes);
    }

    /** @test */
    public function can_add_single_item_to_plan()
    {
        // Create treatment plan
        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);

        // Update data with one new item
        $updateData = [
            'dentist_id' => $this->user->id,
            'notes' => '',
            'items' => [
                [
                    'id' => null, // New item
                    'treatment_id' => $this->treatment->id,
                    'tooth_number' => '11',
                    'appointment_date' => '',
                    'estimated_price' => 100.00,
                ],
            ],
            'deleted_items' => [],
        ];

        // Send PATCH request
        $response = $this->actingAs($this->user)
            ->patchJson(route('treatment-plans.update', $plan), $updateData);

        // Assert successful response
        $response->assertStatus(200);

        // Assert item was created
        $this->assertEquals(1, $plan->items()->count());
        $this->assertDatabaseHas('treatment_plan_items', [
            'treatment_plan_id' => $plan->id,
            'treatment_id' => $this->treatment->id,
            'tooth_number' => '11',
            'estimated_price' => 100.00,
        ]);
    }
}