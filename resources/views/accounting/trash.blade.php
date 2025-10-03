<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Çöp Kutusu</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Silinen faturaları yönetin</p>
            </div>
            <a href="{{ route('accounting.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Muhasebeye Dön
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="trashManager()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Silinen Faturalar</h3>
                        <span x-show="selectedInvoices.length > 0" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                            <span x-text="selectedInvoices.length"></span> seçili
                        </span>
                    </div>
                    <div class="flex gap-2" x-show="selectedInvoices.length > 0">
                        <button @click="bulkRestore()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Geri Yükle
                        </button>
                        <button @click="bulkDelete()" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Kalıcı Sil
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0">
                            <tr>
                                <th class="px-6 py-4 text-left">
                                    <input type="checkbox" @change="toggleAll()" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Fatura No</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hasta</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tutar</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Silinme Tarihi</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($trashedInvoices as $invoice)
                                <tr class="{{ $loop->even ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800' }} hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" :value="$invoice->id" x-model="selectedInvoices" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900 dark:text-slate-100">{{ $invoice->invoice_no }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</td>
                                    <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->deleted_at->format('d.m.Y H:i') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <form action="{{ route('accounting.trash.restore', $invoice->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Geri Yükle
                                                </button>
                                            </form>
                                            <form action="{{ route('accounting.trash.remove', $invoice->id) }}" method="POST" class="inline" onsubmit="return confirm('Bu faturayı KALICI OLARAK silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!');">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Kalıcı Sil
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Çöp kutusu boş</h3>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Henüz hiç fatura silinmemiş veya tümü geri yüklenmiş.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function trashManager() {
            return {
                selectedInvoices: [],

                toggleAll() {
                    const checkboxes = document.querySelectorAll('input[type="checkbox"][x-model="selectedInvoices"]');
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

                    if (allChecked) {
                        this.selectedInvoices = [];
                    } else {
                        this.selectedInvoices = Array.from(checkboxes).map(cb => cb.value);
                    }
                },

                bulkRestore() {
                    if (this.selectedInvoices.length === 0) return;

                    if (!confirm(`${this.selectedInvoices.length} faturayı geri yüklemek istediğinizden emin misiniz?`)) {
                        return;
                    }

                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("accounting.trash.bulk-restore") }}';

                    // CSRF token
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrf);

                    // Selected invoices
                    this.selectedInvoices.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'invoice_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                },

                bulkDelete() {
                    if (this.selectedInvoices.length === 0) return;

                    if (!confirm(`${this.selectedInvoices.length} faturayı KALICI OLARAK silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!`)) {
                        return;
                    }

                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("accounting.trash.bulk-force-delete") }}';

                    // CSRF token
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrf);

                    // Selected invoices
                    this.selectedInvoices.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'invoice_ids[]';
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
