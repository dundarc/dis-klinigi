<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Patient Information Card -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
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
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
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
                        <p class="text-slate-500 dark:text-slate-400">KVKK Onayı</p>
                        @if($patient->consent_kvkk_at)
                            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">Onaylandı</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-1 text-xs font-medium text-slate-800 dark:text-slate-200">Onaylanmamış</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Gelecek Randevular</h2>
                <div class="space-y-4">
                    @forelse($upcomingAppointments as $appointment)
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

                <!-- Pagination -->
                <div x-show="totalPages > 1" class="mt-6 flex justify-center">
                    <div class="flex space-x-2">
                        <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1" class="px-3 py-1 bg-slate-600 hover:bg-slate-700 disabled:bg-slate-400 text-white text-sm font-medium rounded transition-colors">
                            Önceki
                        </button>
                        <template x-for="page in totalPages" :key="page">
                            <button @click="currentPage = page" :class="page === currentPage ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-600 hover:bg-slate-700'" class="px-3 py-1 text-white text-sm font-medium rounded transition-colors" x-text="page"></button>
                        </template>
                        <button @click="currentPage = Math.min(totalPages, currentPage + 1)" :disabled="currentPage === totalPages" class="px-3 py-1 bg-slate-600 hover:bg-slate-700 disabled:bg-slate-400 text-white text-sm font-medium rounded transition-colors">
                            Sonraki
                        </button>
                    </div>
                </div>
            </div>

            <!-- Past Visits -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6" x-data="visitSearch()">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Geçmiş Ziyaretler</h2>
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
                    <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Ziyaret bulunmuyor</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Bu hasta için kayıtlı ziyaret bulunmuyor.</p>
                </div>

                <!-- Pagination -->
                <div x-show="totalPages > 1" class="mt-6 flex justify-center">
                    <div class="flex space-x-2">
                        <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1" class="px-3 py-1 bg-slate-600 hover:bg-slate-700 disabled:bg-slate-400 text-white text-sm font-medium rounded transition-colors">
                            Önceki
                        </button>
                        <template x-for="page in totalPages" :key="page">
                            <button @click="currentPage = page" :class="page === currentPage ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-600 hover:bg-slate-700'" class="px-3 py-1 text-white text-sm font-medium rounded transition-colors" x-text="page"></button>
                        </template>
                        <button @click="currentPage = Math.min(totalPages, currentPage + 1)" :disabled="currentPage === totalPages" class="px-3 py-1 bg-slate-600 hover:bg-slate-700 disabled:bg-slate-400 text-white text-sm font-medium rounded transition-colors">
                            Sonraki
                        </button>
                    </div>
                </div>
            </div>

            <!-- General Medical Notes -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
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
        </div>
    </div>

    <script>
        function treatmentPlanSearch() {
            return {
                plans: @json($treatmentPlans->items()),
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
                visits: @json($encounters->items()),
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
