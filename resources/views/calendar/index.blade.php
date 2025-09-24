<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="flex flex-col gap-2 md:flex-row md:items-baseline md:justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('calendar.title') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $monthLabel }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <x-secondary-button-link :href="$previousMonthUrl">&larr; {{ __('calendar.previous') }}</x-secondary-button-link>
                    <x-secondary-button-link :href="$todayUrl">{{ __('calendar.today') }}</x-secondary-button-link>
                    <x-secondary-button-link :href="$nextMonthUrl">{{ __('calendar.next') }} &rarr;</x-secondary-button-link>
                    @can('create', \App\Models\Appointment::class)
                        <x-primary-button-link :href="route('waiting-room.appointments.create')">
                            {{ __('calendar.new_appointment') }}
                        </x-primary-button-link>
                    @endcan
                </div>
            </div>

            <x-card>
                <form method="GET" class="space-y-6">
                    <input type="hidden" name="month" value="{{ $currentMonthKey }}">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label value="{{ __('calendar.dentists') }}" />
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
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('calendar.no_dentist') }}</p>
                                @endforelse
                            </div>
                        </div>
                        <div>
                            <x-input-label value="{{ __('calendar.statuses') }}" />
                            <div class="mt-3 space-y-2">
                                @foreach($statusOptions as $status)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                        <input
                                            type="checkbox"
                                            name="statuses[]"
                                            value="{{ $status['value'] }}"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900"
                                            @checked(in_array($status['value'], $selectedStatuses, true))
                                        >
                                        <span>{{ $status['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-end gap-3">
                        @if($filtersApplied)
                            <x-secondary-button-link :href="route('calendar', ['month' => $currentMonthKey])">
                                {{ __('calendar.clear_filters') }}
                            </x-secondary-button-link>
                        @endif
                        <x-primary-button>
                            {{ __('calendar.apply_filters') }}
                        </x-primary-button>
                    </div>
                </form>
            </x-card>

            <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                    @foreach($weekDays as $dayName)
                        <div class="px-2 py-3">{{ $dayName }}</div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 divide-x divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($days as $day)
                        @php
                            $dayClasses = $day['isCurrentMonth']
                                ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100'
                                : 'bg-gray-50 dark:bg-gray-900/40 text-gray-400 dark:text-gray-500';
                        @endphp
                        <div class="min-h-[10rem] p-3 {{ $dayClasses }}">
                            <div class="flex items-baseline justify-between">
                                <span class="text-sm font-semibold">{{ $day['date']->format('j') }}</span>
                                @if($day['isToday'])
                                    <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-[0.65rem] font-semibold text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-200">{{ __('calendar.today') }}</span>
                                @endif
                            </div>
                            <div class="mt-2 space-y-2">
                                @forelse($day['appointments'] as $appointment)
                                    @php
                                        $statusValue = $appointment->status->value;
                                        $badgeClasses = $statusStyles[$statusValue] ?? $statusStyles['default'];
                                    @endphp
                                    <a
                                        href="{{ route('calendar.show', $appointment) }}"
                                        class="block rounded border-l-4 px-2 py-1 text-xs leading-tight transition hover:bg-indigo-50 dark:hover:bg-indigo-900/40 {{ $badgeClasses }}"
                                    >
                                        <div class="flex justify-between gap-2">
                                            <span class="font-semibold">{{ $appointment->start_at->format('H:i') }}</span>
                                            <span class="truncate">{{ $appointment->dentist->name }}</span>
                                        </div>
                                        <div class="mt-1 text-sm font-medium">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</div>
                                        <div class="text-[0.65rem] uppercase tracking-wide text-gray-600 dark:text-gray-300">
                                            {{ $statusLabels[$statusValue] ?? $statusValue }}
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ __('calendar.no_appointment') }}</p>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>