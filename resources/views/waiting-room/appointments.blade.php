<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Bekleyen Hastalar (Randevulu & Acil)') }}
            </h2>
            {{-- Aksiyon Butonları --}}
            <div class="flex items-center space-x-2">
                {{-- YENİ BUTON: Check-in ekranına yönlendirme --}}
                <x-primary-button-link href="{{ route('appointments.today') }}">
                    Check-in Yap
                </x-primary-button-link>
                <x-secondary-button-link href="{{ route('waiting-room.appointments.create') }}">
                    Yeni Randevu Ekle
                </x-secondary-button-link>
                <x-secondary-button-link href="{{ route('waiting-room.appointments.search') }}">
                    Randevu Ara
                </x-secondary-button-link>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="space-y-4">
                    @forelse($waitingEncounters as $encounter)
                         <div class="p-4 border rounded-lg dark:border-gray-700 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-4">
                                    <p class="font-bold text-lg dark:text-white">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
                                    @if($encounter->triage_level)
                                        <x-triage-badge :level="$encounter->triage_level" />
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            Randevulu
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Giriş: {{ $encounter->arrived_at->format('H:i') }} | 
                                    Hekim: {{ $encounter->dentist->name ?? 'Atanmadı' }} |
                                    Randevu Saati: {{ $encounter->appointment?->start_at->format('H:i') ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                @can('update', $encounter)
                                    <x-primary-button-link href="{{ route('waiting-room.action', $encounter) }}">
                                        İşlem Yap
                                    </x-primary-button-link>
                                @endcan
                            </div>
                        </div>
                    @empty
                         <p class="text-center py-4 text-gray-500 dark:text-gray-400">Bekleyen hasta bulunmamaktadır.</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

