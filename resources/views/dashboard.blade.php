<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ana Sayfa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold">Hoşgeldin, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">Kliniğin anlık durumunu buradan takip edebilirsin.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if ($showCheckInCard)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Check-in bekleyen randevular (bugün)
                                </h3>
                                <x-primary-button-link href="{{ route('appointments.today') }}">
                                    Check-in ekranına git
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
                                            Bugün için check-in bekleyen randevu yok.
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($showEmergencyCard)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Acilde sıra bekleyenler
                                </h3>
                                <x-primary-button-link href="{{ route('waiting-room.emergency') }}">
                                    Acil ekranına git
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
                                                        Giriş: {{ $encounter->arrived_at->format('H:i') }} - Dr. {{ $encounter->dentist?->name ?? 'Atanmamış' }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <x-triage-badge :level="$encounter->triage_level" />
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="py-4 text-sm text-center text-gray-500">
                                            Acil bekleme listesi boş.
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($showUnpaidInvoicesCard)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Son 5 tahsil edilmemiş fatura
                                </h3>
                                <x-primary-button-link href="{{ route('accounting.index') }}">
                                    Muhasebeye git
                                </x-primary-button-link>
                            </div>
                            <div class="mt-4 flow-root">
                                <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($unpaidInvoices as $invoice)
                                        <li class="py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                        {{ $invoice->invoice_no }}
                                                    </p>
                                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                        {{ $invoice->patient?->first_name }} {{ $invoice->patient?->last_name }} - {{ number_format($invoice->grand_total, 2, ',', '.') }} TL
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="py-4 text-sm text-center text-gray-500">
                                            Tahsil edilmemiş fatura yok.
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($showCompletedCard)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Son 5 işlemi biten hasta (bugün)
                                </h3>
                                <x-primary-button-link href="{{ route('waiting-room.completed') }}">
                                    Tamamlananlar listesine git
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
                                            Bugün işlemi tamamlanan hasta yok.
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
