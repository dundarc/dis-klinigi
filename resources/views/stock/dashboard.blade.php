<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-2xl p-8 mb-8 text-white">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Stok Yönetim Paneli</h1>
                        <p class="text-blue-100 text-lg">Stok hareketlerini ve kritik verileri modern arayüz ile yönetin</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('stock.purchases.create') }}" class="inline-flex items-center px-6 py-3 text-sm font-semibold text-blue-600 bg-white rounded-xl hover:bg-blue-50 focus:ring-4 focus:ring-white/30 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Yeni Fatura
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('stock.suppliers.index') }}" class="inline-flex items-center px-4 py-3 text-sm font-medium text-white/90 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Cari
                            </a>
                            <a href="{{ route('stock.expenses.index') }}" class="inline-flex items-center px-4 py-3 text-sm font-medium text-white/90 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Giderler
                            </a>
                            <a href="{{ route('stock.items.index') }}" class="inline-flex items-center px-4 py-3 text-sm font-medium text-white/90 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Stok
                            </a>
                            <a href="{{ route('stock.purchases.index') }}" class="inline-flex items-center px-4 py-3 text-sm font-medium text-white/90 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Faturalar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Live Search Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 p-8 backdrop-blur-sm">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Hızlı Arama</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm">Tüm stok verilerinde anında arama yapın</p>
                    </div>
                </div>
                <div class="relative">
                    <div class="relative">
                        <input type="text" id="global-search" placeholder="Stok kalemi, tedarikçi, hizmet, fatura veya gider ara..."
                               class="w-full pl-12 pr-4 py-4 text-lg border-2 border-slate-200 dark:border-slate-600 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-100 transition-all duration-200">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div id="search-results" class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 max-h-80 overflow-y-auto z-50 hidden">
                        <div class="p-4">
                            <div class="space-y-3" id="search-results-list"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $stats = [
                        [
                            'label' => 'Toplam Kalem',
                            'value' => number_format((int) $summary['total_items']),
                            'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                            'gradient' => 'from-blue-500 to-blue-600',
                            'bg' => 'bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20',
                            'text' => 'text-blue-600 dark:text-blue-400'
                        ],
                        [
                            'label' => 'Aktif Kalem',
                            'value' => number_format((int) $summary['active_items']),
                            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                            'gradient' => 'from-green-500 to-green-600',
                            'bg' => 'bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20',
                            'text' => 'text-green-600 dark:text-green-400'
                        ],
                        [
                            'label' => 'Toplam Stok Miktarı',
                            'value' => number_format($summary['total_stock_quantity'], 2),
                            'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                            'gradient' => 'from-purple-500 to-purple-600',
                            'bg' => 'bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20',
                            'text' => 'text-purple-600 dark:text-purple-400'
                        ],
                        [
                            'label' => 'Kritik Kalem',
                            'value' => number_format((int) $summary['critical_count']),
                            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
                            'gradient' => 'from-red-500 to-red-600',
                            'bg' => 'bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20',
                            'text' => 'text-red-600 dark:text-red-400',
                            'link' => route('stock.movements.critical')
                        ],
                        [
                            'label' => 'Negatif Stok',
                            'value' => number_format((int) $summary['negative_count']),
                            'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                            'gradient' => 'from-rose-500 to-rose-600',
                            'bg' => 'bg-gradient-to-br from-rose-50 to-rose-100 dark:from-rose-900/20 dark:to-rose-800/20',
                            'text' => 'text-rose-600 dark:text-rose-400'
                        ],
                        [
                            'label' => 'Aylık Gider',
                            'value' => number_format($summary['monthly_expenses'], 2, ',', '.') . ' <span class="text-sm font-normal">TL</span>',
                            'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
                            'gradient' => 'from-indigo-500 to-indigo-600',
                            'bg' => 'bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20',
                            'text' => 'text-indigo-600 dark:text-indigo-400'
                        ],
                    ];
                @endphp

                @foreach($stats as $stat)
                    @if(isset($stat['link']))
                        <a href="{{ $stat['link'] }}" class="block group">
                    @endif
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 {{ isset($stat['link']) ? 'hover:bg-slate-50/50 dark:hover:bg-slate-700/30' : '' }} backdrop-blur-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">{{ $stat['label'] }}</p>
                                <p class="text-3xl font-bold text-slate-900 dark:text-slate-100" {!! isset($stat['value']) && str_contains($stat['value'], '<span') ? '' : '' !!}>{!! $stat['value'] !!}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-br {{ $stat['gradient'] }} rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @if(isset($stat['link']))
                            <div class="mt-4 flex items-center text-sm text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300 font-medium">
                                <span>Detayları gör</span>
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    @if(isset($stat['link']))
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- Data Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Critical Stocks -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden backdrop-blur-sm">
                    <div class="px-8 py-6 border-b border-slate-200/50 dark:border-slate-700/50 flex items-center justify-between bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Kritik Stoklar</h3>
                        </div>
                        <a href="{{ route('stock.movements.critical') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 rounded-xl hover:bg-amber-200 dark:hover:bg-amber-900/50 transition-all duration-200">
                            <span>Tümünü Gör</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="p-8">
                        <div id="critical-stocks-container">
                            <div class="space-y-4" id="critical-stocks-list">
                                @forelse($criticalItems->take(5) as $item)
                                    <a href="{{ route('stock.items.show', $item) }}" class="block">
                                        <div class="flex items-center justify-between p-5 rounded-2xl border border-amber-200/50 dark:border-amber-700/50 bg-gradient-to-r from-amber-50/50 to-orange-50/50 dark:from-amber-900/10 dark:to-orange-900/10 hover:shadow-lg hover:border-amber-300 dark:hover:border-amber-600 transition-all duration-200 cursor-pointer">
                                            <div class="flex items-center">
                                                <div class="flex items-center justify-center w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl mr-4">
                                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-amber-800 dark:text-amber-200">{{ $item->name }}</p>
                                                    <p class="text-sm text-amber-600 dark:text-amber-300">Kategori: {{ $item->category?->name ?? 'Belirsiz' }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-lg font-bold text-amber-800 dark:text-amber-200">{{ number_format($item->quantity, 2) }}</span>
                                                <p class="text-sm text-amber-600 dark:text-amber-300">{{ $item->unit }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center py-12">
                                        <div class="flex items-center justify-center w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-2xl mx-auto mb-4">
                                            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-medium text-slate-500 dark:text-slate-400">Kritik stok bulunmuyor</p>
                                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Tüm stoklar yeterli seviyede</p>
                                    </div>
                                @endforelse
                            </div>
                            @if($criticalItems->count() > 5)
                                <div class="mt-6 flex justify-center">
                                    <div class="flex space-x-2" id="critical-stocks-pagination"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Negative Stocks -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden backdrop-blur-sm">
                    <div class="px-8 py-6 border-b border-slate-200/50 dark:border-slate-700/50 flex items-center bg-gradient-to-r from-rose-50 to-pink-50 dark:from-rose-900/10 dark:to-pink-900/10">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Negatif Stok</h3>
                    </div>
                    <div class="p-8">
                        <div id="negative-stocks-container">
                            <div class="space-y-4" id="negative-stocks-list">
                                @forelse($negativeItems->take(5) as $item)
                                    <a href="{{ route('stock.items.show', $item) }}" class="block">
                                        <div class="flex items-center justify-between p-5 rounded-2xl border border-rose-200/50 dark:border-rose-700/50 bg-gradient-to-r from-rose-50/50 to-pink-50/50 dark:from-rose-900/10 dark:to-pink-900/10 hover:shadow-lg hover:border-rose-300 dark:hover:border-rose-600 transition-all duration-200">
                                            <div class="flex items-center">
                                                <div class="flex items-center justify-center w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-xl mr-4">
                                                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-rose-800 dark:text-rose-200">{{ $item->name }}</p>
                                                    <p class="text-sm text-rose-600 dark:text-rose-300">Kategori: {{ $item->category?->name ?? 'Belirsiz' }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-lg font-bold text-rose-800 dark:text-rose-200">{{ number_format($item->quantity, 2) }}</span>
                                                <p class="text-sm text-rose-600 dark:text-rose-300">{{ $item->unit }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center py-12">
                                        <div class="flex items-center justify-center w-16 h-16 bg-rose-100 dark:bg-rose-900/30 rounded-2xl mx-auto mb-4">
                                            <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-medium text-slate-500 dark:text-slate-400">Negatif stok bulunmuyor</p>
                                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Tüm stoklar pozitif seviyede</p>
                                    </div>
                                @endforelse
                            </div>
                            @if($negativeItems->count() > 5)
                                <div class="mt-6 flex justify-center">
                                    <div class="flex space-x-2" id="negative-stocks-pagination"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Partial and Unpaid Invoices -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden backdrop-blur-sm">
                    <div class="px-8 py-6 border-b border-slate-200/50 dark:border-slate-700/50 flex items-center bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Kısmi ve Ödenmemiş Faturalar</h3>
                    </div>
                    <div class="p-8">
                        <div id="partial-invoices-container">
                            <div class="space-y-4" id="partial-invoices-list">
                                @forelse($pendingInvoices as $invoice)
                                    <a href="{{ route('stock.purchases.show', $invoice) }}" class="block">
                                        <div class="flex items-center justify-between p-5 rounded-2xl border border-blue-200/50 dark:border-blue-700/50 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-blue-900/10 dark:to-indigo-900/10 hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 cursor-pointer">
                                            <div class="flex items-center">
                                                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl mr-4">
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-blue-800 dark:text-blue-200">{{ $invoice->invoice_number ?? 'Numarasız' }}</p>
                                                    <p class="text-sm text-blue-600 dark:text-blue-300">{{ $invoice->supplier?->name ?? 'Tedarikçi Yok' }}</p>
                                                    <p class="text-sm text-blue-500 dark:text-blue-400">{{ $invoice->invoice_date?->format('d.m.Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-lg font-bold text-blue-800 dark:text-blue-200">{{ number_format($invoice->grand_total, 2, ',', '.') }}</span>
                                                <p class="text-sm text-blue-600 dark:text-blue-300">TL</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center py-12">
                                        <div class="flex items-center justify-center w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl mx-auto mb-4">
                                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-medium text-slate-500 dark:text-slate-400">Bekleyen fatura yok</p>
                                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Tüm faturalar güncel</p>
                                    </div>
                                @endforelse
                            </div>
                            @if($pendingInvoices->count() > 5)
                                <div class="mt-6 flex justify-center">
                                    <div class="flex space-x-2" id="partial-invoices-pagination"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Overdue Invoices -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden backdrop-blur-sm">
                    <div class="px-8 py-6 border-b border-slate-200/50 dark:border-slate-700/50 flex items-center bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/10 dark:to-rose-900/10">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Geciken Faturalar</h3>
                    </div>
                    <div class="p-8">
                        <div id="overdue-invoices-container">
                            <div class="space-y-4" id="overdue-invoices-list">
                                @forelse($overdueInvoices as $invoice)
                                    <a href="{{ route('stock.purchases.show', $invoice) }}" class="block">
                                        <div class="flex items-center justify-between p-5 rounded-2xl border border-red-200/50 dark:border-red-700/50 bg-gradient-to-r from-red-50/50 to-rose-50/50 dark:from-red-900/10 dark:to-rose-900/10 hover:shadow-lg hover:border-red-300 dark:hover:border-red-600 transition-all duration-200 cursor-pointer">
                                            <div class="flex items-center">
                                                <div class="flex items-center justify-center w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-xl mr-4">
                                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-red-800 dark:text-red-200">{{ $invoice->invoice_number ?? 'Numarasız' }}</p>
                                                    <p class="text-sm text-red-600 dark:text-red-300">{{ $invoice->supplier?->name ?? 'Tedarikçi Yok' }}</p>
                                                    <p class="text-sm text-red-500 dark:text-red-400">{{ $invoice->invoice_date?->format('d.m.Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-lg font-bold text-red-800 dark:text-red-200">{{ number_format($invoice->grand_total, 2, ',', '.') }}</span>
                                                <p class="text-sm text-red-600 dark:text-red-300">TL</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center py-12">
                                        <div class="flex items-center justify-center w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-2xl mx-auto mb-4">
                                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-medium text-slate-500 dark:text-slate-400">Geciken fatura yok</p>
                                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Tüm ödemeler zamanında</p>
                                    </div>
                                @endforelse
                            </div>
                            @if($overdueInvoices->count() > 5)
                                <div class="mt-6 flex justify-center">
                                    <div class="flex space-x-2" id="overdue-invoices-pagination"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('global-search');
            const searchType = document.getElementById('search-type');
            const searchResults = document.getElementById('search-results');
            const searchResultsList = document.getElementById('search-results-list');
            let searchTimeout;

            // Live search functionality
            function performSearch() {
                const query = searchInput.value.trim();

                if (query.length < 2) {
                    searchResults.classList.add('hidden');
                    return;
                }

                fetch(`{{ route('stock.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResultsList.innerHTML = '';

                        if (data.length === 0) {
                            searchResultsList.innerHTML = '<p class="text-slate-500 dark:text-slate-400 text-sm">Sonuç bulunamadı.</p>';
                        } else {
                            data.forEach(item => {
                                const resultDiv = document.createElement('div');
                                resultDiv.className = 'p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 cursor-pointer transition-colors';
                                resultDiv.onclick = () => window.location.href = item.url;

                                let content = `<div class="font-medium text-slate-900 dark:text-slate-100">${item.name}</div>`;
                                let typeLabel = '';

                                switch (item.type) {
                                    case 'item':
                                        typeLabel = `Stok Kalemi`;
                                        content += `<div class="text-sm text-slate-600 dark:text-slate-400">${item.quantity} ${item.unit} - ${item.category || 'Kategorisiz'}</div>`;
                                        break;
                                    case 'supplier':
                                        typeLabel = `Tedarikçi`;
                                        break;
                                    case 'service':
                                        typeLabel = `Hizmet`;
                                        content += `<div class="text-sm text-slate-600 dark:text-slate-400">${item.supplier || 'Tedarikçisiz'}</div>`;
                                        break;
                                    case 'invoice':
                                        typeLabel = `Fatura`;
                                        content += `<div class="text-sm text-slate-600 dark:text-slate-400">${item.supplier || 'Tedarikçisiz'} - ${item.total} TL</div>`;
                                        break;
                                    case 'expense':
                                        typeLabel = `Gider`;
                                        content += `<div class="text-sm text-slate-600 dark:text-slate-400">${item.amount} TL</div>`;
                                        break;
                                }

                                content = `<div class="flex justify-between items-start"><span>${content}</span><span class="text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 px-2 py-1 rounded">${typeLabel}</span></div>`;

                                resultDiv.innerHTML = content;
                                searchResultsList.appendChild(resultDiv);
                            });
                        }

                        searchResults.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        searchResults.classList.add('hidden');
                    });
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 300);
            });

            // AJAX pagination functions
            function loadPartialInvoices(page = 1) {
                fetch(`{{ route('stock.api.partial-invoices') }}?page=${page}`)
                    .then(response => response.json())
                    .then(data => {
                        updateInvoicesList('partial-invoices-list', data.data);
                        updatePagination('partial-invoices-pagination', data, page, loadPartialInvoices);
                    })
                    .catch(error => console.error('Error loading partial invoices:', error));
            }

            function loadOverdueInvoices(page = 1) {
                fetch(`{{ route('stock.api.overdue-invoices') }}?page=${page}`)
                    .then(response => response.json())
                    .then(data => {
                        updateInvoicesList('overdue-invoices-list', data.data);
                        updatePagination('overdue-invoices-pagination', data, page, loadOverdueInvoices);
                    })
                    .catch(error => console.error('Error loading overdue invoices:', error));
            }

            function loadCriticalStocks(page = 1) {
                fetch(`{{ route('stock.api.critical-stocks') }}?page=${page}`)
                    .then(response => response.json())
                    .then(data => {
                        updateStocksList('critical-stocks-list', data.data, 'critical');
                        updatePagination('critical-stocks-pagination', data, page, loadCriticalStocks);
                    })
                    .catch(error => console.error('Error loading critical stocks:', error));
            }

            function loadNegativeStocks(page = 1) {
                fetch(`{{ route('stock.api.negative-stocks') }}?page=${page}`)
                    .then(response => response.json())
                    .then(data => {
                        updateStocksList('negative-stocks-list', data.data, 'negative');
                        updatePagination('negative-stocks-pagination', data, page, loadNegativeStocks);
                    })
                    .catch(error => console.error('Error loading negative stocks:', error));
            }

            function updateInvoicesList(containerId, invoices) {
                const container = document.getElementById(containerId);
                if (invoices.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Fatura bulunamadı.</p>
                        </div>
                    `;
                } else {
                    container.innerHTML = invoices.map(invoice => `
                        <div class="p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <p class="font-medium text-slate-900 dark:text-slate-100">${invoice.invoice_number}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">${invoice.supplier_name} - ${invoice.invoice_date}</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">${invoice.grand_total} TL</p>
                        </div>
                    `).join('');
                }
            }

            function updateStocksList(containerId, stocks, type) {
                const container = document.getElementById(containerId);
                const isCritical = type === 'critical';

                if (stocks.length === 0) {
                    const icon = isCritical
                        ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z'
                        : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z';
                    const color = isCritical ? 'text-amber-400' : 'text-rose-400';
                    const message = isCritical ? 'Kritik stok bulunmuyor.' : 'Negatif stok bulunmuyor.';

                    container.innerHTML = `
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 ${color}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icon}"></path>
                            </svg>
                            <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">${message}</p>
                        </div>
                    `;
                } else {
                    const borderColor = isCritical ? 'border-amber-200 dark:border-amber-700' : 'border-rose-200 dark:border-rose-700';
                    const bgColor = isCritical ? 'bg-amber-50 dark:bg-amber-900/20' : 'bg-rose-50 dark:bg-rose-900/20';
                    const textColor = isCritical ? 'text-amber-800 dark:text-amber-200' : 'text-rose-800 dark:text-rose-200';
                    const textColorLight = isCritical ? 'text-amber-600 dark:text-amber-300' : 'text-rose-600 dark:text-rose-300';

                    container.innerHTML = stocks.map(stock => `
                        <div class="flex items-center justify-between p-4 rounded-lg border ${borderColor} ${bgColor} hover:shadow-md transition-shadow">
                            <div>
                                <p class="font-medium ${textColor}">${stock.name}</p>
                                <p class="text-sm ${textColorLight}">Kategori: ${stock.category_name || 'Belirsiz'}</p>
                            </div>
                            <span class="text-sm font-semibold ${textColor}">${stock.quantity} ${stock.unit}</span>
                        </div>
                    `).join('');
                }
            }

            function updatePagination(paginationId, data, currentPage, loadFunction) {
                const paginationContainer = document.getElementById(paginationId);
                if (data.last_page <= 1) {
                    paginationContainer.innerHTML = '';
                    return;
                }

                let paginationHtml = '';

                // Previous button
                if (data.current_page > 1) {
                    paginationHtml += `<button class="px-3 py-1 text-sm bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded hover:bg-slate-50 dark:hover:bg-slate-600" onclick="${loadFunction.name}(${data.current_page - 1})">Önceki</button>`;
                }

                // Page numbers
                for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
                    const isActive = i === data.current_page;
                    const activeClass = isActive ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300';
                    paginationHtml += `<button class="px-3 py-1 text-sm ${activeClass} border border-slate-300 dark:border-slate-600 rounded hover:bg-slate-50 dark:hover:bg-slate-600 ${isActive ? '' : 'hover:bg-slate-50 dark:hover:bg-slate-600'}" onclick="${loadFunction.name}(${i})">${i}</button>`;
                }

                // Next button
                if (data.current_page < data.last_page) {
                    paginationHtml += `<button class="px-3 py-1 text-sm bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded hover:bg-slate-50 dark:hover:bg-slate-600" onclick="${loadFunction.name}(${data.current_page + 1})">Sonraki</button>`;
                }

                paginationContainer.innerHTML = paginationHtml;
            }

            // Initialize pagination if there are more than 5 items
            @if($pendingInvoices->count() > 5)
                loadPartialInvoices();
            @endif

            @if($overdueInvoices->count() > 5)
                loadOverdueInvoices();
            @endif

            @if($criticalItems->count() > 5)
                loadCriticalStocks();
            @endif

            @if($negativeItems->count() > 5)
                loadNegativeStocks();
            @endif
        });
    </script>
</x-app-layout>
