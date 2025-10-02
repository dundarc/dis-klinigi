<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support MODIFY COLUMN with ENUM, so we skip this for SQLite compatibility
        // The enum values are handled at application level
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'export' values by updating them to 'restore' for SQLite compatibility
        DB::table('kvkk_audit_logs')
            ->where('action', 'export')
            ->update(['action' => 'restore']);
    }
};
