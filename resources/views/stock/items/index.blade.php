<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Stok Kalemleri</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Stok kartlarini yonetin, kategorilere ayirin ve durumlarini takip edin.</p>
            </div>
            <x-primary-button-link href="{{ route('stock.items.create') }}">Yeni Kalem</x-primary-button-link>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <x-card class="space-y-6" x-data="{ showCategoryForm: false }">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">Kalem Filtresi</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Kategoriye, duruma veya arama terimine gore listeyi daraltin.</p>
                    </div>
                    <x-secondary-button type="button" @click="showCategoryForm = !showCategoryForm">
                        Kategori Ekle
                    </x-secondary-button>
                </div>

                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
                    <div>
                        <x-input-label for="q" value="Arama" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="q" name="q" type="text" value="{{ $filters['q'] ?? '' }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" placeholder="Kalem adi, SKU veya barkod" />
                    </div>
                    <div>
                        <x-input-label for="category" value="Kategori" class="text-slate-600 dark:text-slate-300" />
                        <select id="category" name="category" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Tum Kategoriler</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="status" value="Durum" class="text-slate-600 dark:text-slate-300" />
                        <select id="status" name="status" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Tum Durumlar</option>
                            <option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option>
                            <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Pasif</option>
                            <option value="critical" @selected(($filters['status'] ?? '') === 'critical')>Kritik</option>
                            <option value="negative" @selected(($filters['status'] ?? '') === 'negative')>Negatif</option>
                        </select>
                    </div>
                    <div class="col-span-full flex flex-wrap items-end justify-end gap-2">
                        <x-secondary-button type="button" onclick="window.location='{{ route('stock.items.index') }}';">
                            Sifirla
                        </x-secondary-button>
                        <x-primary-button type="submit">Filtrele</x-primary-button>
                    </div>
                </form>

                <div x-show="showCategoryForm" x-transition class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 p-4">
                    <form method="POST" action="{{ route('stock.categories.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @csrf
                        <input type="hidden" name="return_to" value="{{ url()->current() }}">
                        <div class="sm:col-span-1">
                            <x-input-label for="item_category_name" value="Kategori Adi" class="text-slate-600 dark:text-slate-300" />
                            <x-text-input id="item_category_name" name="name" type="text" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-1">
                            <x-input-label for="item_category_description" value="Aciklama" class="text-slate-600 dark:text-slate-300" />
                            <x-text-input id="item_category_description" name="description" type="text" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" />
                        </div>
                        <div class="sm:col-span-1 flex items-end justify-end gap-2">
                            <x-secondary-button type="button" @click="showCategoryForm = false">Vazgec</x-secondary-button>
                            <x-primary-button type="submit">Kategori Kaydet</x-primary-button>
                        </div>
                    </form>
                </div>
            </x-card>

            <x-card class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-100 dark:bg-slate-800/70 text-slate-600 dark:text-slate-300">
                            <tr>
                                <th class="px-4 py-2 text-left">Kalem</th>
                                <th class="px-4 py-2 text-left">Kategori</th>
                                <th class="px-4 py-2 text-left">Miktar</th>
                                <th class="px-4 py-2 text-left">Minimum</th>
                                <th class="px-4 py-2 text-left">Durum</th>
                                <th class="px-4 py-2 text-right">Islemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($items as $item)
                                <tr class="bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-800 dark:text-slate-100">{{ $item->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">SKU: {{ $item->sku ?? '-' }} - Barkod: {{ $item->barcode ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $item->category?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ number_format($item->minimum_quantity, 2) }} {{ $item->unit }}</td>
                                    <td class="px-4 py-3">
                                        @if(!$item->is_active)
                                            <span class="inline-flex items-center rounded-full bg-slate-200 dark:bg-slate-700 px-2.5 py-1 text-xs font-medium text-slate-700 dark:text-slate-200">Pasif</span>
                                        @elseif($item->quantity < 0)
                                            <span class="inline-flex items-center rounded-full bg-rose-100 dark:bg-rose-500/20 px-2.5 py-1 text-xs font-medium text-rose-600 dark:text-rose-200">Negatif</span>
                                        @elseif($item->isBelowMinimum())
                                            <span class="inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-500/20 px-2.5 py-1 text-xs font-medium text-amber-600 dark:text-amber-200">Kritik</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-500/20 px-2.5 py-1 text-xs font-medium text-emerald-600 dark:text-emerald-200">Uygun</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <x-secondary-button-link href="{{ route('stock.items.edit', $item) }}">Duzenle</x-secondary-button-link>
                                            <form method="POST" action="{{ route('stock.items.destroy', $item) }}" onsubmit="return confirm('Bu kalemi silmek istiyor musunuz?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button>Sil</x-danger-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">Kayit bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

