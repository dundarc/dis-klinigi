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
    Schema::table('appointments', function (Blueprint $table) {
        $table->foreignId('referred_from_user_id')->nullable()->constrained('users')->nullOnDelete()->after('dentist_id');
        $table->string('referral_status')->nullable()->after('referred_from_user_id'); // Örn: pending, accepted, rejected
        $table->text('referral_notes')->nullable()->after('referral_status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
};
