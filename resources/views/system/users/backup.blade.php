<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Yedekleme İşlemleri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Yeni Yedek Alma -->
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Yeni Yedek Oluştur</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Sadece veritabanının (hastalar, randevular, faturalar vb.) yedeğini oluşturur. Bu işlem arka planda çalışır.
                </p>
                <form method="POST" action="{{ route('system.backup.run') }}" class="mt-4">
                    @csrf
                    <x-primary-button>Veritabanını Yedekle</x-primary-button>
                </form>
            </x-card>
            
            <!-- Mevcut Yedekler -->
            <x-card>
                 <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Mevcut Yedekler</h3>
                 <div class="mt-4 space-y-2">
                     @forelse($backups as $backup)
                        <div class="flex justify-between items-center p-2 border rounded-md dark:border-gray-700">
                            <span class="font-mono text-sm">{{ $backup->path() }}</span>
                            <span class="text-sm text-gray-500">{{ $backup->sizeInBytes() / 1024 / 1024 > 1 ? number_format($backup->sizeInBytes() / 1024 / 1024, 2) . ' MB' : number_format($backup->sizeInBytes() / 1024, 2) . ' KB' }}</span>
                        </div>
                     @empty
                        <p class="text-sm text-gray-500">Henüz hiç yedek oluşturulmamış.</p>
                     @endforelse
                 </div>
            </x-card>
            
            <!-- Tehlikeli İşlemler -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-red-600 dark:text-red-400">Veritabanını Temizle</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        BU İŞLEM GERİ ALINAMAZ! Tüm hastaları, randevuları, faturaları ve ziyaret kayıtlarını kalıcı olarak siler. Sadece kullanıcılar ve sistem ayarları korunur.
                    </p>
                    <form method="POST" action="{{ route('system.backup.wipe') }}" class="mt-6" onsubmit="return confirm('TÜM KLİNİK VERİLERİNİ (HASTALAR, RANDEVULAR, FATURALAR VB.) SİLMEK İSTEDİĞİNİZDEN EMİN MİSİNİZ? BU İŞLEM GERİ ALINAMAZ!');">
                        @csrf
                        <x-danger-button>Tüm Klinik Verilerini Sil</x-danger-button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
