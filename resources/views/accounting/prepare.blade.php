<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Yeni Fatura Oluştur: Önizleme ve Düzenleme') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('accounting.store') }}" x-data="invoicePrepareManager({ initialItems: {{ $items->toJson() }}, selectedStatus: 'draft' })">
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

                    <!-- Invoice Status Selection -->
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border dark:border-gray-700">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Fatura Durumu</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="draft" class="text-blue-600 focus:ring-blue-500" checked>
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Taslak (DRAFT)</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Fatura düzenlenebilir kalır</p>
                            </div>
                            <div>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="unpaid" class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Ödenmedi (UNPAID)</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Fatura yayınlanır, ödeme beklenir</p>
                            </div>
                            <div>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="vadeli" class="text-blue-600 focus:ring-blue-500" x-model="selectedStatus">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Vadeli (POSTPONED)</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Vade tarihi belirlenir</p>
                            </div>
                        </div>

                        <!-- Due Date Field - Only show when POSTPONED is selected -->
                        <div class="mt-4" x-show="selectedStatus === 'vadeli'" x-transition>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vade Tarihi</label>
                            <input type="date" id="due_date" name="due_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" :required="selectedStatus === 'vadeli'" :min="new Date().toISOString().slice(0, 10)">
                            <p class="text-xs text-gray-500 mt-1">Faturanın ödenmesi gereken tarih</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Fatura Kalemleri</h4>
                        <template x-for="(item, index) in items" :key="index">
                            <div class="p-3 border rounded-md dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                                <input type="hidden" x-bind:name="`items[${index}][patient_treatment_id]`" x-bind:value="item.patient_treatment_id">
                                @if(isset($treatments) && $treatments->where('id', $item['patient_treatment_id'])->first()?->treatmentPlanItem?->treatmentPlan)
                                    @php
                                        $treatment = $treatments->where('id', $item['patient_treatment_id'])->first();
                                        $treatmentPlan = $treatment->treatmentPlanItem->treatmentPlan;
                                    @endphp
                                    <div class="mb-2">
                                        <span class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/30 px-2 py-1 text-xs font-medium text-purple-800 dark:text-purple-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Tedavi Planı #{{ $treatmentPlan->id }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex items-end gap-2">
                                    <div class="flex-grow">
                                        <x-input-label x-bind:for="`desc_${index}`" value="Açıklama"/>
                                        <x-text-input x-bind:id="`desc_${index}`" type="text" x-bind:name="`items[${index}][description]`" class="w-full mt-1" x-model="item.description" />
                                    </div>
                                    <div class="w-20">
                                        <x-input-label x-bind:for="`quantity_${index}`" value="Adet"/>
                                        <x-text-input x-bind:id="`quantity_${index}`" type="number" x-bind:name="`items[${index}][quantity]`" class="w-full mt-1" x-model.number="item.quantity" />
                                    </div>
                                    <div class="w-28">
                                        <x-input-label x-bind:for="`price_${index}`" value="Birim Fiyat"/>
                                        <x-text-input x-bind:id="`price_${index}`" type="number" step="0.01" x-bind:name="`items[${index}][unit_price]`" class="w-full mt-1" x-model.number="item.unit_price" />
                                    </div>
                                    <input type="hidden" x-bind:name="`items[${index}][vat_rate]`" x-bind:value="item.vat_rate ?? {{ config('accounting.vat_rate') * 100 }}">
                                    <x-danger-button type="button" @click="items.splice(index, 1)">X</x-danger-button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <x-secondary-button type="button" @click="items.push({ description: '', quantity: 1, unit_price: 0, vat_rate: {{ config('accounting.vat_rate') * 100 }}, patient_treatment_id: null })" class="mt-2">
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
                selectedStatus: config.selectedStatus || 'draft',
                items: config.initialItems || [],
                get subtotal() {
                    return this.items.reduce((total, item) => total + (item.quantity * item.unit_price), 0);
                },
                get vatTotal() {
                    return this.items.reduce((total, item) => total + (item.quantity * item.unit_price * ((item.vat_rate || {{ config('accounting.vat_rate') * 100 }}) / 100)), 0);
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

