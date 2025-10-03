<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE treatment_plans MODIFY COLUMN status ENUM('draft','active','completed','cancelled','cancelled_partial') DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE treatment_plans MODIFY COLUMN status ENUM('draft','active','completed','cancelled') DEFAULT 'draft'");
    }
};