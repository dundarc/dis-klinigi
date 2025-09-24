<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Stok Paneli</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Stok hareketlerini ve kritik verileri tek ekranda izleyin.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <x-primary-button-link href="{{ route('stock.purchases.create') }}">Yeni Fatura</x-primary-button-link>
                <x-secondary-button-link href="{{ route('stock.suppliers.index') }}">Cari Islemleri</x-secondary-button-link>
                <x-secondary-button-link href="{{ route('stock.expenses.index') }}">Giderler</x-secondary-button-link>
                <x-secondary-button-link href="{{ route('stock.items.index') }}">Stok Kalemleri</x-secondary-button-link>
                <x-secondary-button-link href="{{ route('stock.purchases.index') }}">Faturalar</x-secondary-button-link>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            @php
                $stats = [
                    ['label' => 'Toplam Kalem', 'value' => number_format((int) $summary['total_items'])],
                    ['label' => 'Aktif Kalem', 'value' => number_format((int) $summary['active_items'])],
                    ['label' => 'Toplam Stok Miktari', 'value' => number_format($summary['total_stock_quantity'], 2)],
                    ['label' => 'Kritik Kalem', 'value' => number_format((int) $summary['critical_count']), 'accent' => 'from-amber-500/20 to-amber-500/5 text-amber-600 dark:text-amber-200'],
                    ['label' => 'Negatif Stok', 'value' => number_format((int) $summary['negative_count']), 'accent' => 'from-rose-500/20 to-rose-500/5 text-rose-600 dark:text-rose-200'],
                    ['label' => 'Aylik Gider', 'value' => number_format($summary['monthly_expenses'], 2, ',', '.') . ' TL', 'accent' => 'from-indigo-500/20 to-indigo-500/5 text-indigo-600 dark:text-indigo-200'],
                ];
            @endphp

            <div class="grid grid-cols-1 gap-4">
                @foreach($stats as $stat)
                    <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/90 dark:bg-slate-900/70 backdrop-blur shadow-sm">
                        <div class="flex items-center justify-between p-6">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $stat['label'] }}</p>
                                <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ $stat['value'] }}</p>
                            </div>
                            <div class="hidden sm:block">
                                <div class="h-16 w-16 rounded-full bg-gradient-to-br {{ $stat['accent'] ?? 'from-slate-500/20 to-slate-500/5 text-slate-500' }}"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @php
                $sections = [
                    'Kritik Stoklar' => $criticalItems,
                    'Negatif Stok' => $negativeItems,
                    'Son Hareketler' => $recentMovements,
                    'Bekleyen Faturalar' => $pendingInvoices,
                    'Geciken Faturalar' => $overdueInvoices,
                    'Son Kullanici Kayitlari' => $recentUsage,
                ];

                $emptyMessages = [
                    'Kritik Stoklar' => 'Kritik stok bulunmuyor.',
                    'Negatif Stok' => 'Negatif stok bulunmuyor.',
                    'Son Hareketler' => 'Hareket bulunmuyor.',
                    'Bekleyen Faturalar' => 'Bekleyen fatura yok.',
                    'Geciken Faturalar' => 'Geciken fatura yok.',
                    'Son Kullanici Kayitlari' => 'Kullanim kaydi yok.',
                ];
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($sections as $title => $collection)
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/90 dark:bg-slate-900/70 backdrop-blur shadow-sm p-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
                            <span class="text-xs uppercase tracking-wide text-slate-400 dark:text-slate-500">Guncel</span>
                        </div>

                        @switch($title)
                            @case('Kritik Stoklar')
                                <div class="space-y-3">
                                    @forelse($collection as $item)
                                        <div class="rounded-xl border border-amber-200 dark:border-amber-500/40 bg-amber-50/80 dark:bg-amber-500/10 p-3">
                                            <div class="flex items-center justify-between gap-4">
                                                <div>
                                                    <p class="font-medium text-amber-700 dark:text-amber-200">{{ $item->name }}</p>
                                                    <p class="text-xs text-amber-600/80">Kategori: {{ $item->category?->name ?? 'Belirsiz' }}</p>
                                                </div>
                                                <span class="text-sm font-semibold text-amber-700 dark:text-amber-100">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $emptyMessages[$title] }}</p>
                                    @endforelse
                                </div>
                                @break

                            @case('Negatif Stok')
                                <div class="space-y-3">
                                    @forelse($collection as $item)
                                        <div class="rounded-xl border border-rose-200 dark:border-rose-500/40 bg-rose-50/80 dark:bg-rose-500/10 p-3">
                                            <div class="flex items-center justify-between gap-4">
                                                <div>
                                                    <p class="font-medium text-rose-700 dark:text-rose-200">{{ $item->name }}</p>
                                                    <p class="text-xs text-rose-600/80">Kategori: {{ $item->category?->name ?? 'Belirsiz' }}</p>
                                                </div>
                                                <span class="text-sm font-semibold text-rose-700 dark:text-rose-100">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $emptyMessages[$title] }}</p>
                                    @endforelse
                                </div>
                                @break

                            @case('Son Hareketler')
                                <ul class="space-y-3 text-sm">
                                    @forelse($collection as $movement)
                                        <li class="flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-700/60 bg-slate-50/90 dark:bg-slate-900/60 p-3">
                                            <div>
                                                <p class="font-medium text-slate-700 dark:text-slate-200">{{ $movement->stockItem?->name ?? 'Kalem Silinmis' }}</p>
                                                <p class="text-xs text-slate-500">{{ ucfirst($movement->direction) }} - {{ $movement->created_at->format('d.m H:i') }}</p>
                                            </div>
                                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-100">{{ number_format($movement->quantity, 2) }}</span>
                                        </li>
                                    @empty
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $emptyMessages[$title] }}</p>
                                    @endforelse
                                </ul>
                                @break

                            @case('Bekleyen Faturalar')
                            @case('Geciken Faturalar')
                                <ul class="space-y-3 text-sm">
                                    @forelse($collection as $invoice)
                                        <li class="rounded-xl border border-slate-200 dark:border-slate-700/60 bg-slate-50/90 dark:bg-slate-900/60 p-3">
                                            <p class="font-medium text-slate-700 dark:text-slate-200">{{ $invoice->invoice_number ?? 'Numarasiz' }}</p>
                                            <p class="text-xs text-slate-500">{{ $invoice->supplier?->name ?? 'Tedarikci Yok' }} - {{ $invoice->invoice_date?->format('d.m.Y') }}</p>
                                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-100">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</p>
                                        </li>
                                    @empty
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $emptyMessages[$title] }}</p>
                                    @endforelse
                                </ul>
                                @break

                            @default
                                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/60">
                                    <table class="min-w-full text-sm bg-white/95 dark:bg-slate-900/70">
                                        <thead class="bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300">
                                            <tr>
                                                <th class="px-4 py-2 text-left">Kullanici</th>
                                                <th class="px-4 py-2 text-left">Kalem</th>
                                                <th class="px-4 py-2 text-left">Miktar</th>
                                                <th class="px-4 py-2 text-left">Tarih</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                            @forelse($collection as $usage)
                                                @foreach($usage->items as $usageItem)
                                                    <tr class="bg-white dark:bg-slate-900">
                                                        <td class="px-4 py-2">{{ $usage->recordedBy?->name ?? 'Bilinmiyor' }}</td>
                                                        <td class="px-4 py-2">{{ $usageItem->stockItem?->name ?? 'Kalem Silinmis' }}</td>
                                                        <td class="px-4 py-2">{{ number_format($usageItem->quantity, 2) }}</td>
                                                        <td class="px-4 py-2">{{ optional($usage->used_at)->format('d.m.Y H:i') }}</td>
                                                    </tr>
                                                @endforeach
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">{{ $emptyMessages[$title] }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @break
                        @endswitch
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>


