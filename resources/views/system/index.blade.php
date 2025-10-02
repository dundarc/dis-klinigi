<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Sistem Ayarları') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Klinik Bilgileri --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 overflow-hidden shadow-lg sm:rounded-xl border border-blue-100 dark:border-gray-600">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Klinik Bilgileri</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Klinik Adı</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $settings['clinic_name'] ?? 'Belirtilmemiş' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Adres</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $settings['clinic_city'] ?? '' }}, {{ $settings['clinic_district'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">İletişim</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $settings['clinic_phone'] ?? '' }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $settings['clinic_email'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kullanıcı İstatistikleri --}}
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-700 overflow-hidden shadow-lg sm:rounded-xl border border-green-100 dark:border-gray-600">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Kullanıcı İstatistikleri</h3>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full mb-3">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $userCounts[App\Enums\UserRole::ADMIN->value] ?? 0 }}</p>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Yönetici</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full mb-3">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $userCounts[App\Enums\UserRole::DENTIST->value] ?? 0 }}</p>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Doktor</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-full mb-3">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $userCounts[App\Enums\UserRole::RECEPTIONIST->value] ?? 0 }}</p>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Resepsiyonist</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full mb-3">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $userCounts[App\Enums\UserRole::ACCOUNTANT->value] ?? 0 }}</p>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Muhasebeci</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sistem Yönetimi Kartları --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Kullanıcılar --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-gray-200 dark:border-gray-600 group hover:border-blue-300 dark:hover:border-blue-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Kullanıcı Yönetimi</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Kullanıcıları yönetin</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Kullanıcıları görüntüleyin, yeni kullanıcılar ekleyin veya mevcut kullanıcıları düzenleyin.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Yönet
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Klinik Detayları --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-gray-200 dark:border-gray-600 group hover:border-green-300 dark:hover:border-green-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Klinik Detayları</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Bilgileri düzenleyin</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Klinik adı, adres ve vergi bilgileri gibi detayları düzenleyin.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.details') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Düzenle
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Tedavi Tanımları --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-gray-200 dark:border-gray-600 group hover:border-purple-300 dark:hover:border-purple-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Tedavi Tanımları</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Tedavileri yönetin</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Klinikte kullanılan tedavi kodlarını ve fiyatlarını yönetin.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.treatments.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Yönet
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Yedekleme --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-gray-200 dark:border-gray-600 group hover:border-orange-300 dark:hover:border-orange-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Yedekleme</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Veri güvenliği</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Veritabanı yedekleme ve geri yükleme işlemlerini yapın.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.backup') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                İşlemler
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Çöp Kutusu --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-gray-200 dark:border-gray-600 group hover:border-red-300 dark:hover:border-red-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-red-500 to-red-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Çöp Kutusu</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Silinen dosyalar</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Soft delete yapılmış dosyaları geri yükleyin veya kalıcı olarak silin.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.trash-docs.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Yönet
                            </a>
                        </div>
                    </div>
                </div>

                {{-- E-posta Ayarları --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-gray-200 dark:border-gray-600 group hover:border-indigo-300 dark:hover:border-indigo-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">E-posta Ayarları</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">SMTP ve şablonlar</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                SMTP ayarları, e-posta şablonları, gönderim logları ve istatistikleri yönetin.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.email.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Yönet
                            </a>
                        </div>
                    </div>
                </div>

                {{-- AI Ayarları --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl border border-gray-200 dark:border-gray-600 group hover:border-cyan-300 dark:hover:border-cyan-500">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">AI Ayarları</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Yapay zeka entegrasyonu</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                AI API anahtarı ve bağlantı ayarlarını yapılandırın, test edin.
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('system.ai.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Yönet
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
