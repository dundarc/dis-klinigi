<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Bekleme Odası</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Hasta akışını yönetin ve takip edin</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('appointments.today') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Check-in Yap
                </a>
                <a href="{{ route('waiting-room.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Yeni Randevu
                </a>
                <a href="{{ route('waiting-room.appointments.search') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Randevu Ara
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Navigation Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('waiting-room.appointments') }}" class="block p-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 hover:border-blue-300 dark:hover:border-blue-600">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Randevu Bekleyen Hastalar</h5>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Check-in yapılmış randevulu hastaları görüntüleyin.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('waiting-room.emergency') }}" class="block p-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 hover:border-red-300 dark:hover:border-red-600">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Acil Hastalar Listesi</h5>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Triyaj seviyesine göre acil hastaları yönetin.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('waiting-room.completed') }}" class="block p-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 hover:border-green-300 dark:hover:border-green-600">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Tamamlanan İşlemler</h5>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Bugün işlemleri biten hastaların listesi.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Patient Status Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Check-in Bekleyen Hastalar -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden" style="height: 384px;">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Check-in Bekleyen Hastalar</h3>
                            @if($pendingAppointments->count() > 5)
                                <a href="{{ route('appointments.today') }}" class="ml-auto text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">Tümünü Gör</a>
                            @endif
                        </div>
                        <div class="p-6 overflow-y-auto" style="height: 280px;">
                            <div class="space-y-4">
                                @forelse($pendingAppointments->take(5) as $appointment)
                                    <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                                        <div class="flex-1">
                                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ $appointment->patient->first_name??"--" }} {{ $appointment->patient->last_name??"---" }}</p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                                Randevu: {{ $appointment->start_at?->format('H:i') ?? '--' }} |
                                                Hekim: {{ $appointment->dentist->name ?? '--' }}
                                            </p>
                                        </div>
                                        @can('accessReceptionistFeatures', \App\Models\Appointment::class)
                                            <div class="ml-4 flex gap-2">
                                                <form method="POST" action="{{ route('appointments.checkin', $appointment) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                        Check-in
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('appointments.no-show', $appointment) }}" class="inline" onsubmit="return confirm('Bu hastayı \'Gelmedi\' olarak işaretlemek istediğinizden emin misiniz?')">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                        Gelmedi
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <a href="{{ route('appointments.today') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                Detay
                                            </a>
                                        @endcan
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Bugün check-in bekleyen randevu yok.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Check-in Yapmış Hastalar -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden" style="height: 384px;">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7M12 4a4 4 0 100 8 4 4 0 000-8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Check-in Yapmış Hastalar</h3>
                            @if($checkedInEncounters->count() > 5)
                                <a href="{{ route('waiting-room.appointments') }}" class="ml-auto text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">Tümünü Gör</a>
                            @endif
                        </div>
                        <div class="p-6 overflow-y-auto" style="height: 280px;">
                            <div class="space-y-4">
                                @forelse($checkedInEncounters->take(5) as $encounter)
                                    <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                                        <div class="flex-1">
                                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ $encounter->patient->first_name??"--" }} {{ $encounter->patient->last_name??"--" }}</p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                                Giriş: {{ $encounter->arrived_at?->format('H:i') ?? '--' }} |
                                                Hekim: {{ $encounter->dentist->name ?? '--' }}
                                            </p>
                                        </div>
                                        <a href="{{ route('waiting-room.action', $encounter) }}" class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            İşlem Yap
                                        </a>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Şu anda check-in yapılmış hasta yok.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Acilde Sıra Bekleyenler -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden" style="height: 384px;">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Acilde Sıra Bekleyenler</h3>
                            <div class="ml-auto flex gap-2">
                                @if($emergencyEncounters->count() > 5)
                                    <a href="{{ route('waiting-room.emergency') }}" class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium">Tümünü Gör</a>
                                @endif
                                <a href="{{ route('waiting-room.emergency.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    Acil Kaydı Ekle
                                </a>
                            </div>
                        </div>
                        <div class="p-6 overflow-y-auto" style="height: 280px;">
                            <div class="space-y-4">
                                @forelse($emergencyEncounters->take(5) as $encounter)
                                    <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                                        <div class="flex-1">
                                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                                Triyaj: {{ ucfirst($encounter->triage_level?->value ?? '--') }} |
                                                Giriş: {{ $encounter->arrived_at?->format('H:i') ?? '--' }}
                                            </p>
                                        </div>
                                        <a href="{{ route('waiting-room.action', $encounter) }}" class="ml-4 inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            İşlem Yap
                                        </a>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Acilde bekleyen hasta yok.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Bugün Tamamlanan İşlemler -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden" style="height: 384px;">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Bugün Tamamlanan İşlemler</h3>
                            @if($completedEncounters->count() > 5)
                                <a href="{{ route('waiting-room.completed') }}" class="ml-auto text-sm text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium">Tümünü Gör</a>
                            @endif
                        </div>
                        <div class="p-6 overflow-y-auto" style="height: 280px;">
                            <div class="space-y-4">
                                @forelse($completedEncounters->take(5) as $encounter)
                                    <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                                        <div class="flex-1">
                                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ $encounter->patient->first_name??"--" }} {{ $encounter->patient->last_name??"---" }}</p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                                Bitiş: {{ $encounter->ended_at?->format('H:i') ?? '--' }} |
                                                Hekim: {{ $encounter->dentist->name ?? '--' }}
                                            </p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('waiting-room.show', $encounter) }}" class="inline-flex items-center px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                Görüntüle
                                            </a>
                                            <a href="{{ route('waiting-room.action', $encounter) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                Düzenle
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Bugün tamamlanan işlem yok.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>