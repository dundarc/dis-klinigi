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
            $table->string('consent_method')->default('wet_signature')->after('status'); // wet_signature or email_verification
            $table->string('verification_token')->nullable()->after('consent_method');
            $table->timestamp('email_sent_at')->nullable()->after('verification_token');
            $table->timestamp('email_verified_at')->nullable()->after('email_sent_at');
            $table->timestamp('cancellation_pdf_generated_at')->nullable()->after('withdrawn_at');
            $table->timestamp('cancellation_pdf_downloaded_at')->nullable()->after('cancellation_pdf_generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consents', function (Blueprint $table) {
            $table->dropColumn(['cancellation_pdf_generated_at', 'cancellation_pdf_downloaded_at']);
        });
    }
};
