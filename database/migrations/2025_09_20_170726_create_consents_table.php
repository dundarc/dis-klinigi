<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('type');
            $table->string('text_version');
            $table->timestamp('accepted_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consents');
    }
};