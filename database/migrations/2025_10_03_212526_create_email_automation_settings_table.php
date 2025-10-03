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
        Schema::create('email_automation_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('patient_checkin_to_dentist')->default(false);
            $table->boolean('emergency_patient_to_dentist')->default(false);
            $table->boolean('kvkk_consent_to_admin')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_automation_settings');
    }
};
