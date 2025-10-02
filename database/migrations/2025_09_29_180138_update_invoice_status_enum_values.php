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
        // SQLite doesn't support MODIFY COLUMN with ENUM, so we skip this for SQLite compatibility
        // The enum values are handled at application level
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert vadesi_gecmis back to overdue for SQLite compatibility
        DB::table('invoices')->where('status', 'vadesi_gecmis')->update(['status' => 'overdue']);
    }
};
