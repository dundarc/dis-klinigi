<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\User;
use App\Models\Treatment;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use App\Enums\TreatmentPlanItemAppointmentAction;
use App\Enums\TreatmentPlanItemStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class TreatmentPlanEditTest extends TestCase
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
        
        // Create test treatments
        $this->treatment1 = Treatment::factory()->create(['name' => 'Filling', 'default_price' => 100.00]);
        $this->treatment2 = Treatment::factory()->create(['name' => 'Cleaning', 'default_price' => 200.00]);
    }

    /** @test */
    public function auto_save_updates_treatment_plan_via_json_request()
    {
        // Create treatment plan with items
        $plan = TreatmentPlan::create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'notes' => 'Original notes',
            'total_estimated_cost' => 200.00,
            'status' => \App\Enums\TreatmentPlanStatus::DRAFT,
        ]);

        $item1 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'treatment_id' => $this->treatment1->id,
            'tooth_number' => '11',
            'estimated_price' => 100.00,
        ]);

        $item2 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'treatment_id' => $this->treatment2->id,
            'tooth_number' => '12',
            'estimated_price' => 200.00,
        ]);

        // Prepare update data with JSON structure
        $updateData = [
            'dentist_id' => $this->user->id,
            'notes' => 'Updated notes',
            'items' => [
                [
                    'id' => $item1->id,
                    'treatment_id' => $item1->treatment_id,
                    'tooth_number' => '21', // Changed
                    'appointment_date' => '',
                    'estimated_price' => 150.00, // Changed
                ],
                [
                    'id' => $item2->id,
                    'treatment_id' => $item2->treatment_id,
                    'tooth_number' => '22', // Changed
                    'appointment_date' => '',
                    'estimated_price' => 250.00, // Changed
                ],
            ],
            'deleted_items' => [],
        ];

        // Send PATCH request with JSON
        $response = $this->actingAs($this->user)
            ->patchJson(route('treatment-plans.update', $plan), $updateData);

        // Assert successful response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Treatment plan updated successfully.'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'updated_items'
            ]);

        // Assert database changes
        $plan->refresh();
        $this->assertEquals('Updated notes', $plan->notes);

        $item1->refresh();
        $this->assertEquals('21', $item1->tooth_number);
        $this->assertEquals(150.00, $item1->estimated_price);

        $item2->refresh();
        $this->assertEquals('22', $item2->tooth_number);
        $this->assertEquals(250.00, $item2->estimated_price);
        
        // Assert total cost updated
        $this->assertEquals(400.00, $plan->total_estimated_cost);
    }

    /** @test */
    public function auto_save_handles_item_addition_and_removal_with_proper_history()
    {
        // Create treatment plan with one item
        $plan = TreatmentPlan::create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'notes' => '',
            'total_estimated_cost' => 100.00,
            'status' => \App\Enums\TreatmentPlanStatus::DRAFT,
        ]);

        $existingItem = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'treatment_id' => $this->treatment1->id,
            'tooth_number' => '11',
            'estimated_price' => 100.00,
        ]);

        // Prepare update data: remove existing item, add new one
        $updateData = [
            'dentist_id' => $this->user->id,
            'notes' => '',
            'items' => [
                [
                    'id' => null, // New item
                    'treatment_id' => $this->treatment2->id,
                    'tooth_number' => '12',
                    'appointment_date' => '',
                    'estimated_price' => 200.00,
                ],
            ],
            'deleted_items' => [$existingItem->id],
        ];

        // Send PATCH request
        $response = $this->actingAs($this->user)
            ->patchJson(route('treatment-plans.update', $plan), $updateData);

        // Assert successful response
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // Assert item was deleted
        $this->assertDatabaseMissing('treatment_plan_items', [
            'id' => $existingItem->id,
        ]);

        // Assert new item was created
        $this->assertDatabaseHas('treatment_plan_items', [
            'treatment_plan_id' => $plan->id,
            'treatment_id' => $this->treatment2->id,
            'tooth_number' => '12',
            'estimated_price' => 200.00,
        ]);

        // Assert history was logged for deletion
        $this->assertDatabaseHas('treatment_plan_item_histories', [
            'treatment_plan_item_id' => $existingItem->id,
            'old_status' => TreatmentPlanItemStatus::PLANNED->value,
            'new_status' => TreatmentPlanItemStatus::CANCELLED->value,
            'notes' => 'Removed from treatment plan',
        ]);
        
        // Assert creation history was logged for new item
        $newItem = TreatmentPlanItem::where('treatment_plan_id', $plan->id)
            ->where('treatment_id', $this->treatment2->id)
            ->first();
        $this->assertNotNull($newItem);
        
        $this->assertDatabaseHas('treatment_plan_item_histories', [
            'treatment_plan_item_id' => $newItem->id,
            'old_status' => null,
            'new_status' => TreatmentPlanItemStatus::PLANNED->value,
            'notes' => 'Treatment plan item created',
        ]);
    }

    /** @test */
    public function item_removal_detaches_from_appointments_and_encounters_with_history()
    {
        // Create treatment plan and item with appointment
        $plan = TreatmentPlan::create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'notes' => '',
            'total_estimated_cost' => 100.00,
            'status' => \App\Enums\TreatmentPlanStatus::DRAFT,
        ]);

        $appointment = Appointment::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $item = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'treatment_id' => $this->treatment1->id,
            'appointment_id' => $appointment->id,
        ]);

        // Link item to an encounter
        $encounter = \App\Models\Encounter::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);
        $encounter->treatmentPlanItems()->attach($item->id);

        // Remove item via update
        $updateData = [
            'dentist_id' => $this->user->id,
            'notes' => '',
            'items' => [],
            'deleted_items' => [$item->id],
        ];

        $this->actingAs($this->user)
            ->patchJson(route('treatment-plans.update', $plan), $updateData);

        // Assert item is deleted
        $this->assertDatabaseMissing('treatment_plan_items', ['id' => $item->id]);

        // Assert detached from encounter
        $this->assertDatabaseMissing('encounter_treatment_plan_item', [
            'treatment_plan_item_id' => $item->id,
        ]);

        // Assert appointment history logged
        $this->assertDatabaseHas('treatment_plan_item_appointment', [
            'treatment_plan_item_id' => $item->id,
            'appointment_id' => $appointment->id,
            'action' => TreatmentPlanItemAppointmentAction::REMOVED->value,
        ]);
        
        // Assert appointment was cancelled since no items remain
        $appointment->refresh();
        $this->assertEquals(AppointmentStatus::CANCELLED, $appointment->status);
        $this->assertStringContains('Tedavi öğeleri kaldırıldı - otomatik iptal edildi', $appointment->notes);
    }

    /** @test */
    public function appointment_date_changes_create_and_update_appointments_correctly()
    {
        // Create treatment plan with item
        $plan = TreatmentPlan::create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'total_estimated_cost' => 100.00,
            'status' => \App\Enums\TreatmentPlanStatus::DRAFT,
        ]);

        $item = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'treatment_id' => $this->treatment1->id,
            'estimated_price' => 100.00,
            'appointment_id' => null, // No appointment initially
        ]);

        $appointmentDate = Carbon::now()->addDays(7)->format('Y-m-d\TH:i');

        // Add appointment date
        $updateData = [
            'dentist_id' => $this->user->id,
            'notes' => '',
            'items' => [
                [
                    'id' => $item->id,
                    'treatment_id' => $item->treatment_id,
                    'tooth_number' => $item->tooth_number,
                    'appointment_date' => $appointmentDate,
                    'estimated_price' => $item->estimated_price,
                ],
            ],
            'deleted_items' => [],
        ];

        $response = $this->actingAs($this->user)
            ->patchJson(route('treatment-plans.update', $plan), $updateData);

        $response->assertStatus(200);

        // Assert appointment was created
        $item->refresh();
        $this->assertNotNull($item->appointment_id);
        
        $appointment = $item->appointment;
        $this->assertEquals($this->patient->id, $appointment->patient_id);
        $this->assertEquals($this->user->id, $appointment->dentist_id);
        $this->assertEquals(AppointmentStatus::SCHEDULED, $appointment->status);

        // Assert appointment history logged
        $this->assertDatabaseHas('treatment_plan_item_appointment', [
            'treatment_plan_item_id' => $item->id,
            'appointment_id' => $appointment->id,
            'action' => TreatmentPlanItemAppointmentAction::PLANNED->value,
        ]);

        // Now update the appointment date
        $newAppointmentDate = Carbon::now()->addDays(14)->format('Y-m-d\TH:i');
        $updateData['items'][0]['appointment_date'] = $newAppointmentDate;

        $this->actingAs($this->user)
            ->patchJson(route('treatment-plans.update', $plan), $updateData);

        // Assert appointment time was updated (same appointment)
        $appointment->refresh();
        $this->assertEquals(Carbon::parse($newAppointmentDate)->format('Y-m-d H:i'), $appointment->start_at->format('Y-m-d H:i'));
    }

    /** @test */
    public function edit_page_loads_with_correct_data_and_components()
    {
        // Create treatment plan with items
        $plan = TreatmentPlan::create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'notes' => 'Test notes',
            'total_estimated_cost' => 100.00,
            'status' => \App\Enums\TreatmentPlanStatus::DRAFT,
        ]);

        $item = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'treatment_id' => $this->treatment1->id,
        ]);

        // Visit edit page
        $response = $this->actingAs($this->user)
            ->get(route('treatment-plans.edit', $plan));

        // Assert successful
        $response->assertStatus(200);

        // Assert data is passed to view
        $response->assertViewHas('treatmentPlan', $plan);
        $response->assertViewHas('formattedItems');
        $response->assertViewHas('dentists');
        $response->assertViewHas('treatments');
        $response->assertViewHas('fileTypes');

        // Assert Alpine.js component initialization
        $response->assertSee('x-data="treatmentPlanForm({');
        $response->assertSee('treatmentPlan:');
        $response->assertSee('formattedItems:');
        
        // Assert auto-save related elements
        $response->assertSee('setInterval');
        $response->assertSee('autosave');
        $response->assertSee('hasChanges');
    }

    /** @test */
    public function multiple_items_with_same_appointment_are_handled_correctly()
    {
        // Create treatment plan
        $plan = TreatmentPlan::create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'total_estimated_cost' => 300.00,
            'status' => \App\Enums\TreatmentPlanStatus::DRAFT,
        ]);

        $appointmentDate = Carbon::now()->addDays(7)->format('Y-m-d\TH:i');

        // Create multiple items for the same appointment
        $updateData = [
            'dentist_id' => $this->user->id,
            'notes' => '',
            'items' => [
                [
                    'id' => null,
                    'treatment_id' => $this->treatment1->id,
                    'tooth_number' => '11',
                    'appointment_date' => $appointmentDate,
                    'estimated_price' => 100.00,
                ],
                [
                    'id' => null,
                    'treatment_id' => $this->treatment2->id,
                    'tooth_number' => '12',
                    'appointment_date' => $appointmentDate,
                    'estimated_price' => 200.00,
                ],
            ],
            'deleted_items' => [],
        ];

        $this->actingAs($this->user)
            ->patchJson(route('treatment-plans.update', $plan), $updateData);

        // Assert two items were created
        $this->assertEquals(2, $plan->items()->count());
        
        // Both items should have appointments (might be different appointments)
        $items = $plan->items()->get();
        $this->assertNotNull($items[0]->appointment_id);
        $this->assertNotNull($items[1]->appointment_id);
        
        // Both appointments should be for the same date
        $this->assertEquals(
            $items[0]->appointment->start_at->format('Y-m-d H:i'),
            $items[1]->appointment->start_at->format('Y-m-d H:i')
        );
    }
}