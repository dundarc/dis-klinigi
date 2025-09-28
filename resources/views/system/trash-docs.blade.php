<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Silinen Dosyalar</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Silinen dosyaları yönetin</p>
            </div>
            <a href="{{ route('system.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Sistem Ayarlarına Dön
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Silinen Dosyalar</h3>
                        <span class="text-xs px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 rounded-full">
                            {{ $trashedFiles->total() }} dosya
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    @if($trashedFiles->isNotEmpty())
                        <!-- Bulk Actions -->
                        <div class="mb-4 flex items-center gap-2">
                            <button type="button"
                                    x-data="{ selectedFiles: [] }"
                                    x-show="selectedFiles.length > 0"
                                    @click="bulkRestore()"
                                    class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Seçilenleri Geri Yükle
                            </button>
                            <button type="button"
                                    x-data="{ selectedFiles: [] }"
                                    x-show="selectedFiles.length > 0"
                                    @click="bulkDelete()"
                                    class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Seçilenleri Kalıcı Sil
                            </button>
                        </div>

                        <!-- Files Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            <input type="checkbox" x-model="selectAll" class="rounded border-slate-300 dark:border-slate-600">
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dosya Adı</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hasta</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Yükleyen</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Silinme Tarihi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tür</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Boyut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($trashedFiles as $file)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox"
                                                       x-model="selectedFiles"
                                                       value="{{ $file->id }}"
                                                       class="rounded border-slate-300 dark:border-slate-600">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                                    {{ $file->display_name }}
                                                </div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $file->filename }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-slate-900 dark:text-slate-100">
                                                    {{ $file->patient?->first_name }} {{ $file->patient?->last_name }}
                                                </div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                                    TC: {{ $file->patient?->national_id }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-slate-900 dark:text-slate-100">
                                                    {{ $file->uploader?->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                                                {{ $file->deleted_at?->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                                    {{ $file->type->label() }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                                                {{ number_format($file->size / 1024, 1) }} KB
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <form method="POST" action="{{ route('system.trash-docs.restore', $file) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                            Geri Yükle
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('system.trash-docs.force-delete', $file) }}"
                                                          onsubmit="return confirm('Bu dosyayı kalıcı olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')"
                                                          class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                            Kalıcı Sil
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $trashedFiles->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Silinen dosya bulunmuyor</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Henüz hiç dosya silinmemiş.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function bulkActions() {
            return {
                selectedFiles: [],
                selectAll: false,

                init() {
                    this.$watch('selectAll', (value) => {
                        if (value) {
                            this.selectedFiles = @json($trashedFiles->pluck('id'));
                        } else {
                            this.selectedFiles = [];
                        }
                    });
                },

                bulkRestore() {
                    if (this.selectedFiles.length === 0) return;

                    if (!confirm(`${this.selectedFiles.length} dosyayı geri yüklemek istediğinizden emin misiniz?`)) return;

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("system.trash-docs.bulk-restore") }}';

                    // CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrfInput);

                    // File IDs
                    this.selectedFiles.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'file_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                },

                bulkDelete() {
                    if (this.selectedFiles.length === 0) return;

                    if (!confirm(`${this.selectedFiles.length} dosyayı kalıcı olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!`)) return;

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("system.trash-docs.bulk-force-delete") }}';

                    // CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrfInput);

                    // File IDs
                    this.selectedFiles.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'file_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>
</x-app-layout>