<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Tedarikçi Raporu</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $supplier->name }}</p>
            </div>
            <div class="flex items-center gap-2">
                <!-- Export Buttons -->
                <a href="#" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF
                </a>
                <a href="#" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Excel
                </a>
                <a href="{{ route('reports.stock.supplier-report') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Tedarikçi Seç
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Tedarikçi Bilgileri -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Toplam Borç</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($totalDebt, 2, ',', '.') }} TL</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Ödenen Tutar</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($totalPaid, 2, ',', '.') }} TL</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Kalan Borç</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($remainingDebt, 2, ',', '.') }} TL</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Vadesi Geçmiş</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($overdueInvoices->sum('remaining_amount'), 2, ',', '.') }} TL</p>
                    </div>
                </div>
            </div>

            <!-- Aylık Harcama Grafiği -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Son 12 Ay Harcama Trendi</h3>
                <canvas id="supplierChart" width="400" height="200"></canvas>
            </div>

            <!-- Vadesi Geçmiş Faturalar -->
            @if($overdueInvoices->count() > 0)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Vadesi Geçmiş Faturalar</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Ödenmesi gereken faturalar</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                            <thead class="bg-slate-50 dark:bg-slate-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Fatura No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Tarih</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Vade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Gecikme</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Kalan Tutar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($overdueInvoices as $invoice)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                        <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ $invoice->invoice_number ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->invoice_date->format('d.m.Y') }}</td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->due_date->format('d.m.Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                {{ $invoice->due_date->diffInDays(now()) }} gün
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium text-red-600">{{ number_format($invoice->remaining_amount, 2, ',', '.') }} TL</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Son Faturalar -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Son Faturalar</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">En son 10 fatura</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Fatura No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Tarih</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Vade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Durum</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Tutar</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Ödenen</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Kalan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($recentInvoices as $invoice)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ $invoice->invoice_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->invoice_date->format('d.m.Y') }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->due_date->format('d.m.Y') }}</td>
                                    <td class="px-6 py-4">
                                        @if($invoice->payment_status === 'paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                Ödendi
                                            </span>
                                        @elseif($invoice->payment_status === 'partial')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                Kısmi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                Bekliyor
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-slate-900 dark:text-slate-100">{{ number_format($invoice->total_amount, 2, ',', '.') }} TL</td>
                                    <td class="px-6 py-4 text-right text-green-600">{{ number_format($invoice->payments->sum('amount'), 2, ',', '.') }} TL</td>
                                    <td class="px-6 py-4 text-right font-medium {{ $invoice->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($invoice->remaining_amount, 2, ',', '.') }} TL</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                        Bu tedarikçiye ait fatura bulunmuyor.
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
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('supplierChart').getContext('2d');
            const monthlyData = @json($monthlyData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => item.month),
                    datasets: [{
                        label: 'Aylık Harcama',
                        data: monthlyData.map(item => item.amount),
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('tr-TR', {
                                        style: 'currency',
                                        currency: 'TRY'
                                    }).format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Harcama: ' + new Intl.NumberFormat('tr-TR', {
                                        style: 'currency',
                                        currency: 'TRY'
                                    }).format(context.parsed.y);
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>