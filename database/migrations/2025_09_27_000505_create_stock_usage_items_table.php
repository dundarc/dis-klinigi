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
        Schema::create('stock_usage_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usage_id')->constrained('stock_usages')->onDelete('cascade');
            $table->foreignId('stock_item_id')->constrained('stock_items')->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_usage_items');
    }
};
