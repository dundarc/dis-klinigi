<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Randevu Detayı
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                    {{ __('Randevu Detayı') }}
                </h2>
                <div class="flex gap-3">
                    @if($appointment->status->value !== 'done' && $appointment->status->value !== 'cancelled')
                        <x-primary-button type="button" onclick="window.location.href='{{ route('waiting-room.action', $appointment->id) }}'">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            {{ __('Tedavi İşlemleri') }}
                        </x-primary-button>
                    @endif
                    <x-secondary-button type="button" onclick="window.location.href='{{ route('calendar') }}'">
                        {{ __('Takvime Dön') }}
                    </x-secondary-button>
                </div>
            </div>

            @if (session('status'))
                <x-auth-session-status class="mb-4" :status="session('status')" />
            @endif

            @php
                $oldStart = old('start_at');
                $oldEnd = old('end_at');
                $startValue = $oldStart ? str_replace(' ', 'T', substr($oldStart, 0, 16)) : optional($appointment->start_at)->format('Y-m-d\TH:i');
                $endValue = $oldEnd ? str_replace(' ', 'T', substr($oldEnd, 0, 16)) : optional($appointment->end_at)->format('Y-m-d\TH:i');
            @endphp

            <x-card>
                <div class="grid gap-10 lg:grid-cols-2">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Randevu Özeti') }}</h3>
                        <dl class="mt-6 space-y-4 text-sm text-gray-700 dark:text-gray-300">
                            <div>
                                <dt class="font-medium text-gray-500 dark:text-gray-400">{{ __('Hasta') }}</dt>
                                <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                    {{ $appointment->patient?->first_name }} {{ $appointment->patient?->last_name }}
                                </dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500 dark:text-gray-400">{{ __('Hekim') }}</dt>
                                <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                    {{ $appointment->dentist?->name }}
                                </dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500 dark:text-gray-400">{{ __('Durum') }}</dt>
                                <dd class="mt-1 inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">
                                    {{ __('appointments.status.' . $appointment->status->value) }}
                                </dd>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">{{ __('Başlangıç') }}</dt>
                                    <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                        {{ $appointment->start_at?->format('d.m.Y H:i') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">{{ __('Bitiş') }}</dt>
                                    <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                        {{ $appointment->end_at?->format('d.m.Y H:i') }}
                                    </dd>
                                </div>
                            </div>
                            @if ($appointment->notes)
                                <div>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">{{ __('Notlar') }}</dt>
                                    <dd class="mt-1 whitespace-pre-line text-base text-gray-900 dark:text-gray-100">
                                        {{ $appointment->notes }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Randevuyu Düzenle') }}</h3>
                        <form method="POST" action="{{ route('calendar.update', $appointment) }}" class="mt-6 space-y-5">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="patient_id" :value="__('Hasta')" />
                                <x-select-input id="patient_id" name="patient_id" class="mt-1 block w-full">
                                    <option value="">{{ __('Hasta seçiniz') }}</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}" @selected(old('patient_id', $appointment->patient_id) == $patient->id)>
                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="dentist_id" :value="__('Hekim')" />
                                <x-select-input id="dentist_id" name="dentist_id" class="mt-1 block w-full">
                                    <option value="">{{ __('Hekim seçiniz') }}</option>
                                    @foreach ($dentists as $dentist)
                                        <option value="{{ $dentist->id }}" @selected(old('dentist_id', $appointment->dentist_id) == $dentist->id)>
                                            {{ $dentist->name }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('dentist_id')" class="mt-2" />
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <x-input-label for="start_at" :value="__('Başlangıç')" />
                                    <x-text-input id="start_at" name="start_at" type="datetime-local" class="mt-1 block w-full" :value="$startValue" required />
                                    <x-input-error :messages="$errors->get('start_at')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="end_at" :value="__('Bitiş')" />
                                    <x-text-input id="end_at" name="end_at" type="datetime-local" class="mt-1 block w-full" :value="$endValue" required />
                                    <x-input-error :messages="$errors->get('end_at')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Durum')" />
                                <x-select-input id="status" name="status" class="mt-1 block w-full">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}" @selected(old('status', $appointment->status->value) === $status->value)>
                                            {{ __('appointments.status.' . $status->value) }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="notes" :value="__('Notlar')" />
                                <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $appointment->notes) }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>

                            <div class="flex flex-wrap justify-end gap-3">
                                <x-primary-button>
                                    {{ __('Değişiklikleri Kaydet') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </x-card>

            @can('delete', $appointment)
                <x-card>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Randevuyu Sil') }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        {{ __('Bu işlem geri alınamaz. Randevuya bağlı kayıtların etkilenebileceğini unutmayın.') }}
                    </p>
                    <form method="POST" action="{{ route('calendar.destroy', $appointment) }}" class="mt-5" onsubmit="return confirm('{{ __('Randevuyu silmek istediğinizden emin misiniz?') }}');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>
                            {{ __('Randevuyu Sil') }}
                        </x-danger-button>
                    </form>
                </x-card>
            @endcan

            @if($unplannedItems->isNotEmpty() && $appointment->status->value !== 'done' && $appointment->status->value !== 'cancelled')
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Planlanmamış Tedaviler') }}</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    {{ __('Hastanın tedavi planlarından gelen ve henüz bir randevuya atanmamış tedaviler.') }}
                </p>

                <form method="POST" action="{{ route('appointments.attachItems', $appointment) }}" class="mt-6 space-y-5">
                    @csrf
                    <div class="space-y-4">
                        @foreach($unplannedItems as $item)
                        <div class="flex items-center">
                            <input id="item-{{ $item->id }}" name="items[]" value="{{ $item->id }}" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="item-{{ $item->id }}" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $item->treatment->name }}
                                @if($item->tooth_number)
                                    <span class="text-gray-500">({{ __('Diş') }}: {{ $item->tooth_number }})</span>
                                @endif
                                - (Plan #{{ $item->treatment_plan_id }})
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end mt-6">
                        <x-primary-button>
                            {{ __('Seçilenleri Bu Randevuya Ekle') }}
                        </x-primary-button>
                    </div>
                </form>
            </x-card>
            @endif
        </div>
    </div>
</x-app-layout>
