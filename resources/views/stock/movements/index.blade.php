<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Stok Hareketleri</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Tüm stok giriş, çıkış ve düzeltme hareketlerini görüntüleyin.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <!-- Export Buttons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('stock.movements.export.pdf', request()->query()) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        PDF
                    </a>
                    <a href="{{ route('stock.movements.print', request()->query()) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Yazdır
                    </a>
                </div>
                <a href="{{ route('stock.movements.create-adjustment') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Manuel Düzeltme
                </a>
                <a href="{{ route('stock.movements.critical') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    Kritik Stoklar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="direction" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Hareket Tipi</label>
                            <select name="direction" id="direction" class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                                <option value="">Tümü</option>
                                @foreach($directions as $direction)
                                    <option value="{{ $direction->value }}" @selected($filters['direction'] === $direction->value)>
                                        {{ $direction->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="item_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Stok Kalemi</label>
                            <select name="item_id" id="item_id" class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                                <option value="">Tümü</option>
                                @foreach($stockItems as $item)
                                    <option value="{{ $item->id }}" @selected($filters['item_id'] == $item->id)>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="user_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kullanıcı</label>
                            <select name="user_id" id="user_id" class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                                <option value="">Tümü</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected($filters['user_id'] == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Başlangıç Tarihi</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $filters['start_date'] }}" class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Bitiş Tarihi</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $filters['end_date'] }}" class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filtrele
                        </button>
                        <a href="{{ route('stock.movements.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            Temizle
                        </a>
                    </div>
                </form>
            </div>

            <!-- Movement History -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                        Hareket Geçmişi ({{ $movements->total() }} kayıt)
                    </h3>
                </div>

                @if($movements->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Stok Kalemi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hareket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Miktar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Referans</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kullanıcı</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Notlar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($movements as $movement)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                                            {{ ($movement->movement_date ?? $movement->created_at)->format('d.m.Y H:i') }}
                                        </td>
                                        
                                        <td class="px-6 py-4 text-sm">
                                            <div class="font-medium text-slate-900 dark:text-slate-100">
                                                {{ $movement->stockItem?->name ?? 'Kalem Silinmiş' }}
                                            </div>
                                            @if($movement->stockItem?->category)
                                                <div class="text-slate-500 dark:text-slate-400">
                                                    {{ $movement->stockItem->category->name }}
                                                </div>
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $movement->direction->bgClass() }}">
                                                {{ $movement->direction->label() }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="font-medium {{ $movement->isOutgoing() ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                                {{ $movement->formatted_quantity }}
                                            </span>
                                            @if($movement->stockItem)
                                                <span class="text-slate-500 dark:text-slate-400 ml-1">{{ $movement->stockItem->unit }}</span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                            {{ $movement->reference_display }}
                                        </td>
                                        
                                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                            {{ $movement->creator?->name ?? 'Sistem' }}
                                        </td>
                                        
                                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                            {{ $movement->note ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $movements->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Hareket bulunamadı</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Belirtilen kriterlere uygun stok hareketi bulunmuyor.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>