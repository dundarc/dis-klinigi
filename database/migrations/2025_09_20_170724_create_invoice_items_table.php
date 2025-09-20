<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('patient_treatment_id')->nullable()->constrained('patient_treatments')->nullOnDelete();
            $table->string('description');
            $table->integer('qty')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('vat', 5, 2);
            $table->decimal('line_total', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};