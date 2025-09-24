<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Yedekleme İşlemleri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium">Veritabanı Yedekleme</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Tüm veritabanının bir yedeğini oluşturun.</p>
                            <form method="POST" action="{{ route('system.backup.create') }}" class="mt-2">
                                @csrf
                                <x-primary-button>{{ __('Yedek Oluştur') }}</x-primary-button>
                            </form>
                        </div>

                        <hr class="dark:border-gray-600">

                        <div>
                            <h3 class="text-lg font-medium">Yedekten Geri Yükle</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">En son yedekten geri yükleme yapın. Bu işlem mevcut verilerin üzerine yazacaktır.</p>
                            <form method="POST" action="{{ route('system.backup.restore') }}" class="mt-2" onsubmit="return confirm('Bu işlem tüm mevcut verilerin üzerine yazacaktır. Emin misiniz?');">
                                @csrf
                                <x-primary-button>{{ __('Geri Yükle') }}</x-primary-button>
                            </form>
                        </div>

                        <hr class="dark:border-gray-600">

                        <div>
                            <h3 class="text-lg font-medium text-red-600 dark:text-red-400">Veritabanını Sıfırla</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Bu işlem, kullanıcılar hariç tüm verileri (hastalar, randevular, faturalar vb.) kalıcı olarak silecektir. Bu işlem geri alınamaz.</p>
                            <form method="POST" action="{{ route('system.backup.delete-data') }}" class="mt-2" onsubmit="return confirm('UYARI: Bu işlem kullanıcılar dışındaki tüm verileri kalıcı olarak silecektir. Bu işlemi geri alamazsınız. Devam etmek istediğinizden emin misiniz?');">
                                @csrf
                                <x-danger-button>{{ __('Tüm Verileri Sil') }}</x-danger-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
