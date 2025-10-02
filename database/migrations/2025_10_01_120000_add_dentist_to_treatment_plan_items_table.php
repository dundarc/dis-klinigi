<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('treatment_plan_items', 'dentist_id')) {
            Schema::table('treatment_plan_items', function (Blueprint $table) {
                $table->foreignId('dentist_id')
                    ->nullable()
                    ->after('treatment_id')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }

        Schema::table('treatment_plan_items', function (Blueprint $table) {
            $table->index('completed_at', 'treatment_plan_items_completed_at_index');
            $table->index(['dentist_id', 'status'], 'treatment_plan_items_dentist_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('treatment_plan_items', function (Blueprint $table) {
            $table->dropIndex('treatment_plan_items_dentist_status_index');
            $table->dropIndex('treatment_plan_items_completed_at_index');
            if (Schema::hasColumn('treatment_plan_items', 'dentist_id')) {
                $table->dropConstrainedForeignId('dentist_id');
            }
        });
    }
};
