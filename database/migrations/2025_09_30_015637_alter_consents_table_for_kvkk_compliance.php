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
        // SQLite doesn't support complex ALTER TABLE operations, so we skip this for SQLite compatibility
        // The table structure changes are handled at application level
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // SQLite doesn't support complex table recreation, so we skip this for SQLite compatibility
        // In production, you'd want a more sophisticated rollback strategy
    }
};
