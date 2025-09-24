<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Bekleme Odası') }}
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('waiting-room.appointments') }}" class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Randevu Bekleyen Hastalar</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Kliniğe check-in yapmış randevulu hastaları görüntüleyin ve yönetin.</p>
                </a>

                <a href="{{ route('waiting-room.emergency') }}" class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Acil Hastalar Listesi</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Triyaj seviyesine göre sıralanmış acil ve randevusuz hastaları yönetin.</p>
                </a>

                <a href="{{ route('waiting-room.completed') }}" class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tamamlanan İşlemler</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Bugün işlemleri tamamlanmış hastaların listesini görüntüleyin.</p>
                </a>
            </div>

            @if(auth()->user()?->role === \App\Enums\UserRole::DENTIST)
                <x-card>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Size Atanmış Hastalar</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Acil veya check-in yapılmış hastalarınızı “İşlem Yap” ekranından yönetin ve ziyaret tamamlandığında durumu “Tamamlandı” olarak güncellemeyi unutmayın.</p>
                    </div>
                    @if($doctorEncounters->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">Şu anda bekleyen hasta bulunmuyor.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Hasta</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Tür</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Geliş</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($doctorEncounters as $encounter)
                                        <tr class="bg-white dark:bg-gray-800">
                                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                                {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                                @php
                                                    $typeLabel = match ($encounter->type) {
                                                        \App\Enums\EncounterType::EMERGENCY => 'Acil',
                                                        \App\Enums\EncounterType::WALK_IN => 'Randevusuz',
                                                        default => 'Randevulu',
                                                    };
                                                @endphp
                                                {{ $typeLabel }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                                {{ optional($encounter->arrived_at ?? $encounter->created_at)->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <x-primary-button-link href="{{ route('waiting-room.action', $encounter) }}">
                                                    İşlem Yap
                                                </x-primary-button-link>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>