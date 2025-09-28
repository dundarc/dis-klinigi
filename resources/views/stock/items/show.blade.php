<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Stok Hareketleri</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $item->name }} - {{ $item->sku ?? 'SKU Yok' }}</p>
            </div>
            <a href="{{ route('stock.items.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Listeye Dön
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Stok Kalemi Özeti -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Stok Adı</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->name }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">SKU</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->sku ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Mevcut Stok</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Kategori</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->category?->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Hareket Geçmişi -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Hareket Geçmişi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlem</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Miktar</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Referans</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kullanıcı</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Not</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($movements as $movement)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-4 text-slate-900 dark:text-slate-100">{{ $movement->created_at->format('d.m.Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        @if($movement->direction === 'in')
                                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">Giriş</span>
                                        @elseif($movement->direction === 'out')
                                            <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-200">Çıkış</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">Düzeltme</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">
                                        @if($movement->direction === 'in')
                                            <span class="text-green-600 dark:text-green-400">+{{ number_format($movement->quantity, 2) }}</span>
                                        @elseif($movement->direction === 'out')
                                            <span class="text-red-600 dark:text-red-400">-{{ number_format($movement->quantity, 2) }}</span>
                                        @else
                                            <span class="text-blue-600 dark:text-blue-400">{{ number_format($movement->quantity, 2) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                        @if($movement->reference_type && $movement->reference_id)
                                            @if($movement->reference_type === 'App\\Models\\Stock\\StockPurchaseInvoice')
                                                <a href="{{ route('stock.purchases.show', $movement->reference_id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Fatura #{{ $movement->reference_id }}</a>
                                            @elseif($movement->reference_type === 'App\\Models\\Stock\\StockUsage')
                                                <a href="{{ route('stock.usage.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Kullanım #{{ $movement->reference_id }}</a>
                                            @else
                                                {{ class_basename($movement->reference_type) }} #{{ $movement->reference_id }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $movement->createdBy?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $movement->note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Hareket bulunmuyor</h3>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Bu stok kalemi için henüz hareket kaydı yok.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 px-6 pb-6">
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>