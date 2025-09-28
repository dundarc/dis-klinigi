<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Fatura Düzenle</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $invoice->invoice_number ?? 'Numarasız' }} - {{ $invoice->supplier?->name ?? 'Tedarikçi Yok' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('stock.purchases.show', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Faturayı Görüntüle
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
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Fatura Bilgileri</h3>
                </div>
                <form method="POST" action="{{ route('stock.purchases.update', $invoice) }}" x-data="{ hasChanges: false }" @change="hasChanges = true" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="invoice_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Fatura No <span class="text-red-500">*</span></label>
                            <input id="invoice_number" name="invoice_number" type="text" value="{{ old('invoice_number', $invoice->invoice_number) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                            <x-input-error :messages="$errors->get('invoice_number')" class="mt-2" />
                        </div>
                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tedarikçi <span class="text-red-500">*</span></label>
                            <select id="supplier_id" name="supplier_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">Tedarikçi Seçin</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id', $invoice->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                        </div>
                        <div>
                            <label for="invoice_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Fatura Tarihi <span class="text-red-500">*</span></label>
                            <input id="invoice_date" name="invoice_date" type="date" value="{{ old('invoice_date', optional($invoice->invoice_date)->toDateString()) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                            <x-input-error :messages="$errors->get('invoice_date')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Açıklama</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $invoice->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Kalemler Tablosu (Salt Okunur) -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Fatura Kalemleri</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                                <thead class="bg-slate-50 dark:bg-slate-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Kalem</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Miktar</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Birim Fiyat</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Toplam</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($invoice->items as $item)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-slate-900 dark:text-slate-100">{{ $item->description }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ number_format($item->unit_price, 2, ',', '.') }} TL</td>
                                            <td class="px-4 py-3 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($item->line_total, 2, ',', '.') }} TL</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-right">
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">Genel Toplam: {{ number_format($invoice->grand_total, 2, ',', '.') }} TL</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('stock.purchases.show', $invoice) }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
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
</x-app-layout>
