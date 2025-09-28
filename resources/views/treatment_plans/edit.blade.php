<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-gray-100 dark:to-gray-400 bg-clip-text text-transparent">
                        Tedavi Planını Düzenle
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span>Plan #{{ $treatmentPlan->id }}</span>
                    </p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('treatment-plans.show', $treatmentPlan) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Planı Görüntüle
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
            x-data="treatmentPlanForm({
                treatmentPlan: {
                    id: {{ $treatmentPlan->id }},
                    patient_id: {{ $treatmentPlan->patient_id }},
                    dentist_id: {{ $treatmentPlan->dentist_id ?? 'null' }},
                    status: '{{ $treatmentPlan->status ?? 'draft' }}',
                    notes: @js($treatmentPlan->notes ?? ''),
                    total_estimated_cost: {{ $treatmentPlan->total_estimated_cost ?? 0 }}
                },
                items: [],
                treatments: @js($treatments),
                patientId: {{ $treatmentPlan->patient_id }},
                loading: true,
                hasChanges: false,
                deletedItems: []
            })"
            x-init="loadTreatmentPlanData"
        >

            <!-- Main Form Card -->
            <div class="bg-gradient-to-r from-white via-blue-50/30 to-indigo-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden backdrop-blur-sm">
                <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tedavi Planı Bilgileri</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Temel plan detaylarını düzenleyin</p>
                        </div>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div x-show="loading" class="p-12 text-center">
                    <div class="inline-flex items-center text-blue-600 dark:text-blue-400">
                        <svg class="animate-spin -ml-1 mr-3 h-8 w-8" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-lg font-medium">Tedavi planı verileri yükleniyor...</span>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="saveChanges" x-show="!loading" class="p-8 space-y-8">

                <!-- Plan Header Fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-3">
                        <label for="dentist_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Sorumlu Diş Hekimi</span>
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select id="dentist_id" name="dentist_id"
                            x-model="treatmentPlan.dentist_id"
                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Hekim Seçiniz</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist['id'] }}">{{ $dentist['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Tedavi Planı Durumu</span>
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select id="status" name="status"
                            x-model="treatmentPlan.status"
                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="draft">📝 Taslak</option>
                            <option value="planned">📅 Planlandı</option>
                            <option value="in_progress">⚡ Devam Ediyor</option>
                            <option value="done">✅ Tamamlandı</option>
                            <option value="cancelled">❌ İptal Edildi</option>
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label for="notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Plan Notları</span>
                            </span>
                        </label>
                        <textarea id="notes" name="notes" rows="4"
                            x-model="treatmentPlan.notes"
                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                            placeholder="Tedavi planı ile ilgili genel notlar..."></textarea>
                    </div>
                </div>

                <!-- Treatment Items Section -->
                <div class="border-t border-gray-200/50 dark:border-gray-600/50 pt-8 mt-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tedavi Kalemleri</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Tedavi planına kalemler ekleyin</p>
                            </div>
                        </div>
                        <button type="button" @click="addItem" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Yeni Kalem Ekle
                        </button>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-4" x-show="items.length > 0">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="bg-gradient-to-r from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-700/50 rounded-xl border border-gray-200/50 dark:border-gray-600/50 p-6 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm" x-text="index + 1"></span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tedavi Kalemi</span>
                                        <span x-show="item.id" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            Mevcut
                                        </span>
                                        <span x-show="!item.id" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Yeni
                                        </span>
                                    </div>
                                    <button type="button" @click="removeItem(index)" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Kaldır
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                <span>Tedavi</span>
                                                <span x-show="!item.id" class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <!-- For existing items, show treatment name as text -->
                                        <div x-show="item.id" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-gray-100">
                                            <span x-text="item.treatment_name"></span>
                                        </div>
                                        <!-- For new items, show select -->
                                        <select x-show="!item.id" x-model="item.treatment_id" @change="updatePrice(index)"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                            <option value="">Tedavi Seçiniz</option>
                                            <template x-for="treatment in treatments" :key="treatment.id">
                                                <option :value="treatment.id.toString()" x-text="treatment.name"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <span>Diş No</span>
                                            </span>
                                        </label>
                                        <input type="text" x-model="item.tooth_number"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                            placeholder="örn: 11, 12-13" />
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>Randevu Tarihi</span>
                                            </span>
                                        </label>
                                        <input type="datetime-local" x-model="item.appointment_date"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                <span>Ücret (TL)</span>
                                            </span>
                                        </label>
                                        <input type="number" step="0.01" x-model="item.estimated_price"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Durum</span>
                                            </span>
                                        </label>
                                        <select x-model="item.status"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                            <option value="planned">📅 Planlandı</option>
                                            <option value="in_progress">⚡ Devam Ediyor</option>
                                            <option value="done">✅ Tamamlandı</option>
                                            <option value="cancelled">❌ İptal Edildi</option>
                                            <option value="no_show">🚫 Gelmedi</option>
                                            <option value="invoiced">💰 Faturalandı</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Empty State -->
                    <div x-show="items.length === 0" class="text-center py-12">
                        <div class="w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tedavi kalemi bulunmuyor</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">"Yeni Kalem Ekle" butonuna tıklayarak tedavi kalemleri ekleyebilirsiniz.</p>
                    </div>
                </div>

                <!-- Total Cost Display -->
                <div x-show="items.length > 0" class="bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 dark:from-blue-900/20 dark:via-indigo-900/20 dark:to-purple-900/20 border border-blue-200/50 dark:border-blue-700/50 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-blue-900 dark:text-blue-100">Tahmini Toplam Maliyet</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300">Düzenlenen tedavilerin toplam ücreti</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent" x-text="formatCurrency(totalCost)"></div>
                            <div class="text-sm text-blue-700 dark:text-blue-300 font-medium">TL</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4 pt-8 border-t border-gray-200/50 dark:border-gray-600/50">
                    <a href="{{ route('treatment-plans.show', $treatmentPlan) }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-xl transition-all duration-300 shadow-sm hover:shadow-md">
                        İptal
                    </a>
                    <button type="submit"
                        :disabled="!hasChanges"
                        :class="hasChanges ? 'bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 shadow-lg hover:shadow-xl' : 'bg-gray-400 cursor-not-allowed'"
                        class="px-8 py-3 text-white font-medium rounded-xl transition-all duration-300">
                        <span x-show="!hasChanges">Değişiklik Yok</span>
                        <span x-show="hasChanges">Değişiklikleri Kaydet</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine.js Functions -->
    <script>
        function treatmentPlanForm(config) {
            return {
                treatmentPlan: config.treatmentPlan,
                items: config.items || [],
                treatments: config.treatments || [],
                patientId: config.patientId,
                hasChanges: false,
                deletedItems: [],
                loading: config.loading ?? false,

                init() {
                    this.$watch('items', () => this.hasChanges = true, { deep: true });
                    this.$watch('treatmentPlan', () => this.hasChanges = true, { deep: true });
                    setInterval(() => { if (this.hasChanges) this.persistChanges(false); }, 10000);
                },

                async loadTreatmentPlanData() {
                    try {
                        // Load treatment plan basic data
                        const planRes = await fetch(`/api/treatment-plans/${this.treatmentPlan.id}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (planRes.ok) {
                            const planData = await planRes.json();
                            this.treatmentPlan = {
                                id: planData.id,
                                patient_id: planData.patient_id,
                                dentist_id: planData.dentist_id,
                                status: planData.status,
                                notes: planData.notes || '',
                                total_estimated_cost: planData.total_estimated_cost || 0
                            };
                        }

                        // Load treatment plan items
                        const itemsRes = await fetch(`/api/treatment-plans/${this.treatmentPlan.id}/items`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (itemsRes.ok) {
                            const itemsData = await itemsRes.json();
                            this.items = itemsData.map(item => {
                                // Keep treatment_id as string for HTML select compatibility
                                const treatmentId = item.treatment_id ? item.treatment_id.toString() : '';

                                // Find treatment name from treatments list if not provided in API
                                let treatment_name = item.treatment_name;
                                if (!treatment_name && treatmentId) {
                                    const treatment = this.treatments.find(t => t.id.toString() === treatmentId);
                                    treatment_name = treatment ? treatment.name : '';
                                }

                                return {
                                    id: item.id,
                                    treatment_id: treatmentId,
                                    treatment_name: treatment_name,
                                    tooth_number: item.tooth_number || '',
                                    appointment_date: item.appointment_date || '',
                                    estimated_price: parseFloat(item.estimated_price) || 0.00,
                                    status: item.status || 'planned',
                                    treatment_plan_id: this.treatmentPlan.id
                                };
                            });
                        }

                        this.loading = false;
                        console.log('Treatment plan data loaded successfully');
                    } catch (e) {
                        console.error('Error loading treatment plan data:', e);
                        this.loading = false;
                    }
                },

                get totalCost() {
                    return this.items.reduce((t, i) => t + (parseFloat(i.estimated_price) || 0), 0);
                },

                addItem() {
                    this.items.push({
                        id: null,
                        treatment_id: '',
                        treatment_name: '',
                        tooth_number: '',
                        appointment_date: '',
                        estimated_price: 0.00,
                        status: 'planned',
                        treatment_plan_id: this.treatmentPlan.id
                    });
                    this.hasChanges = true;
                },

                removeItem(index) {
                    const item = this.items[index];

                    // Check if item can be deleted
                    if (item.status === 'done') {
                        alert("Tamamlanmış kalem silinemez ❌");
                        return;
                    }

                    if (item.appointment_date) {
                        alert("Randevusu olan kalem silinemez. Önce randevuyu iptal edin ❌");
                        return;
                    }

                    // Remove from items array
                    this.items.splice(index, 1);

                    // If item has an ID (existing item), add to deletedItems
                    if (item.id) {
                        this.deletedItems.push(item.id);
                    }

                    this.hasChanges = true;
                },

                updatePrice(index) {
                    const treatmentId = this.items[index].treatment_id;
                    const t = this.treatments.find(tr => tr.id.toString() === treatmentId);
                    if (t) {
                        // Update price if default_price exists
                        if (t.default_price) this.items[index].estimated_price = parseFloat(t.default_price);
                        // Update treatment_name
                        this.items[index].treatment_name = t.name;
                    }
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('tr-TR', { minimumFractionDigits: 2 }).format(amount || 0);
                },

                persistChanges(showAlert = false) {
                    if (!this.hasChanges) return;

                    // Prepare new items (items without ID and with treatment selected)
                    const newItems = this.items.filter(item => !item.id && item.treatment_id).map(item => ({
                        treatment_id: item.treatment_id,
                        treatment_name: item.treatment_name,
                        tooth_number: item.tooth_number,
                        appointment_date: item.appointment_date,
                        estimated_price: parseFloat(item.estimated_price) || 0,
                        status: item.status,
                        treatment_plan_id: item.treatment_plan_id
                    }));

                    // Prepare data to send
                    const dataToSend = {
                        new_items: newItems,
                        deleted_items: this.deletedItems
                    };

                    fetch(`/treatment-plans/${this.treatmentPlan.id}`, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(dataToSend)
                    })
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(() => {
                        this.hasChanges = false;
                        if (showAlert) alert("Kaydedildi ✅");
                    })
                    .catch(() => {
                        if (showAlert) alert("Kaydetme hatası ❌");
                    });
                },

                saveChanges() { this.persistChanges(true); }
            }
        }
    </script>
</x-app-layout>
