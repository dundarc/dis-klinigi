<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-gray-100 dark:to-gray-400 bg-clip-text text-transparent">
                        Yeni Hasta Kazanım Raporu
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span>Klinik büyüme ve hasta kazanım trendleri</span>
                    </p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Raporlar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Modern Filter Form -->
            <div class="bg-gradient-to-r from-white via-emerald-50/30 to-green-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-emerald-50/50 to-green-50/50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="p-3 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Rapor Filtreleri</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Analiz edilecek tarih aralığını ve periyot türünü seçin</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <a href="{{ route('reports.new-patient-acquisition.export.excel', request()->query()) }}"
                                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Excel
                                    </a>
                                    <a href="{{ route('reports.new-patient-acquisition.pdf', request()->query()) }}"
                                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        PDF
                                    </a>
                                </div>
                            </div>
                </div>
                <div class="p-8">
                    <x-report-filter-form :action="route('reports.new-patient-acquisition')" :show-period-selector="true" />
                </div>
            </div>

            <!-- Acquisition Overview Cards -->
            @isset($acquisitionData)
            @php
                $totalPatients = $acquisitionData->sum('count');
                $avgPatientsPerPeriod = $acquisitionData->count() > 0 ? $totalPatients / $acquisitionData->count() : 0;
                $bestPeriod = $acquisitionData->sortByDesc('count')->first();
                $periodCount = $acquisitionData->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total New Patients -->
                <div class="group relative bg-gradient-to-br from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-700/50 rounded-2xl border border-gray-200/50 dark:border-gray-600/50 p-6 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-green-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-r from-emerald-500 to-green-600 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Toplam Yeni Hasta</p>
                                <p class="text-2xl font-bold text-emerald-900 dark:text-emerald-100">{{ number_format($totalPatients, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">{{ $periodCount }}</span> periyot boyunca
                        </div>
                    </div>
                </div>

                <!-- Average per Period -->
                <div class="group relative bg-gradient-to-br from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-700/50 rounded-2xl border border-gray-200/50 dark:border-gray-600/50 p-6 hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Periyot Ortalaması</p>
                                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($avgPatientsPerPeriod, 1, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            Hasta/periyot
                        </div>
                    </div>
                </div>

                <!-- Best Period -->
                <div class="group relative bg-gradient-to-br from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-700/50 rounded-2xl border border-gray-200/50 dark:border-gray-600/50 p-6 hover:shadow-xl hover:shadow-purple-500/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-purple-600 dark:text-purple-400">En İyi Periyot</p>
                                <p class="text-lg font-bold text-purple-900 dark:text-purple-100 truncate">{{ $bestPeriod ? Str::limit($bestPeriod->period_formatted, 10) : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $bestPeriod ? $bestPeriod->count : 0 }} yeni hasta
                        </div>
                    </div>
                </div>

                <!-- Growth Trend -->
                <div class="group relative bg-gradient-to-br from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-700/50 rounded-2xl border border-gray-200/50 dark:border-gray-600/50 p-6 hover:shadow-xl hover:shadow-orange-500/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-yellow-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-r from-orange-500 to-yellow-600 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Büyüme Trendi</p>
                                <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">
                                    @if($acquisitionData->count() >= 2)
                                        @php
                                            $firstHalf = $acquisitionData->take(floor($acquisitionData->count() / 2))->sum('count');
                                            $secondHalf = $acquisitionData->skip(floor($acquisitionData->count() / 2))->sum('count');
                                            $growth = $firstHalf > 0 ? (($secondHalf - $firstHalf) / $firstHalf) * 100 : 0;
                                        @endphp
                                        {{ number_format($growth, 1, ',', '.') }}%
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            @if($acquisitionData->count() >= 2)
                                @if($growth > 0)
                                    <span class="text-green-600 dark:text-green-400">↗️ Yükselen</span>
                                @elseif($growth < 0)
                                    <span class="text-red-600 dark:text-red-400">↘️ Düşen</span>
                                @else
                                    <span class="text-gray-600 dark:text-gray-400">→ Sabit</span>
                                @endif
                            @else
                                Yetersiz veri
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral Source Distribution -->
            @isset($referralSourceData)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Referral Source Table -->
                <div class="bg-gradient-to-r from-white via-blue-50/30 to-indigo-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hasta Kaynağı Dağılımı</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Hastaların nereden geldiği</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        @if($referralSourceData->count() > 0)
                            <div class="space-y-4">
                                @foreach($referralSourceData as $source)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-3 h-3 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $source->referral_source }}</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ number_format($source->count) }}</span>
                                            <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full" style="width: {{ $totalPatients > 0 ? ($source->count / $totalPatients) * 100 : 0 }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 w-10 text-right">{{ $totalPatients > 0 ? round(($source->count / $totalPatients) * 100, 1) : 0 }}%</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Hasta kaynağı verisi bulunmuyor.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Referral Source Pie Chart -->
                <div class="bg-gradient-to-r from-white via-purple-50/30 to-pink-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-purple-50/50 to-pink-50/50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Kaynak Dağılım Grafiği</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Pasta grafik görünümü</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="h-64">
                            <canvas id="referralSourceChart" data-chart-data="{{ json_encode($chartData) }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endisset

            <!-- Age Group Distribution -->
            @isset($ageGroupData)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Age Groups Chart -->
                <div class="bg-gradient-to-r from-white via-indigo-50/30 to-purple-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-indigo-50/50 to-purple-50/50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Yaş Grubu Dağılımı</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Yeni hastaların yaş gruplarına göre dağılımı</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        @if($ageGroupData->count() > 0)
                            <div class="space-y-4">
                                @foreach($ageGroupData as $ageGroup)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-3 h-3 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $ageGroup->age_group }}</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($ageGroup->count) }}</span>
                                            <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full" style="width: {{ $totalPatients > 0 ? ($ageGroup->count / $totalPatients) * 100 : 0 }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 w-10 text-right">{{ $totalPatients > 0 ? round(($ageGroup->count / $totalPatients) * 100, 1) : 0 }}%</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Yaş grubu verisi bulunmuyor.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Age Groups Bar Chart -->
                <div class="bg-gradient-to-r from-white via-orange-50/30 to-red-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-orange-50/50 to-red-50/50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Yaş Grubu Bar Grafiği</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Çubuk grafik görünümü</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="h-64">
                            <canvas id="ageGroupChart" data-chart-data="{{ json_encode($chartData) }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="bg-gradient-to-r from-white via-emerald-50/30 to-green-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-emerald-50/50 to-green-50/50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">İstatistikler</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Genel hasta kazanım metrikleri</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Toplam Yeni Hasta</span>
                        <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($totalPatients) }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Ortalama Yaş</span>
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $avgAge }} yaş</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Yaş Bilgisi Olan</span>
                        <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $totalPatients > 0 ? round(($patientsWithAge / $totalPatients) * 100, 1) : 0 }}%</span>
                    </div>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                        <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                            Seçilen tarih aralığındaki veriler
                        </div>
                    </div>
                </div>
            </div>
            @endisset

            <!-- Acquisition Trend Table -->
            <div class="bg-gradient-to-r from-white via-teal-50/30 to-cyan-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-teal-50/50 to-cyan-50/50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hasta Kazanım Trendi</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Zaman bazında yeni hasta kazanım analizi</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    @if($acquisitionData->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">#</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Periyot</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Yeni Hasta Sayısı</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Trend Göstergesi</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Performans</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach ($acquisitionData->sortBy('period') as $index => $data)
                                        @php
                                            $prevData = $index > 0 ? $acquisitionData->sortBy('period')->values()[$index - 1] : null;
                                            $change = $prevData ? $data->count - $prevData->count : 0;
                                            $changePercent = $prevData && $prevData->count > 0 ? (($data->count - $prevData->count) / $prevData->count) * 100 : 0;
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-8 h-8 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-full flex items-center justify-center">
                                                        <span class="text-white font-semibold text-sm">{{ $index + 1 }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $data->period_formatted }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $data->period }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($data->count, 0, ',', '.') }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">yeni hasta</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($prevData)
                                                    <div class="flex items-center">
                                                        @if($change > 0)
                                                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                            </svg>
                                                            <span class="text-sm font-medium text-green-600 dark:text-green-400">+{{ $change }} ({{ number_format($changePercent, 1) }}%)</span>
                                                        @elseif($change < 0)
                                                            <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                                            </svg>
                                                            <span class="text-sm font-medium text-red-600 dark:text-red-400">{{ $change }} ({{ number_format($changePercent, 1) }}%)</span>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path>
                                                            </svg>
                                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Değişim yok</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">İlk periyot</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3">
                                                        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 h-2 rounded-full" style="width: {{ $totalPatients > 0 ? ($data->count / $totalPatients) * 100 : 0 }}%"></div>
                                                    </div>
                                                    <span class="text-xs font-medium text-teal-600 dark:text-teal-400">{{ $totalPatients > 0 ? round(($data->count / $totalPatients) * 100, 1) : 0 }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20">
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100">TOPLAM</td>
                                        <td class="px-6 py-4 text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $periodCount }} periyot</td>
                                        <td class="px-6 py-4 text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($totalPatients, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-blue-600 dark:text-blue-400">
                                            @if($acquisitionData->count() >= 2)
                                                @php
                                                    $firstHalf = $acquisitionData->take(floor($acquisitionData->count() / 2))->sum('count');
                                                    $secondHalf = $acquisitionData->skip(floor($acquisitionData->count() / 2))->sum('count');
                                                    $overallGrowth = $firstHalf > 0 ? (($secondHalf - $firstHalf) / $firstHalf) * 100 : 0;
                                                @endphp
                                                {{ number_format($overallGrowth, 1, ',', '.') }}%
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-teal-600 dark:text-teal-400">100%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Hasta Kazanım Verisi Bulunamadı</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Seçilen tarih aralığında yeni hasta kazanım verisi bulunmuyor.</p>
                        </div>
                    @endif
                </div>
            </div>
            @endisset

        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Referral Source Pie Chart
    const referralSourceCanvas = document.getElementById('referralSourceChart');
    if (referralSourceCanvas) {
        const chartData = JSON.parse(referralSourceCanvas.dataset.chartData);

        new Chart(referralSourceCanvas, {
            type: 'pie',
            data: {
                labels: chartData.referral_sources,
                datasets: [{
                    data: chartData.referral_counts,
                    backgroundColor: [
                        '#3B82F6', // blue
                        '#8B5CF6', // purple
                        '#06B6D4', // cyan
                        '#10B981', // emerald
                        '#F59E0B', // amber
                        '#EF4444', // red
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Age Group Bar Chart
    const ageGroupCanvas = document.getElementById('ageGroupChart');
    if (ageGroupCanvas) {
        const chartData = JSON.parse(ageGroupCanvas.dataset.chartData);

        new Chart(ageGroupCanvas, {
            type: 'bar',
            data: {
                labels: chartData.age_groups,
                datasets: [{
                    label: 'Hasta Sayısı',
                    data: chartData.age_counts,
                    backgroundColor: [
                        '#3B82F6', // blue
                        '#8B5CF6', // purple
                        '#06B6D4', // cyan
                        '#10B981', // emerald
                        '#6B7280', // gray
                    ],
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Hasta Sayısı: ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
