<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip for SQLite as it's handled at application level
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Change the enum values
        DB::statement("ALTER TABLE consents MODIFY COLUMN status ENUM('accepted', 'withdrawn', 'active', 'canceled') DEFAULT 'active'");

        // Normalize existing status values to new active/canceled scheme
        DB::table('consents')->where('status', 'accepted')->update(['status' => 'active']);
        DB::table('consents')->where('status', 'withdrawn')->update(['status' => 'canceled']);

        // Then restrict to only active/canceled
        DB::statement("ALTER TABLE consents MODIFY COLUMN status ENUM('active', 'canceled') DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::table('consents')->where('status', 'active')->update(['status' => 'accepted']);
        DB::table('consents')->where('status', 'canceled')->update(['status' => 'withdrawn']);
    }
};
