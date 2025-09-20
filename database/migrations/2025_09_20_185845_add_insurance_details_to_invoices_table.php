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
    Schema::table('invoices', function (Blueprint $table) {
        // 'vadelendirildi' durumunu eklemek için mevcut sütunu değiştiriyoruz
        $table->string('status')->default('unpaid')->change();
        $table->decimal('insurance_coverage_amount', 10, 2)->default(0)->after('grand_total');
        $table->decimal('patient_payable_amount', 10, 2)->storedAs('grand_total - insurance_coverage_amount')->after('insurance_coverage_amount');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
};
