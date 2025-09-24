<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Yeni Stok Kalemi</h2>
            <x-secondary-button-link href="{{ route('stock.items.index') }}">Listeye Don</x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-card class="space-y-6">
                <form method="POST" action="{{ route('stock.items.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" value="Kalem Adi" />
                            <x-text-input id="name" name="name" type="text" value="{{ old('name') }}" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category_id" value="Kategori" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <option value="">Kategori Secin</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sku" value="SKU" />
                            <x-text-input id="sku" name="sku" type="text" value="{{ old('sku') }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('sku')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="barcode" value="Barkod" />
                            <x-text-input id="barcode" name="barcode" type="text" value="{{ old('barcode') }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('barcode')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="unit" value="Birim" />
                            <x-text-input id="unit" name="unit" type="text" value="{{ old('unit', 'adet') }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="minimum_quantity" value="Minimum Miktar" />
                            <x-text-input id="minimum_quantity" name="minimum_quantity" type="number" step="0.01" value="{{ old('minimum_quantity') }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('minimum_quantity')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="quantity" value="Baslangic Miktari" />
                            <x-text-input id="quantity" name="quantity" type="number" step="0.01" value="{{ old('quantity') }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="allow_negative" value="1" class="rounded border-gray-300 dark:border-gray-700" @checked(old('allow_negative')) />
                                <span>Negatif stok izni</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 dark:border-gray-700" @checked(old('is_active', true)) />
                                <span>Aktif</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <x-secondary-button type="reset">Temizle</x-secondary-button>
                        <x-primary-button type="submit">Kaydet</x-primary-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>


