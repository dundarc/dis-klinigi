<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include the missing values
        DB::statement("ALTER TABLE treatment_plan_item_appointments MODIFY COLUMN action ENUM('planned', 'cancelled', 'rescheduled', 'completed', 'no_show', 'removed', 'updated')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        // First, update any 'removed' or 'updated' values to 'cancelled' to avoid constraint issues
        DB::statement("UPDATE treatment_plan_item_appointments SET action = 'cancelled' WHERE action IN ('removed', 'updated')");
        
        // Then modify the column back to original enum
        DB::statement("ALTER TABLE treatment_plan_item_appointments MODIFY COLUMN action ENUM('planned', 'cancelled', 'rescheduled', 'completed', 'no_show')");
    }
};