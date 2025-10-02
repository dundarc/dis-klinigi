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
        // SQLite doesn't support MODIFY COLUMN with ENUM, so we handle data migration only
        // Update any 'card' values to 'credit_card'
        DB::table('payments')->where('method', 'card')->update(['method' => 'credit_card']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert credit_card back to card for SQLite compatibility
        DB::table('payments')->where('method', 'credit_card')->update(['method' => 'card']);
    }
};
