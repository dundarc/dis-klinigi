<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Fatura Duzenle</h2>
            <x-secondary-button-link href="{{ route('stock.purchases.show', $invoice) }}">Faturayi Gor</x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card class="space-y-6">
                <form method="POST" action="{{ route('stock.purchases.update', $invoice) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="supplier_id" value="Tedarikci" />
                        <select id="supplier_id" name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                            <option value="">Seciniz</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $invoice->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="invoice_number" value="Fatura No" />
                        <x-text-input id="invoice_number" name="invoice_number" type="text" value="{{ old('invoice_number', $invoice->invoice_number) }}" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="invoice_date" value="Fatura Tarihi" />
                        <x-text-input id="invoice_date" name="invoice_date" type="date" value="{{ old('invoice_date', optional($invoice->invoice_date)->toDateString()) }}" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="due_date" value="Vade" />
                        <x-text-input id="due_date" name="due_date" type="date" value="{{ old('due_date', optional($invoice->due_date)->toDateString()) }}" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="payment_status" value="Odeme Durumu" />
                        <select id="payment_status" name="payment_status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                            <option value="pending" @selected(old('payment_status', $invoice->payment_status->value ?? $invoice->payment_status) === 'pending')>Bekleyen</option>
                            <option value="partial" @selected(old('payment_status', $invoice->payment_status->value ?? $invoice->payment_status) === 'partial')>Kismi</option>
                            <option value="paid" @selected(old('payment_status', $invoice->payment_status->value ?? $invoice->payment_status) === 'paid')>Odendi</option>
                            <option value="overdue" @selected(old('payment_status', $invoice->payment_status->value ?? $invoice->payment_status) === 'overdue')>Gecikmis</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="payment_method" value="Odeme Yontemi" />
                        <x-text-input id="payment_method" name="payment_method" type="text" value="{{ old('payment_method', $invoice->payment_method) }}" class="mt-1 block w-full" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="notes" value="Notlar" />
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">{{ old('notes', $invoice->notes) }}</textarea>
                    </div>
                    <div class="md:col-span-2 flex justify-end gap-2">
                        <x-secondary-button type="reset">Temizle</x-secondary-button>
                        <x-primary-button type="submit">Guncelle</x-primary-button>
                    </div>
                </form>
            </x-card>

            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Fatura Kalemleri</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Kalem</th>
                                <th class="px-4 py-2 text-left">Miktar</th>
                                <th class="px-4 py-2 text-left">Birim Fiyat</th>
                                <th class="px-4 py-2 text-left">KDV %</th>
                                <th class="px-4 py-2 text-right">Toplam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $item->description }}</td>
                                    <td class="px-4 py-3">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</td>
                                    <td class="px-4 py-3">{{ number_format($item->unit_price, 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3">{{ number_format($item->vat_rate, 2) }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($item->line_total, 2, ',', '.') }} TL</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>


