<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Muhasebe') }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('accounting.trash') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:underline">
                    <svg class="w-5 h-5 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Çöp Kutusu
                </a>
                <x-primary-button-link href="{{ route('accounting.new') }}">
                    Yeni Fatura Oluştur
                </x-primary-button-link>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Özet Bilgi Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-green-100 dark:bg-green-900/50 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-green-800 dark:text-green-300">Ödenmiş</h3>
                    <p class="text-3xl font-bold text-green-900 dark:text-green-200 mt-2">{{ $paidInvoices->count() }} Adet</p>
                    <p class="text-md text-green-700 dark:text-green-400">{{ number_format($paidInvoices->sum('grand_total'), 2, ',', '.') }} TL</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/50 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-300">Vadesi Geçmiş</h3>
                    <p class="text-3xl font-bold text-red-900 dark:text-red-200 mt-2">{{ $overdueInvoices->count() }} Adet</p>
                    <p class="text-md text-red-700 dark:text-red-400">{{ number_format($overdueInvoices->sum('grand_total'), 2, ',', '.') }} TL</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/50 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300">Taksitlendirilmiş</h3>
                    <p class="text-3xl font-bold text-yellow-900 dark:text-yellow-200 mt-2">{{ $installmentInvoices->count() }} Adet</p>
                     <p class="text-md text-yellow-700 dark:text-yellow-400">{{ number_format($installmentInvoices->sum('grand_total'), 2, ',', '.') }} TL</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/50 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-300">Vadeli</h3>
                    <p class="text-3xl font-bold text-blue-900 dark:text-blue-200 mt-2">{{ $postponedInvoices->count() }} Adet</p>
                    <p class="text-md text-blue-700 dark:text-blue-400">{{ number_format($postponedInvoices->sum('grand_total'), 2, ',', '.') }} TL</p>
                </div>
            </div>

            <!-- Tüm Faturalar Tablosu -->
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tüm Faturalar</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fatura No / İşlem</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hasta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tarih</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tutar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Durum</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($allInvoices as $invoice)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('accounting.invoices.action', $invoice) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $invoice->invoice_no }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $invoice->patient?->first_name }} {{ $invoice->patient?->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $invoice->issue_date->format('d.m.Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $invoice->status->value }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-gray-500">Sistemde fatura bulunmuyor.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($allInvoices->hasPages())
                    <div class="mt-4">
                        {{ $allInvoices->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>

