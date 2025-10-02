<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Acil Hasta Kaydı</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Yeni acil hasta kaydı oluşturun</p>
            </div>
            <a href="{{ route('waiting-room.emergency') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
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
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Hasta Bilgileri</h3>
                </div>

                <form method="POST" action="{{ route('waiting-room.emergency.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Patient Live Search -->
                    <div x-data="patientSearch()" class="relative">
                        <label for="patient_search" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hasta Ara <span class="text-red-500">*</span></label>

                        <!-- Search Input -->
                        <div class="relative mt-1">
                            <input type="text"
                                   id="patient_search"
                                   x-model="searchQuery"
                                   @input.debounce.300ms="searchPatients"
                                   @focus="showDropdown = true"
                                   @keydown.escape="showDropdown = false"
                                   @keydown.arrow-down.prevent="focusNextResult"
                                   @keydown.arrow-up.prevent="focusPreviousResult"
                                   @keydown.enter.prevent="selectHighlightedResult"
                                   placeholder="Hasta adı, soyadı veya TC kimlik no ile arama yapın..."
                                   class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-10"
                                   autocomplete="off">

                            <!-- Search Icon -->
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Hidden Patient ID Field -->
                        <input type="hidden" name="patient_id" x-model="selectedPatientId" required>

                        <!-- Selected Patient Display -->
                        <div x-show="selectedPatient" x-transition class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                            <span x-text="selectedPatient?.full_name || 'Bilinmeyen Hasta'"></span>
                                        </div>
                                        <div class="text-xs text-blue-700 dark:text-blue-300">
                                            TC: <span x-text="selectedPatient?.national_id || 'N/A'"></span>
                                            <span x-show="selectedPatient?.phone"> • Tel: <span x-text="selectedPatient?.phone"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" @click="clearSelection" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Search Results Dropdown -->
                        <div x-show="showDropdown && (searchResults.length > 0 || isSearching)"
                             x-transition
                             class="absolute z-50 mt-1 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg shadow-xl max-h-64 overflow-y-auto">

                            <!-- Loading State -->
                            <div x-show="isSearching" class="p-4 text-center text-slate-500 dark:text-slate-400">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Aranıyor...
                                </div>
                            </div>

                            <!-- Search Results -->
                            <div x-show="!isSearching">
                                <template x-for="(patient, index) in searchResults" :key="patient?.id || index">
                                    <div @click="selectPatient(patient)"
                                         :class="{
                                             'bg-blue-50 dark:bg-blue-900/20': highlightedIndex === index,
                                             'hover:bg-slate-50 dark:hover:bg-slate-700/50': highlightedIndex !== index
                                         }"
                                         class="p-3 cursor-pointer border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                                                    <span x-text="patient?.full_name?.charAt(0)?.toUpperCase() || '?'"></span>
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-slate-900 dark:text-slate-100" x-text="patient?.full_name || 'Bilinmeyen Hasta'"></div>
                                                <div class="text-xs text-slate-600 dark:text-slate-400">
                                                    TC: <span x-text="patient?.national_id || 'N/A'"></span>
                                                    <span x-show="patient?.phone"> • Tel: <span x-text="patient?.phone"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- No Results -->
                                <div x-show="searchResults.length === 0 && searchQuery.length >= 2" class="p-4 text-center text-slate-500 dark:text-slate-400">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-.966-5.5-2.5"></path>
                                    </svg>
                                    Hasta bulunamadı
                                </div>
                            </div>
                        </div>

                        <!-- Minimum Characters Message -->
                        <div x-show="searchQuery.length > 0 && searchQuery.length < 2 && !selectedPatient"
                             class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            En az 2 karakter girin
                        </div>

                        <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />

                        <!-- Create New Patient Link -->
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                            Hasta listede yoksa, önce <a href="{{ route('patients.create') }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">Hastalar ekranından</a> yeni bir kayıt oluşturun.
                        </p>
                    </div>

                    <script>
                        function patientSearch() {
                            return {
                                searchQuery: '',
                                searchResults: [],
                                selectedPatient: null,
                                selectedPatientId: '{{ old('patient_id') }}',
                                showDropdown: false,
                                isSearching: false,
                                highlightedIndex: -1,
                                searchTimeout: null,

                                init() {
                                    // If we have an old patient_id, load the patient data
                                    if (this.selectedPatientId) {
                                        this.loadSelectedPatient();
                                    }
                                },

                                async searchPatients() {
                                    if (this.searchQuery.length < 2) {
                                        this.searchResults = [];
                                        this.showDropdown = false;
                                        return;
                                    }

                                    this.isSearching = true;
                                    this.showDropdown = true;
                                    this.highlightedIndex = -1;

                                    try {
                                        const response = await fetch(`/api/v1/patients/search?query=${encodeURIComponent(this.searchQuery)}`, {
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest',
                                                'Accept': 'application/json',
                                            }
                                        });

                                        if (response.ok) {
                                            const data = await response.json();
                                            this.searchResults = (data.data || []).filter(patient => patient && patient.id);
                                        } else {
                                            this.searchResults = [];
                                        }
                                    } catch (error) {
                                        console.error('Search error:', error);
                                        this.searchResults = [];
                                    } finally {
                                        this.isSearching = false;
                                    }
                                },

                                selectPatient(patient) {
                                    if (!patient || !patient.id) return;

                                    this.selectedPatient = patient;
                                    this.selectedPatientId = patient.id;
                                    this.searchQuery = patient.full_name || '';
                                    this.showDropdown = false;
                                    this.searchResults = [];
                                    this.highlightedIndex = -1;
                                },

                                clearSelection() {
                                    this.selectedPatient = null;
                                    this.selectedPatientId = '';
                                    this.searchQuery = '';
                                    this.showDropdown = false;
                                    this.searchResults = [];
                                    this.highlightedIndex = -1;
                                },

                                async loadSelectedPatient() {
                                    if (!this.selectedPatientId) return;

                                    try {
                                        // You might want to create an endpoint to get patient by ID
                                        // For now, we'll just set a placeholder
                                        this.selectedPatient = {
                                            id: this.selectedPatientId,
                                            full_name: 'Seçili Hasta',
                                            national_id: 'Yükleniyor...',
                                            phone: null
                                        };
                                    } catch (error) {
                                        console.error('Load patient error:', error);
                                    }
                                },

                                focusNextResult() {
                                    if (this.searchResults.length > 0) {
                                        this.highlightedIndex = Math.min(this.highlightedIndex + 1, this.searchResults.length - 1);
                                    }
                                },

                                focusPreviousResult() {
                                    if (this.searchResults.length > 0) {
                                        this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
                                    }
                                },

                                selectHighlightedResult() {
                                    if (this.highlightedIndex >= 0 && this.highlightedIndex < this.searchResults.length) {
                                        const patient = this.searchResults[this.highlightedIndex];
                                        if (patient) {
                                            this.selectPatient(patient);
                                        }
                                    }
                                }
                            }
                        }
                    </script>

                    <!-- Triage Level -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Aciliyet Durumu <span class="text-red-500">*</span></label>
                        <div class="mt-3 space-y-3" x-data="{ selectedTriage: '{{ old('triage_level', 'green') }}' }">
                            <!-- Green - Normal -->
                            <label class="relative flex cursor-pointer rounded-lg border-2 bg-white dark:bg-slate-700 p-4 shadow-sm transition-all hover:shadow-md"
                                   :class="selectedTriage === 'green' ? 'border-green-500 bg-green-50 dark:bg-green-900/20 ring-2 ring-green-200 dark:ring-green-800' : 'border-slate-200 dark:border-slate-600'">
                                <input type="radio" name="triage_level" value="green" class="sr-only"
                                       x-model="selectedTriage" @change="selectedTriage = 'green'" />
                                <span class="flex flex-1 items-center">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/50 mr-3">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <span class="flex flex-col flex-1">
                                        <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Normal (Yeşil)</span>
                                        <span class="text-xs text-slate-600 dark:text-slate-400">Acil olmayan durumlar</span>
                                    </span>
                                    <div class="flex items-center justify-center w-5 h-5 rounded-full border-2"
                                         :class="selectedTriage === 'green' ? 'border-green-500 bg-green-500' : 'border-slate-300 dark:border-slate-600'">
                                        <div class="w-2 h-2 rounded-full bg-white" x-show="selectedTriage === 'green'"></div>
                                    </div>
                                </span>
                            </label>

                            <!-- Yellow - Urgent -->
                            <label class="relative flex cursor-pointer rounded-lg border-2 bg-white dark:bg-slate-700 p-4 shadow-sm transition-all hover:shadow-md"
                                   :class="selectedTriage === 'yellow' ? 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20 ring-2 ring-yellow-200 dark:ring-yellow-800' : 'border-slate-200 dark:border-slate-600'">
                                <input type="radio" name="triage_level" value="yellow" class="sr-only"
                                       x-model="selectedTriage" @change="selectedTriage = 'yellow'" />
                                <span class="flex flex-1 items-center">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900/50 mr-3">
                                        <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <span class="flex flex-col flex-1">
                                        <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Acil (Sarı)</span>
                                        <span class="text-xs text-slate-600 dark:text-slate-400">Önemli ancak hayati tehlike yok</span>
                                    </span>
                                    <div class="flex items-center justify-center w-5 h-5 rounded-full border-2"
                                         :class="selectedTriage === 'yellow' ? 'border-yellow-500 bg-yellow-500' : 'border-slate-300 dark:border-slate-600'">
                                        <div class="w-2 h-2 rounded-full bg-white" x-show="selectedTriage === 'yellow'"></div>
                                    </div>
                                </span>
                            </label>

                            <!-- Red - Critical -->
                            <label class="relative flex cursor-pointer rounded-lg border-2 bg-white dark:bg-slate-700 p-4 shadow-sm transition-all hover:shadow-md"
                                   :class="selectedTriage === 'red' ? 'border-red-500 bg-red-50 dark:bg-red-900/20 ring-2 ring-red-200 dark:ring-red-800' : 'border-slate-200 dark:border-slate-600'">
                                <input type="radio" name="triage_level" value="red" class="sr-only"
                                       x-model="selectedTriage" @change="selectedTriage = 'red'" />
                                <span class="flex flex-1 items-center">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/50 mr-3">
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <span class="flex flex-col flex-1">
                                        <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Kritik (Kırmızı)</span>
                                        <span class="text-xs text-slate-600 dark:text-slate-400">Hayati tehlike var, acil müdahale gerekli</span>
                                    </span>
                                    <div class="flex items-center justify-center w-5 h-5 rounded-full border-2"
                                         :class="selectedTriage === 'red' ? 'border-red-500 bg-red-500' : 'border-slate-300 dark:border-slate-600'">
                                        <div class="w-2 h-2 rounded-full bg-white" x-show="selectedTriage === 'red'"></div>
                                    </div>
                                </span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('triage_level')" class="mt-2" />
                    </div>

                    <!-- Dentist Assignment -->
                    <div>
                        <label for="dentist_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hekim Atayın <span class="text-red-500">*</span></label>
                        <select id="dentist_id" name="dentist_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Lütfen bir hekim seçin --</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('dentist_id')" class="mt-2" />
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notlar</label>
                        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hasta durumu ile ilgili notlar...">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('waiting-room.emergency') }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <button type="submit" class="px-8 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                            Kaydı Oluştur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>