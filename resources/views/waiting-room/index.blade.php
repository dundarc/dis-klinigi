<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bekleme Odası') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Randevu Bekleyenler Kartı -->
                <a href="{{ route('waiting-room.appointments') }}" class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Randevu Bekleyen Hastalar</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Kliniğe check-in yapmış, randevulu hastaları görüntüleyin ve yönetin.</p>
                </a>

                <!-- Acil Hastalar Kartı -->
                <a href="{{ route('waiting-room.emergency') }}" class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Acil Hastalar Listesi</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Triyaj seviyesine göre sıralanmış acil ve randevusuz hastaları yönetin.</p>
                </a>

                <!-- Tamamlanan İşlemler Kartı -->
                <a href="{{ route('waiting-room.completed') }}" class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tamamlanan İşlemler</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Bugün işlemleri tamamlanmış hastaların listesini görüntüleyin.</p>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>