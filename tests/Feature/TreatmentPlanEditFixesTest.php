<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\Treatment;
use App\Models\TreatmentPlanItem;
use App\Enums\UserRole;
use App\Enums\TreatmentPlanStatus;
use App\Enums\TreatmentPlanItemStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreatmentPlanEditFixesTest extends TestCase
{
    use RefreshDatabase;

    private User $dentist;
    private Patient $patient;
    private TreatmentPlan $treatmentPlan;
    private Treatment $treatment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dentist = User::factory()->create([
            'role' => UserRole::DENTIST,
            'name' => 'Dr. Test',
        ]);

        $this->patient = Patient::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Patient',
        ]);

        $this->treatment = Treatment::factory()->create([
            'name' => 'Test Treatment',
            'default_price' => 100.00,
        ]);

        $this->treatmentPlan = TreatmentPlan::create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->dentist->id,
            'status' => TreatmentPlanStatus::ACTIVE,
            'total_estimated_cost' => 0,
            'notes' => 'Test plan',
        ]);

        $this->actingAs($this->dentist);
    }

    /** @test */
    public function it_can_update_treatment_plan_via_ajax()
    {
        $response = $this->patchJson("/treatment-plans/{$this->treatmentPlan->id}", [
            'dentist_id' => $this->dentist->id,
            'notes' => 'Updated notes',
            'items' => [
                [
                    'treatment_id' => $this->treatment->id,
                    'tooth_number' => '11',
                    'estimated_price' => 150.00,
                ]
            ],
            'deleted_items' => []
        ]);

        $response->assertOk()
                ->assertJson([
                    'success' => true,
                    'message' => 'Treatment plan updated successfully.'
                ])
                ->assertJsonStructure([
                    'updated_items' => [
                        '*' => [
                            'id',
                            'treatment_id',
                            'tooth_number',
                            'estimated_price',
                            'status',
                            'treatment_plan_id'
                        ]
                    ],
                    'total_cost'
                ]);

        $this->treatmentPlan->refresh();
        $this->assertEquals('Updated notes', $this->treatmentPlan->notes);
        $this->assertEquals(150.00, $this->treatmentPlan->total_estimated_cost);
        $this->assertCount(1, $this->treatmentPlan->items);
    }

    /** @test */
    public function it_can_auto_save_treatment_plan()
    {
        $item = TreatmentPlanItem::create([
            'treatment_plan_id' => $this->treatmentPlan->id,
            'treatment_id' => $this->treatment->id,
            'estimated_price' => 100.00,
            'status' => TreatmentPlanItemStatus::PLANNED,
            'tooth_number' => '11',
        ]);

        $response = $this->patchJson("/treatment-plans/{$this->treatmentPlan->id}", [
            'dentist_id' => $this->dentist->id,
            'notes' => 'Auto-saved notes',
            'items' => [
                [
                    'id' => $item->id,
                    'treatment_id' => $this->treatment->id,
                    'tooth_number' => '12',
                    'estimated_price' => 120.00,
                    'treatment_plan_id' => $this->treatmentPlan->id
                ]
            ],
            'deleted_items' => []
        ]);

        $response->assertOk()
                ->assertJson(['success' => true]);

        $item->refresh();
        $this->assertEquals('12', $item->tooth_number);
        $this->assertEquals(120.00, $item->estimated_price);
    }

    /** @test */
    public function it_handles_item_deletion_properly()
    {
        $item = TreatmentPlanItem::create([
            'treatment_plan_id' => $this->treatmentPlan->id,
            'treatment_id' => $this->treatment->id,
            'estimated_price' => 100.00,
            'status' => TreatmentPlanItemStatus::PLANNED,
            'tooth_number' => '11',
        ]);

        $response = $this->patchJson("/treatment-plans/{$this->treatmentPlan->id}", [
            'dentist_id' => $this->dentist->id,
            'notes' => $this->treatmentPlan->notes,
            'items' => [],
            'deleted_items' => [$item->id]
        ]);

        $response->assertOk()
                ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('treatment_plan_items', [
            'id' => $item->id
        ]);
    }

    /** @test */
    public function it_can_complete_treatment_plan_item_via_api()
    {
        $item = TreatmentPlanItem::create([
            'treatment_plan_id' => $this->treatmentPlan->id,
            'treatment_id' => $this->treatment->id,
            'status' => TreatmentPlanItemStatus::PLANNED,
            'tooth_number' => '11',
            'estimated_price' => 100.00,
        ]);

        $response = $this->postJson("/api/v1/treatment-plan-items/{$item->id}/complete");

        $response->assertOk()
                ->assertJson([
                    'success' => true,
                    'message' => 'Treatment plan item marked as completed.'
                ])
                ->assertJsonStructure([
                    'item' => [
                        'id',
                        'status',
                        'completed_at'
                    ]
                ]);

        $item->refresh();
        $this->assertEquals(TreatmentPlanItemStatus::DONE, $item->status);
        $this->assertNotNull($item->completed_at);
    }

    /** @test */
    public function it_can_cancel_treatment_plan_item_via_api()
    {
        $item = TreatmentPlanItem::create([
            'treatment_plan_id' => $this->treatmentPlan->id,
            'treatment_id' => $this->treatment->id,
            'status' => TreatmentPlanItemStatus::PLANNED,
            'tooth_number' => '11',
            'estimated_price' => 100.00,
        ]);

        $response = $this->postJson("/api/v1/treatment-plan-items/{$item->id}/cancel");

        $response->assertOk()
                ->assertJson([
                    'success' => true,
                    'message' => 'Treatment plan item cancelled.'
                ])
                ->assertJsonStructure([
                    'item' => [
                        'id',
                        'status',
                        'cancelled_at'
                    ]
                ]);

        $item->refresh();
        $this->assertEquals(TreatmentPlanItemStatus::CANCELLED, $item->status);
        $this->assertNotNull($item->cancelled_at);
    }
}