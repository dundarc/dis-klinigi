<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Raporlar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Finansal Raporlar -->
                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Finansal Raporlar</h3>
                </div>

                <x-report-card 
                    href="{{ route('reports.financial-summary') }}" 
                    title="Finansal Özet ve Gelir Raporu"
                    description="Belirtilen aralıktaki toplam ciro, tahsilat, sigorta alacağı ve vadeli alacakları gösterir."
                />

                <x-report-card 
                    href="{{ route('reports.dentist-performance') }}" 
                    title="Hekim Performans Raporu"
                    description="Her hekimin yaptığı işlem, hasta sayısı ve ürettiği ciro bazında performansını analiz eder."
                />

                <x-report-card 
                    href="{{ route('reports.treatment-revenue') }}" 
                    title="Tedavi Bazlı Gelir Raporu"
                    description="Hangi tedavilerin en çok gelir getirdiğini ve en sık uygulandığını gösterir."
                />

                <!-- Operasyonel Raporlar -->
                <div class="col-span-1 md:col-span-2 lg:col-span-3 mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Operasyonel Raporlar</h3>
                </div>

                <x-report-card 
                    href="{{ route('reports.appointment-analysis') }}" 
                    title="Randevu Analiz Raporu"
                    description="Gerçekleşen, iptal edilen ve gelinmeyen (no-show) randevu oranlarını analiz eder."
                />

                <x-report-card 
                    href="{{ route('reports.new-patient-acquisition') }}" 
                    title="Yeni Hasta Kazanım Raporu"
                    description="Kliniğin büyüme hızını ve belirli bir dönemde kazanılan yeni hasta sayısını gösterir."
                />

            </div>
        </div>
    </div>
</x-app-layout>
