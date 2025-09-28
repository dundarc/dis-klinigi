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
        Schema::table('patient_treatments', function (Blueprint $table) {
            $table->unsignedBigInteger('treatment_plan_item_id')->nullable()->after('treatment_id');
            $table->foreign('treatment_plan_item_id')->references('id')->on('treatment_plan_items')->onDelete('set null');
            $table->index('treatment_plan_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_treatments', function (Blueprint $table) {
            $table->dropForeign(['treatment_plan_item_id']);
            $table->dropIndex(['treatment_plan_item_id']);
            $table->dropColumn('treatment_plan_item_id');
        });
    }
};
