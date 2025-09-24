<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Bekleme Odası
            </h2>
            <div class="flex items-center space-x-2">
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
            {{-- Üst kutucuklar --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('waiting-room.appointments') }}"
                   class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:shadow-md transition">
                    <h5 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Randevu Bekleyen Hastalar</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Check-in yapılmış randevulu hastaları görüntüleyin.</p>
                </a>

                <a href="{{ route('waiting-room.emergency') }}"
                   class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:shadow-md transition">
                    <h5 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Acil Hastalar Listesi</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Triyaj seviyesine göre acil hastaları yönetin.</p>
                </a>

                <a href="{{ route('waiting-room.completed') }}"
                   class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:shadow-md transition">
                    <h5 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Tamamlanan İşlemler</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Bugün işlemleri biten hastaların listesi.</p>
                </a>
            </div>

            {{-- 2x2 Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Sütun 1 --}}
                <div class="space-y-6">
                    {{-- Check-in Bekleyen Hastalar --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4 border-l-4 border-blue-500 pl-3">
                            <div class="flex items-center gap-2">
                                {{-- User Plus --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4.354a4 4 0 100 7.292m6 9.354V18a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8-10h.01M16 6h6m-3-3v6"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Check-in Bekleyen Hastalar</h3>
                            </div>
                            <a href="{{ route('appointments.today') }}" class="btn-secondary">Tümünü Gör</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($pendingAppointments as $appointment)
                                <div class="card-item">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            Randevu: {{ $appointment->start_at?->format('H:i') ?? '--' }} |
                                            Hekim: {{ $appointment->dentist->name ?? '--' }}
                                        </p>
                                    </div>
                                    @can('accessReceptionistFeatures', \App\Models\Appointment::class)
                                        <form method="POST" action="{{ route('appointments.checkin', $appointment) }}">
                                            @csrf
                                            <button type="submit" class="btn-primary">İşlem Yap</button>
                                        </form>
                                    @else
                                        <a href="{{ route('appointments.today') }}" class="btn-secondary">Detay</a>
                                    @endcan
                                </div>
                            @empty
                                <p class="empty-text">Bugün check-in bekleyen randevu yok.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Check-in Yapmış Hastalar --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4 border-l-4 border-indigo-500 pl-3">
                            <div class="flex items-center gap-2">
                                {{-- User Check --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 13l4 4L19 7M12 4a4 4 0 100 8 4 4 0 000-8z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Check-in Yapmış Hastalar</h3>
                            </div>
                            <a href="{{ route('waiting-room.appointments') }}" class="btn-secondary">Tümünü Gör</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($checkedInEncounters as $encounter)
                                <div class="card-item">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            Giriş: {{ $encounter->arrived_at?->format('H:i') ?? '--' }} |
                                            Hekim: {{ $encounter->dentist->name ?? '--' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('waiting-room.action', $encounter) }}" class="btn-primary">İşlem Yap</a>
                                </div>
                            @empty
                                <p class="empty-text">Şu anda check-in yapılmış hasta yok.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Sütun 2 --}}
                <div class="space-y-6">
                    {{-- Acilde Sıra Bekleyenler --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4 border-l-4 border-red-500 pl-3">
                            <div class="flex items-center gap-2">
                                {{-- Alert --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Acilde Sıra Bekleyenler</h3>
                            </div>
                            <a href="{{ route('waiting-room.emergency.create') }}" class="btn-primary">Acil Kaydı Ekle</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($emergencyEncounters as $encounter)
                                <div class="card-item">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            Triyaj: {{ ucfirst($encounter->triage_level?->value ?? '--') }} |
                                            Giriş: {{ $encounter->arrived_at?->format('H:i') ?? '--' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('waiting-room.action', $encounter) }}" class="btn-primary">İşlem Yap</a>
                                </div>
                            @empty
                                <p class="empty-text">Acilde bekleyen hasta yok.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Bugün Tamamlanan İşlemler --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4 border-l-4 border-green-500 pl-3">
                            <div class="flex items-center gap-2">
                                {{-- Check Circle --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Bugün Tamamlanan İşlemler</h3>
                            </div>
                            <a href="{{ route('waiting-room.completed') }}" class="btn-secondary">Tümünü Gör</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($completedEncounters as $encounter)
                                <div class="card-item">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            Bitiş: {{ $encounter->ended_at?->format('H:i') ?? '--' }} |
                                            Hekim: {{ $encounter->dentist->name ?? '--' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('waiting-room.action', $encounter) }}" class="btn-secondary">Ziyaret Düzenle</a>
                                </div>
                            @empty
                                <p class="empty-text">Bugün tamamlanan işlem yok.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Reusable classes --}}
<style>
    .btn-primary {
        @apply inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest
               text-white
               bg-indigo-600 dark:bg-indigo-500
               hover:bg-indigo-700 dark:hover:bg-indigo-400
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
               transition;
    }

    .btn-secondary {
        @apply inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest
               text-gray-700 dark:text-gray-100
               bg-gray-100 dark:bg-gray-800
               border border-gray-300 dark:border-gray-600
               hover:bg-gray-200 dark:hover:bg-gray-700
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400
               transition;
    }

    .card-item {
        @apply flex flex-col md:flex-row md:items-center md:justify-between gap-3 p-4 rounded-lg
               border border-gray-200 dark:border-gray-600
               bg-gray-50 dark:bg-gray-800;
    }

    .empty-text {
        @apply text-sm text-gray-500 dark:text-gray-400 text-center py-4;
    }
</style>