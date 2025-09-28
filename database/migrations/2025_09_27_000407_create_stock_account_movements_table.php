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
        Schema::create('stock_account_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('stock_suppliers')->onDelete('cascade');
            $table->enum('direction', ['debit', 'credit']);
            $table->decimal('amount', 10, 2);
            $table->date('movement_date');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'credit_card', 'check'])->nullable();
            $table->string('description');
            $table->morphs('reference');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_account_movements');
    }
};
