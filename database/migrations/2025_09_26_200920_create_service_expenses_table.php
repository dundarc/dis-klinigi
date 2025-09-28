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
        Schema::create('service_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('service_provider')->nullable(); // electricity company, internet provider, etc.
            $table->string('service_type'); // electricity, water, internet, advertising, etc.
            $table->string('invoice_number')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->enum('status', ['paid', 'pending', 'overdue'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('invoice_path')->nullable(); // uploaded invoice file
            $table->json('payment_history')->nullable(); // for partial payments
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_expenses');
    }
};
