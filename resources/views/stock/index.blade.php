<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Stok Yönetim Paneli</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Stok durumunu ve kritik verileri takip edin.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('stock.items.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-colors">
                    Yeni Stok Kalemi
                </a>
                <a href="{{ route('stock.purchases.create') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors">
                    Yeni Fatural
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- Critical Stock Items -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Kritik Stok Kalemleri</h3>
                        <a href="{{ route('stock.items.index', ['status' => 'critical']) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            Tümü
                        </a>
                    </div>
                    <div class="p-6">
                        @forelse($criticalStockItems ?? [] as $item)
                            <div class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-slate-100">{{ $item->name }}</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $item->category?->name ?? 'Kategori Yok' }}</p>
                                </div>
                                <span class="text-sm font-semibold text-amber-600 dark:text-amber-400">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Kritik stok kalemi bulunmuyor.</p>
                            </div>
                        @endforelse
                        @if(isset($criticalStockItemsTotal) && $criticalStockItemsTotal > 5)
                            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                                <p class="text-sm text-slate-600 dark:text-slate-400">Toplam: {{ $criticalStockItemsTotal }} kayıt</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Negative Stock Items -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Negatif Stok Kalemleri</h3>
                        <a href="{{ route('stock.items.index', ['status' => 'negative']) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            Tümü
                        </a>
                    </div>
                    <div class="p-6">
                        @forelse($negativeStockItems ?? [] as $item)
                            <div class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-slate-100">{{ $item->name }}</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $item->category?->name ?? 'Kategori Yok' }}</p>
                                </div>
                                <span class="text-sm font-semibold text-rose-600 dark:text-rose-400">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Negatif stok kalemi bulunmuyor.</p>
                            </div>
                        @endforelse
                        @if(isset($negativeStockItemsTotal) && $negativeStockItemsTotal > 5)
                            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                                <p class="text-sm text-slate-600 dark:text-slate-400">Toplam: {{ $negativeStockItemsTotal }} kayıt</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pending Invoices -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Bekleyen Faturalar</h3>
                        <a href="{{ route('stock.purchases.index', ['payment_status' => 'pending']) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            Tümü
                        </a>
                    </div>
                    <div class="p-6">
                        @forelse($pendingInvoices ?? [] as $invoice)
                            <div class="py-3 border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                                <p class="font-medium text-slate-900 dark:text-slate-100">{{ $invoice->invoice_number }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $invoice->supplier?->name ?? 'Tedarikçi Yok' }}</p>
                                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</p>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Bekleyen fatura bulunmuyor.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Son İşlemler</h3>
                        <a href="{{ route('stock.items.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            Tümü
                        </a>
                    </div>
                    <div class="p-6">
                        @forelse($recentTransactions ?? [] as $transaction)
                            <div class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-slate-100">{{ $transaction->description }}</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $transaction->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                                <span class="text-sm font-semibold {{ $transaction->amount >= 0 ? 'text-green-600 dark:text-green-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $transaction->amount >= 0 ? '+' : '' }}{{ number_format($transaction->amount, 2, ',', '.') }} TL
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Son işlem bulunmuyor.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Overdue Invoices -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Geciken Faturalar</h3>
                        <a href="{{ route('stock.purchases.index', ['payment_status' => 'overdue']) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            Tümü
                        </a>
                    </div>
                    <div class="p-6">
                        @forelse($overdueInvoices ?? [] as $invoice)
                            <div class="py-3 border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                                <p class="font-medium text-slate-900 dark:text-slate-100">{{ $invoice->invoice_number }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $invoice->supplier?->name ?? 'Tedarikçi Yok' }}</p>
                                <p class="text-sm font-semibold text-rose-600 dark:text-rose-400">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $invoice->due_date?->format('d.m.Y') }} tarihinde vadesi geçmiş</p>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Geciken fatura bulunmuyor.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent User Logs -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Son Kullanıcı Kayıtları</h3>
                        <a href="{{ route('stock.items.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            Tümü
                        </a>
                    </div>
                    <div class="p-6">
                        @forelse($recentUserLogs ?? [] as $log)
                            <div class="py-3 border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                                <p class="font-medium text-slate-900 dark:text-slate-100">{{ $log->user?->name ?? 'Bilinmiyor' }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $log->action }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $log->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Kullanıcı kaydı bulunmuyor.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>