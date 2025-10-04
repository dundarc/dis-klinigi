<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 dark:from-emerald-800 dark:via-teal-800 dark:to-cyan-800 relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 0%, transparent 50%);"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg ring-4 ring-white/10">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold text-white tracking-tight">Faturalar</h1>
                            <p class="mt-2 text-emerald-100 text-lg">Stok giriş faturalarını yönetin ve takip edin</p>
                            <div class="mt-3 flex items-center space-x-4 text-sm text-emerald-200">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Toplam: {{ $invoices->total() }} fatura
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('stock.purchases.create') }}"
                           class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Yeni Fatura
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ANA GRID -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 -mt-8 relative z-10">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

                <!-- SOL TARAF -->
                <div class="xl:col-span-8 space-y-8">

                    <!-- Fatura Filtresi -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fatura Filtresi</h2>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">Tedarikçi, durum veya tarih aralığına göre sonuçları daraltın</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tedarikçi</label>
                                    <select id="supplier_id" name="supplier_id" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <option value="">Tüm Tedarikçiler</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" @selected(($filters['supplier_id'] ?? '') == $supplier->id)>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Durum</label>
                                    <select id="payment_status" name="payment_status" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <option value="">Tüm Durumlar</option>
                                        <option value="pending" @selected(($filters['payment_status'] ?? '') === 'pending')>Bekliyor</option>
                                        <option value="partial" @selected(($filters['payment_status'] ?? '') === 'partial')>Kısmi</option>
                                        <option value="paid" @selected(($filters['payment_status'] ?? '') === 'paid')>Ödendi</option>
                                        <option value="overdue" @selected(($filters['payment_status'] ?? '') === 'overdue')>Gecikmiş</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Başlangıç Tarihi</label>
                                    <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Bitiş Tarihi</label>
                                    <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                </div>

                                <div class="md:col-span-2 lg:col-span-4 flex items-center justify-end gap-4 pt-4">
                                    <button type="button" onclick="window.location='{{ route('stock.purchases.index') }}';" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-xl transition-all duration-300 shadow-sm hover:shadow-md">
                                        Sıfırla
                                    </button>
                                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                        Filtrele
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Faturalar Tablosu -->
                    @if($invoices->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Faturalar</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $invoices->total() }} fatura görüntüleniyor</p>
                            </div>
                            <button onclick="exportInvoices(event)" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                                Dışa Aktar
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="invoicesTable">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Fatura No</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Tedarikçi</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Tarih</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Durum</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Tutar</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($invoices as $invoice)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number ?? '-' }}</td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $invoice->supplier?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ optional($invoice->invoice_date)->format('d.m.Y') ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            @if($invoice->is_cancelled)
                                                <span class="badge badge-danger">İptal</span>
                                            @elseif($invoice->payment_status->value === 'paid')
                                                <span class="badge badge-success">Ödendi</span>
                                            @elseif($invoice->payment_status->value === 'partial')
                                                <span class="badge badge-info">Kısmi</span>
                                            @elseif($invoice->payment_status->value === 'overdue')
                                                <span class="badge badge-danger animate-pulse">Gecikmiş</span>
                                            @elseif($invoice->payment_status->value === 'installment')
                                                <span class="badge badge-warning">Taksitli</span>
                                            @else
                                                <span class="badge badge-secondary">Bekliyor</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-900 dark:text-white font-semibold">₺{{ number_format($invoice->grand_total, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('stock.purchases.show', $invoice) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Görüntüle</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Modern Pagination -->
                        @if($invoices->hasPages())
                            <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                                <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">{{ $invoices->firstItem() }}</span> -
                                        <span class="font-medium">{{ $invoices->lastItem() }}</span>
                                        arası gösteriliyor,
                                        toplam <span class="font-medium">{{ $invoices->total() }}</span> fatura
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <!-- Previous Button -->
                                        @if($invoices->onFirstPage())
                                            <button disabled class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-700 dark:text-gray-500 rounded-xl cursor-not-allowed">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                                Önceki
                                            </button>
                                        @else
                                            <a href="{{ $invoices->previousPageUrl() }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                                Önceki
                                            </a>
                                        @endif

                                        <!-- Page Numbers -->
                                        <div class="flex items-center space-x-1">
                                            @foreach($invoices->getUrlRange(1, $invoices->lastPage()) as $page => $url)
                                                @if($page == $invoices->currentPage())
                                                    <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl shadow-lg">
                                                        {{ $page }}
                                                    </span>
                                                @else
                                                    <a href="{{ $url }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                                        {{ $page }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>

                                        <!-- Next Button -->
                                        @if($invoices->hasMorePages())
                                            <a href="{{ $invoices->nextPageUrl() }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                                Sonraki
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @else
                                            <button disabled class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-700 dark:text-gray-500 rounded-xl cursor-not-allowed">
                                                Sonraki
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @else
                    <div class="bg-white dark:bg-gray-800 p-12 text-center rounded-2xl shadow">
                        <p class="text-gray-500 dark:text-gray-400">Bu kriterlere uygun fatura bulunamadı.</p>
                    </div>
                    @endif
                </div>

                <!-- SAĞ PANEL -->
                <div class="xl:col-span-4">
                    <div class="sticky top-8 space-y-6">

                        <!-- Hızlı İşlemler -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20">
                                <h3 class="text-lg font-bold text-emerald-900 dark:text-emerald-100">Hızlı İşlemler</h3>
                            </div>
                            <div class="p-6 space-y-3">
                                <a href="{{ route('stock.purchases.create') }}" class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition">Yeni Fatura</a>
                                <button onclick="exportInvoices(event)" class="w-full flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition">Dışa Aktar</button>
                            </div>
                        </div>

                        <!-- Finansal Durum -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20">
                                <h3 class="text-lg font-bold text-amber-900 dark:text-amber-100">Finansal Durum</h3>
                            </div>
                            <div class="p-6 space-y-3">
                                <div class="flex justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                    <span class="text-sm font-medium text-red-700 dark:text-red-300">Gecikmiş</span>
                                    <span class="text-lg font-bold text-red-800 dark:text-red-200">{{ $invoices->filter(fn($i) => $i->payment_status->value === 'overdue')->count() }}</span>
                                </div>
                                <div class="flex justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Bekleyen</span>
                                    <span class="text-lg font-bold text-blue-800 dark:text-blue-200">{{ $invoices->filter(fn($i) => in_array($i->payment_status->value, ['pending', 'partial']))->count() }}</span>
                                </div>
                                <div class="flex justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <span class="text-sm font-medium text-green-700 dark:text-green-300">Tamamlanan</span>
                                    <span class="text-lg font-bold text-green-800 dark:text-green-200">{{ $invoices->filter(fn($i) => $i->payment_status->value === 'paid')->count() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Faturalar Rehberi -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20">
                                <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-100">Faturalar Rehberi</h3>
                            </div>
                            <div class="p-6 space-y-4 text-sm text-gray-700 dark:text-gray-400">
                                <p>1️⃣ Tedarikçi, durum veya tarih aralığına göre filtreleme yapabilirsiniz.</p>
                                <p>2️⃣ Durum renklerine göre faturaları hızlıca ayırt edin.</p>
                                <p>3️⃣ Sağdaki hızlı işlemlerden yeni fatura oluşturabilir veya dışa aktarım yapabilirsiniz.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.exportInvoices = function(event) {
                const button = event.target.closest('button');
                const original = button.innerHTML;
                button.innerHTML = '⏳ Dışa Aktarılıyor...';
                button.disabled = true;

                setTimeout(() => {
                    const rows = document.querySelectorAll('#invoicesTable tbody tr');
                    let csv = 'Fatura No,Tedarikçi,Tarih,Durum,Tutar\n';
                    rows.forEach(row => {
                        const cells = row.querySelectorAll('td');
                        if (cells.length >= 5) {
                            const fno = cells[0].textContent.trim();
                            const sup = cells[1].textContent.trim();
                            const date = cells[2].textContent.trim();
                            const status = cells[3].textContent.trim();
                            const amount = cells[4].textContent.trim();
                            csv += `"${fno}","${sup}","${date}","${status}","${amount}"\n`;
                        }
                    });
                    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'faturalar.csv';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    button.innerHTML = original;
                    button.disabled = false;
                }, 1000);
            };
        });
        </script>
        @endpush
    </div>
</x-app-layout>
