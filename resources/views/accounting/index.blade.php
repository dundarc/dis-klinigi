<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Muhasebe Paneli') }}
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ now()->translatedFormat('d F Y, l') }}
            </span>
        </div>
    </x-slot>

    @php
        $formatCurrency = static function ($value) {
            return number_format((float) $value, 2, ',', '.') . ' ₺';
        };

        $statusLabels = [
            \App\Enums\InvoiceStatus::PAID->value => 'Ödendi',
            \App\Enums\InvoiceStatus::UNPAID->value => 'Ödenmedi',
            \App\Enums\InvoiceStatus::PARTIAL->value => 'Kısmi Ödendi',
            \App\Enums\InvoiceStatus::POSTPONED->value => 'Vadelendirildi',
        ];

        $statusColors = [
            \App\Enums\InvoiceStatus::PAID->value => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            \App\Enums\InvoiceStatus::UNPAID->value => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            \App\Enums\InvoiceStatus::PARTIAL->value => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
            \App\Enums\InvoiceStatus::POSTPONED->value => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Tahsilat</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $formatCurrency($metrics['totalCollected']) }}
                    </p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Tüm zamanlarda alınan ödemelerin toplamı</p>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bu Ay Tahsilat</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $formatCurrency($metrics['collectedThisMonth']) }}
                    </p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ now()->translatedFormat('F Y') }} döneminde alınan ödemeler</p>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bekleyen Tahsilat</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $formatCurrency($metrics['outstandingReceivables']) }}
                    </p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Ödenmemiş veya kısmi ödenmiş faturaların toplam bakiyesi</p>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bu Ay Kesilen Fatura</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ number_format($metrics['invoiceCountThisMonth']) }}
                    </p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ now()->translatedFormat('F Y') }} döneminde oluşturulan faturalar</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Aylık Gelir Trendleri</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Ödenmiş faturalar</span>
                    </div>
                    @if ($monthlyRevenueChart['labels']->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">Grafik oluşturmak için yeterli veri bulunamadı.</p>
                    @else
                        <div class="h-72">
                            <canvas id="monthlyRevenueChart"></canvas>
                        </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ödeme Yöntemleri</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Toplam tahsilatlar</span>
                    </div>
                    @if ($paymentMethodChart['labels']->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">Henüz kayıtlı bir ödeme bulunmuyor.</p>
                    @else
                        <div class="h-72">
                            <canvas id="paymentMethodChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Son Ödemeler</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">En güncel 5 kayıt</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    <th class="px-3 py-2 text-left">Fatura</th>
                                    <th class="px-3 py-2 text-left">Hasta</th>
                                    <th class="px-3 py-2 text-left">Tarih</th>
                                    <th class="px-3 py-2 text-right">Tutar</th>
                                    <th class="px-3 py-2 text-left">Yöntem</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-200">
                                @forelse ($recentPayments as $payment)
                                    <tr>
                                        <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $payment->invoice->invoice_no }}</td>
                                        <td class="px-3 py-2">{{ $payment->invoice->patient->full_name }}</td>
                                        <td class="px-3 py-2">{{ optional($payment->paid_at)->format('d.m.Y') }}</td>
                                        <td class="px-3 py-2 text-right">{{ $formatCurrency($payment->amount) }}</td>
                                        <td class="px-3 py-2 uppercase tracking-wide text-xs text-gray-500 dark:text-gray-400">{{ $payment->method }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">Henüz ödeme kaydı bulunmuyor.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Sigorta Özeti ({{ now()->translatedFormat('F Y') }})</h3>
                    <dl class="space-y-4 text-sm text-gray-700 dark:text-gray-200">
                        <div class="flex items-center justify-between">
                            <dt>Sigortanın Karşıladığı</dt>
                            <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ $formatCurrency($insuranceSummary['coverage']) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt>Hastanın Ödemesi Gereken</dt>
                            <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ $formatCurrency($insuranceSummary['patientPortion']) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt>Sigortalı Fatura Sayısı</dt>
                            <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($insuranceSummary['insuredInvoices']) }}</dd>
                        </div>
                    </dl>
                    <p class="mt-6 text-xs text-gray-500 dark:text-gray-400">Sigorta desteği bulunan faturaların genel görünümü.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Son Faturalar</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">En güncel 8 kayıt</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    <th class="px-3 py-2 text-left">Fatura</th>
                                    <th class="px-3 py-2 text-left">Hasta</th>
                                    <th class="px-3 py-2 text-left">Tarih</th>
                                    <th class="px-3 py-2 text-right">Tutar</th>
                                    <th class="px-3 py-2 text-right">Ödenen</th>
                                    <th class="px-3 py-2 text-right">Kalan</th>
                                    <th class="px-3 py-2 text-left">Durum</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-200">
                                @forelse ($recentInvoices as $invoice)
                                    <tr>
                                        <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $invoice->invoice_no }}</td>
                                        <td class="px-3 py-2">{{ $invoice->patient->full_name }}</td>
                                        <td class="px-3 py-2">{{ optional($invoice->issue_date)->format('d.m.Y') }}</td>
                                        <td class="px-3 py-2 text-right">{{ $formatCurrency($invoice->grand_total) }}</td>
                                        <td class="px-3 py-2 text-right">{{ $formatCurrency($invoice->payments_sum_amount ?? 0) }}</td>
                                        <td class="px-3 py-2 text-right">{{ $formatCurrency($invoice->balance_due) }}</td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$invoice->status->value] ?? 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200' }}">
                                                {{ $statusLabels[$invoice->status->value] ?? \Illuminate\Support\Str::title($invoice->status->value) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">Henüz fatura kaydı bulunmuyor.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Geciken Tahsilatlar</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">30 günden eski</span>
                    </div>
                    <div class="space-y-4">
                        @forelse ($overdueInvoices as $invoice)
                            <div class="border border-gray-100 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $invoice->patient->full_name }}</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ optional($invoice->issue_date)->format('d.m.Y') }}</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Kalan Tutar</span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $formatCurrency($invoice->balance_due) }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Geciken tahsilat bulunmuyor.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
     <script>
    window.accountingData = {
        monthlyRevenue: {
            labels: @json($monthlyRevenueChart['labels']),
            data: @json($monthlyRevenueChart['data']),
        },
        paymentMethods: {
            labels: @json($paymentMethodChart['labels']),
            data: @json($paymentMethodChart['data']),
        }
    };
</script>

        @vite(['resources/js/accounting.js'])
    @endpush
</x-app-layout>