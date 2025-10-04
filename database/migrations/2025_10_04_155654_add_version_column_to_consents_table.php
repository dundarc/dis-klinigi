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
            $table->string('version')->default('1.0')->after('patient_id');
            $table->json('snapshot')->nullable()->after('user_agent');
            $table->string('hash')->nullable()->after('snapshot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consents', function (Blueprint $table) {
            $table->dropColumn(['version', 'snapshot', 'hash']);
        });
    }
};
