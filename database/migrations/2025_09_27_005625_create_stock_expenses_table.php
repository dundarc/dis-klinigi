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
        Schema::create('stock_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('stock_expense_categories')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('stock_suppliers')->nullOnDelete();
            $table->string('title');
            $table->date('expense_date');
            $table->decimal('amount', 10, 2);
            $table->decimal('vat_rate', 5, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'credit_card', 'check'])->nullable();
            $table->date('due_date')->nullable();
            $table->date('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_expenses');
    }
};
