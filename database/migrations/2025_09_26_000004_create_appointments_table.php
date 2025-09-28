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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('treatment_plan_id')->nullable()->constrained()->onDelete('set null');
            $table->datetime('start_at');
            $table->datetime('end_at');
            $table->enum('status', ['scheduled', 'confirmed', 'checked_in', 'in_service', 'completed', 'cancelled', 'no_show']);
            $table->string('room')->nullable();
            $table->text('notes')->nullable();
            $table->integer('queue_number')->nullable();
            $table->datetime('checked_in_at')->nullable();
            $table->datetime('called_at')->nullable();
            $table->timestamps();

            $table->index(['start_at', 'end_at']);
            $table->index(['patient_id', 'start_at']);
            $table->index(['dentist_id', 'start_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};