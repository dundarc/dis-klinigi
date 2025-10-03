<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Takvim</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $monthLabel }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('calendar.export', ['format' => 'pdf'] + request()->query()) }}"
                   class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF
                </a>
                <a href="{{ route('waiting-room.appointments.search') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Randevu Ara
                </a>
                @can('create', \App\Models\Appointment::class)
                <a href="{{ route('waiting-room.appointments.create') }}"
                   class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Yeni Randevu
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Modern Header with View Toggle --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Takvim Görünümü</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Randevularınızı yönetin</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="flex bg-slate-100 dark:bg-slate-700 rounded-lg p-1">
                            <a href="{{ route('calendar.day') . '?' . http_build_query(request()->query()) }}"
                               class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ $currentView === 'day' ? 'bg-white dark:bg-slate-600 text-slate-900 dark:text-slate-100 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100' }}">
                                Günlük
                            </a>
                            <a href="{{ route('calendar.week') . '?' . http_build_query(request()->query()) }}"
                               class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ $currentView === 'week' ? 'bg-white dark:bg-slate-600 text-slate-900 dark:text-slate-100 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100' }}">
                                Haftalık
                            </a>
                            <a href="{{ route('calendar') . '?' . http_build_query(request()->query()) }}"
                               class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ $currentView === 'month' ? 'bg-white dark:bg-slate-600 text-slate-900 dark:text-slate-100 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100' }}">
                                Aylık
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modern Filter Section --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 no-print">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Filtreler</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Randevuları filtreleyin</p>
                    </div>
                </div>

                <form method="GET" class="space-y-6">
                    <input type="hidden" name="view" value="{{ $currentView }}">
                    <input type="hidden" name="month" value="{{ $currentMonthKey }}">

                    <div class="grid gap-6 lg:grid-cols-2">
                        {{-- Modern Doctor Selection --}}
                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Doktor Seçimi
                            </label>
                            <div class="relative">
                                @if($showDentistFilter)
                                    <div x-data="{ open: false }" class="relative">
                                        <button type="button" @click="open = !open"
                                            class="w-full flex items-center justify-between rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-3 text-left text-sm shadow-sm transition-colors hover:border-slate-400 dark:hover:border-slate-500">
                                            <span class="text-slate-900 dark:text-slate-100">
                                                @if(count($selectedDentists))
                                                    {{ count($selectedDentists) }} doktor seçildi
                                                @else
                                                    Tüm doktorlar
                                                @endif
                                            </span>
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>

                                        <div x-show="open" @click.away="open=false"
                                            class="absolute z-20 mt-2 w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-xl">
                                            <div class="max-h-64 overflow-y-auto p-3 space-y-3">
                                                @foreach($dentists as $dentist)
                                                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer">
                                                        <input type="checkbox" name="dentists[]" value="{{ $dentist->id }}"
                                                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700"
                                                            @checked(in_array($dentist->id, $selectedDentists, true))>
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                                                <span class="text-xs font-medium text-blue-600 dark:text-blue-400">
                                                                    {{ substr($dentist->name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $dentist->name }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ auth()->user()->name }}</span>
                                    </div>
                                    <input type="hidden" name="dentists[]" value="{{ auth()->id() }}">
                                @endif
                            </div>
                        </div>

                        {{-- Modern Status Filters --}}
                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Randevu Durumları
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($statusOptions as $status)
                                    <label class="flex items-center gap-2 p-3 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 hover:border-slate-300 dark:hover:border-slate-500 cursor-pointer transition-colors">
                                        <input type="checkbox" name="statuses[]" value="{{ $status['value'] }}"
                                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700"
                                            @checked(in_array($status['value'], $selectedStatuses, true))>
                                        <div class="flex items-center gap-2">
                                            <div class="w-3 h-3 rounded-full {{ $statusStyles[$status['value']] ? 'bg-green-500' : 'bg-slate-400' }}"></div>
                                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $status['label'] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-600">
                        @if($filtersApplied)
                            <a href="{{ route('calendar', ['view'=>$currentView,'month'=>$currentMonthKey]) }}"
                               class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Filtreleri Temizle
                            </a>
                        @endif
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filtreleri Uygula
                        </button>
                    </div>
                </form>
            </div>

            {{-- Modern Navigation --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 no-print">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-center gap-4">
                        {{-- Previous/Next Navigation --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ $previousMonthUrl }}"
                               class="inline-flex items-center px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Önceki Ay
                            </a>
                            <a href="{{ $nextMonthUrl }}"
                               class="inline-flex items-center px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors">
                                Sonraki Ay
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        {{-- Today Button --}}
                        <a href="{{ route('calendar.today') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Bugün
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            {{ $monthLabel }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modern Calendar Grid --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                {{-- Modern Day Headers --}}
                <div class="grid grid-cols-7 bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-600">
                    @foreach($weekDays as $dayName)
                        <div class="px-4 py-4 text-center">
                            <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ $dayName }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Modern Day Cells --}}
                <div class="grid grid-cols-7">
                    @foreach($days as $day)
                        @php
                            $isCurrentMonth = $day['isCurrentMonth'];
                            $isToday = $day['isToday'] ?? false;
                            $cellId = $isToday ? ($todayAnchorId ?? null) : null;
                            $hasAppointments = count($day['appointments']) > 0;
                        @endphp

                        <div @if($cellId) id="{{ $cellId }}" @endif
                             class="min-h-[180px] p-4 border-r border-b border-slate-200 dark:border-slate-600 last:border-r-0 {{ !$isCurrentMonth ? 'bg-slate-50/50 dark:bg-slate-800/30' : 'bg-white dark:bg-slate-800' }} {{ $isToday ? 'ring-2 ring-blue-500 ring-inset' : '' }} scroll-mt-24">

                            {{-- Day Number --}}
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-lg font-bold {{ $isCurrentMonth ? 'text-slate-900 dark:text-slate-100' : 'text-slate-400 dark:text-slate-500' }}">
                                    {{ $day['date']->format('j') }}
                                </span>
                                @if($isToday)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">
                                        Bugün
                                    </span>
                                @endif
                            </div>

                            {{-- Appointments --}}
                            <div class="space-y-2">
                                @forelse($day['appointments'] as $appointment)
                                    @php
                                        $statusValue = $appointment->status->value;
                                        $statusColor = match($statusValue) {
                                            'confirmed' => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-300 dark:border-green-700',
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-300 dark:border-yellow-700',
                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-300 dark:border-red-700',
                                            'completed' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-300 dark:border-blue-700',
                                            default => 'bg-slate-100 text-slate-800 border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600'
                                        };
                                    @endphp

                                    <a href="{{ route('calendar.show', $appointment) }}"
                                       class="block p-3 rounded-lg border transition-all hover:shadow-md hover:scale-[1.02] {{ $statusColor }}"
                                       title="{{ $appointment->patient ? $appointment->patient->first_name . ' ' . $appointment->patient->last_name : 'Hasta bilgisi yok' }} - {{ optional($appointment->treatment)->name ?? 'N/A' }}">

                                        {{-- Time and Doctor --}}
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-bold text-slate-900 dark:text-slate-100">
                                                {{ $appointment->start_at->format('H:i') }}
                                            </span>
                                            <div class="flex items-center gap-1">
                                                <div class="w-5 h-5 bg-slate-200 dark:bg-slate-600 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-slate-600 dark:text-slate-300">
                                                        {{ mb_substr($appointment->dentist?->name ?? '', 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Patient Name --}}
                                        <div class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate">
                                            {{ $appointment->patient ? $appointment->patient->first_name . ' ' . $appointment->patient->last_name : 'Hasta bilgisi yok' }}
                                        </div>

                                        {{-- Treatment --}}
                                        <div class="text-xs text-slate-600 dark:text-slate-400 mt-1 truncate">
                                            {{ optional($appointment->treatment)->name ?? 'Tedavi belirtilmemiş' }}
                                        </div>

                                        {{-- Status Badge --}}
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                                {{ $statusLabels[$statusValue] ?? ucfirst($statusValue) }}
                                            </span>
                                        </div>
                                    </a>
                                @empty
                                    {{-- Empty State --}}
                                    <div class="flex flex-col items-center justify-center h-16 text-center">
                                        <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-2">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </div>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mb-2">Randevu yok</p>
                                        @can('create', \App\Models\Appointment::class)
                                            <a href="{{ route('waiting-room.appointments.create', ['date' => $day['date']->toDateString()]) }}"
                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 rounded-md transition-colors">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Ekle
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