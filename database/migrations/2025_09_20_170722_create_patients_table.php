<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('national_id')->nullable()->unique();
            $table->date('birth_date');
            $table->string('gender');
            $table->string('phone_primary');
            $table->string('phone_secondary')->nullable();
            $table->string('email')->nullable();
            $table->text('address_text')->nullable();
            $table->timestamp('consent_kvkk_at')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};