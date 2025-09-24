<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Yeni Stok Faturasi</h2>
            <x-secondary-button-link href="{{ route('stock.purchases.index') }}">Listeye Don</x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-card class="space-y-6" x-data="{ mode: @js(old('mode', 'manual')), items: @js(old('items', [['stock_item_id' => null, 'description' => '', 'quantity' => 1, 'unit' => 'adet', 'unit_price' => 0, 'vat_rate' => 0, 'create_item' => false]])) }" x-init="mode = mode || 'manual'">
                <form method="POST" action="{{ route('stock.purchases.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="supplier_id" value="Tedarikci" />
                            <select id="supplier_id" name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <option value="">Seciniz</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="invoice_number" value="Fatura No" />
                            <x-text-input id="invoice_number" name="invoice_number" type="text" value="{{ old('invoice_number') }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="invoice_date" value="Fatura Tarihi" />
                            <x-text-input id="invoice_date" name="invoice_date" type="date" value="{{ old('invoice_date', now()->toDateString()) }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="due_date" value="Vade" />
                            <x-text-input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="payment_status" value="Odeme Durumu" />
                            <select id="payment_status" name="payment_status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <option value="pending" @selected(old('payment_status') === 'pending')>Bekleyen</option>
                                <option value="partial" @selected(old('payment_status') === 'partial')>Kismi</option>
                                <option value="paid" @selected(old('payment_status') === 'paid')>Odendi</option>
                                <option value="overdue" @selected(old('payment_status') === 'overdue')>Gecikmis</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="payment_method" value="Odeme Yontemi" />
                            <x-text-input id="payment_method" name="payment_method" type="text" value="{{ old('payment_method') }}" class="mt-1 block w-full" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="notes" value="Notlar" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="mode" value="manual" x-model="mode" class="mr-2" {{ old('mode', 'manual') === 'manual' ? 'checked' : '' }} />
                            <span>Manuel Giris</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="mode" value="upload" x-model="mode" class="mr-2" {{ old('mode') === 'upload' ? 'checked' : '' }} />
                            <span>PDF Yukle</span>
                        </label>
                    </div>

                    <div x-show="mode === 'upload'" :class="mode === 'upload' ? '' : 'hidden'">
                        <x-input-label for="file" value="Fatura PDF" />
                        <input id="file" name="file" type="file" accept="application/pdf" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700" />
                        <p class="mt-2 text-xs text-gray-500">PDF otomatik olarak sisteme eklenir ve daha sonra duzenlenebilir.</p>
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>

                    <div x-show="mode === 'manual'" :class="mode === 'manual' ? '' : 'hidden'" class="space-y-4">
                        <div class="flex justify-end">
                            <x-secondary-button type="button" @click="items.push({stock_item_id: null, description: '', quantity: 1, unit: 'adet', unit_price: 0, vat_rate: 0, create_item: false})">Kalem Ekle</x-secondary-button>
                        </div>
                        <template x-for="(line, index) in items" :key="index">
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-3 border border-dashed border-gray-300 dark:border-gray-700 p-4 rounded-md">
                                <div class="md:col-span-2">
                                    <x-input-label :value="'Stok Kalemi'" />
                                    <select :name="`items[${index}][stock_item_id]`" x-model="line.stock_item_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                        <option value="">Yeni Kalem</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label :value="'Aciklama'" />
                                    <x-text-input type="text" x-bind:name="'items[' + index + '][description]'" x-model="line.description" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label :value="'Miktar'" />
                                    <x-text-input type="number" step="0.01" x-bind:name="'items[' + index + '][quantity]'" x-model="line.quantity" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label :value="'Birim'" />
                                    <x-text-input type="text" x-bind:name="'items[' + index + '][unit]'" x-model="line.unit" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label :value="'Birim Fiyat'" />
                                    <x-text-input type="number" step="0.01" x-bind:name="'items[' + index + '][unit_price]'" x-model="line.unit_price" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label :value="'KDV %'" />
                                    <x-text-input type="number" step="0.01" x-bind:name="'items[' + index + '][vat_rate]'" x-model="line.vat_rate" class="mt-1 block w-full" />
                                </div>
                                <div class="md:col-span-6 flex flex-wrap items-center justify-between gap-4">
                                    <label class="inline-flex items-center text-xs text-gray-500">
                                        <input type="checkbox" x-bind:name="'items[' + index + '][create_item]'" value="1" x-model="line.create_item" class="mr-2 rounded border-gray-300 dark:border-gray-700" />
                                        Stokta yoksa yeni kalem olustur
                                    </label>
                                    <button type="button" class="text-xs text-red-600" @click="items.splice(index, 1)" x-show="items.length > 1">Kaldir</button>
                                </div>
                            </div>
                        </template>
                        <x-input-error :messages="$errors->get('items')" class="mt-2" />
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

