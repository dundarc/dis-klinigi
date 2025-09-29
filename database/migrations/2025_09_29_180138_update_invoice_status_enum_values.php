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
        // First expand the enum to include both old and new values temporarily
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('draft', 'issued', 'unpaid', 'partial', 'paid', 'cancelled', 'vadeli', 'taksitlendirildi', 'overdue', 'vadesi_gecmis')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original enum values
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('draft', 'issued', 'paid', 'overdue', 'cancelled')");
        // Convert vadesi_gecmis back to overdue
        DB::table('invoices')->where('status', 'vadesi_gecmis')->update(['status' => 'overdue']);
    }
};
