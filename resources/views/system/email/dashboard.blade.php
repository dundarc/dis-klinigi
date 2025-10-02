<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">E-posta Yönetimi</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">E-posta sistemi ayarları ve yönetimi</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- İstatistik Kartları --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Şablonlar --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Toplam Şablon</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $totalTemplates }}</p>
                            <p class="text-xs text-green-600 dark:text-green-400">{{ $activeTemplates }} aktif</p>
                        </div>
                    </div>
                </div>

                {{-- Gönderilen E-postalar --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Gönderilen E-posta</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($totalLogs) }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Toplam kayıt</p>
                        </div>
                    </div>
                </div>

                {{-- Bounce Sayısı --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Bounce Kayıtları</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($bounceCount) }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Geri dönen e-posta</p>
                        </div>
                    </div>
                </div>

                {{-- Sistem Durumu --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Sistem Durumu</p>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">Aktif</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">E-posta servisi çalışıyor</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ana İşlem Kartları --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- SMTP Ayarları --}}
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-slate-200 dark:border-slate-700 group hover:border-blue-300 dark:hover:border-blue-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">SMTP Ayarları</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Sunucu konfigürasyonu</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                SMTP sunucusu, DKIM/SPF imzalama ve e-posta gönderim ayarları.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.email.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Yapılandır
                            </a>
                        </div>
                    </div>
                </div>

                {{-- E-posta Şablonları --}}
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-slate-200 dark:border-slate-600 group hover:border-green-300 dark:hover:border-green-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">E-posta Şablonları</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Şablon yönetimi</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                E-posta şablonlarını oluşturun, düzenleyin ve yönetin.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.email.templates.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Yönet
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Gönderim Logları --}}
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-slate-200 dark:border-slate-600 group hover:border-purple-300 dark:hover:border-purple-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Gönderim Logları</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">E-posta geçmişi</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Gönderilen e-postaları görüntüleyin ve takip edin.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.email.logs.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Görüntüle
                            </a>
                        </div>
                    </div>
                </div>

                {{-- İstatistikler --}}
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-slate-200 dark:border-slate-600 group hover:border-orange-300 dark:hover:border-orange-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">İstatistikler</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Performans analizi</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                E-posta gönderim istatistiklerini ve raporlarını inceleyin.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.email.stats.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Görüntüle
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Bounce Yönetimi --}}
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-slate-200 dark:border-slate-600 group hover:border-red-300 dark:hover:border-red-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-red-500 to-red-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Bounce Yönetimi</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Geri dönen e-postalar</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Bounce kayıtlarını inceleyin ve e-posta listelerinizi temizleyin.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.email.bounces.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Yönet
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Hızlı İşlemler --}}
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-slate-200 dark:border-slate-600 group hover:border-indigo-300 dark:hover:border-indigo-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Hızlı İşlemler</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Test ve bakım</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Test e-postası gönderin veya sistem bakım işlemlerini yapın.
                            </p>
                        </div>
                        <div class="mt-4">
                            <button onclick="openTestEmailModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Test E-postası
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Son Aktiviteler --}}
            @if($recentLogs->count() > 0)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Son E-posta Gönderimleri</h3>
                </div>
                <div class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($recentLogs as $log)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-{{ $log->status === 'sent' ? 'green' : ($log->status === 'failed' ? 'red' : 'yellow') }}-100 dark:bg-{{ $log->status === 'sent' ? 'green' : ($log->status === 'failed' ? 'red' : 'yellow') }}-900 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-{{ $log->status === 'sent' ? 'green' : ($log->status === 'failed' ? 'red' : 'yellow') }}-600 dark:text-{{ $log->status === 'sent' ? 'green' : ($log->status === 'failed' ? 'red' : 'yellow') }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $log->subject }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $log->to_email }} • {{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($log->status === 'sent') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($log->status === 'failed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                @switch($log->status)
                                    @case('sent') Gönderildi @break
                                    @case('failed') Başarısız @break
                                    @case('queued') Kuyrukta @break
                                @endswitch
                            </span>
                            <a href="{{ route('system.email.logs.show', $log) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                                Detay
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Test Email Modal --}}
    <div id="testEmailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-slate-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">Test E-postası Gönder</h3>
                <form id="testEmailForm">
                    <div class="mb-4">
                        <label for="test_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">E-posta Adresi</label>
                        <input type="email" id="test_email" name="test_email" required
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                               placeholder="test@example.com">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeTestEmailModal()" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200">
                            İptal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Gönder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openTestEmailModal() {
            document.getElementById('testEmailModal').classList.remove('hidden');
        }

        function closeTestEmailModal() {
            document.getElementById('testEmailModal').classList.add('hidden');
        }

        document.getElementById('testEmailForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('test_email').value;

            fetch('/system/email/test', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ test_email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Test e-postası başarıyla kuyruğa alındı!');
                    closeTestEmailModal();
                } else {
                    alert('Hata: ' + data.message);
                }
            })
            .catch(error => {
                alert('Bir hata oluştu: ' + error.message);
            });
        });

        // Close modal when clicking outside
        document.getElementById('testEmailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTestEmailModal();
            }
        });
    </script>
</x-app-layout>