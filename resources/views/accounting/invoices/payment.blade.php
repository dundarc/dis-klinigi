<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Ödeme Yönetimi</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $invoice->invoice_no }} - {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('accounting.invoices.show', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Faturayı Görüntüle
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

            <!-- Invoice Summary -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Toplam Tutar</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">Ödenen Tutar</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($invoice->payments->sum('amount'), 2, ',', '.') }} TL</p>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                        <p class="text-sm font-medium text-red-600 dark:text-red-400">Kalan Bakiye</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400" x-data="{ remaining: {{ $invoice->grand_total - $invoice->payments->sum('amount') }} }" x-text="remaining.toFixed(2).replace('.', ',') + ' TL'"></p>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Durum</p>
                        <x-status-badge :status="$invoice->status" />
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Ödeme Hareketleri</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Tarih</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Tutar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Yöntem</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Açıklama</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($invoice->payments as $payment)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">{{ $payment->paid_at->format('d.m.Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-green-600 dark:text-green-400">{{ number_format($payment->amount, 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">
                                        {{ match($payment->method) {
                                            'cash' => 'Nakit',
                                            'bank_transfer' => 'Havale/EFT',
                                            'credit_card' => 'Kredi Kartı',
                                            'check' => 'Çek',
                                            default => ucfirst($payment->method)
                                        } }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $payment->notes ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <form action="{{ route('accounting.invoices.remove-payment', [$invoice, $payment]) }}" method="POST" class="inline" onsubmit="return confirm('Bu ödemeyi iptal etmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                İptal Et
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                        Henüz ödeme yapılmamış.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Payment Form -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-6">Yeni Ödeme Ekle</h3>
                <form action="{{ route('accounting.invoices.store-payment', $invoice) }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Tutarı (TL)</label>
                            <input type="number" step="0.01" min="0.01" :max="{{ $invoice->grand_total - $invoice->payments->sum('amount') }}" id="amount" name="amount" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Maksimum: {{ number_format($invoice->grand_total - $invoice->payments->sum('amount'), 2, ',', '.') }} TL</p>
                        </div>

                        <div>
                            <label for="method" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Yöntemi</label>
                            <select id="method" name="method" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="cash">Nakit</option>
                                <option value="bank_transfer">Havale/EFT</option>
                                <option value="credit_card">Kredi Kartı</option>
                                <option value="check">Çek</option>
                            </select>
                        </div>

                        <div>
                            <label for="paid_at" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Tarihi</label>
                            <input type="datetime-local" id="paid_at" name="paid_at" :value="new Date().toISOString().slice(0, 16)" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Açıklama (İsteğe bağlı)</label>
                            <input type="text" id="notes" name="notes" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ödemeyi Kaydet
                        </button>
                    </div>
                </form>
            </div>

            <!-- Insurance Coverage -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-6">Sigorta Karşılama Tutarı</h3>
                <form action="{{ route('accounting.invoices.update-insurance', $invoice) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="insurance_coverage_amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Sigorta Karşılama Tutarı (TL)</label>
                        <input type="number" step="0.01" min="0" :max="{{ $invoice->grand_total }}" id="insurance_coverage_amount" name="insurance_coverage_amount" value="{{ $invoice->insurance_coverage_amount ?? 0 }}" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Maksimum: {{ number_format($invoice->grand_total, 2, ',', '.') }} TL</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Güncelle
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>