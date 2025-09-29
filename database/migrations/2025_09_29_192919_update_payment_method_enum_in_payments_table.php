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
        // First expand the enum to include 'credit_card' and 'check'
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'card', 'bank_transfer', 'credit_card', 'check', 'insurance')");

        // Then update any 'card' values to 'credit_card'
        DB::statement("UPDATE payments SET method = 'credit_card' WHERE method = 'card'");

        // Finally modify the enum to remove 'card'
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'bank_transfer', 'credit_card', 'check', 'insurance')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'card', 'bank_transfer', 'insurance')");
    }
};
