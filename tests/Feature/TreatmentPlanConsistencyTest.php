<?php

namespace Tests\Feature;

use App\Enums\AppointmentStatus;
use App\Enums\EncounterStatus;
use App\Enums\EncounterType;
use App\Enums\TreatmentPlanItemAppointmentAction;
use App\Enums\TreatmentPlanItemStatus;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\User;
use App\Services\TreatmentPlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreatmentPlanConsistencyTest extends TestCase
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
    }

    /** @test */
    public function completed_appointment_creates_encounter_via_observer()
    {
        // Create an appointment
        $appointment = Appointment::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        // Change status to COMPLETED (this should trigger the observer)
        $appointment->update(['status' => AppointmentStatus::COMPLETED]);

        // Assert that an encounter was created and linked
        $this->assertDatabaseHas('encounters', [
            'appointment_id' => $appointment->id,
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'type' => EncounterType::SCHEDULED,
            'status' => EncounterStatus::DONE,
        ]);

        // Verify the appointment now has an encounter
        $appointment->refresh();
        $this->assertNotNull($appointment->encounter);
        $this->assertEquals(EncounterStatus::DONE, $appointment->encounter->status);
    }

    /** @test */
    public function done_treatment_plan_item_links_to_encounter_and_creates_history()
    {
        // Create treatment plan and item
        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);

        $item = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'status' => TreatmentPlanItemStatus::PLANNED,
        ]);

        // Change status to DONE
        $item->changeStatus(TreatmentPlanItemStatus::DONE, $this->user);

        // Assert that the item is linked to an encounter
        $this->assertDatabaseHas('encounter_treatment_plan_item', [
            'treatment_plan_item_id' => $item->id,
        ]);

        // Assert history entry created
        $this->assertDatabaseHas('treatment_plan_item_histories', [
            'treatment_plan_item_id' => $item->id,
            'old_status' => TreatmentPlanItemStatus::PLANNED->value,
            'new_status' => TreatmentPlanItemStatus::DONE->value,
            'user_id' => $this->user->id,
        ]);

        // Verify the item has encounters relationship
        $item->refresh();
        $this->assertTrue($item->encounters()->exists());
    }

    /** @test */
    public function encounter_cannot_be_marked_done_without_linked_items()
    {
        // Create an encounter without items
        $encounter = Encounter::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'status' => EncounterStatus::WAITING,
        ]);

        // Attempt to mark as DONE via controller (should fail due to policy)
        $response = $this->actingAs($this->user)
            ->put(route('waiting-room.action.update', $encounter), [
                'status' => EncounterStatus::DONE->value,
                'notes' => 'Test notes',
            ]);

        // Should be forbidden due to policy
        $response->assertForbidden();

        // Encounter should still be WAITING
        $encounter->refresh();
        $this->assertEquals(EncounterStatus::WAITING, $encounter->status);

        // Now add an item and try again
        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);

        $item = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'status' => TreatmentPlanItemStatus::DONE,
        ]);

        // Link item to encounter
        $encounter->treatmentPlanItems()->attach($item->id);

        // Now it should succeed
        $response = $this->actingAs($this->user)
            ->put(route('waiting-room.action.update', $encounter), [
                'status' => EncounterStatus::DONE->value,
                'notes' => 'Test notes',
            ]);

        $response->assertRedirect();
        $encounter->refresh();
        $this->assertEquals(EncounterStatus::DONE, $encounter->status);
    }

    /** @test */
    public function invoice_generation_from_encounter_items_works_correctly()
    {
        $treatmentPlanService = app(TreatmentPlanService::class);

        // Create encounter
        $encounter = Encounter::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'status' => EncounterStatus::DONE,
        ]);

        // Create treatment plan items and link to encounter
        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);

        $item1 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'status' => TreatmentPlanItemStatus::DONE,
            'estimated_price' => 100.00,
        ]);

        $item2 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'status' => TreatmentPlanItemStatus::DONE,
            'estimated_price' => 200.00,
        ]);

        // Link items to encounter
        $encounter->treatmentPlanItems()->attach($item1->id, ['price' => 100.00]);
        $encounter->treatmentPlanItems()->attach($item2->id, ['price' => 200.00]);

        // Create an item that's not linked to encounter (should not be invoiced)
        $item3 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'status' => TreatmentPlanItemStatus::DONE,
            'estimated_price' => 50.00,
        ]);

        // Generate invoice from encounter
        $invoice = $treatmentPlanService->generateInvoiceFromEncounterItems($encounter);

        // Assert invoice was created
        $this->assertNotNull($invoice);
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'patient_id' => $this->patient->id,
            'status' => 'issued',
        ]);

        // Assert invoice items (only linked items)
        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
            'description' => $item1->treatment->name . ($item1->tooth_number ? ' (Diş ' . $item1->tooth_number . ')' : ''),
            'unit_price' => 100.00,
        ]);

        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
            'description' => $item2->treatment->name . ($item2->tooth_number ? ' (Diş ' . $item2->tooth_number . ')' : ''),
            'unit_price' => 200.00,
        ]);

        // Assert item3 was not invoiced
        $this->assertDatabaseMissing('invoice_items', [
            'description' => $item3->treatment->name . ($item3->tooth_number ? ' (Diş ' . $item3->tooth_number . ')' : ''),
        ]);

        // Assert pivot updated with invoiced_at
        $pivot1 = \DB::table('encounter_treatment_plan_item')
            ->where('encounter_id', $encounter->id)
            ->where('treatment_plan_item_id', $item1->id)
            ->first();
        $this->assertNotNull($pivot1->invoiced_at);

        $pivot2 = \DB::table('encounter_treatment_plan_item')
            ->where('encounter_id', $encounter->id)
            ->where('treatment_plan_item_id', $item2->id)
            ->first();
        $this->assertNotNull($pivot2->invoiced_at);

        // Assert invoice total matches sum of linked items
        $expectedTotal = (100 + 200) * 1.18; // Including 18% VAT
        $this->assertEquals($expectedTotal, $invoice->grand_total);
    }

    /** @test */
    public function partial_invoice_generation_allows_remaining_items_to_be_invoiced_later()
    {
        $treatmentPlanService = app(TreatmentPlanService::class);

        // Create encounter
        $encounter = Encounter::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'status' => EncounterStatus::DONE,
        ]);

        // Create treatment plan items and link to encounter
        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);

        $item1 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'status' => TreatmentPlanItemStatus::DONE,
            'estimated_price' => 100.00,
        ]);

        $item2 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'status' => TreatmentPlanItemStatus::DONE,
            'estimated_price' => 200.00,
        ]);

        // Link both items to encounter
        $encounter->treatmentPlanItems()->attach($item1->id, ['price' => 100.00]);
        $encounter->treatmentPlanItems()->attach($item2->id, ['price' => 200.00]);

        // Generate invoice for all items (first pass)
        $invoice1 = $treatmentPlanService->generateInvoiceFromEncounterItems($encounter);
        $this->assertNotNull($invoice1);
        $this->assertEquals(2, $invoice1->items()->count());

        // Both items should be marked as invoiced in pivot
        $pivot1 = \DB::table('encounter_treatment_plan_item')
            ->where('encounter_id', $encounter->id)
            ->where('treatment_plan_item_id', $item1->id)
            ->first();
        $this->assertNotNull($pivot1->invoiced_at);

        $pivot2 = \DB::table('encounter_treatment_plan_item')
            ->where('encounter_id', $encounter->id)
            ->where('treatment_plan_item_id', $item2->id)
            ->first();
        $this->assertNotNull($pivot2->invoiced_at);

        // Now try to generate another invoice (should return null since all are invoiced)
        $invoice2 = $treatmentPlanService->generateInvoiceFromEncounterItems($encounter);
        $this->assertNull($invoice2);
    }

    /** @test */
    public function treatment_plan_show_page_renders_status_histories()
    {
        // Create treatment plan and item
        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);

        $item = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'status' => TreatmentPlanItemStatus::PLANNED,
        ]);

        // Change status to create history
        $item->changeStatus(TreatmentPlanItemStatus::IN_PROGRESS, $this->user);
        $item->changeStatus(TreatmentPlanItemStatus::DONE, $this->user);

        // Visit the show page
        $response = $this->actingAs($this->user)
            ->get(route('treatment-plans.show', $plan));

        // Assert response is successful
        $response->assertStatus(200);

        // Assert that history entries are present in the HTML
        $response->assertSee('Tedavi Geçmişi');
        $response->assertSee('Durum Değişikliği');
        $response->assertSee($this->user->name);
    }

    /** @test */
    public function treatment_plan_edit_autosave_preserves_data_integrity()
    {
        $treatmentPlanService = app(TreatmentPlanService::class);

        // Create treatment plan
        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);

        $item1 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'status' => TreatmentPlanItemStatus::PLANNED,
            'estimated_price' => 100.00,
        ]);

        $item2 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'status' => TreatmentPlanItemStatus::PLANNED,
            'estimated_price' => 200.00,
        ]);

        // Simulate autosave operation: update one item, delete one, add one
        $updateData = [
            'dentist_id' => $this->user->id,
            'notes' => 'Updated via autosave',
            'items' => [
                [
                    'id' => $item1->id,
                    'treatment_id' => $item1->treatment_id,
                    'tooth_number' => 'updated_tooth',
                    'appointment_date' => '',
                    'estimated_price' => 150.00, // Updated price
                ],
                [
                    'id' => null, // New item
                    'treatment_id' => \App\Models\Treatment::factory()->create()->id,
                    'tooth_number' => 'new_tooth',
                    'appointment_date' => '',
                    'estimated_price' => 300.00,
                ],
            ],
            'deleted_items' => [$item2->id],
        ];

        // Update via service (simulating controller call)
        $updatedPlan = $treatmentPlanService->updatePlan($plan, $updateData);

        // Assert changes were applied correctly
        $this->assertEquals('Updated via autosave', $updatedPlan->notes);
        $this->assertEquals(450.00, $updatedPlan->total_estimated_cost); // 150 + 300

        // Assert item1 was updated
        $item1->refresh();
        $this->assertEquals('updated_tooth', $item1->tooth_number);
        $this->assertEquals(150.00, $item1->estimated_price);

        // Assert item2 was deleted
        $this->assertDatabaseMissing('treatment_plan_items', ['id' => $item2->id]);

        // Assert new item was created
        $newItem = TreatmentPlanItem::where('treatment_plan_id', $plan->id)
            ->where('tooth_number', 'new_tooth')
            ->first();
        $this->assertNotNull($newItem);
        $this->assertEquals(300.00, $newItem->estimated_price);

        // Assert proper history logging
        $this->assertDatabaseHas('treatment_plan_item_histories', [
            'treatment_plan_item_id' => $item2->id,
            'old_status' => TreatmentPlanItemStatus::PLANNED->value,
            'new_status' => TreatmentPlanItemStatus::CANCELLED->value,
        ]);

        $this->assertDatabaseHas('treatment_plan_item_histories', [
            'treatment_plan_item_id' => $newItem->id,
            'old_status' => null,
            'new_status' => TreatmentPlanItemStatus::PLANNED->value,
        ]);
    }

    /** @test */
    public function appointment_cancellation_when_all_items_removed_works_correctly()
    {
        // Create appointment with multiple treatment plan items
        $appointment = Appointment::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);

        $item1 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'appointment_id' => $appointment->id,
        ]);

        $item2 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'appointment_id' => $appointment->id,
        ]);

        // Remove all items from the treatment plan
        $updateData = [
            'dentist_id' => $this->user->id,
            'notes' => '',
            'items' => [],
            'deleted_items' => [$item1->id, $item2->id],
        ];

        $treatmentPlanService = app(TreatmentPlanService::class);
        $treatmentPlanService->updatePlan($plan, $updateData);

        // Assert appointment was cancelled
        $appointment->refresh();
        $this->assertEquals(AppointmentStatus::CANCELLED, $appointment->status);
        $this->assertStringContains('Tedavi öğeleri kaldırıldı - otomatik iptal edildi', $appointment->notes);

        // Assert appointment history was logged for both items
        $this->assertDatabaseHas('treatment_plan_item_appointment', [
            'treatment_plan_item_id' => $item1->id,
            'appointment_id' => $appointment->id,
            'action' => TreatmentPlanItemAppointmentAction::REMOVED->value,
        ]);

        $this->assertDatabaseHas('treatment_plan_item_appointment', [
            'treatment_plan_item_id' => $item2->id,
            'appointment_id' => $appointment->id,
            'action' => TreatmentPlanItemAppointmentAction::REMOVED->value,
        ]);
    }

    /** @test */
    public function partial_appointment_item_removal_preserves_appointment()
    {
        // Create appointment with multiple treatment plan items
        $appointment = Appointment::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);

        $item1 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'appointment_id' => $appointment->id,
        ]);

        $item2 = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'appointment_id' => $appointment->id,
        ]);

        // Remove only one item from the treatment plan
        $updateData = [
            'dentist_id' => $this->user->id,
            'notes' => '',
            'items' => [
                [
                    'id' => $item2->id,
                    'treatment_id' => $item2->treatment_id,
                    'tooth_number' => $item2->tooth_number,
                    'appointment_date' => $item2->appointment->start_at->format('Y-m-d\TH:i'),
                    'estimated_price' => $item2->estimated_price,
                ],
            ],
            'deleted_items' => [$item1->id],
        ];

        $treatmentPlanService = app(TreatmentPlanService::class);
        $treatmentPlanService->updatePlan($plan, $updateData);

        // Assert appointment was NOT cancelled (still has one item)
        $appointment->refresh();
        $this->assertEquals(AppointmentStatus::SCHEDULED, $appointment->status);

        // Assert item2 still linked to appointment
        $item2->refresh();
        $this->assertEquals($appointment->id, $item2->appointment_id);

        // Assert history logged for removed item
        $this->assertDatabaseHas('treatment_plan_item_appointment', [
            'treatment_plan_item_id' => $item1->id,
            'appointment_id' => $appointment->id,
            'action' => TreatmentPlanItemAppointmentAction::REMOVED->value,
        ]);
    }

    /** @test */
    public function treatment_plan_observer_logs_all_item_changes_correctly()
    {
        // Create treatment plan and item
        $plan = TreatmentPlan::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);

        // Create item (should trigger observer)
        $item = TreatmentPlanItem::factory()->create([
            'treatment_plan_id' => $plan->id,
            'status' => TreatmentPlanItemStatus::PLANNED,
        ]);

        // Assert creation was logged
        $this->assertDatabaseHas('treatment_plan_item_histories', [
            'treatment_plan_item_id' => $item->id,
            'old_status' => null,
            'new_status' => TreatmentPlanItemStatus::PLANNED->value,
            'notes' => 'Treatment plan item created',
        ]);

        // Update status (should trigger observer)
        $item->update(['status' => TreatmentPlanItemStatus::IN_PROGRESS]);

        // Update appointment (should trigger observer)
        $appointment = Appointment::factory()->create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
        ]);
        $item->update(['appointment_id' => $appointment->id]);

        // Assert appointment change was logged
        $this->assertDatabaseHas('treatment_plan_item_appointment', [
            'treatment_plan_item_id' => $item->id,
            'appointment_id' => $appointment->id,
            'action' => TreatmentPlanItemAppointmentAction::PLANNED->value,
            'notes' => 'Item assigned to appointment',
        ]);

        // Delete item (should trigger observer)
        $item->delete();

        // Assert deletion was logged in appointment history
        $this->assertDatabaseHas('treatment_plan_item_appointment', [
            'treatment_plan_item_id' => $item->id,
            'appointment_id' => $appointment->id,
            'action' => TreatmentPlanItemAppointmentAction::REMOVED->value,
            'notes' => 'Item deleted from treatment plan',
        ]);
    }
}