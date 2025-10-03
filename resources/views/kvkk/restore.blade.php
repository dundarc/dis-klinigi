<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">KVKK Veri Geri Yükleme</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Soft delete edilmiş hasta verilerini geri yükleme</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('kvkk.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    KVKK Ana Sayfa
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="kvkkRestore">
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Uyarı Kutucuğu -->
                <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 rounded-xl p-6 border border-red-200 dark:border-red-800">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Yönetici Yetkisi Gerekli</h3>
                            <div class="text-sm text-slate-600 dark:text-slate-300">
                                <p>Bu sayfadaki işlemler sadece yönetici yetkisine sahip kullanıcılar tarafından gerçekleştirilebilir. KVKK uyumluluğu için soft delete edilmiş hasta verilerini geri yükleme işlemi dikkatli bir şekilde yapılmalıdır.</p>
                                <p class="mt-2 font-medium">⚠️ Geri yükleme işlemleri audit log ile kaydedilir ve raporlanır.</p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Hasta Listesi -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Soft Delete Edilmiş Hastalar</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                            KVKK uyumluluğu için silinmiş hasta kayıtları ({{ $deletedPatients->total() }} toplam)
                        </p>
                    </div>

                    @if($deletedPatients->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Hasta</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Silinme Tarihi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Son İşlem</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($deletedPatients as $patient)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                            <td class="px-4 py-3">
                                                <div>
                                                    <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                                    </p>
                                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                                        TC: {{ $patient->national_id ? '*** *** ** ' . substr($patient->national_id, -2) : 'Belirtilmemiş' }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">
                                                {{ $patient->deleted_at?->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                @if($patient->kvkkAuditLogs->first())
                                                    @if($patient->kvkkAuditLogs->first()->action->value === 'soft_delete')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                            Soft Delete
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                            {{ ucfirst($patient->kvkkAuditLogs->first()->action->value) }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-slate-500 dark:text-slate-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex gap-2">
                                                    <!-- Tekil Geri Yükleme -->
                                                    <button @click="showRestoreModal = true; selectedPatientId = '{{ $patient->id }}'; selectedPatientName = '{{ $patient->first_name }} {{ $patient->last_name }}'"
                                                            class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                        </svg>
                                                        Geri Yükle
                                                    </button>

                                                    <!-- Kalıcı Silme -->
                                                    <button @click="showHardDeleteModal = true; selectedPatientId = '{{ $patient->id }}'; selectedPatientName = '{{ $patient->first_name }} {{ $patient->last_name }}'"
                                                            class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Kalıcı Sil
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Sayfalama -->
                        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                            {{ $deletedPatients->links() }}
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-2">
                                Geri yüklenecek hasta bulunmuyor
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400">
                                KVKK uyumluluğu için soft delete edilmiş hasta kaydı bulunmuyor.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>



        <!-- Single Restore Modal -->
        <div x-show="showRestoreModal" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             @keydown.escape.window="showRestoreModal = false">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="showRestoreModal = false">
                    <div class="absolute inset-0 bg-slate-500 dark:bg-slate-900 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="'{{ route('kvkk.restore', '') }}/' + selectedPatientId" method="POST">
                        @csrf
                        <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-slate-100">
                                        Geri Yükleme Onayı
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500 dark:text-slate-400">
                                            <strong><span x-text="selectedPatientName"></span></strong> hastasının tüm verilerini geri yüklemek istediğinizden emin misiniz?
                                        </p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                                            Bu işlem hastanın randevularını, faturalarını ve diğer verilerini normal erişime geri döndürecektir.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Geri Yükle
                            </button>
                            <button type="button" @click="showRestoreModal = false"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                İptal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Hard Delete Modal -->
        <div x-show="showHardDeleteModal" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             @keydown.escape.window="showHardDeleteModal = false">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="showHardDeleteModal = false">
                    <div class="absolute inset-0 bg-slate-500 dark:bg-slate-900 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="'{{ route('kvkk.hard-delete', '') }}/' + selectedPatientId" method="POST" x-data="{ confirmed: false }">
                        @csrf
                        <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-slate-100">
                                        Kalıcı Silme Onayı
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-red-600 dark:text-red-400 font-medium">
                                            ⚠️ Bu işlem geri alınamaz!
                                        </p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                                            <strong><span x-text="selectedPatientName"></span></strong> hastasının tüm verilerini kalıcı olarak silmek istediğinizden emin misiniz?
                                        </p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                                            Bu işlem tüm randevuları, faturalarını, tedavi planlarını ve dosyaları kalıcı olarak silecektir.
                                        </p>
                                        <div class="mt-4">
                                            <div class="flex items-center">
                                                <input id="hard-delete-confirm" type="checkbox" x-model="confirmed"
                                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-slate-300 rounded">
                                                <label for="hard-delete-confirm" class="ml-2 block text-sm text-slate-900 dark:text-slate-100">
                                                    İşlemin geri alınamayacağını anladım
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" :disabled="!confirmed"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed sm:ml-3 sm:w-auto sm:text-sm">
                                Kalıcı Olarak Sil
                            </button>
                            <button type="button" @click="showHardDeleteModal = false"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                İptal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kvkkRestore', () => ({
                showRestoreModal: false,
                showHardDeleteModal: false,
                selectedPatientId: null,
                selectedPatientName: null
            }))
        })
    </script>
</x-app-layout>
