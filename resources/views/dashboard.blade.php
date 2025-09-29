<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ana Sayfa') }}
            </h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ now()->locale('tr')->isoFormat('dddd, D MMMM YYYY') }}
                </span>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ now()->format('H:i') }}
                </span>
                <a href="{{ route('notifications.index') }}" class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03-8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.445L4 21l7.868-2.317z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">

            <!-- Welcome Section - Compact -->
            <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-xl shadow-lg border border-gray-200/50 dark:border-gray-600/50 overflow-hidden mb-6">
                <div class="px-6 py-8">
                    <div class="text-center">
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent mb-2">
                            Hoşgeldin, {{ $user->name }}!
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-300">
                            Diş Hekimliği Yönetim Sistemine hoşgeldin.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Prominent Search Section - Compact -->
            <div class="bg-gradient-to-r from-white via-gray-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 rounded-xl shadow-lg border border-gray-200/50 dark:border-gray-600/50 overflow-hidden mb-6">
                <div class="px-6 py-8">
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hızlı Arama</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Hasta veya randevu bilgilerini arayarak hızlıca erişebilirsin
                        </p>
                    </div>

                    <!-- Search Component -->
                    <div x-data="searchComponent()" class="max-w-2xl mx-auto relative">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input
                                x-model="query"
                                @input="search()"
                                type="text"
                                placeholder="Hasta adı, TC kimlik, telefon veya randevu tarihi ara..."
                                class="w-full pl-16 pr-6 py-6 text-xl border-2 border-gray-300 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-4 focus:ring-blue-500 focus:border-transparent shadow-lg transition-all duration-300"
                            >
                        </div>

                        <!-- Search Results -->
                        <div x-show="results.length > 0" x-transition
                             class="fixed z-50 w-full max-w-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-2xl shadow-2xl max-h-96 overflow-y-auto"
                             style="top: calc(50% + 100px); left: 50%; transform: translateX(-50%);">
                            <div class="p-4">
                                <template x-for="result in results" :key="result.url">
                                    <a :href="result.url" class="flex items-center space-x-4 p-4 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl transition-colors duration-200 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x-show="result.type === 'patient'" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x-show="result.type === 'appointment'" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white" x-text="result.label"></p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400" x-text="result.subtitle || ''"></p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Action Buttons - Compact -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                        <a href="{{ route('patients.create') }}" class="group bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="text-center">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold mb-1">Yeni Hasta</h3>
                                <p class="text-xs text-green-100">Hasta kaydı oluştur</p>
                            </div>
                        </a>

                        <a href="{{ route('waiting-room.index') }}" class="group bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="text-center">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold mb-1">Bekleme Odası</h3>
                                <p class="text-xs text-blue-100">Bekleyen hastalar</p>
                            </div>
                        </a>

                        <a href="{{ route('calendar') }}" class="group bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="text-center">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold mb-1">Takvim</h3>
                                <p class="text-xs text-purple-100">Randevular</p>
                            </div>
                        </a>

                        <a href="{{ route('patients.index') }}" class="group bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="text-center">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold mb-1">Hasta Listesi</h3>
                                <p class="text-xs text-orange-100">Tüm hastalar</p>
                            </div>
                        </a>
                    </div>

                </div>
            </div>

            <!-- Quick Stats & Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">

                <!-- Today's Summary -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-800 dark:to-gray-700 rounded-2xl p-6 shadow-lg border border-blue-200/50 dark:border-gray-600/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Bugünkü Özet
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Randevular</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $appointmentStats['total'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Bekleme Odası</span>
                            <span class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ $emergencyCount ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Bugünkü Check-in</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $appointmentStats['checked_in'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-100 dark:from-gray-800 dark:to-gray-700 rounded-2xl p-6 shadow-lg border border-green-200/50 dark:border-gray-600/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Hızlı İşlemler
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('patients.create') }}" class="flex items-center space-x-2 p-3 bg-white/60 dark:bg-gray-700/60 hover:bg-white dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Yeni Hasta Kaydı</span>
                        </a>
                        <a href="{{ route('calendar') }}" class="flex items-center space-x-2 p-3 bg-white/60 dark:bg-gray-700/60 hover:bg-white dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Randevu Takvimi</span>
                        </a>
                        <a href="{{ route('waiting-room.index') }}" class="flex items-center space-x-2 p-3 bg-white/60 dark:bg-gray-700/60 hover:bg-white dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Bekleme Odası</span>
                        </a>
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-100 dark:from-gray-800 dark:to-gray-700 rounded-2xl p-6 shadow-lg border border-purple-200/50 dark:border-gray-600/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Sistem Durumu
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Son Giriş</span>
                            <span class="text-sm font-medium text-green-600 dark:text-green-400">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Yeni' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Rol</span>
                            <span class="text-sm font-medium text-purple-600 dark:text-purple-400">{{ $user->role?->name ?? 'Kullanıcı' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Durum</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Dashboard Content - Optimized for Desktop -->
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 mt-8">

                <!-- Today's Appointments - Compact List -->
                <div class="xl:col-span-2 bg-gradient-to-br from-white to-blue-50/50 dark:from-gray-800 dark:to-blue-900/20 rounded-xl shadow-lg border border-blue-200/50 dark:border-blue-700/50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-blue-200/50 dark:border-blue-700/50 bg-gradient-to-r from-blue-500 to-indigo-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-white">Bugünkü Randevular</h3>
                                    <p class="text-xs text-blue-100">{{ now()->locale('tr')->isoFormat('D MMM') }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                                {{ $todaysAppointments->count() }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4">
                        @if($todaysAppointments->isNotEmpty())
                            <div class="space-y-2 max-h-80 overflow-y-auto">
                                @foreach($todaysAppointments->take(8) as $appointment)
                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-900 rounded-lg border border-blue-100 dark:border-blue-800 hover:shadow-md transition-shadow duration-200">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold text-xs">
                                                    {{ substr($appointment['patient_name'], 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment['patient_name'] }}</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $appointment['doctor_name'] }} • {{ $appointment['time'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $appointment['status_color'] }}">
                                                {{ $appointment['status'] }}
                                            </span>
                                            <a href="{{ route('calendar.show', $appointment['id']) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 text-xs font-medium">
                                                →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                @if($todaysAppointments->count() > 8)
                                    <div class="text-center pt-2">
                                        <a href="{{ route('calendar') }}" class="text-xs text-blue-600 hover:text-blue-900 dark:text-blue-400 font-medium">
                                            +{{ $todaysAppointments->count() - 8 }} daha fazla randevu →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Bugün randevu yok</h3>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Activities - Compact -->
                <div class="xl:col-span-1 bg-gradient-to-br from-white to-green-50/50 dark:from-gray-800 dark:to-green-900/20 rounded-xl shadow-lg border border-green-200/50 dark:border-green-700/50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-green-200/50 dark:border-green-700/50 bg-gradient-to-r from-green-500 to-emerald-600">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-white">Son Aktiviteler</h3>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        @if($recentActivities->isNotEmpty())
                            <div class="space-y-3 max-h-80 overflow-y-auto">
                                @foreach($recentActivities->take(6) as $activity)
                                    <a href="{{ $activity['url'] }}" class="flex items-start space-x-2 p-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors duration-200">
                                        <span class="text-lg flex-shrink-0">{{ $activity['icon'] }}</span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-900 dark:text-white leading-tight">{{ $activity['title'] }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $activity['description'] }}</p>
                                            <p class="text-xs {{ $activity['color'] }} mt-0.5">{{ $activity['time'] }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="mx-auto h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-xs font-medium text-gray-900 dark:text-white">Aktivite yok</h3>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="xl:col-span-1 bg-gradient-to-br from-white to-purple-50/50 dark:from-gray-800 dark:to-purple-900/20 rounded-xl shadow-lg border border-purple-200/50 dark:border-purple-700/50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-purple-200/50 dark:border-purple-700/50 bg-gradient-to-r from-purple-500 to-pink-600">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-white">Hızlı İstatistikler</h3>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 space-y-4">
                        <div class="text-center">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Bekleme Odası</p>
                            <p class="text-xl font-bold text-orange-600 dark:text-orange-400">{{ $emergencyCount }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Bugünkü Check-in</p>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ $appointmentStats['checked_in'] ?? 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Aktif Planlar</p>
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $activeTreatmentPlans }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial & Statistics Cards - Compact -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <!-- Monthly Revenue -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-100 dark:from-gray-800 dark:to-emerald-900/20 rounded-lg p-4 shadow-md border border-green-200/50 dark:border-green-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-green-600 dark:text-green-400">Bu Ay Gelir</p>
                            <p class="text-lg font-bold text-green-900 dark:text-green-100">{{ number_format($monthlyRevenue, 0, ',', '.') }}₺</p>
                        </div>
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center ml-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Monthly Profit -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-800 dark:to-indigo-900/20 rounded-lg p-4 shadow-md border border-blue-200/50 dark:border-blue-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-blue-600 dark:text-blue-400">Bu Ay Kar</p>
                            <p class="text-lg font-bold {{ $monthlyProfit >= 0 ? 'text-blue-900 dark:text-blue-100' : 'text-red-900 dark:text-red-100' }}">{{ number_format($monthlyProfit, 0, ',', '.') }}₺</p>
                        </div>
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center ml-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Patients -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-100 dark:from-gray-800 dark:to-pink-900/20 rounded-lg p-4 shadow-md border border-purple-200/50 dark:border-purple-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-purple-600 dark:text-purple-400">Toplam Hasta</p>
                            <p class="text-lg font-bold text-purple-900 dark:text-purple-100">{{ number_format($totalPatients, 0, ',', '.') }}</p>
                            <p class="text-xs text-purple-600 dark:text-purple-400">+{{ $newPatientsThisMonth }} bu ay</p>
                        </div>
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center ml-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Treatment Statistics -->
                <div class="bg-gradient-to-br from-orange-50 to-red-100 dark:from-gray-800 dark:to-red-900/20 rounded-lg p-4 shadow-md border border-orange-200/50 dark:border-orange-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-orange-600 dark:text-orange-400">Tedavi Tamamlandı</p>
                            <p class="text-lg font-bold text-orange-900 dark:text-orange-100">{{ number_format($completedTreatments, 0, ',', '.') }}</p>
                            <p class="text-xs text-orange-600 dark:text-orange-400">{{ $activeTreatmentPlans }} aktif plan</p>
                        </div>
                        <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center ml-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Status - Compact (if applicable) -->
            @if($showStockCard && ($lowStockItems > 0 || $totalStockValue > 0))
                <div class="mt-6 bg-gradient-to-br from-yellow-50 to-orange-100 dark:from-gray-800 dark:to-orange-900/20 rounded-lg shadow-md border border-yellow-200/50 dark:border-yellow-700/50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-yellow-200/50 dark:border-yellow-700/50 bg-gradient-to-r from-yellow-500 to-orange-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-white">Stok Durumu</h3>
                                </div>
                            </div>
                            @if($lowStockItems > 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 text-white">
                                    ⚠️ {{ $lowStockItems }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <p class="text-xs text-gray-600 dark:text-gray-400">Kritik Stok</p>
                                <p class="text-lg font-bold text-red-600 dark:text-red-400">{{ $lowStockItems }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-600 dark:text-gray-400">Toplam Değer</p>
                                <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ number_format($totalStockValue, 0, ',', '.') }}₺</p>
                            </div>
                        </div>

                        @if($criticalStocks->isNotEmpty())
                            <div class="mt-3 pt-3 border-t border-yellow-200/50 dark:border-yellow-700/50">
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Kritik Öğeler:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($criticalStocks->take(4) as $stock)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200">
                                            {{ $stock->name }} ({{ $stock->quantity }})
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Footer -->
            <div class="text-center text-gray-500 dark:text-gray-400 mt-12">
                <p class="text-sm">Diş Hekimliği Yönetim Sistemi - Ana Sayfa</p>
                <p class="text-xs mt-1">Hızlı ve etkili hasta yönetimi için tasarlandı</p>
            </div>

        </div>
    </div>

    <script>
        function searchComponent() {
            return {
                query: '',
                results: [],
                search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }
                    fetch(`/search?query=${encodeURIComponent(this.query)}`)
                        .then(response => response.json())
                        .then(data => {
                            this.results = data;
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            this.results = [];
                        });
                }
            }
        }
    </script>
</x-app-layout>