<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-baseline md:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Takvim: Bugün</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $todayLabel }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <x-secondary-button-link href="{{ route('calendar') }}">
                    Aylýk Görünüm
                </x-secondary-button-link>
                <x-secondary-button-link href="{{ route('waiting-room.emergency') }}">
                    Acil Listesi
                </x-secondary-button-link>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
                <div class="space-y-6">
                    <x-card>
                        <form method="GET" action="{{ route('calendar.today') }}" class="space-y-4">
                            <div>
                                <x-input-label value="Hekim Filtrele" />
                                <div class="mt-3 space-y-2">
                                    @forelse($dentists as $dentist)
                                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                            <input
                                                type="checkbox"
                                                name="dentists[]"
                                                value="{{ $dentist->id }}"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900"
                                                @checked(in_array($dentist->id, $selectedDentists, true))
                                            >
                                            <span>{{ $dentist->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tanýmlý hekim bulunmuyor.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center justify-end gap-3">
                                @if($filtersApplied)
                                    <x-secondary-button-link href="{{ route('calendar.today') }}">
                                        Filtreyi Temizle
                                    </x-secondary-button-link>
                                @endif
                                <x-primary-button>Filtrele</x-primary-button>
                            </div>
                        </form>
                    </x-card>

                    <x-card>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Bugünün Randevularý</h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $appointments->count() }} randevu</span>
                        </div>
                        @if($hourlySlots->isEmpty())
                            <p class="text-sm text-gray-500 dark:text-gray-400">Bugün için randevu bulunmuyor.</p>
                        @else
                            <div class="space-y-5">
                                @foreach($hourlySlots as $slot)
                                    <div class="flex gap-4">
                                        <div class="w-20 flex-shrink-0 text-sm font-semibold text-indigo-600 dark:text-indigo-300">{{ $slot['label'] }}</div>
                                        <div class="flex-1 space-y-3">
                                            @foreach($slot['appointments'] as $appointment)
                                                @php
                                                    $statusValue = $appointment->status->value;
                                                    $badgeClasses = $statusStyles[$statusValue] ?? $statusStyles['default'];
                                                @endphp
                                                <a
                                                    href="{{ route('calendar.show', $appointment) }}"
                                                    class="block rounded border-l-4 px-4 py-3 text-sm transition hover:bg-indigo-50 dark:hover:bg-indigo-900/40 {{ $badgeClasses }}"
                                                >
                                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                                        <span class="font-semibold">{{ $appointment->start_at->format('H:i') }} - {{ $appointment->end_at?->format('H:i') ?? '---' }}</span>
                                                        <span class="text-[0.65rem] uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ $statusLabels[$statusValue] ?? $statusValue }}</span>
                                                    </div>
                                                    <div class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                    </div>
                                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                                        Hekim: {{ $appointment->dentist->name }}
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </x-card>
                </div>

                <div class="space-y-6">
                    <x-card>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Acil Sýra Bekleyenler</h3>
                            <x-secondary-button-link href="{{ route('waiting-room.emergency') }}">
                                Tümünü Gör
                            </x-secondary-button-link>
                        </div>
                        @if($emergencyEncounters->isEmpty())
                            <p class="text-sm text-gray-500 dark:text-gray-400">Þu anda acil bekleyen hasta bulunmuyor.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($emergencyEncounters as $encounter)
                                    @php
                                        $triageLabel = match ($encounter->triage_level?->value) {
                                            'red' => 'Kýrmýzý',
                                            'yellow' => 'Sarý',
                                            'green' => 'Yeþil',
                                            default => 'Belirtilmemiþ',
                                        };
                                        $arrivalTime = $encounter->arrived_at?->format('H:i') ?? $encounter->created_at?->format('H:i') ?? '--';
                                    @endphp
                                    <div class="rounded border border-gray-200 p-3 dark:border-gray-700">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    Triyaj: {{ $triageLabel }} • Geliþ: {{ $arrivalTime }}
                                                </p>
                                            </div>
                                            <x-primary-button-link href="{{ route('waiting-room.action', $encounter) }}">
                                                Ýþlem Yap
                                            </x-primary-button-link>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
