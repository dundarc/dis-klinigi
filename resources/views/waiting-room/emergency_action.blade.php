<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Acil Kaydı İşlemi: {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sol Taraf: Bilgi Kartı -->
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ziyaret Bilgileri</h3>
                    <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <p><strong>Hasta:</strong> {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
                        <p><strong>Atanan Hekim:</strong> {{ $encounter->dentist->name }}</p>
                        <p><strong>Geliş Saati:</strong> {{ $encounter->arrived_at->format('d.m.Y H:i') }}</p>
                        <p><strong>Mevcut Durum:</strong> <span class="font-semibold">{{ $encounter->status->value }}</span></p>
                        <div class="flex items-center">
                            <strong class="mr-2">Triyaj:</strong> <x-triage-badge :level="$encounter->triage_level" />
                        </div>
                    </div>
                </x-card>

                <!-- Sağ Taraf: İşlem Formu -->
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">İşlem Yap</h3>
                    <form method="POST" action="{{ route('waiting-room.emergency.update', $encounter) }}" class="mt-4">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="status" value="Yeni Durum Ayarla" />
                                <x-select-input id="status" name="status" class="mt-1 block w-full" required>
                                    @foreach($statuses as $status)
                                        {{-- Sadece belirli durumları seçebilsin --}}
                                        @if(in_array($status, [\App\Enums\EncounterStatus::IN_SERVICE, \App\Enums\EncounterStatus::DONE, \App\Enums\EncounterStatus::CANCELLED]))
                                            <option value="{{ $status->value }}" @selected($encounter->status == $status)>{{ ucfirst($status->value) }}</option>
                                        @endif
                                    @endforeach
                                </x-select-input>
                            </div>
                             <div>
                                <x-input-label for="notes" value="İşlem Notu (Opsiyonel)" />
                                <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ $encounter->notes }}</textarea>
                            </div>
                            <div class="flex items-center justify-end">
                                <a href="{{ route('waiting-room.emergency') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">İptal</a>
                                <x-primary-button class="ms-4">
                                    Durumu Güncelle
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>