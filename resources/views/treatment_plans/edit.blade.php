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
                items: @js($items),
                treatments: @js($treatments),
                patientId: {{ $treatmentPlan->patient_id }},
                loading: false,
                hasChanges: false,
                deletedItems: []
            })"
            x-init="init()"
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

                <!-- Form -->
                <form @submit.prevent="persistChanges" class="p-8 space-y-8">

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
                            x-model="treatmentPlan.dentist_id" @change="hasChanges = true"
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
                            x-model="treatmentPlan.status" @change="hasChanges = true"
                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="draft">📝 Taslak</option>
                            <option value="active">📅 Aktif</option>
                            <option value="completed">✅ Tamamlandı</option>
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
                            x-model="treatmentPlan.notes" @input="hasChanges = true"
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
                                    <button type="button" @click="removeItem(index)"
                                        :disabled="item.status === 'done'"
                                        :class="item.status === 'done' ? 'bg-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700'"
                                        class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
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
                                        <select x-show="!item.id" x-model="item.treatment_id" @change="updatePrice(index); hasChanges = true"
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
                                        <input type="text" x-model="item.tooth_number" @input="hasChanges = true"
                                            :disabled="item.status === 'done'"
                                            :class="item.status === 'done' ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed opacity-60' : 'bg-white dark:bg-gray-700'"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
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
                                        <input type="datetime-local" x-model="item.appointment_date" @input="hasChanges = true"
                                            :disabled="item.status === 'done'"
                                            :class="item.status === 'done' ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed opacity-60' : 'bg-white dark:bg-gray-700'"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
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
                                        <input type="number" step="0.01" min="0.01" x-model="item.estimated_price" @input="updateTotalCost(); hasChanges = true"
                                            :disabled="item.status === 'done'"
                                            :class="item.status === 'done' ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed opacity-60' : 'bg-white dark:bg-gray-700'"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
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
                                        <select x-model="item.status" @change="hasChanges = true"
                                            :disabled="item.status === 'done'"
                                            :class="item.status === 'done' ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed opacity-60' : 'bg-white dark:bg-gray-700'"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                            <option value="planned">📅 Planlandı</option>
                                            <option value="in_progress">⚡ Devam Ediyor</option>
                                            <option value="done">✅ Tamamlandı</option>
                                            <option value="cancelled">❌ İptal Edildi</option>
                                            <option value="no_show">🚫 Gelmedi</option>
                                            <option value="invoiced">💰 Faturalandı</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- DONE item warning -->
                                <div x-show="item.status === 'done'" class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-amber-800 dark:text-amber-200">Tamamlanan öğe düzenlenemez</span>
                                    </div>
                                    <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">Bu tedavi kalemi tamamlandığı için düzenleme yapılamaz.</p>
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

                <!-- Save Status Messages -->
                <div x-show="saveMessage" x-transition class="mb-6 p-4 rounded-lg border"
                     :class="saveMessageType === 'success' ? 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-700 dark:text-green-200' :
                            saveMessageType === 'error' ? 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-700 dark:text-red-200' :
                            'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-200'">
                    <div class="flex items-center">
                        <svg x-show="saveMessageType === 'success'" class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <svg x-show="saveMessageType === 'error'" class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <svg x-show="saveMessageType === 'info'" class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span x-text="saveMessage"></span>
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
                                <p class="text-sm text-blue-700 dark:text-blue-300">Düzenlenebilir tedavilerin toplam ücreti</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                                 x-text="formatCurrency(totalCost)"></div>
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
                        :disabled="!hasChanges || saving"
                        :class="(!hasChanges || saving) ? 'bg-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 shadow-lg hover:shadow-xl'"
                        class="px-8 py-3 text-white font-medium rounded-xl transition-all duration-300 flex items-center">
                        <svg x-show="saving" class="animate-spin -ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="!hasChanges && !saving">Değişiklik Yok</span>
                        <span x-show="hasChanges && !saving">Değişiklikleri Kaydet</span>
                        <span x-show="saving">Kaydediliyor...</span>
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
                saving: false,
                saveMessage: '',
                saveMessageType: '',

                init() {
                    // Deep watch for items array changes - reactivity sağlamak için
                    this.$watch('items', (newItems, oldItems) => {
                        this.hasChanges = true;
                        this.updateTotalCost();
                    }, { deep: true });

                    // Watch for treatment plan changes
                    this.$watch('treatmentPlan', (newVal, oldVal) => {
                        this.hasChanges = true;
                    }, { deep: true });

                    // Watch for deleted items
                    this.$watch('deletedItems', (newVal, oldVal) => {
                        this.hasChanges = true;
                    });

                    // Initial total cost calculation
                    this.updateTotalCost();
                },

                // Auto-save kaldırıldı - sadece manuel kaydetme


                get totalCost() {
                    // Computed property olarak toplam maliyeti hesapla - tamamlanan öğeler hariç
                    return this.items
                        .filter(item => item.status !== 'done') // Tamamlanan öğeleri hariç tut
                        .reduce((t, i) => t + (parseFloat(i.estimated_price) || 0), 0);
                },

                updateTotalCost() {
                    // Force reactivity update for total cost - treatmentPlan objesini güncelle
                    // Tamamlanan öğeler hariç
                    this.treatmentPlan = {
                        ...this.treatmentPlan,
                        total_estimated_cost: this.items
                            .filter(item => item.status !== 'done')
                            .reduce((total, item) => {
                                return total + (parseFloat(item.estimated_price) || 0);
                            }, 0)
                    };
                },

                addItem() {
                    // Tedavi seçilmeden kalem eklenemesin - validation kontrolü
                    if (!this.treatmentPlan.dentist_id) {
                        this.showMessage('Önce sorumlu diş hekimi seçmelisiniz.', 'error');
                        alert('❌ Önce sorumlu diş hekimi seçmelisiniz.');
                        return;
                    }

                    // Create new item with proper defaults - reactive olması için düzgün yapı
                    const newItem = {
                        id: null,
                        treatment_id: '',
                        treatment_name: '',
                        tooth_number: '',
                        appointment_date: '',
                        estimated_price: 0.01, // Minimum validation değeri
                        status: 'planned',
                        treatment_plan_id: this.treatmentPlan.id
                    };

                    // Alpine.js reactivity için items array'ini yeniden ata
                    this.items = [...this.items, newItem];
                    this.hasChanges = true;

                    // Toplam maliyeti güncelle
                    this.updateTotalCost();
                },

                removeItem(index) {
                    const item = this.items[index];

                    // Validation checks - güvenlik kontrolleri
                    if (item.status === 'done') {
                        this.showMessage('Tamamlanmış tedavi kalemi silinemez.', 'error');
                        alert('❌ Tamamlanmış tedavi kalemi silinemez.');
                        return;
                    }

                    if (item.appointment_date && item.appointment_date.trim() !== '') {
                        this.showMessage('Randevusu olan tedavi kalemi silinemez. Önce randevuyu iptal edin.', 'error');
                        alert('❌ Randevusu olan tedavi kalemi silinemez. Önce randevuyu iptal edin.');
                        return;
                    }

                    // Confirm deletion for existing items
                    if (item.id && !confirm('Bu tedavi kalemini silmek istediğinizden emin misiniz?')) {
                        return;
                    }

                    // Remove from items array - reactivity için splice kullan
                    this.items.splice(index, 1);

                    // If item has an ID (existing item), add to deletedItems
                    if (item.id) {
                        this.deletedItems.push(item.id);
                    }

                    this.hasChanges = true;
                    // Toplam maliyeti güncelle
                    this.updateTotalCost();
                },

                showMessage(message, type = 'info') {
                    this.saveMessage = message;
                    this.saveMessageType = type;

                    // Clear message after appropriate time
                    const timeout = type === 'error' ? 5000 : 3000;
                    setTimeout(() => {
                        this.saveMessage = '';
                        this.saveMessageType = '';
                    }, timeout);
                },

                updatePrice(index) {
                    const treatmentId = this.items[index].treatment_id;
                    const treatment = this.treatments.find(tr => tr.id.toString() === treatmentId);
                    if (treatment) {
                        // Update price if default_price exists - varsayılan fiyatı ayarla
                        if (treatment.default_price && (!this.items[index].estimated_price || this.items[index].estimated_price == 0)) {
                            this.items[index].estimated_price = parseFloat(treatment.default_price);
                        }
                        // Update treatment_name - tedavi adını güncelle
                        this.items[index].treatment_name = treatment.name;

                        // Reactivity için items array'ini güncelle
                        this.items = [...this.items];
                        this.hasChanges = true;
                        this.updateTotalCost();
                    }
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('tr-TR', { minimumFractionDigits: 2 }).format(amount || 0);
                },

                async persistChanges() {
                    if (!this.hasChanges || this.saving) return;

                    this.saving = true;
                    this.saveMessage = '';
                    this.saveMessageType = '';

                    try {
                        // Validate required fields before sending - validation kontrolü
                        if (!this.treatmentPlan.dentist_id) {
                            const errorMsg = 'Sorumlu diş hekimi seçilmelidir.';
                            alert('❌ ' + errorMsg);
                            throw new Error(errorMsg);
                        }

                        // Eğer hiç değişiklik yoksa çık
                        if (!this.hasChanges) {
                            return;
                        }

                        // Items validation - sadece düzenlenebilir olan geçerli tedavi kalemleri için validation
                        const validItems = this.items.filter(item => item.status !== 'done' && item.treatment_id && item.estimated_price > 0);
                        for (const item of validItems) {
                            if (!item.treatment_id) {
                                const errorMsg = 'Tüm tedavi kalemleri için tedavi türü seçilmelidir.';
                                alert('❌ ' + errorMsg);
                                throw new Error(errorMsg);
                            }
                            if (!item.estimated_price || parseFloat(item.estimated_price) < 0.01) {
                                const errorMsg = 'Tüm tedavi kalemleri için geçerli bir fiyat (minimum 0.01) girilmelidir.';
                                alert('❌ ' + errorMsg);
                                throw new Error(errorMsg);
                            }
                        }

                        // Prepare form data for submission - tüm current state'i gönder
                        const formData = new FormData();

                        // Treatment plan verileri (her zaman gönder)
                        formData.append('dentist_id', this.treatmentPlan.dentist_id);
                        formData.append('status', this.treatmentPlan.status);
                        formData.append('notes', this.treatmentPlan.notes || '');

                        // Sadece düzenlenebilir olan geçerli items'ları gönder
                        let validItemIndex = 0;
                        this.items.forEach((item) => {
                            // Tamamlanan öğeleri gönderme (zaten düzenlenemez)
                            if (item.status === 'done') {
                                return; // Bu öğeyi atla
                            }

                            // Sadece geçerli item'ları gönder (treatment_id ve estimated_price dolu olanlar)
                            if (item.treatment_id && item.estimated_price > 0) {
                                console.log('Gönderilen öğe:', {
                                    id: item.id,
                                    treatment_id: item.treatment_id,
                                    appointment_date: item.appointment_date,
                                    estimated_price: item.estimated_price,
                                    status: item.status
                                });

                                if (item.id) {
                                    // Existing item
                                    formData.append(`items[${validItemIndex}][id]`, item.id);
                                    formData.append(`items[${validItemIndex}][treatment_id]`, item.treatment_id);
                                    formData.append(`items[${validItemIndex}][tooth_number]`, item.tooth_number || '');
                                    formData.append(`items[${validItemIndex}][appointment_date]`, item.appointment_date || '');
                                    formData.append(`items[${validItemIndex}][estimated_price]`, item.estimated_price || 0);
                                    formData.append(`items[${validItemIndex}][status]`, item.status);
                                } else {
                                    // New item - debug log
                                    console.log('Yeni öğe gönderiliyor:', {
                                        treatment_id: item.treatment_id,
                                        appointment_date: item.appointment_date,
                                        estimated_price: item.estimated_price
                                    });
                                    formData.append(`new_items[${validItemIndex}][treatment_id]`, item.treatment_id);
                                    formData.append(`new_items[${validItemIndex}][tooth_number]`, item.tooth_number || '');
                                    formData.append(`new_items[${validItemIndex}][appointment_date]`, item.appointment_date || '');
                                    formData.append(`new_items[${validItemIndex}][estimated_price]`, item.estimated_price || 0);
                                    formData.append(`new_items[${validItemIndex}][status]`, item.status);
                                }
                                validItemIndex++;
                            }
                        });

                        // Add deleted items
                        this.deletedItems.forEach((itemId, index) => {
                            formData.append(`deleted_items[${index}]`, itemId);
                        });

                        // Normal update endpoint kullan
                        const response = await fetch(`/treatment-plans/${this.treatmentPlan.id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'X-HTTP-Method-Override': 'PATCH'
                            },
                            body: formData
                        });

                        if (!response.ok) {
                            let errorMessage = 'Sunucu hatası oluştu.';
                            try {
                                const errorData = await response.json();
                                errorMessage = errorData.message || errorMessage;
                            } catch (e) {
                                // If response is not JSON, use status text
                                errorMessage = response.statusText || errorMessage;
                            }
                            throw new Error(errorMessage);
                        }

                        const result = await response.json();

                        // Update local data with server response - server yanıtını işle
                        if (result.updated_items) {
                            this.items = result.updated_items.map(item => ({
                                id: item.id,
                                treatment_id: item.treatment_id,
                                treatment_name: item.treatment_name || '',
                                tooth_number: item.tooth_number || '',
                                appointment_date: item.appointment_date || '',
                                estimated_price: parseFloat(item.estimated_price) || 0,
                                status: item.status,
                                treatment_plan_id: this.treatmentPlan.id
                            }));
                        }

                        if (result.total_cost !== undefined) {
                            this.treatmentPlan.total_estimated_cost = result.total_cost;
                        }

                        if (result.plan_status) {
                            this.treatmentPlan.status = result.plan_status;
                        }

                        // Clear changes - değişiklikleri temizle
                        this.hasChanges = false;
                        this.deletedItems = [];

                        // Show success message and alert - başarı mesajı göster
                        this.saveMessage = 'Değişiklikler başarıyla kaydedildi.';
                        this.saveMessageType = 'success';
                        alert('✅ Değişiklikler başarıyla kaydedildi!');

                        // Clear message after 3 seconds
                        setTimeout(() => {
                            this.saveMessage = '';
                            this.saveMessageType = '';
                        }, 3000);

                    } catch (error) {
                        console.error('Save error:', error);
                        this.saveMessage = error.message || 'Kaydetme sırasında bir hata oluştu.';
                        this.saveMessageType = 'error';
                        alert('❌ Hata: ' + (error.message || 'Kaydetme sırasında bir hata oluştu.'));

                        // Clear error message after 5 seconds
                        setTimeout(() => {
                            this.saveMessage = '';
                            this.saveMessageType = '';
                        }, 5000);
                    } finally {
                        this.saving = false;
                    }
                },


            }
        }
    </script>
</x-app-layout>
