<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('dentist_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('status')->default(\App\Enums\AppointmentStatus::SCHEDULED->value);
            $table->string('room')->nullable();
            $table->text('notes')->nullable();
            $table->string('queue_number')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamps();
            $table->index(['dentist_id', 'start_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};