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
    Schema::table('patients', function (Blueprint $table) {
        $table->string('tax_office')->nullable()->after('address_text');
        $table->string('emergency_contact_person')->nullable()->after('notes');
        $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_person');
        $table->text('medications_used')->nullable()->after('emergency_contact_phone');
        $table->boolean('has_private_insurance')->default(false)->after('medications_used');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            //
        });
    }
};
