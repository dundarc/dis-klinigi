<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Günü Tamamlanan İşlemler') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="space-y-4">
                    @forelse($completedEncounters as $encounter)
                         <div class="p-4 border rounded-lg dark:border-gray-700 flex justify-between items-center opacity-75">
                            <div>
                                <p class="font-bold text-lg dark:text-white">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Bitiş Saati: {{ $encounter->ended_at?->format('H:i') ?? 'N/A' }} | 
                                    Hekim: {{ $encounter->dentist->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    Tamamlandı
                                </span>
                                {{-- Gelecekte fatura veya hasta detayına link verilebilir --}}
                                <x-secondary-button-link href="{{ route('patients.show', $encounter->patient) }}">
                                    Hasta Detay
                                </x-secondary-button-link>
                            </div>
                        </div>
                    @empty
                         <p class="text-gray-500 dark:text-gray-400 text-center py-4">Bugün tamamlanmış bir işlem bulunmamaktadır.</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
