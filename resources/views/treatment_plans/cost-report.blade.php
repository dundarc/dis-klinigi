<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Maliyet Karşılaştırma Raporu</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $treatmentPlan->patient->first_name }} {{ $treatmentPlan->patient->last_name }} - Plan #{{ $treatmentPlan->id }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('treatment-plans.show', $treatmentPlan) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Planı Görüntüle
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Tahmini Toplam</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ number_format($costSummary['total_estimated'], 2, ',', '.') }} TL</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Faturalanan</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ number_format($costSummary['total_actual'], 2, ',', '.') }} TL</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Ödenen</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ number_format($costSummary['total_paid'], 2, ',', '.') }} TL</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Kalan</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ number_format($costSummary['remaining'], 2, ',', '.') }} TL</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Tamamlanma</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $costSummary['completion_percentage'] }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detailed Breakdown --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Detaylı Karşılaştırma</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                            <thead class="bg-slate-50 dark:bg-slate-800">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tedavi</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Diş No</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Durum</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tahmini Ücret</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Faturalanan</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ödenen</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Fark</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Fark %</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($itemsBreakdown as $breakdown)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900 dark:text-slate-100">{{ $breakdown['item']->treatment->name ?? 'Tedavi Silinmiş' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($breakdown['item']->tooth_number)
                                                <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-1 text-xs font-medium text-slate-800 dark:text-slate-200">{{ $breakdown['item']->tooth_number }}</span>
                                            @else
                                                <span class="text-slate-500 dark:text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($breakdown['item']->status)
                                                @if($breakdown['item']->status->value === 'planned')
                                                    <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-900/30 px-2.5 py-1 text-xs font-medium text-gray-800 dark:text-gray-200">Planlandı</span>
                                                @elseif($breakdown['item']->status->value === 'in_progress')
                                                    <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">Devam Ediyor</span>
                                                @elseif($breakdown['item']->status->value === 'done')
                                                    <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">Tamamlandı</span>
                                                @elseif($breakdown['item']->status->value === 'cancelled')
                                                    <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-200">İptal Edildi</span>
                                                @elseif($breakdown['item']->status->value === 'no_show')
                                                    <span class="inline-flex items-center rounded-full bg-orange-100 dark:bg-orange-900/30 px-2.5 py-1 text-xs font-medium text-orange-800 dark:text-orange-200">Gelmedi</span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/30 px-2.5 py-1 text-xs font-medium text-purple-800 dark:text-purple-200">Faturalandı</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-900 dark:text-slate-100 font-medium">
                                            {{ number_format($breakdown['estimated'], 2, ',', '.') }} TL
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-900 dark:text-slate-100 font-medium">
                                            {{ number_format($breakdown['invoiced'], 2, ',', '.') }} TL
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-900 dark:text-slate-100 font-medium">
                                            {{ number_format($breakdown['paid'], 2, ',', '.') }} TL
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium
                                            @if($breakdown['variance'] > 0) text-green-600 dark:text-green-400
                                            @elseif($breakdown['variance'] < 0) text-red-600 dark:text-red-400
                                            @else text-slate-600 dark:text-slate-400 @endif">
                                            {{ $breakdown['variance'] > 0 ? '+' : '' }}{{ number_format($breakdown['variance'], 2, ',', '.') }} TL
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium
                                            @if($breakdown['variance_percent'] > 0) text-green-600 dark:text-green-400
                                            @elseif($breakdown['variance_percent'] < 0) text-red-600 dark:text-red-400
                                            @else text-slate-600 dark:text-slate-400 @endif">
                                            {{ $breakdown['variance_percent'] > 0 ? '+' : '' }}{{ number_format($breakdown['variance_percent'], 2) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>