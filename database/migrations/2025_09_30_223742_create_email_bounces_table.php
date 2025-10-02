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
        Schema::create('email_bounces', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->enum('bounce_type', ['hard', 'soft', 'complaint', 'other']);
            $table->string('provider')->nullable();
            $table->longText('raw_payload');
            $table->timestamp('occurred_at');
            $table->foreignId('email_log_id')->nullable()->constrained('email_logs')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_bounces');
    }
};
