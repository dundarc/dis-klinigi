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
        Schema::table('email_settings', function (Blueprint $table) {
            $table->string('dkim_domain')->nullable()->after('from_name');
            $table->string('dkim_selector')->nullable()->after('dkim_domain');
            $table->longText('dkim_private_key')->nullable()->after('dkim_selector');
            $table->text('spf_record')->nullable()->after('dkim_private_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_settings', function (Blueprint $table) {
            $table->dropColumn(['dkim_domain', 'dkim_selector', 'dkim_private_key', 'spf_record']);
        });
    }
};
