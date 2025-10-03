<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Başlık ve Giriş -->
                <div class="text-center mb-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-6 shadow-lg">
                        <span class="text-3xl text-white">📚</span>
                    </div>
                    <h1 class="text-5xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-300 bg-clip-text text-transparent mb-6">
                        Yardım ve Kullanım Kılavuzu
                    </h1>
                    <p class="text-xl text-slate-600 dark:text-slate-400 max-w-4xl mx-auto leading-relaxed">
                        Diş hekimi kliniğinizin yönetim sistemi hakkında kapsamlı bilgi ve kullanım rehberi.
                        Her modül için detaylı açıklamalar ve adım adım talimatlar bulabilirsiniz.
                    </p>
                </div>

                <!-- Arama Çubuğu -->
                <div class="mb-12">
                    <div class="max-w-md mx-auto">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="help-search"
                                class="block w-full pl-10 pr-3 py-3 border border-slate-300 dark:border-slate-600 rounded-lg leading-5 bg-white dark:bg-slate-700 placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 dark:text-slate-100"
                                placeholder="Yardım konularında arama yapın..."
                                aria-label="Yardım arama"
                            >
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 text-center">
                            Modül adı, açıklama veya anahtar kelimelerle arama yapabilirsiniz
                        </p>
                    </div>
                </div>

            <!-- Ana Modüller Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16" id="help-cards">
                <!-- Ana Sayfa -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">🏠</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Ana Sayfa</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Sisteme giriş yaptığınızda karşınıza çıkan kontrol paneli. Günün özeti ve hızlı işlemler.
                        </p>
                        <a href="{{ route('help.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Hasta Yönetimi -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-green-300 dark:hover:border-green-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">👥</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Hasta Yönetimi</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Hasta kayıtları, bilgileri ve KVKK işlemleri. Hasta arama ve düzenleme.
                        </p>
                        <a href="{{ route('help.patients') }}" class="inline-flex items-center px-4 py-2 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Randevu Yönetimi -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-purple-300 dark:hover:border-purple-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/10 dark:to-violet-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">📅</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Randevu Yönetimi</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Takvim sistemi, randevu oluşturma, düzenleme ve takip işlemleri.
                        </p>
                        <a href="{{ route('help.calendar') }}" class="inline-flex items-center px-4 py-2 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Tedavi Planları -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-orange-300 dark:hover:border-orange-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900/10 dark:to-amber-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">🦷</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Tedavi Planları</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Hasta tedavi planları oluşturma, düzenleme ve ilerleme takibi.
                        </p>
                        <a href="{{ route('help.treatment-plans') }}" class="inline-flex items-center px-4 py-2 bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Bekleme Odası -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-red-300 dark:hover:border-red-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/10 dark:to-rose-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">🏥</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Bekleme Odası</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Hasta kabulü, triaj sistemi ve tedavi süreci yönetimi.
                        </p>
                        <a href="{{ route('help.waiting-room') }}" class="inline-flex items-center px-4 py-2 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Muhasebe -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-teal-300 dark:hover:border-teal-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900/10 dark:to-cyan-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">💰</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Muhasebe</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Fatura oluşturma, ödeme takibi ve finansal raporlama.
                        </p>
                        <a href="{{ route('help.accounting') }}" class="inline-flex items-center px-4 py-2 bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300 rounded-lg hover:bg-teal-100 dark:hover:bg-teal-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Stok Yönetimi -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/10 dark:to-blue-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">📦</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Stok Yönetimi</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Malzeme stokları, satın almalar ve maliyet takibi.
                        </p>
                        <a href="{{ route('help.stock') }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Raporlar -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-pink-300 dark:hover:border-pink-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-50 to-rose-50 dark:from-pink-900/10 dark:to-rose-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">📊</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Raporlar</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Finansal, operasyonel ve performans raporları.
                        </p>
                        <a href="{{ route('help.reports') }}" class="inline-flex items-center px-4 py-2 bg-pink-50 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 rounded-lg hover:bg-pink-100 dark:hover:bg-pink-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Bildirimler -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-yellow-300 dark:hover:border-yellow-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-yellow-50 to-amber-50 dark:from-yellow-900/10 dark:to-amber-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">🔔</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Bildirimler</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Sistem ve manuel bildirimler, hatırlatmalar.
                        </p>
                        <a href="{{ route('help.notifications') }}" class="inline-flex items-center px-4 py-2 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- KVKK Yönetimi -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-cyan-300 dark:hover:border-cyan-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-cyan-900/10 dark:to-blue-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">🔒</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">KVKK Yönetimi</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Kişisel veri koruması ve hasta onayları.
                        </p>
                        <a href="{{ route('help.kvkk') }}" class="inline-flex items-center px-4 py-2 bg-cyan-50 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 rounded-lg hover:bg-cyan-100 dark:hover:bg-cyan-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Sistem Yönetimi -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-gray-300 dark:hover:border-gray-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-900/10 dark:to-slate-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">⚙️</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Sistem Yönetimi</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Kullanıcı yönetimi, yedekleme ve sistem ayarları.
                        </p>
                        <a href="{{ route('help.system') }}" class="inline-flex items-center px-4 py-2 bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- AI Asistanı -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-emerald-300 dark:hover:border-emerald-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/10 dark:to-green-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">🤖</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">AI Asistanı</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Yapay zeka destekli tedavi önerileri ve bilgi.
                        </p>
                        <a href="{{ route('help.ai') }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Profil Yönetimi -->
                <div class="help-card group relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 hover:border-violet-300 dark:hover:border-violet-600 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-violet-50 to-purple-50 dark:from-violet-900/10 dark:to-purple-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-gradient-to-r from-violet-500 to-violet-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-2xl">👤</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Profil Yönetimi</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                            Kişisel bilgiler, şifre ve güvenlik ayarları.
                        </p>
                        <a href="{{ route('help.profile') }}" class="inline-flex items-center px-4 py-2 bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 rounded-lg hover:bg-violet-100 dark:hover:bg-violet-900/50 font-medium transition-colors duration-200 group-hover:shadow-md">
                            <span>Detaylı Bilgi</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hızlı Başlangıç Rehberi -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-xl p-8 mb-12">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-6 text-center">🚀 Hızlı Başlangıç</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-2xl">1</span>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-2">Sisteme Giriş</h3>
                        <p class="text-slate-600 dark:text-slate-400">
                            Kullanıcı adınız ve şifrenizle sisteme giriş yapın.
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-2xl">2</span>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-2">İlk Hasta Kaydı</h3>
                        <p class="text-slate-600 dark:text-slate-400">
                            "Hastalar" bölümünden ilk hastanızı kaydedin.
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-2xl">3</span>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-2">Randevu Oluşturma</h3>
                        <p class="text-slate-600 dark:text-slate-400">
                            Takvim üzerinden ilk randevunuzu planlayın.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 border-t border-slate-200 dark:border-slate-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-center md:text-left mb-4 md:mb-0">
                        <p class="text-slate-600 dark:text-slate-400 text-sm">
                            Geliştirici: <span class="font-semibold text-slate-900 dark:text-slate-100">dundarc</span>
                        </p>
                        <a href="mailto:developer@dundarc.com.tr" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                            developer@dundarc.com.tr
                        </a>
                    </div>
                    <div class="text-center md:text-right">
                        <p class="text-slate-900 dark:text-slate-100 font-semibold">
                            KYS - Klinik Yönetim Sistemi
                        </p>
                        <p class="text-slate-600 dark:text-slate-400 text-sm">
                            Versiyon: 1.0 (Stable)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('help-search');
            const helpCards = document.querySelectorAll('.help-card');
            const cardsContainer = document.getElementById('help-cards');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;

                helpCards.forEach(card => {
                    const text = card.textContent.toLowerCase();
                    const isVisible = text.includes(searchTerm);
                    card.style.display = isVisible ? '' : 'none';
                    if (isVisible) visibleCount++;
                });

                // Show/hide "no results" message
                let noResultsMsg = document.getElementById('no-results');
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'no-results';
                    noResultsMsg.className = 'text-center py-12';
                    noResultsMsg.innerHTML = `
                        <div class="text-slate-500 dark:text-slate-400">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-2">Arama sonucu bulunamadı</h3>
                            <p class="text-sm">Farklı anahtar kelimelerle tekrar deneyin.</p>
                        </div>
                    `;
                    cardsContainer.appendChild(noResultsMsg);
                }
                noResultsMsg.style.display = visibleCount === 0 && searchTerm !== '' ? 'block' : 'none';
            });
        });
    </script>
</x-app-layout>