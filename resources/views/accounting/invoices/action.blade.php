<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Fatura Düzenleme</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $invoice->invoice_no }} - {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('accounting.invoices.show', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Görüntüle
                </a>
                <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF İndir
                </a>
                <form method="POST" action="{{ route('accounting.invoices.destroy', $invoice) }}" onsubmit="return confirm('Bu faturayı silmek istediğinizden emin misiniz?');" class="inline">
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

    <div class="py-8" x-data="invoiceEditor()" @change="hasChanges = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Payment Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Toplam Tutar</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</p>
                        </div>
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Ödenen Tutar</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalPaid, 2, ',', '.') }} TL</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Kalan Tutar</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($outstandingBalance, 2, ',', '.') }} TL</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

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
                        <x-status-badge :status="$invoice->status" />
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Fatura Kalemleri</h3>
                    <button type="button" @click="addItem()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Kalem Ekle
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Kalem</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Miktar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Birim Fiyat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Ara Toplam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            <template x-for="(item, index) in items" :key="'item-' + index">
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="item.description" x-bind:name="'items[' + index + '][description]'" class="w-full border-0 bg-transparent focus:ring-0 text-sm" placeholder="Kalem açıklaması" />
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" min="1" x-model="item.qty" x-bind:name="'items[' + index + '][qty]'" class="w-full border-0 bg-transparent focus:ring-0 text-sm text-center" placeholder="1" @input="updateTotals()" />
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" step="0.01" min="0" x-model="item.unit_price" x-bind:name="'items[' + index + '][unit_price]'" class="w-full border-0 bg-transparent focus:ring-0 text-sm text-right" placeholder="0.00" @input="updateTotals()" />
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-slate-100" x-text="formatCurrency((item.qty || 0) * (item.unit_price || 0))"></td>
                                    <td class="px-4 py-3">
                                        <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-red-600 hover:text-red-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Totals Section -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-slate-600 dark:text-slate-400">Ara Toplam</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100" x-text="formatCurrency(subtotal)"></p>
                        </div>
                        <div>
                            <p class="text-slate-600 dark:text-slate-400">KDV (%{{ config('accounting.vat_rate') * 100 }})</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100" x-text="formatCurrency(vat)"></p>
                        </div>
                        <div>
                            <p class="text-slate-600 dark:text-slate-400">Genel Toplam</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400" x-text="formatCurrency(total)"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Settings -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Fatura Ayarları</h3>
                <form method="POST" action="{{ route('accounting.invoices.update', $invoice) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hasta</label>
                            <select id="patient_id" name="patient_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @isset($patients)
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" @selected($invoice->patient_id === $patient->id)>{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label for="issue_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Fatura Tarihi</label>
                            <input id="issue_date" name="issue_date" type="date" value="{{ $invoice->issue_date?->format('Y-m-d') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Durumu</label>
                            <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @isset($statuses)
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->value }}" @selected($invoice->status->value === $status->value)>{{ ucfirst(str_replace('_', ' ', $status->value)) }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label for="insurance_coverage_amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Sigorta Karşılama Tutarı (TL)</label>
                            <input id="insurance_coverage_amount" name="insurance_coverage_amount" type="number" step="0.01" min="0" value="{{ $invoice->insurance_coverage_amount ?? 0 }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notlar</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $invoice->notes ?? '' }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('accounting.invoices.show', $invoice) }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <button type="submit" :disabled="!hasChanges" :class="hasChanges ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-400 cursor-not-allowed'" class="px-6 py-2 text-white font-medium rounded-lg transition-colors">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function invoiceEditor() {
            return {
                hasChanges: false,
                items: @json($invoice->items->map(function($item) {
                    return [
                        'description' => $item->description,
                        'qty' => $item->qty,
                        'unit_price' => $item->unit_price
                    ];
                })->toArray()) ?? [{
                    description: '',
                    qty: 1,
                    unit_price: 0
                }],
                subtotal: 0,
                vat: 0,
                total: 0,

                init() {
                    this.updateTotals();
                },

                addItem() {
                    this.items.push({
                        description: '',
                        qty: 1,
                        unit_price: 0
                    });
                    this.updateTotals();
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                        this.updateTotals();
                    }
                },

                updateTotals() {
                    this.subtotal = this.items.reduce((sum, item) => {
                        const qty = parseFloat(item.qty) || 0;
                        const price = parseFloat(item.unit_price) || 0;
                        return sum + (qty * price);
                    }, 0);

                    this.vat = this.subtotal * {{ config('accounting.vat_rate') }}; // {{ config('accounting.vat_rate') * 100 }}% KDV
                    this.total = this.subtotal + this.vat;
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('tr-TR', {
                        style: 'currency',
                        currency: 'TRY'
                    }).format(amount);
                }
            }
        }
    </script>
</x-app-layout>
