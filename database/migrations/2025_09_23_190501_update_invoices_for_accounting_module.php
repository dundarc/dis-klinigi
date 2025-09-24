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
        // 1. ADIM: Mevcut verileri temizle
        // Yeni ENUM listemizde olmayan tüm eski 'status' değerlerini
        // güvenli bir varsayılan değere ('unpaid') güncelliyoruz.
        $validStatuses = ['unpaid', 'partial', 'paid', 'vadeli', 'taksitlendirildi', 'vadesi_gecmis'];
        DB::table('invoices')
            ->whereNotIn('status', $validStatuses)
            ->update(['status' => 'unpaid']);

        // 2. ADIM: Tablo yapısını güvenle değiştir
        // Veriler artık temiz olduğu için bu komut hatasız çalışacaktır.
        $newStatuses = "'unpaid','partial','paid','vadeli','taksitlendirildi','vadesi_gecmis'";
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM({$newStatuses}) NOT NULL DEFAULT 'unpaid'");

        Schema::table('invoices', function (Blueprint $table) {
            // Ödeme detayları
            $table->string('payment_method')->nullable()->after('status');
            $table->timestamp('paid_at')->nullable()->after('payment_method');
            $table->date('due_date')->nullable()->after('paid_at');
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
            $table->dropColumn(['payment_method', 'paid_at', 'due_date', 'payment_details']);
            $table->dropSoftDeletes();
        });
    }
};

