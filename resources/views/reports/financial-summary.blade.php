<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Finansal Özet ve Gelir Raporu
            </h2>
            <a href="{{ route('reports.financial-summary.pdf', request()->query()) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                PDF Olarak İndir
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-report-filter-form :action="route('reports.financial-summary')" />

            <!-- Özet Kartları -->
            @isset($summary)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-summary-card title="Toplam Ciro" value="{{ number_format($summary['total_revenue'], 2) }} TL" />
                <x-summary-card title="Tahsil Edilen Tutar" value="{{ number_format($summary['collected_amount'], 2) }} TL" />
                <x-summary-card title="Sigortadan Beklenen" value="{{ number_format($summary['insurance_pending'], 2) }} TL" />
                <x-summary-card title="Vadeli Alacaklar" value="{{ number_format($summary['postponed_amount'], 2) }} TL" />
            </div>
            @endisset

            <!-- Günlük Gelir Dökümü -->
            @isset($dailyBreakdown)
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Günlük Gelir Dökümü</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tahsil Edilen Tutar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($dailyBreakdown as $entry)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($entry->date)->format('d.m.Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($entry->total, 2) }} TL</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Seçilen aralıkta veri bulunamadı.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
            @endisset
        </div>
    </div>
</x-app-layout>
