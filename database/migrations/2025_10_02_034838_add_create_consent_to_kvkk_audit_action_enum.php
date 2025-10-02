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
        DB::statement("ALTER TABLE kvkk_audit_logs MODIFY COLUMN action ENUM('soft_delete','hard_delete','restore','export','create_consent','cancel_consent')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE kvkk_audit_logs MODIFY COLUMN action ENUM('soft_delete','hard_delete','restore','export','cancel_consent')");
    }
};
