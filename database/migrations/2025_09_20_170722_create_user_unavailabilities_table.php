<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_unavailabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_unavailabilities');
    }
};