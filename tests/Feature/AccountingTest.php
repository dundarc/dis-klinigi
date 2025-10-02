<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\UserRole;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountingTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $accountant;
    private User $dentist;
    private Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->accountant = User::factory()->create(['role' => UserRole::ACCOUNTANT]);
        $this->dentist = User::factory()->create(['role' => UserRole::DENTIST]);
        $this->patient = Patient::factory()->create();
    }

    /** @test */
    public function admin_can_create_invoice()
    {
        $invoiceData = [
            'patient_id' => $this->patient->id,
            'items' => [
                [
                    'description' => 'Diş Temizliği',
                    'quantity' => 1,
                    'unit_price' => 500.00,
                    'vat' => 20,
                    'patient_treatment_id' => null,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('accounting.store'), $invoiceData);

        $response->assertRedirect();
        $this->assertDatabaseHas('invoices', [
            'patient_id' => $this->patient->id,
            'grand_total' => 600.00, // 500 + 20% VAT
        ]);
    }

    /** @test */
    public function accountant_can_update_invoice()
    {
        $invoice = Invoice::factory()->create([
            'patient_id' => $this->patient->id,
            'status' => InvoiceStatus::DRAFT,
        ]);

        $updateData = [
            '_method' => 'PUT',
            'patient_id' => $this->patient->id,
            'issue_date' => now()->format('Y-m-d'),
            'status' => InvoiceStatus::PAID->value,
            'payment_method' => 'cash',
            'paid_at' => now()->format('Y-m-d H:i:s'),
            'notes' => 'Updated notes',
        ];

        $response = $this->actingAs($this->accountant)
            ->post(route('accounting.invoices.update', $invoice), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => InvoiceStatus::PAID,
            'payment_method' => 'cash',
        ]);
    }

    /** @test */
    public function dentist_cannot_access_accounting_features()
    {
        $response = $this->actingAs($this->dentist)
            ->get(route('accounting.index'));

        $response->assertForbidden();
    }

    /** @test */
    public function invoice_with_installment_and_partial_payment()
    {
        $invoice = Invoice::factory()->create([
            'patient_id' => $this->patient->id,
            'grand_total' => 1000.00,
            'status' => InvoiceStatus::INSTALLMENT,
        ]);

        // Create installment plan
        $installmentData = [
            '_method' => 'PUT',
            'patient_id' => $this->patient->id,
            'issue_date' => now()->format('Y-m-d'),
            'status' => InvoiceStatus::PARTIAL->value,
            'partial_payment_amount' => 300.00,
            'partial_payment_method' => 'cash',
            'partial_payment_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->accountant)
            ->post(route('accounting.invoices.update', $invoice), $installmentData);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount' => 300.00,
            'method' => 'cash',
        ]);
    }

    /** @test */
    public function invoice_with_insurance_and_discount()
    {
        $invoice = Invoice::factory()->create([
            'patient_id' => $this->patient->id,
            'grand_total' => 1000.00,
            'insurance_coverage_amount' => 200.00,
            'discount_total' => 100.00,
        ]);

        $payableAmount = $invoice->patient_payable_amount;

        // Insurance + discount should reduce the payable amount
        $this->assertEquals(700.00, $payableAmount); // 1000 - 200 - 100
    }

    /** @test */
    public function cannot_delete_invoice_with_related_records()
    {
        $invoice = Invoice::factory()->create([
            'patient_id' => $this->patient->id,
        ]);

        // Create related payment
        Payment::factory()->create(['invoice_id' => $invoice->id]);

        $response = $this->actingAs($this->admin)
            ->delete(route('accounting.invoices.destroy', $invoice));

        $response->assertRedirect();
        $this->assertDatabaseHas('invoices', ['id' => $invoice->id]); // Should still exist
    }

    /** @test */
    public function installment_plan_generation_with_zero_count_throws_exception()
    {
        $invoice = Invoice::factory()->create([
            'patient_id' => $this->patient->id,
            'grand_total' => 1000.00,
        ]);

        $this->expectException(\App\Exceptions\InstallmentPlanException::class);

        $controller = new \App\Http\Controllers\AccountingController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('generateInstallmentPlan');
        $method->setAccessible(true);

        $method->invokeArgs($controller, [$invoice, 0, now()->format('Y-m-d')]);
    }

    /** @test */
    public function invoice_totals_automatically_recalculated_when_items_change()
    {
        $invoice = Invoice::factory()->create([
            'patient_id' => $this->patient->id,
        ]);

        // Create invoice item
        InvoiceItem::factory()->create([
            'invoice_id' => $invoice->id,
            'quantity' => 1,
            'unit_price' => 500.00,
            'vat' => 20,
            'line_total' => 500.00,
        ]);

        // Refresh invoice from database
        $invoice->refresh();

        // Check if totals were recalculated by observer
        $this->assertEquals(500.00, $invoice->subtotal);
        $this->assertEquals(100.00, $invoice->vat_total); // 20% of 500
        $this->assertEquals(600.00, $invoice->grand_total);
    }

    /** @test */
    public function bulk_delete_only_deletes_invoices_without_related_records()
    {
        $invoiceWithPayment = Invoice::factory()->create(['patient_id' => $this->patient->id]);
        $invoiceWithoutPayment = Invoice::factory()->create(['patient_id' => $this->patient->id]);

        Payment::factory()->create(['invoice_id' => $invoiceWithPayment->id]);

        $response = $this->actingAs($this->admin)
            ->post(route('accounting.trash.bulk-force-delete'), [
                'invoice_ids' => [$invoiceWithPayment->id, $invoiceWithoutPayment->id]
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('invoices', ['id' => $invoiceWithPayment->id]); // Should still exist
        $this->assertDatabaseMissing('invoices', ['id' => $invoiceWithoutPayment->id]); // Should be deleted
    }
}