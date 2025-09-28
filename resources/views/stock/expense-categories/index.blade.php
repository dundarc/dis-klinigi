<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">{{ __('Gider Kategorileri') }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Gider kategorilerini yönetin') }}</p>
            </div>
            <x-secondary-button-link href="{{ route('stock.expenses.index') }}">{{ __('Giderler') }}</x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <x-card class="space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('Yeni Kategori Ekle') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Yeni bir gider kategorisi oluşturun') }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('stock.expense-categories.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    @csrf
                    <div>
                        <x-input-label for="name" value="{{ __('Kategori Adı') }}" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="name" name="name" type="text" value="{{ old('name') }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button type="submit">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Kategori Ekle') }}
                        </x-primary-button>
                    </div>
                </form>
            </x-card>

            <x-card class="space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('Mevcut Kategoriler') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Toplam :count kategori', ['count' => $categories->total()]) }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-100 dark:bg-slate-800/70 text-slate-600 dark:text-slate-300">
                            <tr>
                                <th class="px-4 py-3 text-left">{{ __('Kategori') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('Gider Sayısı') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('Oluşturulma') }}</th>
                                <th class="px-4 py-3 text-right">{{ __('İşlemler') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($categories as $category)
                                <tr class="bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-slate-800 dark:text-slate-100">{{ $category->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $category->slug }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-1 text-xs font-medium text-slate-600 dark:text-slate-200">
                                            {{ $category->expenses_count }} {{ __('gider') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">
                                        {{ $category->created_at->format('d.m.Y') }}
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div x-data="{ open: false }" class="inline-flex flex-col items-end gap-2">
                                            <div class="flex gap-2">
                                                <a href="{{ route('stock.expense-categories.show', $category) }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    {{ __('Görüntüle') }}
                                                </a>
                                                <button type="button" @click="open = !open" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    {{ __('Düzenle') }}
                                                </button>
                                                @if($category->expenses_count == 0)
                                                    <form method="POST" action="{{ route('stock.expense-categories.destroy', $category) }}" onsubmit="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-danger-button>
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            {{ __('Sil') }}
                                                        </x-danger-button>
                                                    </form>
                                                @else
                                                    <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400" title="Bu kategoriye ait giderler bulunduğu için silinemez">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                        </svg>
                                                        {{ __('Korunan') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div x-show="open" x-transition x-cloak class="w-full mt-2 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700">
                                                <form method="POST" action="{{ route('stock.expense-categories.update', $category) }}" class="grid grid-cols-1 gap-3">
                                                    @csrf
                                                    @method('PUT')
                                                    <div>
                                                        <x-input-label for="edit_name_{{ $category->id }}" value="{{ __('Kategori Adı') }}" class="text-slate-600 dark:text-slate-300" />
                                                        <x-text-input id="edit_name_{{ $category->id }}" name="name" type="text" value="{{ $category->name }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" required />
                                                    </div>
                                                    <div class="flex justify-end gap-2">
                                                        <x-secondary-button type="button" @click="open = false">{{ __('İptal') }}</x-secondary-button>
                                                        <x-primary-button type="submit">{{ __('Kaydet') }}</x-primary-button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                            <p class="text-lg font-medium">{{ __('Kategori bulunamadı') }}</p>
                                            <p class="text-sm">{{ __('Henüz hiç gider kategorisi oluşturmadınız') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($categories->hasPages())
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>