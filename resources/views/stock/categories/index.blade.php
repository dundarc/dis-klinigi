<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Stok Kategorileri</h2>
            <x-secondary-button-link href="{{ route('stock.items.index') }}">Stok Kalemleri</x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card class="space-y-4">
                <form method="POST" action="{{ route('stock.categories.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    @csrf
                    <div>
                        <x-input-label for="name" value="Kategori Adi" />
                        <x-text-input id="name" name="name" type="text" value="{{ old('name') }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="description" value="Aciklama" />
                        <x-text-input id="description" name="description" type="text" value="{{ old('description') }}" class="mt-1 block w-full" />
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button type="submit">Kategori Ekle</x-primary-button>
                    </div>
                </form>
            </x-card>

            <x-card class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Kategori</th>
                                <th class="px-4 py-2 text-left">Aciklama</th>
                                <th class="px-4 py-2 text-left">Kalem Sayisi</th>
                                <th class="px-4 py-2 text-right">Islemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($categories as $category)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $category->description ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $category->items_count }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <div x-data="{ open: false }" class="inline-flex flex-col items-end gap-2">
                                            <div class="flex gap-2">
                                                <button type="button" @click="open = !open" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">Duzenle</button>
                                                <form method="POST" action="{{ route('stock.categories.destroy', $category) }}" onsubmit="return confirm('Kategori silinsin mi?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button>Sil</x-danger-button>
                                                </form>
                                            </div>
                                            <div x-show="open" x-cloak class="w-full">
                                                <form method="POST" action="{{ route('stock.categories.update', $category) }}" class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <x-text-input name="name" type="text" value="{{ $category->name }}" class="w-full" required />
                                                    <x-text-input name="description" type="text" value="{{ $category->description }}" class="w-full" />
                                                    <div class="md:col-span-2 text-right">
                                                        <x-primary-button type="submit">Kaydet</x-primary-button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">Kategori bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

