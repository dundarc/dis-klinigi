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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('encounter_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('uploader_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['xray', 'document', 'photo', 'other']);
            $table->string('filename');
            $table->string('original_filename');
            $table->string('path');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'type']);
            $table->index(['encounter_id', 'created_at']);
            $table->index(['uploader_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};