<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <a href="{{ route('help') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Yardım Ana Sayfasına Dön
                    </a>
                </div>

                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-500 to-violet-600 rounded-full mb-6 shadow-lg">
                        <span class="text-3xl text-white">📅</span>
                    </div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-300 bg-clip-text text-transparent mb-4">
                        Randevu Yönetimi Detaylı Kılavuzu
                    </h1>
                    <p class="text-xl text-slate-600 dark:text-slate-400 max-w-3xl mx-auto leading-relaxed">
                        Takvim sistemi ile randevu planlamayı, yönetmeyi ve takip etmeyi öğrenin.
                    </p>
                </div>

                <div class="space-y-16">
                    <!-- Giriş -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">ℹ️</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Giriş</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-calendar-intro"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-calendar-intro" class="help-content">
                            <div class="prose prose-slate dark:prose-invert max-w-none">
                                <p class="text-slate-700 dark:text-slate-300 mb-4 text-lg leading-relaxed">
                                    Takvim sistemi, kliniğinizin randevu planlamasını ve zaman yönetimini sağlayan kapsamlı bir araçtır.
                                </p>
                                <p class="text-slate-700 dark:text-slate-300 text-lg leading-relaxed">
                                    Bu bölümde randevu oluşturabilir, düzenleyebilir, iptal edebilir ve takvim görünümünü yönetebilirsiniz.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Takvim Görünümleri -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">👁️</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Takvim Görünümleri</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-calendar-views"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-calendar-views" class="help-content">
                            <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                                Farklı zaman dilimlerinde takvimi görüntüleme.
                            </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="group relative bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 text-center">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                        <span class="text-white text-2xl">📅</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100 mb-3">Gün Görünümü</h3>
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        Seçili günün tüm randevularını saat saat görürsünüz.
                                    </p>
                                </div>
                            </div>

                            <div class="group relative bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 text-center">
                                <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                        <span class="text-white text-2xl">📊</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-green-900 dark:text-green-100 mb-3">Hafta Görünümü</h3>
                                    <p class="text-sm text-green-800 dark:text-green-200">
                                        Bir haftalık randevu planlamasını görürsünüz.
                                    </p>
                                </div>
                            </div>

                            <div class="group relative bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 text-center">
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/10 dark:to-violet-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                        <span class="text-white text-2xl">🗓️</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-purple-900 dark:text-purple-100 mb-3">Ay Görünümü</h3>
                                    <p class="text-sm text-purple-800 dark:text-purple-200">
                                        Tüm ayın randevu yoğunluğunu görürsünüz.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Randevu Oluşturma -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">➕</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Randevu Oluşturma</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-calendar-create"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-calendar-create" class="help-content">
                            <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                                Yeni randevu ekleme adımları.
                            </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">📅</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100">Takvimden Oluşturma</h3>
                                </div>
                                <ol class="text-sm text-blue-800 dark:text-blue-200 space-y-3">
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">1</span>
                                        <span>Takvim sayfasını açın</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">2</span>
                                        <span>İstediğiniz tarih ve saate tıklayın</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">3</span>
                                        <span>"Yeni Randevu" penceresi açılır</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">4</span>
                                        <span>Hasta seçin veya yeni hasta ekleyin</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">5</span>
                                        <span>Doktor ve tedavi türünü belirtin</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">6</span>
                                        <span>Süreyi ayarlayın</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">7</span>
                                        <span>"Kaydet" butonuna tıklayın</span>
                                    </li>
                                </ol>
                            </div>

                            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">👤</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-green-900 dark:text-green-100">Hasta Sayfasından Oluşturma</h3>
                                </div>
                                <ol class="text-sm text-green-800 dark:text-green-200 space-y-3">
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">1</span>
                                        <span>Hasta detay sayfasını açın</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">2</span>
                                        <span>"Yeni Randevu" butonuna tıklayın</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">3</span>
                                        <span>Tarih ve saat seçin</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">4</span>
                                        <span>Doktor belirleyin</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">5</span>
                                        <span>Tedavi notu ekleyin</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">6</span>
                                        <span>"Oluştur" butonuna tıklayın</span>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Randevu Durumları -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">📊</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Randevu Durumları</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-calendar-statuses"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-calendar-statuses" class="help-content">
                            <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                                Randevuların farklı aşamaları ve anlamları.
                            </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="group relative bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center mb-3">
                                        <div class="w-4 h-4 bg-blue-500 rounded-full mr-3"></div>
                                        <h4 class="font-bold text-blue-900 dark:text-blue-100">Planlandı</h4>
                                    </div>
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        Randevu oluşturuldu, hasta henüz gelmedi.
                                    </p>
                                </div>
                            </div>

                            <div class="group relative bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center mb-3">
                                        <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                                        <h4 class="font-bold text-green-900 dark:text-green-100">Onaylandı</h4>
                                    </div>
                                    <p class="text-sm text-green-800 dark:text-green-200">
                                        Hasta randevuyu onayladı.
                                    </p>
                                </div>
                            </div>

                            <div class="group relative bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/10 dark:to-violet-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center mb-3">
                                        <div class="w-4 h-4 bg-purple-500 rounded-full mr-3"></div>
                                        <h4 class="font-bold text-purple-900 dark:text-purple-100">Kabul Edildi</h4>
                                    </div>
                                    <p class="text-sm text-purple-800 dark:text-purple-200">
                                        Hasta kliniğe geldi, bekleme odasında.
                                    </p>
                                </div>
                            </div>

                            <div class="group relative bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/10 dark:to-teal-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center mb-3">
                                        <div class="w-4 h-4 bg-emerald-500 rounded-full mr-3"></div>
                                        <h4 class="font-bold text-emerald-900 dark:text-emerald-100">Tamamlandı</h4>
                                    </div>
                                    <p class="text-sm text-emerald-800 dark:text-emerald-200">
                                        Tedavi başarıyla yapıldı.
                                    </p>
                                </div>
                            </div>

                            <div class="group relative bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/10 dark:to-rose-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center mb-3">
                                        <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                                        <h4 class="font-bold text-red-900 dark:text-red-100">İptal Edildi</h4>
                                    </div>
                                    <p class="text-sm text-red-800 dark:text-red-200">
                                        Randevu iptal edildi.
                                    </p>
                                </div>
                            </div>

                            <div class="group relative bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/20 dark:to-gray-800/20 border border-gray-200 dark:border-gray-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-900/10 dark:to-slate-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center mb-3">
                                        <div class="w-4 h-4 bg-gray-500 rounded-full mr-3"></div>
                                        <h4 class="font-bold text-gray-900 dark:text-gray-100">Gelmedi</h4>
                                    </div>
                                    <p class="text-sm text-gray-800 dark:text-gray-200">
                                        Hasta randevuya gelmedi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </section>
        

                    <!-- Randevu Düzenleme -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">✏️</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Randevu Düzenleme</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-calendar-edit"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-calendar-edit" class="help-content">
                            <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                                Mevcut randevuları değiştirme.
                            </p>

                            <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 border border-amber-200 dark:border-amber-800 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">⚙️</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-amber-900 dark:text-amber-100">Düzenleme İşlemleri</h3>
                                </div>
                                <ul class="text-sm text-amber-800 dark:text-amber-200 space-y-3">
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Tarih/Saat Değiştirme:</strong> Randevuyu farklı zamana taşıyın</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Doktor Değiştirme:</strong> Farklı doktora aktarın</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Tedavi Notu Ekleme:</strong> Ek bilgiler ekleyin</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Durum Güncelleme:</strong> Randevu aşamasını değiştirin</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Hasta Bilgilerini Güncelleme:</strong> İletişim bilgilerini düzenleyin</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Randevu Arama ve Filtreleme -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">🔍</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Arama ve Filtreleme</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-calendar-search"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-calendar-search" class="help-content">
                            <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                                Randevuları hızlı bulma.
                            </p>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900/20 dark:to-cyan-800/20 border border-cyan-200 dark:border-cyan-800 rounded-xl p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-cyan-500 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-white text-sm">🔎</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-cyan-900 dark:text-cyan-100">Arama Seçenekleri</h3>
                                    </div>
                                    <ul class="text-sm text-cyan-800 dark:text-cyan-200 space-y-3">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-cyan-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Hasta Adı:</strong> Hasta adına göre arama</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-cyan-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Doktor Adı:</strong> Doktor adına göre filtre</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-cyan-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Tarih Aralığı:</strong> Belirli tarihlerdeki randevular</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-cyan-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Durum:</strong> Randevu durumuna göre</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-cyan-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Telefon:</strong> Telefon numarasına göre</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/20 dark:to-teal-800/20 border border-teal-200 dark:border-teal-800 rounded-xl p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-teal-500 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-white text-sm">🎛️</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-teal-900 dark:text-teal-100">Filtre Kombinasyonları</h3>
                                    </div>
                                    <ul class="text-sm text-teal-800 dark:text-teal-200 space-y-3">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-teal-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Bugünkü Randevular:</strong> Sadece bugün için</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-teal-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Gelecek Randevular:</strong> Önümüzdeki randevular</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-teal-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Gecikmiş Randevular:</strong> Geçmiş randevular</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-teal-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Acil Randevular:</strong> Öncelikli işlemler</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-teal-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>İptal Edilenler:</strong> İptal geçmişini gör</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End of search and filtering grid -->
                        

                    <!-- Randevu Hatırlatmaları -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-rose-500 to-rose-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">🔔</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Randevu Hatırlatmaları</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-calendar-reminders"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-calendar-reminders" class="help-content">
                            <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                                Otomatik hatırlatma sistemi.
                            </p>

                            <div class="bg-gradient-to-br from-rose-50 to-rose-100 dark:from-rose-900/20 dark:to-rose-800/20 border border-rose-200 dark:border-rose-800 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-rose-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">📢</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-rose-900 dark:text-rose-100">Hatırlatma Türleri</h3>
                                </div>
                                <ul class="text-sm text-rose-800 dark:text-rose-200 space-y-3">
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-rose-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>SMS Hatırlatma:</strong> Randevudan 1 gün önce</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-rose-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>E-posta Hatırlatma:</strong> Randevu detayları ile</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-rose-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Telefon Hatırlatma:</strong> Manuel arama ile</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-rose-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Onay İsteği:</strong> Randevu onayı için</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-rose-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Değişiklik Bildirimi:</strong> Randevu değişikliğinde</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Takvim Ayarları -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-slate-500 to-slate-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">⚙️</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Takvim Ayarları</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-calendar-settings"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-calendar-settings" class="help-content">
                            <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                                Takvim görünümünü kişiselleştirme.
                            </p>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/20 dark:to-slate-800/20 border border-slate-200 dark:border-slate-800 rounded-xl p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-slate-500 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-white text-sm">🎨</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Görünüm Ayarları</h3>
                                    </div>
                                    <ul class="text-sm text-slate-800 dark:text-slate-200 space-y-3">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-slate-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Çalışma Saatleri:</strong> Kliniğin açık olduğu saatler</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-slate-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Tatil Günleri:</strong> Kapalı olunan günler</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-slate-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Randevu Süreleri:</strong> Varsayılan süreler</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-slate-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Renk Kodlaması:</strong> Durumlara göre renkler</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/20 dark:to-gray-800/20 border border-gray-200 dark:border-gray-800 rounded-xl p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-white text-sm">👨‍⚕️</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Doktor Takvimleri</h3>
                                    </div>
                                    <ul class="text-sm text-gray-800 dark:text-gray-200 space-y-3">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-gray-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Çoklu Doktor:</strong> Farklı doktorların takvimleri</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-gray-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Müsaitlik:</strong> Doktorların müsait saatleri</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-gray-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>İzin Günleri:</strong> Doktor izinleri</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-gray-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Yerine Bakma:</strong> Vekalet sistemi</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Raporlar ve İstatistikler -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">📊</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Raporlar ve İstatistikler</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-calendar-reports"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-calendar-reports" class="help-content">
                            <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                                Randevu istatistiklerini görüntüleme.
                            </p>

                            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">📈</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-emerald-900 dark:text-emerald-100">Mevcut Raporlar</h3>
                                </div>
                                <ul class="text-sm text-emerald-800 dark:text-emerald-200 space-y-3">
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Günlük Randevu Raporu:</strong> Günün randevu özeti</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Doktor Performans Raporu:</strong> Doktor bazlı istatistikler</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Hasta Yoğunluk Raporu:</strong> Saatlik dağılım</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>İptal/Gelmedi Raporu:</strong> Randevu başarısı</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Gelir Raporu:</strong> Randevu bazlı gelir</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">💡</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Kullanım İpuçları</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-calendar-tips"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-calendar-tips" class="help-content">

                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-white text-sm">🎯</span>
                                </div>
                                <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100">Takvimi Etkili Kullanma</h3>
                            </div>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-3">
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Düzenli Kontrol:</strong> Takvimi günlük kontrol edin</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Rezervasyon Bloğu:</strong> Acil durumlar için zaman ayırın</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Hasta Onayı:</strong> Önemli randevuları onaylayın</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Hatırlatma Sistemi:</strong> Otomatik hatırlatmaları kullanın</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Yedek Plan:</strong> İptaller için alternatif zamanlar belirleyin</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>İletişim:</strong> Hasta ile sürekli iletişim halinde olun</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sorun Giderme -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">🔧</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Sık Karşılaşılan Sorunlar</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-calendar-troubleshooting"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-calendar-troubleshooting" class="help-content">

                        <div class="space-y-6">
                            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">⚠️</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-red-900 dark:text-red-100">Çakışan Randevular</h3>
                                </div>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Aynı saate birden fazla randevu girildi.
                                </p>
                                <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-3">
                                    <p class="text-sm text-red-700 dark:text-red-300">
                                        <strong>Çözüm:</strong> Takvim görünümünde çakışmaları kontrol edin, sistem otomatik uyarı verir.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border border-orange-200 dark:border-orange-800 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">📱</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-orange-900 dark:text-orange-100">Randevu Hatırlatmaları Gitmiyor</h3>
                                </div>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    SMS veya e-posta hatırlatmaları gönderilmiyor.
                                </p>
                                <div class="bg-orange-100 dark:bg-orange-900/30 rounded-lg p-3">
                                    <p class="text-sm text-orange-700 dark:text-orange-300">
                                        <strong>Çözüm:</strong> Sistem ayarlarından e-posta/SMS ayarlarını kontrol edin.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
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
            try {
                const toggleButtons = document.querySelectorAll('.help-toggle');

                toggleButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        try {
                            const content = this.closest('section').querySelector('.help-content');
                            const icon = this.querySelector('svg');

                            if (!content || !icon) {
                                console.warn('Help toggle: Required elements not found');
                                return;
                            }

                            const isExpanded = this.getAttribute('aria-expanded') === 'true';

                            // Toggle content visibility with smooth transition
                            if (isExpanded) {
                                content.style.display = 'none';
                                this.setAttribute('aria-expanded', 'false');
                                icon.style.transform = 'rotate(180deg)';
                            } else {
                                content.style.display = 'block';
                                this.setAttribute('aria-expanded', 'true');
                                icon.style.transform = 'rotate(0deg)';
                            }
                        } catch (error) {
                            console.error('Error toggling help content:', error);
                        }
                    });
                });
            } catch (error) {
                console.error('Error initializing help toggles:', error);
            }
        });
    </script>
</x-app-layout>