<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">E-posta İstatistikleri</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">E-posta gönderim istatistikleri ve raporları</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('system.email.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    E-posta Ayarlarına Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ number_format($stats['total_sent']) }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Gönderilen</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ number_format($stats['total_failed']) }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Başarısız</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ number_format($stats['total_queued']) }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Kuyruğa Alınan</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">%{{ number_format($stats['success_rate'], 1) }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Başarı Oranı</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Sent Info -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Son Gönderim Bilgisi</h3>
                <div class="text-sm text-slate-600 dark:text-slate-400">
                    @if($stats['last_sent_at'])
                        Son e-posta gönderimi: <strong>{{ $stats['last_sent_at']->format('d.m.Y H:i:s') }}</strong>
                    @else
                        Henüz gönderilmiş e-posta bulunmuyor.
                    @endif
                </div>
            </div>

            <!-- Daily Chart Placeholder -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Son 30 Günlük İstatistikler</h3>

                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h4 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-2">Grafik Yakında Gelecek</h4>
                    <p class="text-slate-500 dark:text-slate-400">
                        Bu bölümde son 30 günün günlük gönderim istatistikleri görsel olarak gösterilecektir.
                        Chart.js veya benzeri bir kütüphane ile çizgi grafik eklenebilir.
                    </p>
                </div>

                <!-- Text-based stats as fallback -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($stats['daily_stats'] as $date => $dayStats)
                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                        <h4 class="font-medium text-slate-900 dark:text-slate-100 mb-2">{{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</h4>
                        <div class="space-y-1 text-sm">
                            @php
                                $sent = $dayStats->where('status', 'sent')->sum('count') ?? 0;
                                $failed = $dayStats->where('status', 'failed')->sum('count') ?? 0;
                                $queued = $dayStats->where('status', 'queued')->sum('count') ?? 0;
                            @endphp
                            <div class="flex justify-between">
                                <span class="text-green-600 dark:text-green-400">Gönderilen:</span>
                                <span class="font-medium">{{ $sent }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-600 dark:text-red-400">Başarısız:</span>
                                <span class="font-medium">{{ $failed }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-yellow-600 dark:text-yellow-400">Kuyruğa Alınan:</span>
                                <span class="font-medium">{{ $queued }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>