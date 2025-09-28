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
            // Add invoiced_at field
            $table->timestamp('invoiced_at')->nullable();
            $table->foreignId('invoice_item_id')->nullable()->constrained()->onDelete('set null');

            // Add unique constraint to prevent duplicate links
            $table->unique(['encounter_id', 'treatment_plan_item_id'], 'unique_encounter_item_link');

            // Add indexes for performance
            $table->index('encounter_id');
            $table->index('treatment_plan_item_id');
            $table->index('invoiced_at');
            $table->index('invoice_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encounter_treatment_plan_item', function (Blueprint $table) {
            // Drop unique constraint
            $table->dropUnique('unique_encounter_item_link');

            // Drop foreign key and columns
            $table->dropForeign(['invoice_item_id']);
            $table->dropColumn(['invoiced_at', 'invoice_item_id']);
        });
    }
};
