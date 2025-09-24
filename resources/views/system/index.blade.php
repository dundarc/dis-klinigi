<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sistem Ayarları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Özet Bilgiler</h3>
                    <div class="mt-4 space-y-2">
                        <p><strong>Klinik Adı:</strong> {{ $settings['clinic_name'] ?? '' }}</p>
                        <p><strong>Adres:</strong> {{ $settings['clinic_city'] ?? '' }}, {{ $settings['clinic_district'] ?? '' }}</p>
                        <p><strong>Vergi Kimlik Numarası:</strong> {{ $settings['clinic_tax_id'] ?? '' }}</p>
                        <p><strong>Vergi Dairesi:</strong> {{ $settings['clinic_tax_office'] ?? '' }}</p>
                        <p><strong>Klinik Telefon Numarası:</strong> {{ $settings['clinic_phone'] ?? '' }}</p>
                        <p><strong>Klinik E-posta Adresi:</strong> {{ $settings['clinic_email'] ?? '' }}</p>
                        <p><strong>Klinik Web Adresi:</strong> {{ $settings['clinic_web'] ?? '' }}</p>
                        <hr class="my-4">
                        <p><strong>Yönetici:</strong> {{ $userCounts[App\Enums\UserRole::ADMIN->value] ?? 0 }} adet</p>
                        <p><strong>Doktor:</strong> {{ $userCounts[App\Enums\UserRole::DENTIST->value] ?? 0 }} adet</p>
                        <p><strong>Resepsiyonist:</strong> {{ $userCounts[App\Enums\UserRole::RECEPTIONIST->value] ?? 0 }} adet</p>
                        <p><strong>Muhasebeci:</strong> {{ $userCounts[App\Enums\UserRole::ACCOUNTANT->value] ?? 0 }} adet</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kullanıcılar Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Kullanıcılar</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Kullanıcıları yönetin, yeni kullanıcılar ekleyin veya mevcut kullanıcıları düzenleyin.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('system.users.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Kullanıcıları Yönet</a>
                        </div>
                    </div>
                </div>

                <!-- Klinik Detayları Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Klinik Detayları</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Klinik adı, adres ve vergi bilgileri gibi detayları düzenleyin.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('system.details') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Detayları Düzenle</a>
                        </div>
                    </div>
                </div>

                <!-- Tedaviler Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tedavi Tanımları</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Klinikte kullanılan tedavi kodlarını ve fiyatlarını yönetin.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('system.treatments.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Tedavileri Yönet</a>
                        </div>
                    </div>
                </div>

                <!-- Yedekleme Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Yedekleme</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Veritabanı yedekleme ve geri yükleme işlemlerini yapın.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('system.backup') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Yedekleme İşlemleri</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
