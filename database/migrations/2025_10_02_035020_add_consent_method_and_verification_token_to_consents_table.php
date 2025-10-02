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
        Schema::table('consents', function (Blueprint $table) {
            $table->string('consent_method')->nullable()->after('status');
            $table->string('verification_token')->nullable()->after('consent_method');
            $table->timestamp('email_sent_at')->nullable()->after('verification_token');
            $table->timestamp('email_verified_at')->nullable()->after('email_sent_at');
            $table->string('signature_path')->nullable()->after('hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consents', function (Blueprint $table) {
            $table->dropColumn(['consent_method', 'verification_token', 'email_sent_at', 'email_verified_at', 'signature_path']);
        });
    }
};
