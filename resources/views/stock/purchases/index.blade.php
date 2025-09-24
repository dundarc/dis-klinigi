<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Stok Faturalari</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Stok giris faturalarini listeleyin, filtreleyin ve yonetin.</p>
            </div>
            <x-primary-button-link href="{{ route('stock.purchases.create') }}">Yeni Fatura</x-primary-button-link>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <x-card class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">Fatura Filtresi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tedarikci, durum ya da tarih araligina gore sonuclari daraltin.</p>
                </div>
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
                    <div>
                        <x-input-label for="supplier_id" value="Tedarikci" class="text-slate-600 dark:text-slate-300" />
                        <select id="supplier_id" name="supplier_id" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Tum Tedarikciler</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(($filters['supplier_id'] ?? '') == $supplier->id)>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="payment_status" value="Durum" class="text-slate-600 dark:text-slate-300" />
                        <select id="payment_status" name="payment_status" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Tum Durumlar</option>
                            <option value="pending" @selected(($filters['payment_status'] ?? '') === 'pending')>Bekleyen</option>
                            <option value="partial" @selected(($filters['payment_status'] ?? '') === 'partial')>Kismi</option>
                            <option value="paid" @selected(($filters['payment_status'] ?? '') === 'paid')>Odendi</option>
                            <option value="overdue" @selected(($filters['payment_status'] ?? '') === 'overdue')>Gecikmis</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="date_from" value="Baslangic" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" />
                    </div>
                    <div>
                        <x-input-label for="date_to" value="Bitis" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" />
                    </div>
                    <div class="col-span-full flex flex-wrap items-end justify-end gap-2">
                        <x-secondary-button type="button" onclick="window.location='{{ route('stock.purchases.index') }}';">Sifirla</x-secondary-button>
                        <x-primary-button type="submit">Filtrele</x-primary-button>
                    </div>
                </form>
            </x-card>

            <x-card class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-100 dark:bg-slate-800/70 text-slate-600 dark:text-slate-300">
                            <tr>
                                <th class="px-4 py-2 text-left">Fatura No</th>
                                <th class="px-4 py-2 text-left">Tedarikci</th>
                                <th class="px-4 py-2 text-left">Tarih</th>
                                <th class="px-4 py-2 text-left">Durum</th>
                                <th class="px-4 py-2 text-right">Toplam</th>
                                <th class="px-4 py-2 text-right">Islemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($invoices as $invoice)
                                <tr class="bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800">
                                    <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-100">{{ $invoice->invoice_number ?? 'Numarasiz' }}</td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $invoice->supplier?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $invoice->invoice_date?->format('d.m.Y') ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ ucfirst($invoice->payment_status) }}</td>
                                    <td class="px-4 py-3 text-right text-slate-800 dark:text-slate-100">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <x-secondary-button-link href="{{ route('stock.purchases.show', $invoice) }}">Goruntule</x-secondary-button-link>
                                            <x-secondary-button-link href="{{ route('stock.purchases.edit', $invoice) }}">Duzenle</x-secondary-button-link>
                                            <form method="POST" action="{{ route('stock.purchases.destroy', $invoice) }}" onsubmit="return confirm('Fatura silinsin mi?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button>Sil</x-danger-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">Fatura bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

