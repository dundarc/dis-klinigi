<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Patient Information Card -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $patient->first_name }} {{ $patient->last_name }}</h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $age ? $age . ' yaşında' : 'Yaş bilgisi yok' }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Bilgileri Düzenle
                            </a>
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 text-sm">
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">Ana Telefon</p>
                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $patient->phone_primary ?? 'Belirtilmemiş' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">İkincil Telefon</p>
                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $patient->phone_secondary ?? 'Belirtilmemiş' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">E-posta</p>
                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $patient->email ?? 'Belirtilmemiş' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">Adres</p>
                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $patient->address_text ?? 'Belirtilmemiş' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">Özel Sağlık Sigortası</p>
                            @if($patient->has_private_insurance)
                                <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">Var</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-200">Yok</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">{{ __('patient.kvkk.consent') }}</p>
                            @if($patient->hasKvkkConsent())
                                <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">{{ __('patient.kvkk.approved') }}</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-1 text-xs font-medium text-slate-800 dark:text-slate-200">{{ __('patient.kvkk.not_approved') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- KVKK Consent Warning -->
                @unless($patient->hasKvkkConsent())
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-6 mb-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-amber-800 dark:text-amber-200 mb-2">KVKK Onamı Gerekli</h3>
                            <p class="text-amber-700 dark:text-amber-300 mb-4">
                                Bazı işlemlerin yapılması için hukuken KVKK onamı almanız gerekmektedir. Ancak bu işlemler KYS sisteminde kısıtlanmamıştır. Herhangi bir hukuki problemle karşılaşmamak için hastadan KVKK onamı alınız!
                            </p>
                            <a href="{{ route('kvkk.create-consent', $patient) }}"
                               class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                KVKK Onamı Oluştur
                            </a>
                        </div>
                    </div>
                </div>
                @endunless

                <!-- Three Column Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Upcoming Appointments -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Gelecek Randevular</h2>
                        <div class="h-64 overflow-y-auto">
                            <div class="space-y-4">
                                @forelse($upcomingAppointments->take(5) as $appointment)
                                    <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                                        <div>
                                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $appointment->start_at->format('d.m.Y H:i') }}</p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">{{ $appointment->dentist?->name }}</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">Planlandı</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Gelecek randevu bulunmuyor.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Treatment Plans -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6" x-data="treatmentPlanSearch()">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Tedavi Planları</h2>
                            <a href="{{ route('patients.treatment-plans.create', $patient) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Yeni Plan Oluştur
                            </a>
                        </div>

                        <!-- Live Search -->
                        <div class="mb-6">
                            <label for="plan_search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Canlı Arama</label>
                            <input type="text" x-model="searchQuery" @input="filterPlans()" placeholder="Tedavi adı, diş no veya doktor ara..." class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="h-64 overflow-y-auto">
                            <div x-show="filteredPlans.length > 0" class="space-y-4">
                                <template x-for="plan in paginatedPlans" :key="plan.id">
                                    <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                                        <div>
                                            <h3 class="font-semibold text-slate-900 dark:text-slate-100">Tedavi Planı #<span x-text="plan.id"></span></h3>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">Dr. <span x-text="plan.dentist ? plan.dentist.name : 'Bilinmeyen Doktor'"></span> • <span x-text="formatDate(plan.created_at)"></span></p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400"><span x-text="plan.items.length"></span> tedavi kalemi • <span x-text="formatCurrency(plan.total_estimated_cost)"></span></p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span :class="getStatusClass(plan.status)" x-text="getStatusLabel(plan.status)" class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium"></span>
                                            <a :href="`/treatment-plans/${plan.id}`" class="inline-flex items-center px-3 py-1 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded transition-colors">
                                                Görüntüle
                                            </a>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div x-show="filteredPlans.length === 0" class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Tedavi planı bulunmuyor</h3>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Bu hasta için henüz tedavi planı oluşturulmamış.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Past Treatments -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6" x-data="visitSearch()">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Geçmiş Tedaviler</h2>
                            <a href="{{ route('waiting-room.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Yeni Randevu
                            </a>
                        </div>

                        <!-- Live Search -->
                        <div class="mb-6">
                            <label for="visit_search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Canlı Arama</label>
                            <input type="text" x-model="searchQuery" @input="filterVisits()" placeholder="Doktor adı veya tarih ara..." class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="h-64 overflow-y-auto">
                            <div x-show="filteredVisits.length > 0" class="space-y-4">
                                <template x-for="visit in paginatedVisits" :key="visit.id">
                                    <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                                        <div>
                                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100" x-text="formatDateTime(visit.arrived_at || visit.created_at)"></p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400" x-text="visit.dentist ? visit.dentist.name : 'Doktor bilgisi yok'"></p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400" x-text="visit.notes || ''"></p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span :class="getVisitStatusClass(visit.status)" x-text="getVisitStatusLabel(visit.status)" class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"></span>
                                            <a :href="`/waiting-room/${visit.id}/show`" class="inline-flex items-center px-3 py-1 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded transition-colors">
                                                Görüntüle
                                            </a>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div x-show="filteredVisits.length === 0" class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Tedavi bulunmuyor</h3>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Bu hasta için kayıtlı tedavi bulunmuyor.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General Medical Notes -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mt-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Genel Tıbbi Notlar</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Notlar</label>
                            <p class="text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-700/50 p-3 rounded-lg">{{ $patient->notes ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Kullandığı İlaçlar</label>
                            <p class="text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-700/50 p-3 rounded-lg">{{ $patient->medications_used ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Patient Files -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mt-6" x-data="patientFiles()">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Hasta Dosyaları</h2>
                        <button @click="$dispatch('open-modal', 'upload-file-modal')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Dosya Yükle
                        </button>
                    </div>
    
                    <!-- Search -->
                    <div x-show="files.length > 5" class="mb-6">
                        <label for="file_search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Dosya Ara</label>
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Dosya adı veya türüne göre ara..." class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Pagination Info -->
                    <div x-show="files.length > 0" class="mb-4 text-sm text-slate-600 dark:text-slate-400">
                        <span x-text="paginationInfo"></span>
                    </div>
    
                    <!-- Files List -->
                    <div x-show="files.length > 0" class="space-y-4">
                        <template x-for="file in paginatedFiles" :key="file.id">
                            <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                                <div class="flex items-center space-x-3">
                                    <!-- File Icon -->
                                    <div class="flex-shrink-0">
                                        <svg x-show="file.type === 'xray'" class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <svg x-show="file.type === 'photo'" class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <svg x-show="file.type === 'document'" class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <svg x-show="file.type === 'other'" class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-slate-900 dark:text-slate-100" x-text="file.display_name"></h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">
                                            <span x-text="file.type_label"></span> •
                                            <span x-text="file.size_formatted"></span> •
                                            <span x-text="file.created_at_formatted"></span>
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400" x-show="file.uploader">
                                            Yükleyen: <span x-text="file.uploader.name"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a :href="file.download_url" target="_blank" class="inline-flex items-center px-3 py-1 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        İndir
                                    </a>
                                </div>
                            </div>
                        </template>
    
                        <!-- Pagination Controls -->
                        <div x-show="totalPages > 1" class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-700">
                            <button @click="prevPage()" :disabled="currentPage === 1"
                                    class="px-3 py-1 text-sm font-medium text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-md hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Önceki
                            </button>
    
                            <div class="flex items-center space-x-1">
                                <template x-for="page in totalPages" :key="page">
                                    <button @click="goToPage(page)"
                                            :class="page === currentPage ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
                                            class="px-3 py-1 text-sm font-medium border border-slate-300 dark:border-slate-600 rounded-md">
                                        <span x-text="page"></span>
                                    </button>
                                </template>
                            </div>
    
                            <button @click="nextPage()" :disabled="currentPage === totalPages"
                                    class="px-3 py-1 text-sm font-medium text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-md hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Sonraki
                            </button>
                        </div>
                    </div>
    
                    <!-- Empty State -->
                    <div x-show="filteredFiles.length === 0 && files.length > 0" class="text-center py-8">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Arama kriterlerinize uygun dosya bulunamadı.</p>
                    </div>
    
                    <div x-show="files.length === 0" class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Dosya bulunmuyor</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Bu hasta için henüz dosya yüklenmemiş.</p>
                        <button @click="$dispatch('open-modal', 'upload-file-modal')" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            İlk Dosyayı Yükle
                        </button>
                    </div>
                </div>
        </div>
    </div>

    <!-- File Upload Modal -->
    @include('patients.partials.modal-file-upload')

    @php
    $patientFiles = $patient->files->map(function($file) {
        return [
            'id' => $file->id,
            'display_name' => $file->display_name,
            'type' => $file->type->value,
            'type_label' => $file->type->label(),
            'size_formatted' => round($file->size / 1024, 1) . ' KB',
            'created_at_formatted' => $file->created_at->format('d.m.Y H:i'),
            'download_url' => $file->download_url,
            'uploader' => $file->uploader ? ['name' => $file->uploader->name] : null
        ];
    })->toArray();
    @endphp

    <script>
        function patientFiles() {
            return {
                files: @json($patientFiles),
                searchQuery: '',
                currentPage: 1,
                itemsPerPage: 5,

                get filteredFiles() {
                    if (!this.searchQuery) return this.files;
                    const query = this.searchQuery.toLowerCase();
                    return this.files.filter(file => {
                        return file.display_name.toLowerCase().includes(query) ||
                               file.type_label.toLowerCase().includes(query);
                    });
                },

                get totalPages() {
                    return Math.ceil(this.filteredFiles.length / this.itemsPerPage);
                },

                get paginatedFiles() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredFiles.slice(start, end);
                },

                get paginationInfo() {
                    const total = this.filteredFiles.length;
                    const start = (this.currentPage - 1) * this.itemsPerPage + 1;
                    const end = Math.min(this.currentPage * this.itemsPerPage, total);
                    return `${total} tane dosya var. Siz ${start}-${end} arasını görüyorsunuz.`;
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                    }
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                    }
                },

                goToPage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },

                submitFile(form) {
                    const formData = new FormData(form);
                    formData.append('patient_id', '{{ $patient->id }}');
                    fetch('/patients/{{ $patient->id }}/files', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload the page to show the new file
                            window.location.reload();
                        } else {
                            alert('Dosya yüklenirken hata oluştu: ' + (data.message || 'Bilinmeyen hata'));
                        }
                    })
                    .catch(error => {
                        console.error('Upload error:', error);
                        alert('Dosya yüklenirken hata oluştu.');
                    });
                }
            }
        }

        function treatmentPlanSearch() {
            return {
                plans: @json(collect($treatmentPlans->items())->take(5)),
                searchQuery: '',
                currentPage: 1,
                itemsPerPage: 5,

                get filteredPlans() {
                    if (!this.searchQuery) return this.plans;
                    const query = this.searchQuery.toLowerCase();
                    return this.plans.filter(plan => {
                        const dentistName = plan.dentist ? plan.dentist.name : '';
                        return (dentistName && dentistName.toLowerCase().includes(query)) ||
                               (plan.items && plan.items.some(item =>
                                   (item.treatment && item.treatment.name && item.treatment.name.toLowerCase().includes(query)) ||
                                   (item.tooth_number && item.tooth_number.toString().includes(query))
                               ));
                    });
                },

                get totalPages() {
                    return Math.ceil(this.filteredPlans.length / this.itemsPerPage);
                },

                get paginatedPlans() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredPlans.slice(start, end);
                },

                filterPlans() {
                    this.currentPage = 1; // Reset to first page when filtering
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('tr-TR');
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('tr-TR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(amount || 0) + ' ₺';
                },

                getStatusLabel(status) {
                    const labels = {
                        'draft': 'Taslak',
                        'active': 'Aktif',
                        'completed': 'Tamamlandı',
                        'cancelled': 'İptal Edildi'
                    };
                    return labels[status] || status;
                },

                getStatusClass(status) {
                    const classes = {
                        'draft': 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200',
                        'active': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                        'completed': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                        'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200'
                    };
                    return classes[status] || 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200';
                }
            }
        }

        function visitSearch() {
            return {
                visits: @json(collect($encounters->items())->take(5)),
                searchQuery: '',
                currentPage: 1,
                itemsPerPage: 5,

                get filteredVisits() {
                    if (!this.searchQuery) return this.visits;
                    const query = this.searchQuery.toLowerCase();
                    return this.visits.filter(visit => {
                        const dentistName = visit.dentist ? visit.dentist.name : '';
                        return (dentistName && dentistName.toLowerCase().includes(query)) ||
                               (visit.arrived_at && this.formatDateTime(visit.arrived_at).toLowerCase().includes(query)) ||
                               (visit.created_at && this.formatDateTime(visit.created_at).toLowerCase().includes(query));
                    });
                },

                get totalPages() {
                    return Math.ceil(this.filteredVisits.length / this.itemsPerPage);
                },

                get paginatedVisits() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredVisits.slice(start, end);
                },

                filterVisits() {
                    this.currentPage = 1; // Reset to first page when filtering
                },

                formatDateTime(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('tr-TR') + ' ' + date.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
                },

                getVisitStatusLabel(status) {
                    const labels = {
                        'waiting': 'Bekliyor',
                        'in_service': 'Devam Ediyor',
                        'done': 'Tamamlandı',
                        'cancelled': 'İptal Edildi'
                    };
                    return labels[status] || status;
                },

                getVisitStatusClass(status) {
                    const classes = {
                        'waiting': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
                        'in_service': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                        'done': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                        'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200'
                    };
                    return classes[status] || 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200';
                }
            }
        }
    </script>
</x-app-layout>
