<?php

namespace App\Console\Commands;

use Illuminatehen\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WipeClinicData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wipe-clinic-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all clinical data (patients, appointments, etc.) but keeps users and settings.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->confirm('BU İŞLEM GERİ ALINAMAZ! Tüm hasta, randevu, fatura ve ziyaret kayıtlarını kalıcı olarak silmek istediğinizden emin misiniz?')) {
            
            $this->info('Klinik verileri siliniyor...');

            // Foreign key kısıtlamalarını geçici olarak devre dışı bırak
            Schema::disableForeignKeyConstraints();

            // Silinecek tabloların listesi
            $tables = [
                'payments',
                'invoice_items',
                'invoices',
                'prescriptions',
                'patient_treatments',
                'files',
                'consents',
                'encounters',
                'appointments',
                'patients',
                'activity_logs',
                // 'users' ve 'settings' gibi tabloları burada bırakıyoruz.
            ];

            foreach ($tables as $table) {
                DB::table($table)->truncate();
                $this->warn("{$table} tablosu temizlendi.");
            }

            // Kısıtlamaları tekrar aktif et
            Schema::enableForeignKeyConstraints();

            $this->info('Tüm klinik verileri başarıyla silindi. Kullanıcılar ve ayarlar korundu.');
        } else {
            $this->info('İşlem iptal edildi.');
        }
        return 0;
    }
}
