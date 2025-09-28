<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Stok Paneli</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Stok hareketlerini ve kritik verileri tek ekranda izleyin.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <div class="inline-flex rounded-lg shadow-sm" role="group">
                    <a href="{{ route('stock.purchases.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-l-lg hover:bg-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-500 focus:bg-blue-700 transition-colors">
                        Yeni Fatura
                    </a>
                    <a href="{{ route('stock.suppliers.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border-t border-b border-slate-300 hover:bg-slate-50 focus:z-10 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors">
                        Cari İşlemleri
                    </a>
                    <a href="{{ route('stock.expenses.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border-t border-b border-slate-300 hover:bg-slate-50 focus:z-10 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors">
                        Giderler
                    </a>
                    <a href="{{ route('stock.items.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border-t border-b border-slate-300 hover:bg-slate-50 focus:z-10 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors">
                        Stok Kalemleri
                    </a>
                    <a href="{{ route('stock.purchases.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-r-lg hover:bg-slate-50 focus:z-10 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors">
                        Faturalar
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $stats = [
                        [
                            'label' => 'Toplam Kalem',
                            'value' => number_format((int) $summary['total_items']),
                            'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                            'color' => 'text-blue-600 dark:text-blue-400',
                            'bg' => 'bg-blue-50 dark:bg-blue-900/20'
                        ],
                        [
                            'label' => 'Aktif Kalem',
                            'value' => number_format((int) $summary['active_items']),
                            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                            'color' => 'text-green-600 dark:text-green-400',
                            'bg' => 'bg-green-50 dark:bg-green-900/20'
                        ],
                        [
                            'label' => 'Toplam Stok Miktarı',
                            'value' => number_format($summary['total_stock_quantity'], 2),
                            'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                            'color' => 'text-purple-600 dark:text-purple-400',
                            'bg' => 'bg-purple-50 dark:bg-purple-900/20'
                        ],
                        [
                            'label' => 'Kritik Kalem',
                            'value' => number_format((int) $summary['critical_count']),
                            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
                            'color' => 'text-red-600 dark:text-red-400',
                            'bg' => 'bg-red-50 dark:bg-red-900/20',
                            'link' => route('stock.movements.critical')
                        ],
                        [
                            'label' => 'Negatif Stok',
                            'value' => number_format((int) $summary['negative_count']),
                            'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                            'color' => 'text-rose-600 dark:text-rose-400',
                            'bg' => 'bg-rose-50 dark:bg-rose-900/20'
                        ],
                        [
                            'label' => 'Aylık Gider',
                            'value' => number_format($summary['monthly_expenses'], 2, ',', '.') . ' TL',
                            'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
                            'color' => 'text-indigo-600 dark:text-indigo-400',
                            'bg' => 'bg-indigo-50 dark:bg-indigo-900/20'
                        ],
                    ];
                @endphp

                @foreach($stats as $stat)
                    @if(isset($stat['link']))
                        <a href="{{ $stat['link'] }}" class="block">
                    @endif
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition-shadow duration-200 {{ isset($stat['link']) ? 'hover:bg-slate-50 dark:hover:bg-slate-700/50' : '' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ $stat['label'] }}</p>
                                <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $stat['value'] }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 {{ $stat['bg'] }} rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 {{ $stat['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(isset($stat['link']))
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- Data Sections -->
            @php
                $sections = [
                    'Kritik Stoklar' => $criticalItems,
                    'Negatif Stok' => $negativeItems,
                    'Son Hareketler' => $recentMovements,
                    'Bekleyen Faturalar' => $pendingInvoices,
                    'Geciken Faturalar' => $overdueInvoices,
                    'Son Kullanıcı Kayıtları' => $recentUsage,
                ];

                $emptyMessages = [
                    'Kritik Stoklar' => 'Kritik stok bulunmuyor.',
                    'Negatif Stok' => 'Negatif stok bulunmuyor.',
                    'Son Hareketler' => 'Hareket bulunmuyor.',
                    'Bekleyen Faturalar' => 'Bekleyen fatura yok.',
                    'Geciken Faturalar' => 'Geciken fatura yok.',
                    'Son Kullanıcı Kayıtları' => 'Kullanım kaydı yok.',
                ];

                $emptyIcons = [
                    'Kritik Stoklar' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
                    'Negatif Stok' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'Son Hareketler' => 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                    'Bekleyen Faturalar' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'Geciken Faturalar' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'Son Kullanıcı Kayıtları' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                ];
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($sections as $title => $collection)
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $title }}</h3>
                            @if($title === 'Kritik Stoklar')
                                <a href="{{ route('stock.movements.critical') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                    Tümünü Gör
                                </a>
                            @elseif($title === 'Son Hareketler')
                                <a href="{{ route('stock.movements.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                    Tümünü Gör
                                </a>
                            @endif
                        </div>
                        <div class="p-6">
                            @switch($title)
                                @case('Kritik Stoklar')
                                @case('Negatif Stok')
                                    <div class="space-y-4">
                                        @forelse($collection as $item)
                                            <div class="flex items-center justify-between p-4 rounded-lg border {{ $title === 'Kritik Stoklar' ? 'border-amber-200 dark:border-amber-700 bg-amber-50 dark:bg-amber-900/20' : 'border-rose-200 dark:border-rose-700 bg-rose-50 dark:bg-rose-900/20' }} hover:shadow-md transition-shadow">
                                                <div>
                                                    <p class="font-medium {{ $title === 'Kritik Stoklar' ? 'text-amber-800 dark:text-amber-200' : 'text-rose-800 dark:text-rose-200' }}">{{ $item->name }}</p>
                                                    <p class="text-sm {{ $title === 'Kritik Stoklar' ? 'text-amber-600 dark:text-amber-300' : 'text-rose-600 dark:text-rose-300' }}">Kategori: {{ $item->category?->name ?? 'Belirsiz' }}</p>
                                                </div>
                                                <span class="text-sm font-semibold {{ $title === 'Kritik Stoklar' ? 'text-amber-800 dark:text-amber-200' : 'text-rose-800 dark:text-rose-200' }}">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</span>
                                            </div>
                                        @empty
                                            <div class="text-center py-8">
                                                <svg class="mx-auto h-12 w-12 {{ $title === 'Kritik Stoklar' ? 'text-amber-400' : 'text-rose-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $emptyIcons[$title] }}"></path>
                                                </svg>
                                                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ $emptyMessages[$title] }}</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    @break

                                @case('Son Hareketler')
                                    <div class="space-y-3">
                                        @forelse($collection as $movement)
                                            <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                                <div>
                                                    <p class="font-medium text-slate-900 dark:text-slate-100">{{ $movement->stockItem?->name ?? 'Kalem Silinmiş' }}</p>
                                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ ucfirst($movement->direction->value) }} - {{ $movement->created_at->format('d.m H:i') }}</p>
                                                </div>
                                                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ number_format($movement->quantity, 2) }}</span>
                                            </div>
                                        @empty
                                            <div class="text-center py-8">
                                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $emptyIcons[$title] }}"></path>
                                                </svg>
                                                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ $emptyMessages[$title] }}</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    @break

                                @case('Bekleyen Faturalar')
                                @case('Geciken Faturalar')
                                    <div class="space-y-3">
                                        @forelse($collection as $invoice)
                                            <div class="p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                                <p class="font-medium text-slate-900 dark:text-slate-100">{{ $invoice->invoice_number ?? 'Numarasız' }}</p>
                                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $invoice->supplier?->name ?? 'Tedarikçi Yok' }} - {{ $invoice->invoice_date?->format('d.m.Y') }}</p>
                                                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</p>
                                            </div>
                                        @empty
                                            <div class="text-center py-8">
                                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $emptyIcons[$title] }}"></path>
                                                </svg>
                                                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ $emptyMessages[$title] }}</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    @break

                                @default
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                            <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kullanıcı</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kalem</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Miktar</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                                @forelse($collection as $usage)
                                                    @foreach($usage->items as $index => $usageItem)
                                                        <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800' }} hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">{{ $usage->recordedBy?->name ?? 'Bilinmiyor' }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">{{ $usageItem->stockItem?->name ?? 'Kalem Silinmiş' }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">{{ number_format($usageItem->quantity, 2) }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">{{ optional($usage->used_at)->format('d.m.Y H:i') }}</td>
                                                        </tr>
                                                    @endforeach
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="px-6 py-12 text-center">
                                                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $emptyIcons[$title] }}"></path>
                                                            </svg>
                                                            <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ $emptyMessages[$title] }}</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
