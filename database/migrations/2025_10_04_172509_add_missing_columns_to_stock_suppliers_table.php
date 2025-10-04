<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_suppliers', function (Blueprint $table) {
            $table->string('type')->default('supplier')->after('name');
            $table->string('tax_office')->nullable()->after('tax_number');
            $table->text('notes')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_suppliers', function (Blueprint $table) {
            $table->dropColumn(['type', 'tax_office', 'notes']);
        });
    }
};
