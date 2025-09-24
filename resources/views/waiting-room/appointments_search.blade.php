<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Geçmiş/Gelecek Randevu Ara') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <!-- Arama Formu -->
                <form method="GET" action="{{ route('waiting-room.appointments.search') }}" class="p-4 mb-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="start_date" value="Başlangıç Tarihi" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="request('start_date')" />
                        </div>
                        <div>
                            <x-input-label for="end_date" value="Bitiş Tarihi" />
                            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="request('end_date')" />
                        </div>
                        <div>
                            <x-input-label for="patient_id" value="Hasta" />
                            <x-select-input id="patient_id" name="patient_id" class="mt-1 block w-full">
                                <option value="">Tüm Hastalar</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" @selected(request('patient_id') == $patient->id)>
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                        </div>
                        <div>
                            <x-input-label for="dentist_id" value="Hekim" />
                            <x-select-input id="dentist_id" name="dentist_id" class="mt-1 block w-full">
                                <option value="">Tüm Hekimler</option>
                                @foreach($dentists as $dentist)
                                    <option value="{{ $dentist->id }}" @selected(request('dentist_id') == $dentist->id)>
                                        {{ $dentist->name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <x-primary-button>
                            Randevuları Ara
                        </x-primary-button>
                    </div>
                </form>

                <!-- Arama Sonuçları -->
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Arama Sonuçları</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tarih ve Saat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hasta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hekim</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Durum</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if($appointments->isNotEmpty())
                                @foreach ($appointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $appointment->start_at->format('d.m.Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $appointment->dentist->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $appointment->status->value }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <x-secondary-button-link href="{{ route('calendar.show', $appointment) }}">
                                                    İşlem
                                                </x-secondary-button-link>
                                                @if($appointment->encounter)
                                                    <x-secondary-button-link href="{{ route('waiting-room.action', $appointment->encounter) }}">
                                                        Ziyareti Gör
                                                    </x-secondary-button-link>
                                                @else
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">Check-in yapılmamış</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Arama kriterlerinize uygun randevu bulunamadı.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                 @if($appointments->hasPages())
                    <div class="mt-4">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
