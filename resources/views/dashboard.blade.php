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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-3xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden mb-8">
                <div class="px-8 py-12">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent mb-4">
                            Hoşgeldin, {{ $user->name }}!
                        </h1>
                        <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                            Diş Hekimliği Yönetim Sistemine hoşgeldin. Hızlı arama yaparak hastalarını ve randevularını yönetebilirsin.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Prominent Search Section -->
            <div class="bg-gradient-to-r from-white via-gray-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 rounded-3xl shadow-2xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden mb-8">
                <div class="px-8 py-12">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Hızlı Arama</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-300">
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

                    <!-- Quick Action Buttons -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-12">
                        <a href="{{ route('patients.create') }}" class="group bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold mb-1">Yeni Hasta Ekle</h3>
                                <p class="text-sm text-green-100">Yeni hasta kaydı oluştur</p>
                            </div>
                        </a>

                        <a href="{{ route('waiting-room.index') }}" class="group bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold mb-1">Bekleme Odası</h3>
                                <p class="text-sm text-blue-100">Bekleyen hastaları yönet</p>
                            </div>
                        </a>

                        <a href="{{ route('calendar') }}" class="group bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold mb-1">Takvim</h3>
                                <p class="text-sm text-purple-100">Randevuları görüntüle</p>
                            </div>
                        </a>

                        <a href="{{ route('patients.index') }}" class="group bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold mb-1">Hasta Listesi</h3>
                                <p class="text-sm text-orange-100">Tüm hastaları görüntüle</p>
                            </div>
                        </a>
                    </div>

                    <!-- Additional Quick Links -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                        <a href="{{ route('reports.index') }}" class="flex items-center justify-center space-x-2 p-4 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Raporlar</span>
                        </a>

                        <a href="{{ route('accounting.index') }}" class="flex items-center justify-center space-x-2 p-4 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Muhasebe</span>
                        </a>

                        <a href="{{ route('stock.dashboard') }}" class="flex items-center justify-center space-x-2 p-4 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Stok</span>
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