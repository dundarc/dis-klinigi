<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('invoice_no')->unique();
            $table->date('issue_date');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('vat_total', 10, 2);
            $table->decimal('discount_total', 10, 2)->default(0.00);
            $table->decimal('grand_total', 10, 2);
            $table->enum('status', ['draft', 'issued', 'paid', 'overdue', 'cancelled']);
            $table->text('notes')->nullable();
            $table->decimal('insurance_coverage_amount', 10, 2)->default(0.00);
            $table->string('payment_method')->nullable();
            $table->date('due_date')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'issue_date']);
            $table->index(['status', 'due_date']);
            $table->index('invoice_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};