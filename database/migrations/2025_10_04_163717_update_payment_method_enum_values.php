<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include all payment methods
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'bank_transfer', 'credit_card', 'check', 'insurance')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'card', 'bank_transfer', 'insurance')");
    }
};
