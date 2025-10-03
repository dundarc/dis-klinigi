<?php

namespace App\Console\Commands;

use App\Models\Treatment;
use Illuminate\Console\Command;

class SetMedicalVatRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'treatments:set-medical-vat {--rate=10 : KDV oranı (varsayılan: 10)} {--confirm : Onay olmadan çalıştır}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tüm tedaviler için tıbbi hizmet KDV oranını ayarlar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vatRate = (float) $this->option('rate');
        $confirmed = $this->option('confirm');

        if ($vatRate < 0 || $vatRate > 100) {
            $this->error('KDV oranı 0-100 arasında olmalıdır.');
            return 1;
        }

        $treatmentCount = Treatment::count();

        if ($treatmentCount === 0) {
            $this->info('Güncellenecek tedavi bulunamadı.');
            return 0;
        }

        // Mevcut KDV dağılımını göster
        $this->info('Mevcut KDV dağılımı:');
        $currentVatStats = [
            '0' => Treatment::where('default_vat', 0)->count(),
            '8' => Treatment::where('default_vat', 8)->count(),
            '10' => Treatment::where('default_vat', 10)->count(),
            '18' => Treatment::where('default_vat', 18)->count(),
            '20' => Treatment::where('default_vat', 20)->count(),
        ];

        foreach ($currentVatStats as $rate => $count) {
            if ($count > 0) {
                $this->line("  %{$rate} KDV: {$count} tedavi");
            }
        }

        $otherCount = Treatment::whereNotIn('default_vat', [0, 8, 10, 18, 20])->count();
        if ($otherCount > 0) {
            $this->line("  Diğer: {$otherCount} tedavi");
        }

        // Onay iste
        if (!$confirmed) {
            if (!$this->confirm("{$treatmentCount} tedavi için KDV oranını %{$vatRate} olarak ayarlamak istediğinizden emin misiniz?")) {
                $this->info('İşlem iptal edildi.');
                return 0;
            }
        }

        // Güncelleme işlemi
        $this->info("KDV oranları güncelleniyor...");
        $bar = $this->output->createProgressBar($treatmentCount);

        Treatment::chunk(100, function ($treatments) use ($vatRate, $bar) {
            foreach ($treatments as $treatment) {
                $treatment->update(['default_vat' => $vatRate]);
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ {$treatmentCount} tedavi için KDV oranı %{$vatRate} olarak ayarlandı.");
        $this->info('Türkiye\'de tıbbi hizmetler için uygulanan standart KDV oranı %10\'dur.');

        return 0;
    }
}
