<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Randevu Arama</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Geçmiş ve gelecek randevuları ara</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('waiting-room.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Yeni Randevu
                </a>
                <a href="{{ route('waiting-room.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Bekleme Odasına Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <!-- Search Form -->
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Arama Kriterleri</h3>
                </div>

                <form method="GET" action="{{ route('waiting-room.appointments.search') }}" x-data="appointmentSearch()" class="p-6 bg-slate-50 dark:bg-slate-800/50">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Date Range -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Başlangıç Tarihi</label>
                            <input id="start_date" name="start_date" type="date" :value="request('start_date')" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Bitiş Tarihi</label>
                            <input id="end_date" name="end_date" type="date" :value="request('end_date')" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <!-- Patient Search -->
                        <div x-data="{ open: false }" class="relative">
                            <label for="patient_search" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hasta</label>
                            <div class="mt-1 relative">
                                <input type="text"
                                       x-model="patientQuery"
                                       @input="searchPatients()"
                                       @focus="open = true; patientOpen = true; dentistOpen = false"
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

                            <!-- Patient Search Results -->
                            <div x-show="open && patientResults.length > 0 && patientOpen"
                                 x-transition
                                 class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-800 shadow-lg max-h-60 rounded-lg py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm border border-slate-200 dark:border-slate-700">
                                <template x-for="(patient, index) in patientResults" :key="'patient-' + (patient?.id || index)">
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
                            <div x-show="selectedPatient" class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-blue-900 dark:text-blue-100" x-text="selectedPatient?.name || ''"></span>
                                    </div>
                                    <button type="button" @click="clearPatientSelection()" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Dentist Search -->
                        <div x-data="{ open: false }" class="relative">
                            <label for="dentist_search" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hekim</label>
                            <div class="mt-1 relative">
                                <input type="text"
                                       x-model="dentistQuery"
                                       @input="searchDentists()"
                                       @focus="open = true; dentistOpen = true; patientOpen = false"
                                       @blur="setTimeout(() => open = false, 200)"
                                       class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-4 pr-10"
                                       placeholder="Hekim adı ile ara..."
                                       autocomplete="off">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <input type="hidden" name="dentist_id" x-model="selectedDentistId">

                            <!-- Dentist Search Results -->
                            <div x-show="open && dentistResults.length > 0 && dentistOpen"
                                 x-transition
                                 class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-800 shadow-lg max-h-60 rounded-lg py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm border border-slate-200 dark:border-slate-700">
                                <template x-for="(dentist, index) in dentistResults" :key="'dentist-' + (dentist?.id || index)">
                                    <div @click="selectDentist(dentist)"
                                         class="cursor-pointer select-none relative py-3 pl-4 pr-9 hover:bg-slate-100 dark:hover:bg-slate-700">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="font-medium block truncate text-slate-900 dark:text-slate-100" x-text="dentist?.name || ''"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Selected Dentist Display -->
                            <div x-show="selectedDentist" class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-green-900 dark:text-green-100" x-text="selectedDentist?.name || ''"></span>
                                    </div>
                                    <button type="button" @click="clearDentistSelection()" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Randevuları Ara
                        </button>
                    </div>
                </form>

                <!-- Search Results -->
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Arama Sonuçları</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih ve Saat</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hasta</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hekim</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Durum</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @if($appointments->isNotEmpty())
                                @foreach ($appointments as $appointment)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">{{ $appointment->start_at->format('d.m.Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">{{ $appointment->dentist->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($appointment->status === \App\Enums\AppointmentStatus::SCHEDULED)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">Planlandı</span>
                                            @elseif($appointment->status === \App\Enums\AppointmentStatus::CONFIRMED)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">Onaylandı</span>
                                            @elseif($appointment->encounter)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200">İşlem Yapıldı</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200">İptal Edildi</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('calendar.show', $appointment) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                    İşlem
                                                </a>
                                                @if($appointment->encounter)
                                                    <span class="text-slate-400">•</span>
                                                    <a href="{{ route('waiting-room.action', $appointment->encounter) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                                        Ziyareti Gör
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-500 dark:text-slate-400 ml-2">Check-in yapılmamış</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.618-2.618.14-.143.282-.29.428-.441a6.948 6.948 0 001.235-1.526L9 12zm6 0l-.428.441c.453.377.876.809 1.235 1.526C15.29 13.991 13.34 15 11 15c-.551 0-1.081-.094-1.582-.265l.428-.441A6.948 6.948 0 0011 12z"></path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Arama kriterlerinize uygun randevu bulunamadı</h3>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Farklı arama kriterleri deneyin veya yeni randevu oluşturun.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if($appointments->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function appointmentSearch() {
            return {
                patientQuery: '',
                dentistQuery: '',
                patientResults: [],
                dentistResults: [],
                selectedPatient: null,
                selectedDentist: null,
                selectedPatientId: '',
                selectedDentistId: '',
                patientOpen: false,
                dentistOpen: false,
                patientTimeout: null,
                dentistTimeout: null,

                searchPatients() {
                    clearTimeout(this.patientTimeout);
                    this.patientTimeout = setTimeout(() => {
                        if (this.patientQuery.length > 2) {
                            fetch(`/patients/search?q=${encodeURIComponent(this.patientQuery)}`)
                                .then(response => response.json())
                                .then(data => {
                                    this.patientResults = Array.isArray(data.data) ? data.data : [];
                                })
                                .catch(error => {
                                    console.error('Patient search error:', error);
                                    this.patientResults = [];
                                });
                        } else {
                            this.patientResults = [];
                        }
                    }, 300);
                },

                searchDentists() {
                    clearTimeout(this.dentistTimeout);
                    this.dentistTimeout = setTimeout(() => {
                        if (this.dentistQuery.length > 2) {
                            fetch(`/dentists/search?q=${encodeURIComponent(this.dentistQuery)}`)
                                .then(response => response.json())
                                .then(data => {
                                    this.dentistResults = Array.isArray(data) ? data : [];
                                })
                                .catch(error => {
                                    console.error('Dentist search error:', error);
                                    this.dentistResults = [];
                                });
                        } else {
                            this.dentistResults = [];
                        }
                    }, 300);
                },

                selectPatient(patient) {
                    if (patient) {
                        this.selectedPatient = patient;
                        this.selectedPatientId = patient.id;
                        this.patientQuery = patient.name;
                        this.patientResults = [];
                    }
                },

                selectDentist(dentist) {
                    if (dentist) {
                        this.selectedDentist = dentist;
                        this.selectedDentistId = dentist.id;
                        this.dentistQuery = dentist.name;
                        this.dentistResults = [];
                    }
                },

                clearPatientSelection() {
                    this.selectedPatient = null;
                    this.selectedPatientId = '';
                    this.patientQuery = '';
                    this.patientResults = [];
                },

                clearDentistSelection() {
                    this.selectedDentist = null;
                    this.selectedDentistId = '';
                    this.dentistQuery = '';
                    this.dentistResults = [];
                }
            }
        }
    </script>
</x-app-layout>
