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
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('dentist_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('type', ['scheduled', 'emergency', 'walk_in']);
            $table->enum('triage_level', ['red', 'yellow', 'green'])->nullable();
            $table->datetime('arrived_at');
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();
            $table->enum('status', ['waiting', 'in_service', 'done', 'cancelled']);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'arrived_at']);
            $table->index(['dentist_id', 'arrived_at']);
            $table->index(['status', 'arrived_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounters');
    }
};