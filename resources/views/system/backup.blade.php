<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Yedekleme İşlemleri') }}
                </h2>
            </div>
            <a href="{{ route('system.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Geri Dön
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Create Backup Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-9 0V1m10 3V1m0 3l1 1v16a2 2 0 01-2 2H6a2 2 0 01-2-2V5l1-1z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Veritabanı Yedekleme</h3>
                    </div>

                <div class="p-6">
                    {{-- Backup Success with Download Link --}}
                    @if(session('backup_file'))
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-green-800 dark:text-green-200">Yedek başarıyla oluşturuldu!</p>
                                    <p class="text-xs text-green-600 dark:text-green-400">{{ session('backup_file') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('system.backup.download', session('backup_file')) }}"
                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                İndir
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- File Upload Success --}}
                    @if(session('uploaded_file'))
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Yedek dosyası başarıyla yüklendi!</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400">{{ session('uploaded_file') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="space-y-6">
                        {{-- Manual Backup Creation --}}
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Manuel Yedek Oluştur</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Sadece veritabanı yedeği alınır. Dosyalar yedeklenmez. En fazla 5 yedek tutulur, eski yedekler otomatik olarak silinir.
                                </p>
                                <form method="POST" action="{{ route('system.backup.create') }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        {{ __('Yedek Oluştur') }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- File Upload for Restoration --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Harici Yedek Dosyası Yükle</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        Bilgisayarınızdan SQL yedek dosyası yükleyin. Bu dosya geri yükleme için kullanılabilir.
                                    </p>
                                    <form method="POST" action="{{ route('system.backup.upload') }}" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="backup_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SQL Yedek Dosyası Seçin</label>
                                            <input type="file" id="backup_file" name="backup_file" accept=".sql"
                                                   class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                          file:mr-4 file:py-2 file:px-4
                                                          file:rounded-lg file:border-0
                                                          file:text-sm file:font-medium
                                                          file:bg-purple-50 file:text-purple-700
                                                          hover:file:bg-purple-100
                                                          dark:file:bg-purple-900/30 dark:file:text-purple-300">
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maksimum dosya boyutu: 50MB</p>
                                        </div>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                            </svg>
                                            Dosyayı Yükle
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Restore Backup Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Yedekten Geri Yükle</h3>
                    </div>

                <div class="p-6">
                    <div class="space-y-6">
                        {{-- Available Backups List --}}
                        <div id="available-backups" class="space-y-3">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">Mevcut Yedekler</h4>
                            <div id="backups-list" class="space-y-2">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Yedekler yükleniyor...</div>
                            </div>
                        </div>

                        {{-- Restore from Selected Backup --}}
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Seçili Yedeği Geri Yükle</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Yukarıdan bir yedek seçin ve geri yükleme yapın. Bu işlem mevcut verilerin üzerine yazacaktır.
                                </p>
                                <form method="POST" action="{{ route('system.backup.restore-file') }}" class="space-y-4" onsubmit="return confirmRestore()">
                                    @csrf
                                    <div>
                                        <label for="restore_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Geri Yüklenecek Yedek</label>
                                        <select id="restore_file" name="restore_file" required
                                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                            <option value="">Yedek seçin...</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        {{ __('Geri Yükle') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reset Database Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-red-200 dark:border-red-700">
                <div class="px-6 py-4 border-b border-red-200 dark:border-red-700">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-red-600 dark:text-red-400">Veritabanını Sıfırla</h3>
                        <span class="text-xs text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/20 px-2 py-1 rounded-full">Tehlikeli İşlem</span>
                    </div>

                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Tüm Verileri Sil</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Bu işlemden önce mutlaka yedek alın. Sistem yedeği olmadan verilerinizi geri getiremezsiniz.
                            </p>
                            <a href="{{ route('system.backup.destroy-data') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                {{ __('Önizleme ve Sil') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmRestore() {
            return confirm('⚠️ UYARI: Bu işlem tüm mevcut verilerin üzerine yazacaktır.\n\nSeçili yedekten geri yükleme yapmak istediğinizden emin misiniz?');
        }

        function confirmReset() {
            return confirm('🚨 KRİTİK UYARI: Bu işlem kullanıcılar dışındaki TÜM verileri kalıcı olarak silecektir!\n\n• Hastalar\n• Randevular\n• Faturalar\n• Stok bilgileri\n• Tedavi kayıtları\n\nBu işlemi GERİ ALAMAZSINIZ!\n\nDevam etmek istediğinizden emin misiniz?');
        }

        // Load available backups on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAvailableBackups();
        });

        function loadAvailableBackups() {
            fetch('{{ route("system.backup.available") }}')
                .then(response => response.json())
                .then(data => {
                    const backupsList = document.getElementById('backups-list');
                    const restoreSelect = document.getElementById('restore_file');

                    if (data.length === 0) {
                        backupsList.innerHTML = '<div class="text-sm text-gray-500 dark:text-gray-400">Henüz hiç yedek bulunmuyor.</div>';
                        restoreSelect.innerHTML = '<option value="">Yedek bulunmuyor</option>';
                        return;
                    }

                    // Populate backups list
                    backupsList.innerHTML = data.map(backup => `
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 dark:text-gray-100">${backup.name}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    ${new Date(backup.created * 1000).toLocaleString('tr-TR')} •
                                    ${(backup.size / 1024 / 1024).toFixed(2)} MB
                                </div>
                            </div>
                            <a href="{{ url('/system/backup/download') }}/${backup.name}"
                               class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                İndir
                            </a>
                        </div>
                    `).join('');

                    // Populate restore select
                    restoreSelect.innerHTML = '<option value="">Yedek seçin...</option>' +
                        data.map(backup => `<option value="${backup.name}">${backup.name} (${(backup.size / 1024 / 1024).toFixed(2)} MB)</option>`).join('');
                })
                .catch(error => {
                    console.error('Yedekler yüklenirken hata:', error);
                    document.getElementById('backups-list').innerHTML = '<div class="text-sm text-red-600 dark:text-red-400">Yedekler yüklenirken hata oluştu.</div>';
                });
        }
    </script>
</x-app-layout>
