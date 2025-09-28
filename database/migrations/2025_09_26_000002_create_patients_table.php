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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('national_id', 11)->nullable()->unique();
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone_primary', 20);
            $table->string('phone_secondary', 20)->nullable();
            $table->string('email')->nullable()->unique();
            $table->text('address_text')->nullable();
            $table->string('tax_office')->nullable();
            $table->datetime('consent_kvkk_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('emergency_contact_person')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->text('medications_used')->nullable();
            $table->boolean('has_private_insurance')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};