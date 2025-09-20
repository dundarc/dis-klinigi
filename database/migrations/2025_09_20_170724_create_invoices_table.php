<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('invoice_no')->unique();
            $table->date('issue_date');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('vat_total', 10, 2);
            $table->decimal('discount_total', 10, 2);
            $table->decimal('grand_total', 10, 2);
            $table->string('status')->default(\App\Enums\InvoiceStatus::UNPAID->value);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};