<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ana Sayfa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Hoşgeldin Kutusu -->
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold">Hoşgeldin, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">Kliniğin anlık durumunu aşağıdan takip edebilirsin.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Check-in Yapmamış Hastalar -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Check-in Bekleyen Randevular (Bugün)
                            </h3>
                            <x-primary-button-link href="{{ route('appointments.today') }}">
                                Check-in Ekranına Git
                            </x-primary-button-link>
                        </div>
                        <div class="mt-4 flow-root">
                            <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($uncheckedAppointments as $appointment)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                    {{ $appointment->patient?->first_name }} {{ $appointment->patient?->last_name }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                    {{ $appointment->start_at->format('H:i') }} - Dr. {{ $appointment->dentist?->name ?? '[Silinmiş Hekim]' }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-4 text-sm text-center text-gray-500">
                                        Bugün için check-in bekleyen randevu bulunmuyor.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Acilde Sıra Bekleyen Hastalar -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                     <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Acilde Sıra Bekleyenler
                            </h3>
                            <x-primary-button-link href="{{ route('waiting-room.emergency') }}">
                                Acil Ekranına Git
                            </x-primary-button-link>
                        </div>
                         <div class="mt-4 flow-root">
                            <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($emergencyQueue as $encounter)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                    {{ $encounter->patient?->first_name }} {{ $encounter->patient?->last_name }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                    Geliş: {{ $encounter->arrived_at->format('H:i') }} - Dr. {{ $encounter->dentist?->name ?? 'Atanmadı' }}
                                                </p>
                                            </div>
                                            <div>
                                                <x-triage-badge :level="$encounter->triage_level" />
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                     <li class="py-4 text-sm text-center text-gray-500">
                                        Acilde sıra bekleyen hasta bulunmuyor.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tahsil Edilmemiş Faturalar -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                     <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Son 5 Tahsil Edilmemiş Fatura
                            </h3>
                            <x-primary-button-link href="{{ route('accounting.index') }}">
                                Muhasebe Ekranına Git
                            </x-primary-button-link>
                        </div>
                         <div class="mt-4 flow-root">
                            <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                               @forelse($unpaidInvoices as $invoice)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                    {{ $invoice->patient?->first_name }} {{ $invoice->patient?->last_name }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                    {{ $invoice->issue_date->format('d.m.Y') }} - {{ number_format($invoice->grand_total, 2, ',', '.') }} TL
                                                </p>
                                            </div>
                                             <div>
                                                <span class="text-xs font-semibold px-2 py-1 rounded-full 
                                                    @if($invoice->status === \App\Enums\InvoiceStatus::OVERDUE) bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300
                                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300 @endif">
                                                    {{ $invoice->status->value }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                     <li class="py-4 text-sm text-center text-gray-500">
                                        Tahsil edilmemiş fatura bulunmuyor.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tedavisi Tamamlanmış Hastalar (Günlük) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Son 5 İşlemi Biten Hasta (Bugün)
                            </h3>
                            <x-primary-button-link href="{{ route('waiting-room.completed') }}">
                                Tamamlananlar Listesine Git
                            </x-primary-button-link>
                        </div>
                         <div class="mt-4 flow-root">
                            <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($completedEncounters as $encounter)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                    {{ $encounter->patient?->first_name }} {{ $encounter->patient?->last_name }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                    Çıkış: {{ $encounter->ended_at?->format('H:i') }} - Dr. {{ $encounter->dentist?->name }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-4 text-sm text-center text-gray-500">
                                        Bugün işlemi tamamlanan hasta bulunmuyor.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>

