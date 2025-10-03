<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-gray-100 dark:to-gray-400 bg-clip-text text-transparent">
                        Hasta Yönetimi
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Hasta kayıtlarını görüntüleyin ve yönetin</p>
                </div>
            </div>
            <div class="flex gap-3">
                 <a style="background-color:red" href="{{ route('kvkk.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    KVKK
                </a>
                 <a href="{{ route('treatment-plans.all') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Tedavi Planları
                </a>
                 <a href="{{ route('patients.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Yeni Hasta Ekle
                </a>
             </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-800 dark:to-indigo-900/20 rounded-2xl p-6 shadow-lg border border-blue-200/50 dark:border-indigo-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Toplam Hasta</p>
                            <p class="text-3xl font-bold text-blue-900 dark:text-blue-100" x-text="total || 0"></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-emerald-100 dark:from-gray-800 dark:to-emerald-900/20 rounded-2xl p-6 shadow-lg border border-green-200/50 dark:border-emerald-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Aktif Tedavi</p>
                            <p class="text-3xl font-bold text-green-900 dark:text-green-100">0</p>
                        </div>
                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-100 dark:from-gray-800 dark:to-pink-900/20 rounded-2xl p-6 shadow-lg border border-purple-200/50 dark:border-pink-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Bu Ay Yeni</p>
                            <p class="text-3xl font-bold text-purple-900 dark:text-purple-100">0</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-gradient-to-r from-white via-blue-50/30 to-indigo-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden backdrop-blur-sm" x-data="patientSearch()" x-init="loadPatients()">
                <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hasta Arama</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Ad, soyad, telefon veya TC ile hızlı arama yapın</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Enhanced Search Section -->
                    <div class="mb-8">
                        <div class="max-w-2xl mx-auto">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text"
                                       x-model="query"
                                       @input="search()"
                                       class="block w-full pl-16 pr-6 py-6 text-xl border-2 border-gray-300 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-4 focus:ring-blue-500 focus:border-transparent shadow-lg transition-all duration-300"
                                       placeholder="Hasta adı, TC kimlik, telefon veya kayıt tarihi ile ara...">
                                <div x-show="query.length > 0" @click="query = ''; search()" class="absolute inset-y-0 right-0 pr-6 flex items-center cursor-pointer">
                                    <svg class="h-6 w-6 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-3">
                                <span x-show="query.length === 0">Tüm hastaları görüntülemek için arama yapın</span>
                                <span x-show="query.length > 0" x-text="`Arama: ${query}`"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Enhanced Patient List -->
                    <!-- Desktop Card Grid View -->
                    <div class="hidden md:block">
                        <div x-show="loading" class="text-center py-12">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <p class="mt-4 text-gray-600 dark:text-gray-400">Hastalar yükleniyor...</p>
                        </div>

                        <div x-show="!loading && patients.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <template x-for="patient in patients" :key="patient.id">
                                <div class="bg-gradient-to-br from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-700/50 rounded-2xl border border-gray-200/50 dark:border-gray-600/50 p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold text-lg" x-text="patient.name.charAt(0).toUpperCase()"></span>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                                    <a :href="'/patients/' + patient.id" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors" x-text="patient.name"></a>
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400" x-text="patient.created_at"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span x-show="patient.kvkk_consent" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ __('patient.kvkk.consent') }}
                                            </span>
                                            <span x-show="!patient.kvkk_consent" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ __('patient.kvkk.consent') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="space-y-3 mb-4">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600 dark:text-gray-400" x-text="patient.phone || 'Telefon bilgisi yok'"></span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200/50 dark:border-gray-600/50">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Hasta #<span x-text="patient.id"></span>
                                        </div>
                                        <a :href="'/patients/' + patient.id" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Detaylar
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Enhanced Mobile Card View -->
                    <div class="md:hidden">
                        <div x-show="loading" class="text-center py-12">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <p class="mt-4 text-gray-600 dark:text-gray-400">Hastalar yükleniyor...</p>
                        </div>

                        <div x-show="!loading && patients.length > 0" class="space-y-4">
                            <template x-for="patient in patients" :key="patient.id">
                                <div class="bg-gradient-to-br from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-700/50 rounded-2xl border border-gray-200/50 dark:border-gray-600/50 p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-bold text-lg" x-text="patient.name.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between mb-2">
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">
                                                        <a :href="'/patients/' + patient.id" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors" x-text="patient.name"></a>
                                                    </h3>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400" x-text="patient.created_at"></p>
                                                </div>
                                                <div class="flex items-center space-x-2 ml-2">
                                                    <span x-show="patient.kvkk_consent" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        KVKK
                                                    </span>
                                                    <span x-show="!patient.kvkk_consent" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        KVKK
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                    </svg>
                                                    <span class="text-sm text-gray-600 dark:text-gray-400 truncate" x-text="patient.phone || 'Telefon bilgisi yok'"></span>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between pt-3 border-t border-gray-200/50 dark:border-gray-600/50">
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    Hasta #<span x-text="patient.id"></span>
                                                </div>
                                                <a :href="'/patients/' + patient.id" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Detaylar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="patients.length === 0 && !loading" class="text-center py-16">
                            <div class="w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Hasta bulunamadı</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Arama kriterlerinize uygun hasta bulunmuyor.</p>
                            <button @click="query = ''; search()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Tüm Hastaları Göster
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Pagination & Results Info -->
                    <div x-show="lastPage > 1 || total > 0" class="mt-8 pt-6 border-t border-gray-200/50 dark:border-gray-600/50">
                        <!-- Results Summary -->
                        <div x-show="total > 0" class="text-center mb-6">
                            <div class="inline-flex items-center space-x-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 px-6 py-3 rounded-2xl border border-blue-200/50 dark:border-gray-600/50">
                                <div class="text-center">
                                    <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent" x-text="total"></div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">Toplam Hasta</div>
                                </div>
                                <div class="w-px h-8 bg-gray-300 dark:bg-gray-600"></div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white" x-text="currentPage"></div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Şu Anki Sayfa</div>
                                </div>
                                <div class="w-px h-8 bg-gray-300 dark:bg-gray-600"></div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white" x-text="lastPage"></div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Toplam Sayfa</div>
                                </div>
                            </div>
                        </div>

                        <!-- Modern Pagination -->
                        <div x-show="lastPage > 1" class="flex justify-center">
                            <nav class="flex items-center space-x-2">
                                <!-- Previous Button -->
                                <button @click="previousPage()"
                                        :disabled="currentPage === 1"
                                        :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed bg-gray-100 dark:bg-gray-700' : 'hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white hover:shadow-lg'"
                                        class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl transition-all duration-300 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Önceki
                                </button>

                                <!-- Page Numbers -->
                                <template x-for="page in getVisiblePages()" :key="page">
                                    <button @click="goToPage(page)"
                                            :class="page === currentPage ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'hover:bg-gradient-to-r hover:from-gray-100 hover:to-gray-200 dark:hover:from-gray-700 dark:hover:to-gray-600'"
                                            class="px-4 py-3 text-sm font-medium bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl transition-all duration-300 min-w-[3rem]">
                                        <span x-text="page"></span>
                                    </button>
                                </template>

                                <!-- Next Button -->
                                <button @click="nextPage()"
                                        :disabled="currentPage === lastPage"
                                        :class="currentPage === lastPage ? 'opacity-50 cursor-not-allowed bg-gray-100 dark:bg-gray-700' : 'hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white hover:shadow-lg'"
                                        class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl transition-all duration-300 shadow-sm">
                                    Sonraki
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function patientSearch() {
            return {
                query: '',
                patients: [],
                loading: false,
                searchTimeout: null,
                currentPage: 1,
                lastPage: 1,
                total: 0,

                init() {
                    this.loadPatients();
                },

                loadPatients(page = 1) {
                    this.loading = true;
                    const params = new URLSearchParams();
                    if (this.query.length > 0) {
                        params.append('q', this.query);
                    }
                    params.append('page', page);

                    fetch(`/patients/search?${params.toString()}`)
                        .then(response => response.json())
                        .then(data => {
                            this.patients = data.data;
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
                        this.loadPatients();
                    }, 300); // 300ms debounce
                },

                goToPage(page) {
                    if (page >= 1 && page <= this.lastPage) {
                        this.currentPage = page;
                        this.loadPatients(page);
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

                getVisiblePages() {
                    const delta = 2;
                    const range = [];
                    const rangeWithDots = [];

                    for (let i = Math.max(2, this.currentPage - delta); i <= Math.min(this.lastPage - 1, this.currentPage + delta); i++) {
                        range.push(i);
                    }

                    if (this.currentPage - delta > 2) {
                        rangeWithDots.push(1, '...');
                    } else {
                        rangeWithDots.push(1);
                    }

                    rangeWithDots.push(...range);

                    if (this.currentPage + delta < this.lastPage - 1) {
                        rangeWithDots.push('...', this.lastPage);
                    } else if (this.lastPage > 1) {
                        rangeWithDots.push(this.lastPage);
                    }

                    return rangeWithDots.filter((item, index, arr) => arr.indexOf(item) === index);
                }
            }
        }
    </script>
</x-app-layout>
