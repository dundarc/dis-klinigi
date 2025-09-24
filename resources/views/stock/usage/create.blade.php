<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Stok Kullanimi Kaydi</h2>
            @can('accessStockManagement')
                <x-secondary-button-link href="{{ route('stock.usage.index') }}">Kayitlari Gor</x-secondary-button-link>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-card class="space-y-6" x-data="{ lines: {{ json_encode(old('items', [['stock_item_id' => null, 'quantity' => 1, 'notes' => null]])) }} }">
                <form method="POST" action="{{ route('stock.usage.store') }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="used_at" value="Tarih" />
                            <x-text-input id="used_at" name="used_at" type="datetime-local" value="{{ old('used_at') }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="encounter_id" value="Ziyaret ID" />
                            <x-text-input id="encounter_id" name="encounter_id" type="number" value="{{ old('encounter_id') }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('encounter_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="patient_treatment_id" value="Tedavi ID" />
                            <x-text-input id="patient_treatment_id" name="patient_treatment_id" type="number" value="{{ old('patient_treatment_id') }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('patient_treatment_id')" class="mt-2" />
                        </div>
                        <div class="md:col-span-3">
                            <x-input-label for="notes" value="Notlar" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Kullanilan Kalemler</h3>
                        <x-secondary-button type="button" @click="lines.push({stock_item_id: null, quantity: 1, notes: ''})">Kalem Ekle</x-secondary-button>
                    </div>

                    <template x-for="(line, index) in lines" :key="index">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 border border-dashed border-gray-300 dark:border-gray-700 p-4 rounded-md">
                            <div class="md:col-span-2">
                                <x-input-label :value="'Kalem'" />
                                <select :name="`items[${index}][stock_item_id]`" x-model="line.stock_item_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                    <option value="">Seciniz</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ number_format($item->quantity, 2) }} {{ $item->unit }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label :value="'Miktar'" />
                                <x-text-input type="number" step="0.01" :name="`items[${index}][quantity]`" x-model="line.quantity" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <x-input-label :value="'Not'" />
                                <x-text-input type="text" :name="`items[${index}][notes]`" x-model="line.notes" class="mt-1 block w-full" />
                            </div>
                            <div class="md:col-span-4 text-right">
                                <button type="button" class="text-xs text-red-600" @click="lines.splice(index, 1)" x-show="lines.length > 1">Kaldir</button>
                            </div>
                        </div>
                    </template>
                    <x-input-error :messages="$errors->get('items')" class="mt-2" />

                    <div class="flex justify-end gap-2">
                        <x-secondary-button type="reset">Temizle</x-secondary-button>
                        <x-primary-button type="submit">Kaydet</x-primary-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>

