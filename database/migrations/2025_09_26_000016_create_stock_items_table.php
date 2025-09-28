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
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->nullable()->unique();
            $table->string('barcode')->nullable();
            $table->foreignId('category_id')->constrained('stock_categories')->onDelete('cascade');
            $table->string('unit');
            $table->decimal('minimum_quantity', 10, 2)->default(0.00);
            $table->decimal('quantity', 10, 2)->default(0.00);
            $table->boolean('allow_negative')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category_id', 'is_active']);
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};