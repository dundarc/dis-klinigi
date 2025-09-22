<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Önce mevcut ENUM değerlerini alıp yenilerini ekleyerek SQL sorgusunu hazırlıyoruz.
        // Bu, veritabanı türünden bağımsız en güvenilir yöntemlerden biridir.
        $newStatuses = "'unpaid','partial','paid','ileri_tarihte_odenecek','taksitlendirildi'";
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM({$newStatuses}) NOT NULL DEFAULT 'unpaid'");

        Schema::table('invoices', function (Blueprint $table) {
            // Ödeme detayları
            $table->string('payment_method')->nullable()->after('status');
            $table->date('due_date')->nullable()->after('payment_method');
            $table->json('payment_details')->nullable()->after('due_date'); // Taksit bilgileri gibi detaylar için

            // Çöp Kutusu (Soft Deletes)
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri alma işlemi için orijinal ENUM değerlerini tanımlıyoruz.
        $originalStatuses = "'unpaid','partial','paid'";
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM({$originalStatuses}) NOT NULL DEFAULT 'unpaid'");

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'due_date', 'payment_details']);
            $table->dropSoftDeletes();
        });
    }
};
