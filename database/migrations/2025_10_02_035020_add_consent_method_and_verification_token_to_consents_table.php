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
            $columns = Schema::getColumnListing('consents');
            if (!in_array('consent_method', $columns)) {
                $table->string('consent_method')->nullable()->after('status');
            }
            if (!in_array('verification_token', $columns)) {
                $table->string('verification_token')->nullable()->after('consent_method');
            }
            if (!in_array('email_sent_at', $columns)) {
                $table->timestamp('email_sent_at')->nullable()->after('verification_token');
            }
            if (!in_array('email_verified_at', $columns)) {
                $table->timestamp('email_verified_at')->nullable()->after('email_sent_at');
            }
            if (!in_array('signature_path', $columns)) {
                $table->string('signature_path')->nullable();
            }
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
