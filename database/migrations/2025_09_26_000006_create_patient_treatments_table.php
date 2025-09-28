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
        Schema::create('patient_treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('encounter_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('treatment_id')->constrained()->onDelete('cascade');
            $table->string('tooth_number')->nullable();
            $table->enum('status', ['planned', 'in_progress', 'done', 'invoiced'])->default('done');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('vat', 5, 2)->default(20.00);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->datetime('performed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'performed_at']);
            $table->index(['encounter_id', 'status']);
            $table->index(['treatment_id', 'performed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_treatments');
    }
};