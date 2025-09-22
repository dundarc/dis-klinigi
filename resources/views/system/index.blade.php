<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sistem Ayarları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sol Taraf: Özet Bilgiler -->
                <div class="lg:col-span-2">
                    <x-card>
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4 dark:border-gray-700">Klinik Özet Bilgileri</h3>
                        <div class="space-y-3 text-gray-700 dark:text-gray-300">
                            <p><strong>Klinik Adı:</strong> {{ $clinicDetails['name'] ?? 'Belirtilmemiş' }}</p>
                            <p><strong>Adres:</strong> {{ $clinicDetails['address'] ?? '' }}, {{ $clinicDetails['district'] ?? '' }}/{{ $clinicDetails['city'] ?? '' }}</p>
                            <p><strong>Vergi Dairesi:</strong> {{ $clinicDetails['tax_office'] ?? '' }} - <strong>VKN:</strong> {{ $clinicDetails['tax_id'] ?? '' }}</p>
                            <p><strong>Telefon:</strong> {{ $clinicDetails['phone'] ?? '' }}</p>
                            <p><strong>E-posta:</strong> {{ $clinicDetails['email'] ?? '' }}</p>
                            <p><strong>Web Sitesi:</strong> <a href="{{ $clinicDetails['website'] ?? '#' }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $clinicDetails['website'] ?? '' }}</a></p>
                        </div>

                        <h3 class="text-lg font-semibold border-b pb-2 mt-8 mb-4 dark:border-gray-700">Kullanıcı İstatistikleri</h3>
                        <div class="space-y-3 text-gray-700 dark:text-gray-300">
                            <p><strong>Yönetici:</strong> {{ $userCounts[App\Enums\UserRole::ADMIN->value] ?? 0 }} adet</p>
                            <p><strong>Doktor:</strong> {{ $userCounts[App\Enums\UserRole::DENTIST->value] ?? 0 }} adet</p>
                            <p><strong>Resepsiyonist:</strong> {{ $userCounts[App\Enums\UserRole::RECEPTIONIST->value] ?? 0 }} adet</p>
                            <p><strong>Muhasebeci:</strong> {{ $userCounts[App\Enums\UserRole::ACCOUNTANT->value] ?? 0 }} adet</p>
                        </div>
                    </x-card>
                </div>

                <!-- Sağ Taraf: Yönetim Kartları -->
                <div class="space-y-6">
                    <a href="{{ route('system.details') }}" class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700">
                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">Klinik Detayları</h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">Klinik adı, adres ve vergi bilgilerini düzenleyin.</p>
                    </a>
                    <a href="{{ route('system.users.index') }}" class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700">
                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">Kullanıcılar</h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">Yeni kullanıcı ekleyin, mevcutları düzenleyin veya silin.</p>
                    </a>
                    <a href="{{ route('system.backup') }}" class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700">
                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">Yedekleme İşlemleri</h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">Sistem verilerini yedekleyin, geri yükleyin veya temizleyin.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
