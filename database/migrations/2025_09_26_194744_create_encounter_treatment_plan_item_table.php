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
        Schema::create('encounter_treatment_plan_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id');
            $table->foreign('encounter_id')->references('id')->on('encounters')->onDelete('cascade');
            $table->foreignId('treatment_plan_item_id');
            $table->foreign('treatment_plan_item_id')->references('id')->on('treatment_plan_items')->onDelete('cascade');
            $table->decimal('price', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter_treatment_plan_item');
    }
};
