<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Haftalık Takvim
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Üst Başlık ve Görünümler --}}
            <div class="flex flex-col gap-2 md:flex-row md:items-baseline md:justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('calendar.title') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $monthLabel }}</p>
                </div>
                <div class="flex space-x-2">
                    <x-secondary-button-link :href="route('calendar.day') . '?' . http_build_query(request()->query())" :active="$currentView==='day'">
                        {{ __('Günlük') }}
                    </x-secondary-button-link>
                    <x-secondary-button-link :href="route('calendar.week') . '?' . http_build_query(request()->query())" :active="$currentView==='week'">
                        {{ __('Haftalık') }}
                    </x-secondary-button-link>
                    <x-secondary-button-link :href="route('calendar') . '?' . http_build_query(request()->query())" :active="$currentView==='month'">
                        {{ __('Aylık') }}
                    </x-secondary-button-link>
                    @if(in_array($currentView, ['week', 'day']))
                        <button onclick="window.print()" class="px-4 py-2 bg-green-600 text-white rounded-md">
                            {{ __('Yazdır') }}
                        </button>
                        <a href="{{ route('calendar.export', ['format' => 'pdf'] + request()->query()) }}" class="px-4 py-2 bg-red-600 text-white rounded-md">
                            {{ __('PDF') }}
                        </a>
                    @endif
                </div>
            </div>

            {{-- Filtre Kartı --}}
            <x-card class="no-print">
                <form method="GET" class="space-y-6">
                    <input type="hidden" name="view" value="{{ $currentView }}">

                    <div class="grid gap-6 md:grid-cols-2">

                        {{-- Doktor Seçimi --}}
                        <div>
                            <x-input-label value="{{ __('calendar.dentists') }}" />
                            <div class="mt-3">
                                @if($showDentistFilter)
                                    {{-- Çoklu Seçim Dropdown --}}
                                    <div x-data="{ open: false }" class="relative">
                                        <button type="button" @click="open = !open"
                                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-left text-sm shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200">
                                            @if(count($selectedDentists))
                                                {{ __('Seçili:') }} {{ count($selectedDentists) }}
                                            @else
                                                {{ __('Doktor seçin') }}
                                            @endif
                                        </button>

                                        <div x-show="open" @click.away="open=false"
                                            class="absolute z-10 mt-1 w-full rounded-md border border-gray-200 bg-white shadow-lg dark:bg-gray-800 dark:border-gray-700">
                                            <div class="max-h-60 overflow-y-auto p-2 space-y-2">
                                                @foreach($dentists as $dentist)
                                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                        <input type="checkbox" name="dentists[]" value="{{ $dentist->id }}"
                                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900"
                                                            @checked(in_array($dentist->id, $selectedDentists, true))>
                                                        <span>{{ $dentist->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Doktor kendi adını sadece görür --}}
                                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ auth()->user()->name }}
                                    </p>
                                    <input type="hidden" name="dentists[]" value="{{ auth()->id() }}">
                                @endif
                            </div>
                        </div>

                        {{-- Durum Filtreleri --}}
                        <div>
                            <x-input-label value="{{ __('calendar.statuses') }}" />
                            <div class="mt-3 space-y-2">
                                @foreach($statusOptions as $status)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                        <input type="checkbox" name="statuses[]" value="{{ $status['value'] }}"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900"
                                            @checked(in_array($status['value'], $selectedStatuses, true))>
                                        <span>{{ $status['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-end gap-3">
                        @if($filtersApplied)
                            <x-secondary-button-link :href="route('calendar', ['view'=>$currentView])">
                                {{ __('calendar.clear_filters') }}
                            </x-secondary-button-link>
                        @endif
                        <x-primary-button>{{ __('calendar.apply_filters') }}</x-primary-button>
                    </div>
                </form>
            </x-card>

            {{-- Takvim Navigasyonu --}}
            <div class="no-print flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">

                    {{-- Görünüme göre navigasyon --}}
                    @if($currentView === 'month')
                        <x-secondary-button-link :href="$previousMonthUrl">&larr; {{ __('Önceki Ay') }}</x-secondary-button-link>
                        <x-secondary-button-link :href="$nextMonthUrl">{{ __('Sonraki Ay') }} &rarr;</x-secondary-button-link>
                    @elseif($currentView === 'week')
                        <x-secondary-button-link :href="$previousWeekUrl">&larr; {{ __('Önceki Hafta') }}</x-secondary-button-link>
                        <x-secondary-button-link :href="$nextWeekUrl">{{ __('Sonraki Hafta') }} &rarr;</x-secondary-button-link>
                    @elseif($currentView === 'day')
                        <x-secondary-button-link :href="$previousDayUrl">&larr; {{ __('Önceki Gün') }}</x-secondary-button-link>
                        <x-secondary-button-link :href="$nextDayUrl">{{ __('Sonraki Gün') }} &rarr;</x-secondary-button-link>
                    @endif

                    {{-- Her zaman görünen butonlar --}}
 
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <x-secondary-button-link href="{{ route('waiting-room.appointments.search') }}">
                        {{ __('Randevu Ara') }}
                    </x-secondary-button-link>
                    @can('create', \App\Models\Appointment::class)
                        <x-primary-button-link :href="route('waiting-room.appointments.create')">
                            {{ __('calendar.new_appointment') }}
                        </x-primary-button-link>
                    @endcan
                </div>
            </div>

            {{-- Saat bazlı görünüm --}}
            <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Saat') }}
                                </th>
                                @foreach($days as $day)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ $day['date']->format('D j M') }}
                                        @if($day['isToday'])
                                            <span class="text-indigo-600 dark:text-indigo-400"> ({{ __('Bugün') }})</span>
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $hours = collect(range(8, 18));
                            @endphp
                            @foreach($hours as $hour)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ sprintf('%02d:00', $hour) }}
                                    </td>
                                    @foreach($days as $day)
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $hourData = $day['hours']->firstWhere('hour', $hour);
                                            @endphp
                                            @if($hourData && $hourData['appointments']->count() > 0)
                                                <div class="space-y-2">
                                                    @foreach($hourData['appointments'] as $appointment)
                                                        @php
                                                            $statusValue = $appointment->status->value;
                                                            $badgeClasses = $statusStyles[$statusValue] ?? $statusStyles['default'];
                                                        @endphp
                                                        <a href="{{ route('calendar.show', $appointment) }}"
                                                            title="{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }} - {{ optional($appointment->treatment)->name ?? 'N/A' }}"
                                                            class="block rounded border-l-4 px-2 py-1 text-xs leading-tight transition hover:bg-indigo-50 dark:hover:bg-indigo-900/40 {{ $badgeClasses }}">
                                                            <div class="font-semibold">{{ $appointment->start_at->format('H:i') }}</div>
                                                            <div class="text-sm">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</div>
                                                            <div class="text-xs text-gray-600 dark:text-gray-300">{{ $appointment->dentist->name }}</div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="flex justify-center">
                                                    <a href="{{ route('waiting-room.appointments.create', ['date' => $day['date']->toDateString(), 'hour' => $hour]) }}"
                                                        title="{{ __('Randevu Ekle') }}"
                                                        class="text-xs text-blue-500 hover:text-blue-700 dark:text-blue-400">
                                                        +
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <style media="print">
        .no-print { display: none !important; }
        body { font-size: 12px; }
        .print-friendly { page-break-inside: avoid; }
    </style>
</x-app-layout>