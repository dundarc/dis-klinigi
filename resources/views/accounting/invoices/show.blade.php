<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Fatura Görüntüleme</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $invoice->invoice_no }} - {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('accounting.invoices.action', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
                <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF İndir
                </a>
                <a href="{{ route('accounting.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Invoice Header -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Fatura No</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->invoice_no }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Hasta</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Fatura Tarihi</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->issue_date?->format('d.m.Y') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Durum</p>
                        @if($invoice->status->value === 'paid')
                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">Ödenmiş</span>
                        @elseif($invoice->status->value === 'pending')
                            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">Bekliyor</span>
                        @elseif($invoice->status->value === 'overdue')
                            <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-200">Vadesi Geçmiş</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-1 text-xs font-medium text-slate-800 dark:text-slate-200">{{ ucfirst(str_replace('_', ' ', $invoice->status->value)) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Fatura Kalemleri</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Kalem</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Miktar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Birim Fiyat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">KDV</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Ara Toplam</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($invoice->items as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">{{ $item->description }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100 text-center">{{ $item->qty }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100 text-right">{{ number_format($item->unit_price, 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100 text-center">{{ $item->vat }}%</td>
                                    <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-slate-100 text-right">{{ number_format($item->line_total, 2, ',', '.') }} TL</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                        Fatura kalemi bulunmuyor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Totals Section -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-slate-600 dark:text-slate-400">Ara Toplam</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ number_format($invoice->subtotal, 2, ',', '.') }} TL</p>
                        </div>
                        <div>
                            <p class="text-slate-600 dark:text-slate-400">KDV Toplamı</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ number_format($invoice->vat_total, 2, ',', '.') }} TL</p>
                        </div>
                        <div>
                            <p class="text-slate-600 dark:text-slate-400">Genel Toplam</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($invoice->payments->count() > 0)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Ödeme Geçmişi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Tarih</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Tutar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Yöntem</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($invoice->payments as $payment)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">{{ $payment->paid_at?->format('d.m.Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-slate-100">{{ number_format($payment->amount, 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">{{ ucfirst(str_replace('_', ' ', $payment->method ?? 'belirtilmemiş')) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Invoice Notes -->
            @if($invoice->notes)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Notlar</h3>
                <p class="text-slate-700 dark:text-slate-300">{{ $invoice->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>