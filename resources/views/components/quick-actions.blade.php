<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Hızlı İşlemler</h3>

        <!-- Action Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <!-- File & Documents -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Dosya & Evrak</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tedaviye dosya yükleme</p>
                        <button @click="openModal('fileUpload')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors">
                            Dosya Yükle
                        </button>
                    </div>
                </div>
            </div>

            <!-- Appointment Check-in -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-4 rounded-lg border border-green-200 dark:border-green-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Randevu Check-in</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Randevuyu başlat</p>
                        <button @click="openModal('appointmentCheckin')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors">
                            Check-in Yap
                        </button>
                    </div>
                </div>
            </div>

            <!-- New Appointment -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-4 rounded-lg border border-purple-200 dark:border-purple-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Yeni Randevu</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Randevu oluştur</p>
                        <button @click="openModal('newAppointment')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded transition-colors">
                            Randevu Oluştur
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cancel Appointment -->
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 p-4 rounded-lg border border-red-200 dark:border-red-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Randevu İptal</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Randevuyu iptal et</p>
                        <button @click="openModal('cancelAppointment')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                            İptal Et
                        </button>
                    </div>
                </div>
            </div>

            <!-- New Patient -->
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 p-4 rounded-lg border border-indigo-200 dark:border-indigo-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Yeni Hasta</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Hasta kaydı oluştur</p>
                        <button @click="openModal('newPatient')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded transition-colors">
                            Hasta Ekle
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Patient Search -->
            <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900/20 dark:to-cyan-800/20 p-4 rounded-lg border border-cyan-200 dark:border-cyan-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Hasta Bul</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Hızlı hasta arama</p>
                        <button @click="openModal('patientSearch')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-medium rounded transition-colors">
                            Hasta Ara
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Patient Update -->
            <div class="bg-gradient-to-br from-violet-50 to-violet-100 dark:from-violet-900/20 dark:to-violet-800/20 p-4 rounded-lg border border-violet-200 dark:border-violet-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Hasta Güncelle</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Hasta bilgileri düzenle</p>
                        <button @click="openModal('patientUpdate')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-violet-600 hover:bg-violet-700 text-white text-xs font-medium rounded transition-colors">
                            Bilgileri Güncelle
                        </button>
                    </div>
                </div>
            </div>

            <!-- New Treatment Plan -->
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 p-4 rounded-lg border border-emerald-200 dark:border-emerald-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Tedavi Planı</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Yeni plan oluştur</p>
                        <button @click="openModal('newTreatmentPlan')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded transition-colors">
                            Plan Oluştur
                        </button>
                    </div>
                </div>
            </div>

            <!-- Add to Treatment Plan -->
            <div class="bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/20 dark:to-teal-800/20 p-4 rounded-lg border border-teal-200 dark:border-teal-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Plana İşlem Ekle</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Mevcut plana kalem ekle</p>
                        <button @click="openModal('addToTreatmentPlan')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-medium rounded transition-colors">
                            Kalem Ekle
                        </button>
                    </div>
                </div>
            </div>

            <!-- Treatment Plan PDF -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 p-4 rounded-lg border border-orange-200 dark:border-orange-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Plan PDF</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tedavi planı indir</p>
                        <button @click="openModal('treatmentPlanPdf')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded transition-colors">
                            PDF İndir
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stock Item (Admin/Accountant only) -->
            @can('accessStockManagement')
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/20 dark:to-slate-800/20 p-4 rounded-lg border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Stok Kalemi</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Yeni ürün ekle</p>
                        <button @click="openModal('newStockItem')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-slate-600 hover:bg-slate-700 text-white text-xs font-medium rounded transition-colors">
                            Stok Ekle
                        </button>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Invoice/Purchase (Admin/Accountant only) -->
            @can('accessStockManagement')
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 p-4 rounded-lg border border-amber-200 dark:border-amber-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Fatura/Gider</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Satın alma/fatura ekle</p>
                        <button @click="openModal('newInvoice')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded transition-colors">
                            Fatura Ekle
                        </button>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Payment (Admin/Accountant only) -->
            @can('accessStockManagement')
            <div class="bg-gradient-to-br from-rose-50 to-rose-100 dark:from-rose-900/20 dark:to-rose-800/20 p-4 rounded-lg border border-rose-200 dark:border-rose-800 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Ödeme Kaydı</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Ödeme girişi yap</p>
                        <button @click="openModal('newPayment')" class="mt-3 inline-flex items-center px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded transition-colors">
                            Ödeme Ekle
                        </button>
                    </div>
                </div>
            </div>
            @endcan

        </div>
    </div>
</div>