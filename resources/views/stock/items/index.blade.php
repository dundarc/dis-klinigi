<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Stok Kalemleri</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Stok kalemlerinizi yönetin ve takip edin.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <!-- Export Buttons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('stock.items.export.pdf', request()->query()) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        PDF
                    </a>
                    <a href="{{ route('stock.items.export.excel', request()->query()) }}" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Excel
                    </a>
                    <a href="{{ route('stock.items.print', request()->query()) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Yazdır
                    </a>
                </div>
                <a href="{{ route('stock.bulk-movements') }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Toplu İşlem
                </a>
                <a href="{{ route('stock.items.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Yeni Kalem
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <x-card class="space-y-6" x-data="{ showCategoryForm: false }">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('stock.item_filter') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('stock.filter_description') }}</p>
                    </div>
                    <x-secondary-button type="button" @click="showCategoryForm = !showCategoryForm">
                        {{ __('stock.add_category') }}
                    </x-secondary-button>
                </div>

                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="q" value="İsim Ara" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="q" name="q" type="text" value="{{ $filters['q'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Kalem adı ile ara..." />
                    </div>
                    <div>
                        <x-input-label for="category" value="Kategori" class="text-slate-600 dark:text-slate-300" />
                        <select id="category" name="category" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Tüm Kategoriler</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="status" value="Stok Durumu" class="text-slate-600 dark:text-slate-300" />
                        <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Tüm Durumlar</option>
                            <option value="normal" @selected(($filters['status'] ?? '') === 'normal')>Normal</option>
                            <option value="critical" @selected(($filters['status'] ?? '') === 'critical')>Kritik</option>
                            <option value="negative" @selected(($filters['status'] ?? '') === 'negative')>Negatif</option>
                        </select>
                    </div>
                    <div class="col-span-full flex flex-wrap items-end justify-end gap-2">
                        <x-secondary-button type="button" onclick="window.location='{{ route('stock.items.index') }}';">
                            Sıfırla
                        </x-secondary-button>
                        <x-primary-button type="submit">Filtrele</x-primary-button>
                    </div>
                </form>

                <div x-show="showCategoryForm" x-transition class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 p-4">
                    <form method="POST" action="{{ route('stock.categories.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @csrf
                        <input type="hidden" name="return_to" value="{{ url()->current() }}">
                        <div class="sm:col-span-1">
                            <x-input-label for="item_category_name" value="{{ __('stock.category_name') }}" class="text-slate-600 dark:text-slate-300" />
                            <x-text-input id="item_category_name" name="name" type="text" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-1">
                            <x-input-label for="item_category_description" value="{{ __('stock.category_description') }}" class="text-slate-600 dark:text-slate-300" />
                            <x-text-input id="item_category_description" name="description" type="text" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" />
                        </div>
                        <div class="sm:col-span-1 flex items-end justify-end gap-2">
                            <x-secondary-button type="button" @click="showCategoryForm = false">{{ __('common.cancel') }}</x-secondary-button>
                            <x-primary-button type="submit">{{ __('stock.save_category') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </x-card>

            <x-card class="space-y-4">
                <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kalem Adı</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Mevcut Stok</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Birim</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kritik Seviye</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($items as $index => $item)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800' }} hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900 dark:text-slate-100">{{ $item->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $item->category?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ number_format($item->minimum_quantity, 2) }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-1">
                                            @if(!$item->is_active)
                                                <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-1 text-xs font-medium text-slate-800 dark:text-slate-200">Pasif</span>
                                            @elseif($item->quantity < 0)
                                                <span class="inline-flex items-center rounded-full bg-rose-100 dark:bg-rose-900/30 px-2.5 py-1 text-xs font-medium text-rose-800 dark:text-rose-200">Negatif</span>
                                            @elseif($item->isBelowMinimum())
                                                <span class="inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-900/30 px-2.5 py-1 text-xs font-medium text-amber-800 dark:text-amber-200">Kritik</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-1 text-xs font-medium text-emerald-800 dark:text-emerald-200">Normal</span>
                                            @endif
                                            <a href="{{ route('stock.items.show', $item) }}" class="inline-flex items-center p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Hareketleri Görüntüle">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('stock.items.edit', $item) }}" class="inline-flex items-center p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Düzenle">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('stock.items.destroy', $item) }}" onsubmit="return confirm('Bu kalemi silmek istediğinizden emin misiniz?');" class="inline">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Stok kalemi bulunmuyor</h3>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Henüz hiç stok kalemi eklenmemiş. Yeni bir kalem ekleyerek başlayın.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $items->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

