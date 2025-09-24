<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Fatura Detayi</h2>
            <div class="flex gap-2">
                <x-secondary-button-link href="{{ route('stock.purchases.index') }}">Listeye Don</x-secondary-button-link>
                <x-primary-button-link href="{{ route('stock.purchases.edit', $invoice) }}">Duzenle</x-primary-button-link>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Fatura No</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $invoice->invoice_number ?? 'Numarasiz' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tedarikci</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $invoice->supplier?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Fatura Tarihi</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $invoice->invoice_date?->format('d.m.Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Vade</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $invoice->due_date?->format('d.m.Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Odeme Durumu</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ ucfirst($invoice->payment_status) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Odeme Yontemi</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $invoice->payment_method ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Ara Toplam</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($invoice->subtotal, 2, ',', '.') }} TL</p>
                    </div>
                    <div>
                        <p class="text-gray-500">KDV</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($invoice->vat_total, 2, ',', '.') }} TL</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Genel Toplam</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-gray-500">Notlar</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $invoice->notes ?? '-' }}</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Kalemler</h3>
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

            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Cari Hareketleri</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Tarih</th>
                                <th class="px-4 py-2 text-left">Aciklama</th>
                                <th class="px-4 py-2 text-left">Yontem</th>
                                <th class="px-4 py-2 text-left">Yon</th>
                                <th class="px-4 py-2 text-right">Tutar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($invoice->accountMovements as $movement)
                                <tr>
                                    <td class="px-4 py-3">{{ optional($movement->movement_date)->format('d.m.Y') ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $movement->description ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $movement->payment_method ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($movement->direction) }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($movement->amount, 2, ',', '.') }} TL</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">Cari hareket bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            @if($invoice->file_path)
                <x-card>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Fatura Dosyasi</h3>
                    <a href="{{ Storage::disk('public')->url($invoice->file_path) }}" class="text-sm text-indigo-600 dark:text-indigo-400" target="_blank">PDF indirmek icin tiklayin</a>
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>


