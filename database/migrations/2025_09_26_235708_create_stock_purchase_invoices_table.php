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
        Schema::create('stock_purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('stock_suppliers')->onDelete('cascade');
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'credit_card', 'check'])->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('vat_total', 10, 2);
            $table->decimal('grand_total', 10, 2);
            $table->text('notes')->nullable();
            $table->string('file_path')->nullable();
            $table->json('parsed_payload')->nullable();
            $table->json('payment_history')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_purchase_invoices');
    }
};
