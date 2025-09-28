<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Faturalar</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Stok giriş faturalarını yönetin ve takip edin.</p>
            </div>
            <a href="{{ route('stock.purchases.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Yeni Fatura
            </a>
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
                <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Fatura No</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tedarikçi</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Durum</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tutar</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($invoices as $index => $invoice)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800' }} hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900 dark:text-slate-100">{{ $invoice->invoice_number ?? 'Numarasız' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->supplier?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->invoice_date?->format('d.m.Y') ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($invoice->payment_status === 'paid')
                                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">Ödendi</span>
                                        @elseif($invoice->payment_status === 'pending')
                                            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">Bekliyor</span>
                                        @elseif($invoice->payment_status === 'overdue')
                                            <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-200">Gecikti</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-900/30 px-2.5 py-1 text-xs font-medium text-amber-800 dark:text-amber-200">Kısmi</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('stock.purchases.show', $invoice) }}" class="inline-flex items-center p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Görüntüle">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('stock.purchases.edit', $invoice) }}" class="inline-flex items-center p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Düzenle">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('stock.purchases.destroy', $invoice) }}" onsubmit="return confirm('Bu faturayı silmek istediğinizden emin misiniz?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center p-2 text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors" title="Sil">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Fatura bulunmuyor</h3>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Henüz hiç fatura eklenmemiş. Yeni bir fatura ekleyerek başlayın.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $invoices->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

