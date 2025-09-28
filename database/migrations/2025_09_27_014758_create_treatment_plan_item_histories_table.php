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
        Schema::create('treatment_plan_item_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_plan_item_id')->constrained()->onDelete('cascade');
            $table->enum('old_status', ['planned', 'in_progress', 'done', 'cancelled', 'invoiced', 'no_show'])->nullable();
            $table->enum('new_status', ['planned', 'in_progress', 'done', 'cancelled', 'invoiced', 'no_show']);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // For additional context like appointment_id, encounter_id, etc.
            $table->timestamps();

            $table->index(['treatment_plan_item_id', 'created_at'], 'tpih_item_created_idx');
            $table->index(['user_id', 'created_at'], 'tpih_user_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plan_item_histories');
    }
};
