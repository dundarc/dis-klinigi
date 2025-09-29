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
        // Update any existing 'overdue' status values to 'vadesi_gecmis'
        DB::table('invoices')->where('status', 'overdue')->update(['status' => 'vadesi_gecmis']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'vadesi_gecmis' back to 'overdue' if needed
        DB::table('invoices')->where('status', 'vadesi_gecmis')->update(['status' => 'overdue']);
    }
};
