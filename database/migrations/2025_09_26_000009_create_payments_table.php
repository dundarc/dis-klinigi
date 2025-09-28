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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->enum('method', ['cash', 'card', 'bank_transfer', 'insurance']);
            $table->decimal('amount', 10, 2);
            $table->datetime('paid_at');
            $table->string('txn_ref')->nullable();
            $table->timestamps();

            $table->index(['invoice_id', 'paid_at']);
            $table->index(['method', 'paid_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};