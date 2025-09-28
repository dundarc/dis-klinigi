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
        Schema::table('encounter_treatment_plan_item', function (Blueprint $table) {
            if (!Schema::hasColumn('encounter_treatment_plan_item', 'invoiced_at')) {
                $table->timestamp('invoiced_at')->nullable();
                $table->index('invoiced_at');
            }
            if (!Schema::hasColumn('encounter_treatment_plan_item', 'invoice_item_id')) {
                $table->foreignId('invoice_item_id')->nullable()->constrained()->onDelete('set null');
                $table->index('invoice_item_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encounter_treatment_plan_item', function (Blueprint $table) {
            $table->dropIndex('encounter_treatment_plan_item_invoiced_at_index');
            $table->dropIndex('encounter_treatment_plan_item_invoice_item_id_index');
            $table->dropForeign(['invoice_item_id']);
            $table->dropColumn(['invoiced_at', 'invoice_item_id']);
        });
    }
};
