<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tedavi Planları') }}
            </h2>
            <div>
                <x-secondary-button-link href="{{ route('patients.index') }}">
                    Hastalar
                </x-secondary-button-link>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="treatmentPlanSearch()" x-init="loadPlans()">

                    <!-- Arama Kutusu -->
                    <div class="mb-6">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text"
                                   x-model="query"
                                   @input="search()"
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Hasta adı, doktor veya bilgi ile ara...">
                        </div>
                    </div>

                    <!-- Tedavi Planı Listesi -->
                    <!-- Desktop Tablo Görünümü -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hasta Adı</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Doktor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Durum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Toplam Maliyet</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Oluşturulma Tarihi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-for="plan in plans" :key="plan.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="plan.patient_name"></div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400" x-text="plan.patient_phone"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="plan.dentist_name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusClass(plan.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" x-text="plan.status"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="'₺' + plan.total_estimated_cost"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="formatDate(plan.created_at)"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a :href="'/treatment-plans/' + plan.id" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-2">
                                                Göster
                                            </a>
                                            <a :href="'/treatment-plans/' + plan.id + '/edit'" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                                Düzenle
                                            </a>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="plans.length === 0 && !loading">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Sonuç bulunamadı.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobil Kart Görünümü -->
                    <div class="md:hidden space-y-4">
                        <div x-show="loading" class="text-center py-4">
                            <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">Aranıyor...</span>
                        </div>

                        <template x-for="plan in plans" :key="plan.id">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="plan.patient_name"></h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1" x-text="plan.dentist_name"></p>
                                        <div class="mt-2 flex items-center space-x-4">
                                            <span :class="getStatusClass(plan.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" x-text="plan.status"></span>
                                            <span class="text-sm text-gray-600 dark:text-gray-400" x-text="'₺' + plan.total_estimated_cost"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="formatDate(plan.created_at)"></p>
                                    </div>
                                    <div class="ml-4 flex flex-col space-y-2">
                                        <a :href="'/treatment-plans/' + plan.id" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                            Göster
                                        </a>
                                        <a :href="'/treatment-plans/' + plan.id + '/edit'" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 text-sm font-medium">
                                            Düzenle
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="plans.length === 0 && !loading" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            Sonuç bulunamadı.
                        </div>
                    </div>

                    <!-- Loading Indicator for Desktop -->
                    <div x-show="loading" class="hidden md:flex justify-center py-4">
                        <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
                        <span class="ml-2 text-gray-600 dark:text-gray-400">Aranıyor...</span>
                    </div>

                    <!-- Pagination -->
                    <div x-show="lastPage > 1" class="mt-6 flex justify-center">
                        <nav class="flex items-center space-x-1">
                            <!-- Previous Button -->
                            <button @click="previousPage()"
                                    :disabled="currentPage === 1"
                                    :class="{'opacity-50 cursor-not-allowed': currentPage === 1, 'hover:bg-gray-100 dark:hover:bg-gray-700': currentPage > 1}"
                                    class="px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-l-md">
                                ← Önceki
                            </button>

                            <!-- Page Numbers -->
                            <template x-for="page in Array.from({length: lastPage}, (_, i) => i + 1)" :key="page">
                                <button @click="goToPage(page)"
                                        :class="{'bg-blue-600 text-white': page === currentPage, 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700': page !== currentPage}"
                                        class="px-3 py-2 text-sm font-medium bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600">
                                    <span x-text="page"></span>
                                </button>
                            </template>

                            <!-- Next Button -->
                            <button @click="nextPage()"
                                    :disabled="currentPage === lastPage"
                                    :class="{'opacity-50 cursor-not-allowed': currentPage === lastPage, 'hover:bg-gray-100 dark:hover:bg-gray-700': currentPage < lastPage}"
                                    class="px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-r-md">
                                Sonraki →
                            </button>
                        </nav>
                    </div>

                    <!-- Results Info -->
                    <div x-show="total > 0" class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
                        <span x-text="`Toplam ${total} tedavi planı, Sayfa ${currentPage} / ${lastPage}`"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function treatmentPlanSearch() {
            return {
                query: '',
                plans: @json($transformedPlans ?? []),
                loading: false,
                searchTimeout: null,
                currentPage: {{ $plans->currentPage() ?? 1 }},
                lastPage: {{ $plans->lastPage() ?? 1 }},
                total: {{ $plans->total() ?? 0 }},

                init() {
                    // Initial data is already loaded
                },

                loadPlans(page = 1) {
                    this.loading = true;
                    const params = new URLSearchParams();
                    if (this.query.length > 0) {
                        params.append('q', this.query);
                    }
                    params.append('page', page);

                    fetch(`/treatment-plans/all?${params.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            this.plans = data.data;
                            this.currentPage = data.current_page;
                            this.lastPage = data.last_page;
                            this.total = data.total;
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
                        this.currentPage = 1; // Reset to first page on new search
                        this.loadPlans();
                    }, 300); // 300ms debounce
                },

                goToPage(page) {
                    if (page >= 1 && page <= this.lastPage) {
                        this.currentPage = page;
                        this.loadPlans(page);
                    }
                },

                previousPage() {
                    if (this.currentPage > 1) {
                        this.goToPage(this.currentPage - 1);
                    }
                },

                nextPage() {
                    if (this.currentPage < this.lastPage) {
                        this.goToPage(this.currentPage + 1);
                    }
                },

                getStatusClass(status) {
                    const classes = {
                        'draft': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                        'active': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                        'completed': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                        'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                    };
                    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('tr-TR');
                },

            }
        }
    </script>
</x-app-layout>