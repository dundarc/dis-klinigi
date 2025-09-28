<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Yeni Tedavi Planı</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $patient->first_name }} {{ $patient->last_name }} için tedavi planı oluşturun</p>
            </div>
            <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Hasta Detayına Dön
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Tedavi Planı Bilgileri</h3>
                </div>

                <form method="POST" action="{{ route('treatment-plans.store') }}" x-data="treatmentPlanForm()" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                    <!-- Plan Header -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="dentist_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Sorumlu Diş Hekimi <span class="text-red-500">*</span></label>
                            <select id="dentist_id" name="dentist_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">Diş hekimi seçin...</option>
                                @foreach($dentists as $dentist)
                                    <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('dentist_id')" class="mt-2" />
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Plan Notları</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Tedavi planı ile ilgili genel notlar...">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Treatment Items Section -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Tedavi Kalemleri</h4>
                            <button type="button" @click="addItem()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tedavi Ekle
                            </button>
                        </div>

                        <!-- Items List -->
                        <div class="space-y-4" x-show="items.length > 0">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                        <!-- Treatment Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tedavi <span class="text-red-500">*</span></label>
                                            <select x-bind:name="'items[' + index + '][treatment_id]'" x-model="item.treatment_id" @change="updatePrice(index)" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                                <option value="">Tedavi seçin...</option>
                                                @foreach($treatments as $treatment)
                                                    <option value="{{ $treatment->id }}" data-price="{{ $treatment->default_price }}">{{ $treatment->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Tooth Number -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Diş No</label>
                                            <input type="text" x-bind:name="'items[' + index + '][tooth_number]'" x-model="item.tooth_number" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="örn: 11, 12-13">
                                        </div>

                                        <!-- Appointment Date (Optional) -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Randevu Tarihi</label>
                                            <input type="datetime-local" x-bind:name="'items[' + index + '][appointment_date]'" x-model="item.appointment_date" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Opsiyonel - boş bırakabilirsiniz</p>
                                        </div>

                                        <!-- Price (Auto-filled) -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ücret (TL)</label>
                                            <input type="number" step="0.01" x-bind:name="'items[' + index + '][estimated_price]'" x-model="item.estimated_price" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly>
                                        </div>

                                        <!-- Remove Button -->
                                        <div class="flex items-end">
                                            <button type="button" @click="removeItem(index)" class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Empty State -->
                        <div x-show="items.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Henüz tedavi kalemi eklenmemiş</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">"Tedavi Ekle" butonuna tıklayarak tedavi kalemleri ekleyebilirsiniz.</p>
                        </div>

                        <!-- Total Cost Display -->
                        <div x-show="items.length > 0" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="text-sm font-medium text-blue-900 dark:text-blue-100">Tahmini Toplam Maliyet</h5>
                                    <p class="text-xs text-blue-700 dark:text-blue-300">Seçilen tedavilerin toplam ücreti</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-bold text-blue-900 dark:text-blue-100" x-text="formatCurrency(totalCost)"></span>
                                    <span class="text-sm text-blue-700 dark:text-blue-300 ml-1">TL</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('patients.show', $patient) }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <button type="submit" class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Tedavi Planını Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function treatmentPlanForm() {
            return {
                items: [{
                    treatment_id: '',
                    tooth_number: '',
                    appointment_date: '',
                    estimated_price: 0.00
                }],
                treatments: @json($treatments->keyBy('id')),

                get totalCost() {
                    return this.items.reduce((total, item) => total + (parseFloat(item.estimated_price) || 0), 0);
                },

                addItem() {
                    this.items.push({
                        treatment_id: '',
                        tooth_number: '',
                        appointment_date: '',
                        estimated_price: 0.00
                    });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                updatePrice(index) {
                    const treatmentId = this.items[index].treatment_id;
                    if (treatmentId && this.treatments[treatmentId]) {
                        this.items[index].estimated_price = parseFloat(this.treatments[treatmentId].default_price) || 0.00;
                    } else {
                        this.items[index].estimated_price = 0.00;
                    }
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('tr-TR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(amount);
                }
            }
        }
    </script>
</x-app-layout>
