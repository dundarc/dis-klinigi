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
        Schema::table('prescriptions', function (Blueprint $table) {
            // Bir reçete her zaman bir ziyarete (encounter) bağlıdır.
            $table->foreignId('encounter_id')->constrained('encounters')->cascadeOnDelete()->after('patient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['encounter_id']);
            $table->dropColumn('encounter_id');
        });
    }
};