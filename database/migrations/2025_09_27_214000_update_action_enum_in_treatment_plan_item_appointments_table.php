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
        // SQLite doesn't support MODIFY COLUMN with ENUM, so we need to recreate the table
        Schema::table('treatment_plan_item_appointments', function (Blueprint $table) {
            // This is a no-op for SQLite compatibility - the enum values are handled at application level
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        // Update any 'removed' or 'updated' values to 'cancelled' to avoid constraint issues
        DB::table('treatment_plan_item_appointments')
            ->whereIn('action', ['removed', 'updated'])
            ->update(['action' => 'cancelled']);
    }
};