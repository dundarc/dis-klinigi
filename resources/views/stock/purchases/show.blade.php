<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Fatura Detayı</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $invoice->invoice_number ?? 'Numarasız' }} - {{ $invoice->supplier?->name ?? 'Tedarikçi Yok' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('stock.purchases.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Listeye Dön
                </a>
                <a href="{{ route('stock.purchases.edit', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
                <a href="#" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF İndir
                </a>
                <form method="POST" action="{{ route('stock.purchases.destroy', $invoice) }}" onsubmit="return confirm('Bu faturayı silmek istediğinizden emin misiniz?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Sil
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Fatura Özeti -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Fatura Özeti</h3>
                    @php
                        $statusConfig = [
                            'paid' => ['bg-green-100 dark:bg-green-900/30', 'text-green-800 dark:text-green-200', 'Ödendi'],
                            'pending' => ['bg-blue-100 dark:bg-blue-900/30', 'text-blue-800 dark:text-blue-200', 'Bekliyor'],
                            'overdue' => ['bg-red-100 dark:bg-red-900/30', 'text-red-800 dark:text-red-200', 'Gecikmiş'],
                            'partial' => ['bg-amber-100 dark:bg-amber-900/30', 'text-amber-800 dark:text-amber-200', 'Kısmi Ödeme'],
                            'installment' => ['bg-purple-100 dark:bg-purple-900/30', 'text-purple-800 dark:text-purple-200', 'Taksitli'],
                        ];
                        $status = $invoice->payment_status->value;
                        $config = $statusConfig[$status] ?? ['bg-slate-100 dark:bg-slate-700', 'text-slate-800 dark:text-slate-200', ucfirst($status)];
                    @endphp
                    <span class="inline-flex items-center rounded-full {{ $config[0] }} px-3 py-1 text-sm font-medium {{ $config[1] }}">{{ $config[2] }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Fatura No</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->invoice_number ?? 'Numarasız' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Tedarikçi</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->supplier?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Fatura Tarihi</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->invoice_date?->format('d.m.Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Vade Tarihi</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">
                            {{ $invoice->due_date?->format('d.m.Y') ?? '-' }}
                            @if($invoice->due_date && $invoice->due_date < now() && !in_array($invoice->payment_status->value, ['paid']))
                                <span class="ml-2 text-red-500 text-xs">({{ $invoice->due_date->diffForHumans() }})</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Genel Toplam</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ number_format($invoice->grand_total, 2, ',', '.') }} ₺</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Toplam Ödenen</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ number_format($invoice->total_paid, 2, ',', '.') }} ₺</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Kalan Tutar</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100 {{ $invoice->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($invoice->remaining_amount, 2, ',', '.') }} ₺</p>
                    </div>
                    @if($invoice->is_installment)
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Taksit Planı</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">
                            {{ $invoice->total_installments }} Taksit
                            @if($invoice->next_payment_date)
                                <br><small class="text-slate-500">Sonraki: {{ $invoice->next_payment_date->format('d.m.Y') }}</small>
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
                @if($invoice->notes)
                    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <p class="text-slate-500 dark:text-slate-400">Açıklama</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Kalemler -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Fatura Kalemleri</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kalem</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Miktar</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Birim Fiyat</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Toplam</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($invoice->items as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900 dark:text-slate-100">{{ $item->description }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ number_format($item->unit_price, 2, ',', '.') }} TL</td>
                                    <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($item->line_total, 2, ',', '.') }} TL</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- OCR Verileri -->
            @if($invoice->parsed_payload)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">OCR Tarama Sonuçları</h3>
                    </div>
                    <div class="p-6">
                        @if(isset($invoice->parsed_payload['supplier']))
                            <div class="mb-4">
                                <p class="text-slate-500 dark:text-slate-400">Tespit Edilen Tedarikçi</p>
                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->parsed_payload['supplier'] }}</p>
                            </div>
                        @endif
                        @if(isset($invoice->parsed_payload['invoice_number']))
                            <div class="mb-4">
                                <p class="text-slate-500 dark:text-slate-400">Tespit Edilen Fatura No</p>
                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->parsed_payload['invoice_number'] }}</p>
                            </div>
                        @endif
                        @if(isset($invoice->parsed_payload['invoice_date']))
                            <div class="mb-4">
                                <p class="text-slate-500 dark:text-slate-400">Tespit Edilen Fatura Tarihi</p>
                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->parsed_payload['invoice_date'] }}</p>
                            </div>
                        @endif
                        @if(isset($invoice->parsed_payload['items']) && count($invoice->parsed_payload['items']) > 0)
                            <div class="mt-6">
                                <h4 class="text-md font-semibold text-slate-900 dark:text-slate-100 mb-4">Tespit Edilen Ürünler ve Öneriler</h4>
                                <div class="space-y-4">
                                    @foreach($invoice->parsed_payload['items'] as $ocrItem)
                                        <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <p class="font-medium text-slate-900 dark:text-slate-100">{{ $ocrItem['description'] }}</p>
                                                    <p class="text-sm text-slate-600 dark:text-slate-300">
                                                        Miktar: {{ $ocrItem['quantity'] }} {{ $ocrItem['unit'] }},
                                                        Fiyat: {{ number_format($ocrItem['unit_price'], 2, ',', '.') }} TL,
                                                        Toplam: {{ number_format($ocrItem['line_total'], 2, ',', '.') }} TL
                                                    </p>
                                                </div>
                                                <div class="ml-4">
                                                    @if(isset($ocrItem['suggestions']) && count($ocrItem['suggestions']) > 0)
                                                        <div class="text-sm">
                                                            <p class="text-slate-500 dark:text-slate-400 mb-1">Stok Eşleşmeleri:</p>
                                                            <div class="flex flex-wrap gap-1">
                                                                @foreach($ocrItem['suggestions'] as $suggestion)
                                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                                                        {{ $suggestion }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Taksit Planı ve Ödemeler -->
            @if($invoice->is_installment && $invoice->paymentSchedules->count() > 0)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Taksit Planı</h3>
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ $invoice->paymentSchedules->where('status', 'paid')->count() }}/{{ $invoice->paymentSchedules->count() }} taksit ödendi</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Taksit</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Vade Tarihi</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tutar</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ödenen</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kalan</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Durum</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($invoice->paymentSchedules->sortBy('installment_number') as $schedule)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 {{ $schedule->isOverdue() ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                    <td class="px-6 py-4 font-medium">{{ $schedule->installment_number }}</td>
                                    <td class="px-6 py-4">
                                        {{ $schedule->due_date->format('d.m.Y') }}
                                        @if($schedule->isOverdue())
                                            <span class="ml-2 text-red-500 text-xs">({{ $schedule->due_date->diffForHumans() }})</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-medium">{{ number_format($schedule->amount, 2, ',', '.') }} ₺</td>
                                    <td class="px-6 py-4">{{ number_format($schedule->paid_amount, 2, ',', '.') }} ₺</td>
                                    <td class="px-6 py-4">{{ number_format($schedule->remaining_amount, 2, ',', '.') }} ₺</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $scheduleStatusConfig = [
                                                'paid' => ['bg-green-100 dark:bg-green-900/30', 'text-green-800 dark:text-green-200', 'Ödendi'],
                                                'pending' => ['bg-blue-100 dark:bg-blue-900/30', 'text-blue-800 dark:text-blue-200', 'Bekliyor'],
                                                'overdue' => ['bg-red-100 dark:bg-red-900/30', 'text-red-800 dark:text-red-200', 'Gecikmiş'],
                                                'partial' => ['bg-amber-100 dark:bg-amber-900/30', 'text-amber-800 dark:text-amber-200', 'Kısmi'],
                                            ];
                                            $scheduleStatus = $schedule->status->value;
                                            $scheduleConfig = $scheduleStatusConfig[$scheduleStatus] ?? ['bg-slate-100', 'text-slate-800', ucfirst($scheduleStatus)];
                                        @endphp
                                        <span class="inline-flex items-center rounded-full {{ $scheduleConfig[0] }} px-2 py-1 text-xs font-medium {{ $scheduleConfig[1] }}">{{ $scheduleConfig[2] }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($schedule->remaining_amount > 0)
                                            <button type="button" 
                                                    onclick="openPaymentModal({{ $schedule->id }}, {{ $schedule->remaining_amount }}, '{{ $schedule->installment_number }}')" 
                                                    class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors">
                                                Öde
                                            </button>
                                        @endif
                                        @if($schedule->paid_date)
                                            <div class="text-xs text-slate-500 mt-1">{{ $schedule->paid_date->format('d.m.Y') }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @elseif(!$invoice->is_installment && $invoice->remaining_amount > 0)
            <!-- Taksitlendirme Seçenekleri -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Taksitlendirme</h3>
                </div>
                <form method="POST" action="{{ route('stock.purchases.create-installment-schedule', $invoice) }}" class="flex items-end gap-4">
                    @csrf
                    <div class="flex-1">
                        <label for="installments" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Taksit Sayısı</label>
                        <select name="installments" id="installments" class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                            <option value="2">2 Taksit</option>
                            <option value="3" selected>3 Taksit</option>
                            <option value="4">4 Taksit</option>
                            <option value="6">6 Taksit</option>
                            <option value="12">12 Taksit</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                        Taksitlendirme Planı Oluştur
                    </button>
                </form>
                <p class="text-xs text-slate-500 mt-2">Faturayı taksitlendirerek ödeme planı oluşturabilirsiniz.</p>
            </div>
            @endif
                <div class="p-6">
                    @if($invoice->payment_history && count($invoice->payment_history) > 0)
                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                                <thead class="bg-slate-50 dark:bg-slate-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tutar</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ödeme Yöntemi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dekont</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Notlar</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($invoice->payment_history as $payment)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                            <td class="px-4 py-3 text-slate-900 dark:text-slate-100">{{ \Carbon\Carbon::parse($payment['date'])->format('d.m.Y') }}</td>
                                            <td class="px-4 py-3 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($payment['amount'], 2, ',', '.') }} TL</td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $payment['method'] }}</td>
                                            <td class="px-4 py-3">
                                                @if(isset($payment['receipt_path']))
                                                    <a href="{{ Storage::url($payment['receipt_path']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">İndir</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $payment['notes'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($invoice->remaining_amount > 0)
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                            <h4 class="text-md font-semibold text-slate-900 dark:text-slate-100 mb-4">Yeni Ödeme Ekle</h4>
                            <form method="POST" action="{{ route('stock.purchases.addPayment', $invoice) }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="payment_amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Tutarı</label>
                                        <input type="number" step="0.01" name="payment_amount" id="payment_amount" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label for="payment_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Tarihi</label>
                                        <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label for="payment_method" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Yöntemi</label>
                                        <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <option value="nakit">Nakit</option>
                                            <option value="havale">Havale</option>
                                            <option value="kredi_karti">Kredi Kartı</option>
                                            <option value="cek">Çek</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="receipt_file" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Dekont Dosyası</label>
                                        <input type="file" name="receipt_file" id="receipt_file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>
                                </div>
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notlar</label>
                                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Ödeme Ekle
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Ödeme Ekle</h3>
            </div>
            <form id="paymentForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="modal_schedule_id" name="schedule_id">
                
                <div>
                    <label for="modal_payment_amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Tutarı</label>
                    <input type="number" step="0.01" name="payment_amount" id="modal_payment_amount" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="modal_payment_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Tarihi</label>
                    <input type="date" name="payment_date" id="modal_payment_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="modal_payment_method" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Yöntemi</label>
                    <select name="payment_method" id="modal_payment_method" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="nakit">Nakit</option>
                        <option value="havale">Havale</option>
                        <option value="kredi_karti">Kredi Kartı</option>
                        <option value="cek">Çek</option>
                    </select>
                </div>
                
                <div>
                    <label for="modal_receipt_file" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Dekont Dosyası</label>
                    <input type="file" name="receipt_file" id="modal_receipt_file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-slate-500 dark:text-slate-400">
                </div>
                
                <div>
                    <label for="modal_notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notlar</label>
                    <textarea name="notes" id="modal_notes" rows="3" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closePaymentModal()" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                        İptal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        Ödeme Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPaymentModal(scheduleId = null, amount = null, installmentNumber = null) {
            const modal = document.getElementById('paymentModal');
            const form = document.getElementById('paymentForm');
            const scheduleInput = document.getElementById('modal_schedule_id');
            const amountInput = document.getElementById('modal_payment_amount');
            
            form.action = "{{ route('stock.purchases.addPayment', $invoice) }}";
            
            if (scheduleId) {
                scheduleInput.value = scheduleId;
                amountInput.value = amount;
                amountInput.max = amount;
                document.querySelector('#paymentModal h3').textContent = `Taksit ${installmentNumber} Ödemesi`;
            } else {
                scheduleInput.value = '';
                amountInput.value = '';
                amountInput.max = {{ $invoice->remaining_amount }};
                document.querySelector('#paymentModal h3').textContent = 'Ödeme Ekle';
            }
            
            modal.classList.remove('hidden');
        }
        
        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });
    </script>
</x-app-layout>
