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
        Schema::table('kvkk_audit_logs', function (Blueprint $table) {
            // Drop the existing foreign key constraint with cascade delete
            $table->dropForeign(['patient_id']);

            // Recreate the foreign key constraint without cascade delete
            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kvkk_audit_logs', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['patient_id']);

            // Recreate with cascade delete
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }
};
