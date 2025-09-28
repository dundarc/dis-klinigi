<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Yeni Randevu Ekle</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Hasta için yeni randevu oluşturun</p>
            </div>
            <a href="{{ route('waiting-room.appointments') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Listeye Dön
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Randevu Bilgileri</h3>
                </div>

                <form method="POST" action="{{ route('waiting-room.appointments.store') }}" x-data="appointmentForm()" class="p-6 space-y-6">
                    @csrf

                    <!-- Patient Search -->
                    <div x-data="{ open: false }" class="relative">
                        <label for="patient_search" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hasta <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative">
                            <input type="text"
                                   x-model="query"
                                   @input="search()"
                                   @focus="open = true"
                                   @blur="setTimeout(() => open = false, 200)"
                                   class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-4 pr-10"
                                   placeholder="Hasta adı ile ara..."
                                   autocomplete="off">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <input type="hidden" name="patient_id" x-model="selectedPatientId">

                        <!-- Search Results Dropdown -->
                        <div x-show="open && results && results.length > 0"
                             x-transition
                             class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-800 shadow-lg max-h-60 rounded-lg py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm border border-slate-200 dark:border-slate-700">
                            <template x-for="(patient, index) in results" :key="'patient-' + (patient?.id || index)">
                                <div @click="selectPatient(patient)"
                                     class="cursor-pointer select-none relative py-3 pl-4 pr-9 hover:bg-slate-100 dark:hover:bg-slate-700">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-medium block truncate text-slate-900 dark:text-slate-100" x-text="patient?.name || ''"></span>
                                            <span class="text-slate-500 dark:text-slate-400 text-sm" x-text="patient?.phone || ''"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Selected Patient Display -->
                        <div x-show="selectedPatient" class="mt-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-blue-900 dark:text-blue-100" x-text="selectedPatient?.name || ''"></div>
                                        <div class="text-sm text-blue-700 dark:text-blue-300" x-text="selectedPatient?.phone || ''"></div>
                                    </div>
                                </div>
                                <button type="button" @click="clearSelection()" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                    </div>

                    <!-- Treatment Plan Items -->
                    <div x-show="selectedPatient" x-transition class="space-y-4">
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                            <div class="flex items-center justify-between mb-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tedavi Planından İşlemler</label>
                                <button type="button" @click="loadTreatmentPlanItems()" :disabled="loadingTreatmentPlan" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white text-sm font-medium rounded transition-colors">
                                    <span x-show="!loadingTreatmentPlan">Yenile</span>
                                    <span x-show="loadingTreatmentPlan" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Yükleniyor...
                                    </span>
                                </button>
                            </div>

                            <!-- Treatment Plan Items List -->
                            <div x-show="treatmentPlanItems.length > 0" class="space-y-3">
                                <div class="text-sm text-slate-600 dark:text-slate-400 mb-3">
                                    Bu hastanın aktif tedavi planlarından randevuya bağlanabilecek işlemler:
                                </div>
                                <template x-for="item in treatmentPlanItems" :key="item.id">
                                    <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                                        <div class="flex items-center gap-3 flex-1">
                                            <input :id="'item-' + item.id" :name="'treatment_plan_items[]'" :value="item.id" type="checkbox" class="h-4 w-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium text-slate-900 dark:text-slate-100" x-text="item.treatment_name"></span>
                                                    <span x-show="item.tooth_number" class="px-2 py-1 bg-slate-200 dark:bg-slate-700 rounded text-xs">#<span x-text="item.tooth_number"></span></span>
                                                    <span class="px-2 py-1 rounded text-xs font-medium"
                                                          :class="{
                                                              'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200': item.status_color === 'gray',
                                                              'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200': item.status_color === 'blue',
                                                              'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200': item.status_color === 'green',
                                                              'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200': item.status_color === 'purple'
                                                          }"
                                                          x-text="item.status_label"></span>
                                                </div>
                                                <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                                    Plan: <span x-text="item.treatment_plan_title"></span>
                                                    <span x-show="item.estimated_price" class="ml-2">• Tahmini: <span x-text="item.estimated_price"></span> ₺</span>
                                                </div>
                                                <div x-show="item.existing_appointment" class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                                                    ⚠️ Zaten planlanmış: <span x-text="item.existing_appointment.date"></span> (<span x-text="item.existing_appointment.dentist"></span>)
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- No Treatment Plan Items -->
                            <div x-show="treatmentPlanItems.length === 0 && !loadingTreatmentPlan" class="text-center py-8 text-slate-500 dark:text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p>Bu hastanın aktif tedavi planı bulunmuyor veya tüm işlemler tamamlanmış.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dentist Selection -->
                    <div>
                        <label for="dentist_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hekim <span class="text-red-500">*</span></label>
                        <select id="dentist_id" name="dentist_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Hekim Seçin --</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('dentist_id')" class="mt-2" />
                    </div>

                    <!-- Date and Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_at" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Randevu Başlangıç <span class="text-red-500">*</span></label>
                            <input id="start_at" name="start_at" type="datetime-local" :value="old('start_at')" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                            <x-input-error :messages="$errors->get('start_at')" class="mt-2" />
                        </div>
                        <div>
                            <label for="end_at" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Randevu Bitiş <span class="text-red-500">*</span></label>
                            <input id="end_at" name="end_at" type="datetime-local" :value="old('end_at')" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                            <x-input-error :messages="$errors->get('end_at')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notlar</label>
                        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Randevu ile ilgili notlar...">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('waiting-room.appointments') }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <button type="submit" class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Randevu Oluştur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function appointmentForm() {
            return {
                // Patient search properties
                query: '',
                results: [],
                selectedPatient: null,
                selectedPatientId: '',
                searchTimeout: null,

                // Treatment plan properties
                treatmentPlanItems: [],
                loadingTreatmentPlan: false,

                search() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        if (this.query.length > 2) { // Minimum 3 characters
                            fetch(`/patients/search?q=${encodeURIComponent(this.query)}`)
                                .then(response => response.json())
                                .then(data => {
                                    this.results = Array.isArray(data.data) ? data.data : [];
                                })
                                .catch(error => {
                                    console.error('Patient search error:', error);
                                    this.results = [];
                                });
                        } else {
                            this.results = [];
                        }
                    }, 300); // 300ms debounce
                },

                selectPatient(patient) {
                    if (patient) {
                        this.selectedPatient = patient;
                        this.selectedPatientId = patient.id;
                        this.query = patient.name;
                        this.results = [];
                        // Auto-load treatment plan items when patient is selected
                        this.loadTreatmentPlanItems();
                    }
                },

                clearSelection() {
                    this.selectedPatient = null;
                    this.selectedPatientId = '';
                    this.query = '';
                    this.results = [];
                    this.treatmentPlanItems = [];
                },

                loadTreatmentPlanItems() {
                    if (!this.selectedPatientId) return;

                    this.loadingTreatmentPlan = true;
                    fetch(`/waiting-room/patient-treatment-plan-items?patient_id=${this.selectedPatientId}`)
                        .then(response => response.json())
                        .then(data => {
                            this.treatmentPlanItems = data.items || [];
                        })
                        .catch(error => {
                            console.error('Treatment plan items error:', error);
                            this.treatmentPlanItems = [];
                        })
                        .finally(() => {
                            this.loadingTreatmentPlan = false;
                        });
                }
            }
        }
    </script>
</x-app-layout>
