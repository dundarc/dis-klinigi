<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Acil Hastalar Listesi') }}
            </h2>
            @can('createEmergency', App\Models\Encounter::class)
                <x-primary-button-link href="{{ route('waiting-room.emergency.create') }}">
                    Acil Kaydı Ekle
                </x-primary-button-link>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                @if (session('success'))
                    <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="space-y-4">
                    @forelse($emergencyEncounters as $encounter)
                         <div class="p-4 border rounded-lg dark:border-gray-700 flex justify-between items-center">
                            <div>
                                <p class="font-bold text-lg dark:text-white">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Geliş: {{ $encounter->arrived_at->format('H:i') }} | Hekim: {{ $encounter->dentist->name ?? 'Atanmadı' }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <x-triage-badge :level="$encounter->triage_level" />
                                @can('update', $encounter)
                                    {{-- DÜZELTME BURADA: Rota adı 'waiting-room.action' olarak değiştirildi --}}
                                    <x-secondary-button-link href="{{ route('waiting-room.action', $encounter) }}">
                                        İşlem Yap
                                    </x-secondary-button-link>
                                @endcan
                            </div>
                        </div>
                    @empty
                         <p class="text-gray-500 dark:text-gray-400">Bekleyen acil/randevusuz hasta bulunmamaktadır.</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
