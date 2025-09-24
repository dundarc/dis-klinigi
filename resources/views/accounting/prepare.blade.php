<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Yeni Fatura Oluştur: Önizleme ve Düzenleme') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('accounting.store') }}" x-data="invoicePrepareManager({ initialItems: {{ $items->toJson() }} })">
                @csrf
                {{-- Formun göndereceği zorunlu alanlar --}}
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                {{-- DÜZELTME: Zorunlu olan fatura tarihini (issue_date) gizli bir alan olarak ekliyoruz. --}}
                <input type="hidden" name="issue_date" :value="new Date().toISOString().slice(0, 10)">

                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Hasta: {{ $patient->first_name }} {{ $patient->last_name }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">Aşağıdaki fatura kalemlerini düzenleyebilir veya yeni kalemler ekleyebilirsiniz.</p>

                    <div class="space-y-2">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Fatura Kalemleri</h4>
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex items-end gap-2 p-2 border rounded-md dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                                <input type="hidden" x-bind:name="`items[${index}][patient_treatment_id]`" x-bind:value="item.patient_treatment_id">
                                <div class="flex-grow">
                                    <x-input-label x-bind:for="`desc_${index}`" value="Açıklama"/>
                                    <x-text-input x-bind:id="`desc_${index}`" type="text" x-bind:name="`items[${index}][description]`" class="w-full mt-1" x-model="item.description" />
                                </div>
                                <div class="w-20">
                                    <x-input-label x-bind:for="`qty_${index}`" value="Adet"/>
                                    <x-text-input x-bind:id="`qty_${index}`" type="number" x-bind:name="`items[${index}][qty]`" class="w-full mt-1" x-model.number="item.qty" />
                                </div>
                                <div class="w-28">
                                    <x-input-label x-bind:for="`price_${index}`" value="Birim Fiyat"/>
                                    <x-text-input x-bind:id="`price_${index}`" type="number" step="0.01" x-bind:name="`items[${index}][unit_price]`" class="w-full mt-1" x-model.number="item.unit_price" />
                                </div>
                                <input type="hidden" x-bind:name="`items[${index}][vat]`" x-bind:value="item.vat">
                                <x-danger-button type="button" @click="items.splice(index, 1)">X</x-danger-button>
                            </div>
                        </template>
                    </div>

                    <x-secondary-button type="button" @click="items.push({ description: '', qty: 1, unit_price: 0, vat: 20, patient_treatment_id: null })" class="mt-2">
                        Yeni Kalem Ekle
                    </x-secondary-button>

                    <div class="text-right font-semibold border-t pt-4 mt-4 dark:border-gray-700">
                        <p>Ara Toplam: <span x-text="formatCurrency(subtotal)"></span></p>
                        <p>KDV Toplamı: <span x-text="formatCurrency(vatTotal)"></span></p>
                        <p class="text-xl">Genel Toplam: <span x-text="formatCurrency(grandTotal)"></span></p>
                    </div>
                    
                    <div class="flex items-center justify-end mt-6 border-t pt-4 dark:border-gray-700">
                        <a href="{{ route('accounting.new') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Geri</a>
                        <x-primary-button class="ms-4">Faturayı Kaydet</x-primary-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
    @push('scripts')
    <script>
        function invoicePrepareManager(config) {
            return {
                items: config.initialItems || [],
                get subtotal() {
                    return this.items.reduce((total, item) => total + (item.qty * item.unit_price), 0);
                },
                get vatTotal() {
                    return this.items.reduce((total, item) => total + (item.qty * item.unit_price * ((item.vat || 20) / 100)), 0);
                },
                get grandTotal() {
                    return this.subtotal + this.vatTotal;
                },
                formatCurrency(value) {
                    return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(value);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>

