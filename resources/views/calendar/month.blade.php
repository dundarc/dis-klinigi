<x-app-layout>
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
                    <a href="{{ route('calendar.export', ['format' => 'pdf'] + request()->query()) }}" class="px-4 py-2 bg-red-600 text-white rounded-md">
                        {{ __('PDF') }}
                    </a>
                </div>
            </div>

            {{-- Filtre Kartı --}}
            <x-card class="no-print">
                <form method="GET" class="space-y-6">
                    <input type="hidden" name="view" value="{{ $currentView }}">
                    <input type="hidden" name="month" value="{{ $currentMonthKey }}">

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
                            <x-secondary-button-link :href="route('calendar', ['view'=>$currentView,'month'=>$currentMonthKey])">
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
                    <x-secondary-button-link :href="$todayUrl">{{ __('calendar.today') }}</x-secondary-button-link>

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

            {{-- Takvim ızgarası --}}
            <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                {{-- Gün başlıkları --}}
                <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                    @foreach($weekDays as $dayName)
                        <div class="px-2 py-3">{{ $dayName }}</div>
                    @endforeach
                </div>

                {{-- Gün hücreleri --}}
                <div class="grid grid-cols-7 divide-x divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($days as $day)
                        @php
                            $dayClasses = $day['isCurrentMonth']
                                ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100'
                                : 'bg-gray-50 dark:bg-gray-900/40 text-gray-400 dark:text-gray-500';
                            $cellId = ($day['isToday'] ?? false) ? ($todayAnchorId ?? null) : null;
                        @endphp
                        <div @if($cellId) id="{{ $cellId }}" @endif class="min-h-[10rem] p-3 scroll-mt-24 {{ $dayClasses }}">
                            <div class="flex items-baseline justify-between">
                                <span class="text-sm font-semibold">{{ $day['date']->format('j') }}</span>
                                @if($day['isToday'])
                                    <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-[0.65rem] font-semibold text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-200">
                                        {{ __('calendar.today') }}
                                    </span>
                                @endif
                            </div>
                            <div class="mt-2 space-y-2">
                                @forelse($day['appointments'] as $appointment)
                                    @php
                                        $statusValue = $appointment->status->value;
                                        $badgeClasses = $statusStyles[$statusValue] ?? $statusStyles['default'];
                                    @endphp
                                    <a href="{{ route('calendar.show', $appointment) }}"
                                        title="{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }} - {{ optional($appointment->treatment)->name ?? 'N/A' }}"
                                        class="block rounded border-l-4 px-2 py-1 text-xs leading-tight transition hover:bg-indigo-50 dark:hover:bg-indigo-900/40 {{ $badgeClasses }}">
                                        <div class="flex justify-between gap-2">
                                            <span class="font-semibold">{{ $appointment->start_at->format('H:i') }}</span>
                                            <span class="truncate">{{ $appointment->dentist->name }}</span>
                                        </div>
                                        <div class="mt-1 text-sm font-medium">
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                        </div>
                                        <div class="text-[0.65rem] uppercase tracking-wide text-gray-600 dark:text-gray-300">
                                            {{ $statusLabels[$statusValue] ?? $statusValue }}
                                        </div>
                                    </a>
                                @empty
                                    <div class="flex justify-between items-center">
                                        <p class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ __('calendar.no_appointment') }}
                                        </p>
                                        @can('create', \App\Models\Appointment::class)
                                            <a href="{{ route('waiting-room.appointments.create', ['date' => $day['date']->toDateString()]) }}"
                                                class="text-xs text-blue-500 hover:text-blue-700 dark:text-blue-400">
                                                +
                                            </a>
                                        @endcan
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>