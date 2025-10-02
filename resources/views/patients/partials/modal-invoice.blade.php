<x-modal name="invoice-form-modal" :show="$errors->any()" max-width="3xl">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100" 
            x-text="invoice.id ? 'Faturayı Düzenle' : 'Yeni Fatura Oluştur'"></h2>

        <form @submit.prevent="submitInvoice" class="mt-6 space-y-4">
            <!-- Yeni fatura oluşturuluyorsa -->
            <div x-show="!invoice.id">
                <x-input-label value="Faturalandırılacak Tamamlanmış Tedaviler" />
                <div class="mt-2 space-y-2 max-h-60 overflow-y-auto border p-2 rounded-md dark:border-gray-600">
                    @forelse($uninvoicedTreatments as $ut)
                        <label class="flex items-center">
                            <input type="checkbox" value="{{ $ut->id }}" 
                                   x-model="invoice.treatment_ids" 
                                   class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ $ut->display_treatment_name }} ({{ number_format($ut->unit_price, 2) }} TL)
                            </span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500">Faturalandırılacak yeni tedavi bulunmuyor.</p>
                    @endforelse
                </div>
            </div>

            <!-- Mevcut fatura düzenleniyorsa -->
            <div x-show="invoice.id" class="space-y-2">
                <h4 class="font-semibold">Fatura Kalemleri</h4>
                <template x-for="(item, index) in invoice.items" :key="index">
                    <div class="flex items-end gap-2 p-2 border rounded-md dark:border-gray-700">
                        <div class="flex-grow">
                            <x-input-label value="Açıklama"/>
                            <input x-model="item.description" type="text" 
                                   class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="w-20">
                            <x-input-label value="Adet"/>
                            <input x-model.number="item.quantity" type="number"
                                   class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="w-28">
                            <x-input-label value="Birim Fiyat"/>
                            <input x-model.number="item.unit_price" type="number" step="0.01" 
                                   class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        </div>
                        <x-danger-button type="button" @click="invoice.items.splice(index, 1)">Sil</x-danger-button>
                    </div>
                </template>
                <x-secondary-button type="button"
                                    @click="invoice.items.push({description: '', quantity: 1, unit_price: 0, vat_rate: 20})">
                    Yeni Kalem Ekle
                </x-secondary-button>
            </div>

            <!-- Butonlar -->
            <div class="mt-6 flex justify-end">
                <x-secondary-button type="button" @click="$dispatch('close')">İptal</x-secondary-button>
                <x-primary-button class="ms-3" x-text="invoice.id ? 'Güncelle' : 'Oluştur'"></x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
