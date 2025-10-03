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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Side: Invoice Summary and Payment Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Draft Invoice Warning -->
                    @if($invoice->status === \App\Enums\InvoiceStatus::DRAFT)
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-amber-800 dark:text-amber-200 mb-2">Taslak Fatura - Ödeme İşlemleri Kısıtlı</h3>
                                <p class="text-amber-700 dark:text-amber-300 mb-4">
                                    Bu fatura henüz taslak aşamasında olduğu için ödeme işlemleri yapılamaz. Faturayı aktif hale getirmek için durumunu "Ödenmemiş" veya "Vadeli" olarak değiştirmeniz gerekir.
                                </p>
                                <div class="bg-amber-100 dark:bg-amber-900/30 rounded-lg p-4">
                                    <h4 class="font-semibold text-amber-900 dark:text-amber-100 mb-2">Mevcut İşlemler:</h4>
                                    <ul class="text-sm text-amber-800 dark:text-amber-200 space-y-1">
                                        <li>• Fatura bilgilerini düzenleme</li>
                                        <li>• Fatura kalemlerini ekleme/çıkarma</li>
                                        <li>• Fatura durumunu değiştirme</li>
                                        <li>• <strong>Ödeme ekleme/çıkarma (KISITLI)</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

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
                                @if($invoice->status === \App\Enums\InvoiceStatus::DRAFT)
                                <div class="mt-2">
                                    <form action="{{ route('accounting.invoices.update-status', $invoice) }}" method="POST" class="space-y-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="new_status" class="block text-xs font-medium text-blue-700 dark:text-blue-300">Durumu Değiştir:</label>
                                        <select id="new_status" name="status" class="block w-full text-sm rounded-md border-blue-300 dark:border-blue-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <option value="unpaid">Ödenmemiş</option>
                                            <option value="vadeli">Vadeli</option>
                                        </select>
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">
                                            Durumu Güncelle
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment History - For all statuses -->
                    @if(in_array($invoice->status, [\App\Enums\InvoiceStatus::UNPAID, \App\Enums\InvoiceStatus::POSTPONED, \App\Enums\InvoiceStatus::OVERDUE, \App\Enums\InvoiceStatus::INSTALLMENT, \App\Enums\InvoiceStatus::PARTIAL, \App\Enums\InvoiceStatus::PAID]))
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
                                                {{ $payment->method?->label() ?? 'Bilinmiyor' }}
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

                    <!-- Add Payment Form - For payable statuses and paid invoices -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-6">Yeni Ödeme Ekle</h3>
                        @if($invoice->status === \App\Enums\InvoiceStatus::PAID)
                        <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                <strong>Not:</strong> Bu fatura zaten ödenmiş durumda. Yine de ek ödeme ekleyebilirsiniz (örneğin fazla ödeme veya düzeltme için).
                            </p>
                        </div>
                        @endif
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
                                        <option value="insurance">Sigorta</option>
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

                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        Eğer hastanın özel sağlık sigortası tarafından bir ödeme yapılmış ise <strong>Ödeme Yöntemi: Sigorta</strong> olarak seçin ve mutlaka bir açıklama yazın.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($invoice->status === \App\Enums\InvoiceStatus::PAID)
                    <!-- Payment History for Paid Invoices -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Ödeme Hareketleri</h3>
                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">Tamamen Ödenmiş</span>
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
                                                {{ $payment->method?->label() ?? 'Bilinmiyor' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $payment->notes ?? '-' }}</td>
                                            <td class="px-4 py-3 text-right">
                                                <form action="{{ route('accounting.invoices.remove-payment', [$invoice, $payment]) }}" method="POST" class="inline" onsubmit="return confirm('Bu ödemeyi iptal etmek istediğinizden emin misiniz? Bu işlem geri alınamaz ve fatura durumunu değiştirebilir.')">
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
                                                Ödeme bilgisi bulunmuyor.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Add Payment Form for Paid Invoices -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-6">Ek Ödeme Ekle</h3>
                        <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                <strong>Not:</strong> Bu fatura zaten ödenmiş durumda. Yine de ek ödeme ekleyebilirsiniz (örneğin fazla ödeme, düzeltme veya gelecek dönemler için).
                            </p>
                        </div>
                        <form action="{{ route('accounting.invoices.store-payment', $invoice) }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Tutarı (TL)</label>
                                    <input type="number" step="0.01" min="0.01" id="amount" name="amount" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ek ödeme için herhangi bir tutar girebilirsiniz</p>
                                </div>

                                <div>
                                    <label for="method" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Yöntemi</label>
                                    <select id="method" name="method" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="cash">Nakit</option>
                                        <option value="bank_transfer">Havale/EFT</option>
                                        <option value="credit_card">Kredi Kartı</option>
                                        <option value="check">Çek</option>
                                        <option value="insurance">Sigorta</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="paid_at" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Tarihi</label>
                                    <input type="datetime-local" id="paid_at" name="paid_at" :value="new Date().toISOString().slice(0, 16)" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Açıklama (Zorunlu)</label>
                                    <input type="text" id="notes" name="notes" placeholder="Ödeme nedeni (örn: fazla ödeme, düzeltme)" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Ek Ödemeyi Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                    @else
                    <!-- Payment Restricted Message -->
                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                        <div class="text-center">
                            <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Ödeme İşlemleri Kısıtlı</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Bu fatura durumu nedeniyle ödeme işlemleri yapılamaz. Fatura durumunu değiştirmek için yukarıdaki formu kullanın.
                            </p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Side: Usage Tips -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-6">
                        <!-- How to Use This Page -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Bu Sayfa Nasıl Kullanılır?</h3>
                            </div>
                            <div class="space-y-4 text-sm text-blue-800 dark:text-blue-200">
                                <div>
                                    <h4 class="font-semibold mb-2">📊 Üst Kısım - Fatura Özeti</h4>
                                    <p>Faturanın toplam tutarı, ödenen miktar ve kalan bakiye bilgilerini gösterir.</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-2">💰 Ödeme Hareketleri</h4>
                                    <p>Bu faturaya yapılan tüm ödemelerin listesi. İptal etmek istediğiniz ödemeleri buradan kaldırabilirsiniz.</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-2">➕ Yeni Ödeme Ekleme</h4>
                                    <p>Yeni bir ödeme kaydı oluşturmak için bu formu kullanın. Tutar, yöntem ve tarihi belirtin.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods Guide -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-green-900 dark:text-green-100">Ödeme Yöntemleri</h3>
                        </div>
                        <div class="space-y-3 text-sm text-green-800 dark:text-green-200">
                            <div class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <div>
                                    <strong>Nakit:</strong> Hasta tarafından nakit olarak yapılan ödemeler
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <div>
                                    <strong>Havale/EFT:</strong> Banka hesaplarına yapılan transferler
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <div>
                                    <strong>Kredi Kartı:</strong> POS cihazı ile yapılan ödemeler
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <div>
                                    <strong>Çek:</strong> Hasta tarafından verilen çekler
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <div>
                                    <strong>Sigorta:</strong> Özel sağlık sigortası ödemeleri
                                </div>
                            </div>
                        </div>
                    </div>

                        <!-- Important Notes -->
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 rounded-xl p-6 border border-amber-200 dark:border-amber-800">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-amber-900 dark:text-amber-100">Önemli Notlar</h3>
                        </div>
                        <div class="space-y-3 text-sm text-amber-800 dark:text-amber-200">
                            <div class="flex items-start">
                                <span class="w-2 h-2 bg-amber-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <div>
                                    <strong>Açıklama Alanı:</strong> Özellikle sigorta ödemelerinde detaylı açıklama yazın
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="w-2 h-2 bg-amber-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <div>
                                    <strong>Tarih Doğruluğu:</strong> Ödemenin gerçek tarihi ile aynı olsun
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="w-2 h-2 bg-amber-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <div>
                                    <strong>İptal İşlemleri:</strong> Yanlış ödemeleri dikkatli bir şekilde iptal edin
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
