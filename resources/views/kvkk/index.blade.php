<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">KVKK Veri Yönetimi</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Hasta verilerinin KVKK uyumluluğu ve yönetimi</p>
            </div>
            <div class="flex gap-2">
                @can('accessAdminFeatures')
                <a href="{{ route('kvkk.trash.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Çöp Kutusu
                </a>
                @endcan
                <a href="{{ route('kvkk.reports.missing') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Onamı Olmayanlar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- KVKK Süreç Özeti -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">KVKK Veri Yönetimi Süreci</h3>
                        <div class="text-sm text-slate-600 dark:text-slate-300 prose prose-sm max-w-none">
                            <p>Kişisel Verilerin Korunması Kanunu (KVKK) kapsamında hasta verilerinin işlenmesi, saklanması ve silinmesi süreçlerini yönetebilirsiniz. Hasta onamları, veri export işlemleri ve KVKK uyumlu silme işlemleri bu bölümden gerçekleştirilir.</p>
                            <p class="mt-2"><strong>Önemli:</strong> KVKK işlemleri geri alınamaz nitelikte olabilir. Tüm işlemler audit log ile kaydedilir ve raporlanır.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hasta Listesi -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6" x-data="kvkkPatients()" x-init="loadAllPatients()">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">KVKK Onamı Bulunan Hastalar</h3>
                    <div class="flex gap-2">
                        <button @click="sortByName()" class="inline-flex items-center px-3 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                            Alfabetik Sırala
                        </button>
                    </div>
                </div>

                <!-- Pagination Info -->
                <div x-show="allPatients.length > 0" class="mb-4 text-sm text-slate-600 dark:text-slate-400">
                    <span x-text="paginationInfo"></span>
                </div>

                <!-- Search -->
                <div x-show="allPatients.length > 20" class="mb-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text"
                               x-model="query"
                               @input="filterPatients()"
                               placeholder="Hasta adı, soyadı, TC kimlik veya telefon ara..."
                               class="w-full pl-10 pr-4 py-3 rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <div x-show="query.length > 0" @click="query = ''; filterPatients()" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                            <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>

            <!-- Loading -->
            <div x-show="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Hastalar yükleniyor...</p>
            </div>

            <!-- Patients Grid -->
            <div x-show="!loading && paginatedPatients.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="patient in paginatedPatients" :key="patient.id">
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                                    <span x-text="patient.first_name + ' ' + patient.last_name"></span>
                                </h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    TC: <span x-text="patient.national_id ? '***' + patient.national_id.substr(-4) : 'Belirtilmemiş'"></span>
                                </p>
                            </div>
                            <!-- Onam Durumu Rozeti -->
                            <div class="flex-shrink-0">
                                <span x-show="patient.consent_status === 'accepted'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Onaylı
                                </span>
                                <span x-show="patient.consent_status === 'withdrawn'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Geri Çekildi
                                </span>
                                <span x-show="!patient.consent_status || patient.consent_status === null" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Onam Yok
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                <span class="font-medium">Telefon:</span>
                                <span x-text="patient.phone_primary ? '*** *** ' + patient.phone_primary.substr(-4) : 'Belirtilmemiş'"></span>
                            </p>
                            <p x-show="patient.latest_consent_at" class="text-sm text-slate-600 dark:text-slate-400">
                                <span class="font-medium">Son Onam:</span>
                                <span x-text="new Date(patient.latest_consent_at).toLocaleDateString('tr-TR')"></span>
                            </p>
                        </div>

                        <a :href="'/kvkk/' + patient.id"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            KVKK Detaylarını Görüntüle
                        </a>
                    </div>
                </template>

                <!-- Pagination Controls -->
                <div x-show="totalPages > 1" class="flex items-center justify-between pt-6 border-t border-slate-200 dark:border-slate-700">
                    <button @click="prevPage()" :disabled="currentPage === 1"
                            class="px-3 py-2 text-sm font-medium text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-md hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        Önceki
                    </button>

                    <div class="flex items-center space-x-1">
                        <template x-for="page in totalPages" :key="page">
                            <button @click="goToPage(page)"
                                    :class="page === currentPage ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'"
                                    class="px-3 py-2 text-sm font-medium border border-slate-300 dark:border-slate-600 rounded-md">
                                <span x-text="page"></span>
                            </button>
                        </template>
                    </div>

                    <button @click="nextPage()" :disabled="currentPage === totalPages"
                            class="px-3 py-2 text-sm font-medium text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-md hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        Sonraki
                    </button>
                </div>
            </div>

            <div x-show="!loading && filteredPatients.length === 0 && query.length > 0" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 text-center">
                <svg class="w-12 h-12 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-2">
                    Arama sonucu bulunamadı
                </h3>
                <p class="text-slate-600 dark:text-slate-400">
                    Farklı arama kriterleri deneyin.
                </p>
            </div>

            <div x-show="!loading && allPatients.length === 0" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 text-center">
                <svg class="w-12 h-12 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-2">
                    Henüz hasta bulunmuyor
                </h3>
                <p class="text-slate-600 dark:text-slate-400">
                    Sistemde kayıtlı hasta bulunmuyor.
                </p>
            </div>
        </div>
    </div>

    <script>
        function kvkkPatients() {
            return {
                allPatients: [],
                query: '',
                currentPage: 1,
                itemsPerPage: 20,
                loading: false,

                init() {
                    this.loadAllPatients();
                },

                loadAllPatients() {
                    this.loading = true;
                    // Load all patients by making a search request with empty query
                    fetch('/kvkk/search')
                        .then(response => response.json())
                        .then(data => {
                            // Sort alphabetically by first name + last name
                            this.allPatients = data.sort((a, b) => {
                                const nameA = (a.first_name + ' ' + a.last_name).toLowerCase();
                                const nameB = (b.first_name + ' ' + b.last_name).toLowerCase();
                                return nameA.localeCompare(nameB);
                            });
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Load patients error:', error);
                            this.loading = false;
                        });
                },

                get filteredPatients() {
                    if (!this.query) return this.allPatients;
                    const searchTerm = this.query.toLowerCase();
                    return this.allPatients.filter(patient => {
                        const fullName = (patient.first_name + ' ' + patient.last_name).toLowerCase();
                        const nationalId = patient.national_id ? patient.national_id.toLowerCase() : '';
                        const phone = patient.phone_primary ? patient.phone_primary.toLowerCase() : '';
                        return fullName.includes(searchTerm) ||
                               nationalId.includes(searchTerm) ||
                               phone.includes(searchTerm);
                    });
                },

                get totalPages() {
                    return Math.ceil(this.filteredPatients.length / this.itemsPerPage);
                },

                get paginatedPatients() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredPatients.slice(start, end);
                },

                get paginationInfo() {
                    const total = this.filteredPatients.length;
                    if (total === 0) return '';

                    const start = (this.currentPage - 1) * this.itemsPerPage + 1;
                    const end = Math.min(this.currentPage * this.itemsPerPage, total);
                    return `${total} hasta var. Siz ${start}-${end} arasını görüyorsunuz.`;
                },

                filterPatients() {
                    this.currentPage = 1; // Reset to first page when filtering
                },

                sortByName() {
                    this.allPatients.sort((a, b) => {
                        const nameA = (a.first_name + ' ' + a.last_name).toLowerCase();
                        const nameB = (b.first_name + ' ' + b.last_name).toLowerCase();
                        return nameA.localeCompare(nameB);
                    });
                    this.currentPage = 1;
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
                }
            }
        }
    </script>
</x-app-layout>