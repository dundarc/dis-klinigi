<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-gray-100 dark:to-gray-400 bg-clip-text text-transparent">
                        Hekim Performans Raporu
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Hekim performans analizi ve karşılaştırması</span>
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
            <div class="bg-gradient-to-r from-white via-green-50/30 to-emerald-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-green-50/50 to-emerald-50/50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Rapor Filtreleri</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Analiz edilecek tarih aralığını ve hekimi seçin</p>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    <x-report-filter-form :action="route('reports.dentist-performance')" :dentists="$dentists" />
                </div>
            </div>

            <!-- Performance Overview Cards -->
            @isset($performanceData)
            @php
                $totalRevenue = $performanceData->sum('total_revenue');
                $totalPatients = $performanceData->sum('total_patients');
                $totalTreatments = $performanceData->sum('total_treatments');
                $avgRevenuePerTreatment = $totalTreatments > 0 ? $totalRevenue / $totalTreatments : 0;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Revenue -->
                <div class="group relative bg-gradient-to-br from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-700/50 rounded-2xl border border-gray-200/50 dark:border-gray-600/50 p-6 hover:shadow-xl hover:shadow-green-500/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-emerald-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-green-600 dark:text-green-400">Toplam Ciro</p>
                                <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ number_format($totalRevenue, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">₺</span>{{ number_format($totalRevenue, 2, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Total Patients -->
                <div class="group relative bg-gradient-to-br from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-700/50 rounded-2xl border border-gray-200/50 dark:border-gray-600/50 p-6 hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Toplam Hasta</p>
                                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($totalPatients, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $performanceData->count() }} hekim tarafından
                        </div>
                    </div>
                </div>

                <!-- Total Treatments -->
                <div class="group relative bg-gradient-to-br from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-700/50 rounded-2xl border border-gray-200/50 dark:border-gray-600/50 p-6 hover:shadow-xl hover:shadow-purple-500/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Toplam İşlem</p>
                                <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ number_format($totalTreatments, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            Ortalama: {{ number_format($avgRevenuePerTreatment, 0, ',', '.') }} ₺/işlem
                        </div>
                    </div>
                </div>

                <!-- Average Performance -->
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
                                <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Ortalama Performans</p>
                                <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ number_format($totalRevenue / max($performanceData->count(), 1), 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">₺</span>/hekim
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Table -->
            <div class="bg-gradient-to-r from-white via-emerald-50/30 to-green-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-emerald-50/50 to-green-50/50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hekim Performans Dökümü</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Detaylı performans karşılaştırması</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    @if($performanceData->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Hekim</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Hasta Sayısı</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">İşlem Sayısı</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Toplam Ciro</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Ortalama/İşlem</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Performans</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach ($performanceData->sortByDesc('total_revenue') as $index => $data)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                                            <span class="text-white font-semibold text-sm">{{ substr($data->dentist_name, 0, 1) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $data->dentist_name }}</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">Hekim</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ number_format($data->total_patients, 0, ',', '.') }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">hasta</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-purple-600 dark:text-purple-400">{{ number_format($data->total_treatments, 0, ',', '.') }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">işlem</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-green-600 dark:text-green-400">₺{{ number_format($data->total_revenue, 0, ',', '.') }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($data->total_revenue, 2, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-orange-600 dark:text-orange-400">₺{{ $data->total_treatments > 0 ? number_format($data->total_revenue / $data->total_treatments, 0, ',', '.') : '0' }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">ortalama</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3">
                                                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full" style="width: {{ $totalRevenue > 0 ? ($data->total_revenue / $totalRevenue) * 100 : 0 }}%"></div>
                                                    </div>
                                                    <span class="text-xs font-medium text-green-600 dark:text-green-400">{{ $totalRevenue > 0 ? round(($data->total_revenue / $totalRevenue) * 100, 1) : 0 }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100">TOPLAM</td>
                                        <td class="px-6 py-4 text-sm font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalPatients, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-purple-600 dark:text-purple-400">{{ number_format($totalTreatments, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-green-600 dark:text-green-400">₺{{ number_format($totalRevenue, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-orange-600 dark:text-orange-400">₺{{ number_format($avgRevenuePerTreatment, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-green-600 dark:text-green-400">100%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Performans Verisi Bulunamadı</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Seçilen tarih aralığında hekim performans verisi bulunmuyor.</p>
                        </div>
                    @endif
                </div>
            </div>
            @endisset

        </div>
    </div>
</x-app-layout>
