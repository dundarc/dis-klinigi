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
            // Bir tedavi, bir ziyarete (encounter) bağlı olabilir.
            // Nullable olmasının sebebi, bir tedavinin bir ziyaret gerçekleşmeden önce
            // "planlanmış" olabilmesidir.
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete()->after('patient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_treatments', function (Blueprint $table) {
            $table->dropForeign(['encounter_id']);
            $table->dropColumn('encounter_id');
        });
    }
};