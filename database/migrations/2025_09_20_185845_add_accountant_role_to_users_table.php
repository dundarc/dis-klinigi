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
    // Var olan enum'a yeni bir değer eklemek için ham bir SQL sorgusu kullanmak en güvenilir yoldur.
    DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','dentist','assistant','receptionist','accountant') NOT NULL DEFAULT 'dentist'");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
