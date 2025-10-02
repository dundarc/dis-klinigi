<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('invoice_items', 'treatment_plan_item_id')) {
            Schema::table('invoice_items', function (Blueprint $table) {
                $table->foreignId('treatment_plan_item_id')
                    ->nullable()
                    ->after('patient_treatment_id')
                    ->constrained('treatment_plan_items')
                    ->nullOnDelete();

                $table->index('treatment_plan_item_id', 'invoice_items_treatment_plan_item_id_index');
            });
        }
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_items', 'treatment_plan_item_id')) {
                $table->dropIndex('invoice_items_treatment_plan_item_id_index');
                $table->dropConstrainedForeignId('treatment_plan_item_id');
            }
        });
    }
};
