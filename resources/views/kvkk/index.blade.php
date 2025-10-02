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

            <!-- Hasta Arama -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6" x-data="kvkkSearch()" x-init="loadPatients()">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Arama</h3>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text"
                                   x-model="query"
                                   @input="search()"
                                   placeholder="Hasta adı, soyadı, TC kimlik veya telefon ara..."
                                   class="w-full pl-10 pr-4 py-3 rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <div x-show="query.length > 0" @click="query = ''; search()" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-3">
                    <span x-show="query.length === 0">KVKK yönetimi için hasta arayın</span>
                    <span x-show="query.length > 0" x-text="`Arama: ${query}`"></span>
                </p>

            <!-- Arama Sonuçları -->
            <div x-show="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Hastalar yükleniyor...</p>
            </div>

            <div x-show="!loading && patients.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="patient in patients" :key="patient.id">
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
            </div>

            <div x-show="!loading && patients.length === 0 && query.length > 0" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 text-center">
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

            <div x-show="!loading && patients.length === 0 && query.length === 0" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 text-center">
                <svg class="w-12 h-12 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-2">
                    KVKK yönetimi için hasta arayın
                </h3>
                <p class="text-slate-600 dark:text-slate-400">
                    Hasta adı, soyadı, TC kimlik veya telefon numarası ile arama yapabilirsiniz.
                </p>
            </div>
        </div>
    </div>

    <script>
        function kvkkSearch() {
            return {
                query: '',
                patients: [],
                loading: false,
                searchTimeout: null,

                init() {
                    // Load initial patients if there's a query parameter
                    const urlParams = new URLSearchParams(window.location.search);
                    const initialQuery = urlParams.get('q');
                    if (initialQuery) {
                        this.query = initialQuery;
                        this.loadPatients();
                    }
                },

                loadPatients() {
                    this.loading = true;
                    const params = new URLSearchParams();
                    if (this.query.length > 0) {
                        params.append('q', this.query);
                    }

                    fetch(`/kvkk/search?${params.toString()}`)
                        .then(response => response.json())
                        .then(data => {
                            this.patients = data;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            this.loading = false;
                        });
                },

                search() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        if (this.query.length >= 2 || this.query.length === 0) {
                            this.loadPatients();
                        }
                    }, 300); // 300ms debounce
                }
            }
        }
    </script>
</x-app-layout>