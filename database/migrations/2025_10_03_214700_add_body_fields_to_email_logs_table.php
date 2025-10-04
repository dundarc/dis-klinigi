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
        Schema::table('email_logs', function (Blueprint $table) {
            $columns = Schema::getColumnListing('email_logs');
            if (!in_array('body_html', $columns)) {
                $table->longText('body_html')->nullable()->after('subject');
            }
            if (!in_array('body_text', $columns)) {
                $table->longText('body_text')->nullable()->after('body_html');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn(['body_html', 'body_text']);
        });
    }
};
