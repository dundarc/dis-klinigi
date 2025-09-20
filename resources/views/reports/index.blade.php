<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Raporlar ve İstatistikler') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Son 30 Günlük Randevu Sayısı
                    </h3>
                    <div>
                        <canvas id="dailyAppointmentsChart"></canvas>
                    </div>
                </x-card>

                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Randevu Durum Dağılımı (Son 3 Ay)
                    </h3>
                    <div class="max-w-xs mx-auto">
                        <canvas id="appointmentStatusChart"></canvas>
                    </div>
                </x-card>
                
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Chart.js verilerini global bir JS değişkenine aktar --}}
        <script>
            window.chartData = @json($chartData);
        </script>
        @vite(['resources/js/reports.js'])
    @endpush
</x-app-layout>