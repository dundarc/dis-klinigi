<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Fatura Arama</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Gelişmiş filtrelerle fatura arayın</p>
            </div>
            <a href="{{ route('accounting.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Muhasebeye Dön
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Advanced Search Form -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Gelişmiş Arama</h3>
                </div>

                <form method="GET" action="{{ route('accounting.search') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        <!-- Fatura No -->
                        <div>
                            <label for="invoice_no" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Fatura No</label>
                            <input id="invoice_no" name="invoice_no" type="text" value="{{ $filters['invoice_no'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="örn: INV-001" />
                        </div>

                        <!-- Hasta -->
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hasta</label>
                            <select id="patient_id" name="patient_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Tüm Hastalar</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" @selected(($filters['patient_id'] ?? '') == $patient->id)>
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Başlangıç Tarihi -->
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Başlangıç Tarihi</label>
                            <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <!-- Bitiş Tarihi -->
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Bitiş Tarihi</label>
                            <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <!-- Ödeme Durumu -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Durumu</label>
                            <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Tüm Durumlar</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>
                                        {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Minimum Tutar -->
                        <div>
                            <label for="amount_min" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Min. Tutar (TL)</label>
                            <input id="amount_min" name="amount_min" type="number" step="0.01" min="0" value="{{ $filters['amount_min'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="0.00" />
                        </div>

                        <!-- Maximum Tutar -->
                        <div>
                            <label for="amount_max" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Max. Tutar (TL)</label>
                            <input id="amount_max" name="amount_max" type="number" step="0.01" min="0" value="{{ $filters['amount_max'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="1000.00" />
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                        @if($filters)
                            <a href="{{ route('accounting.search') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Filtreleri Temizle
                            </a>
                        @endif
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Ara
                        </button>
                    </div>
                </form>
            </div>

            <!-- Search Results -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Arama Sonuçları</h3>
                    @if($invoices->total() > 0)
                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ $invoices->total() }} sonuç bulundu</span>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Fatura No</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hasta</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Durum</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tutar</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($invoices as $invoice)
                                <tr class="{{ $loop->even ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800' }} hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('accounting.invoices.action', $invoice) }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                            {{ $invoice->invoice_no }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->patient?->first_name }} {{ $invoice->patient?->last_name }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->issue_date->format('d.m.Y') }}</td>
                                    <td class="px-6 py-4">
                                        @if($invoice->status->value === 'paid')
                                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">Ödenmiş</span>
                                        @elseif($invoice->status->value === 'pending')
                                            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">Bekliyor</span>
                                        @elseif($invoice->status->value === 'overdue')
                                            <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-200">Vadesi Geçmiş</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-1 text-xs font-medium text-slate-800 dark:text-slate-200">{{ ucfirst(str_replace('_', ' ', $invoice->status->value)) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('accounting.invoices.action', $invoice) }}" class="inline-flex items-center px-3 py-1 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded transition-colors">
                                            Görüntüle
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-.966-5.618-2.479C5.668 11.592 5 10.358 5 9c0-2.206.9-4.165 2.343-5.657a7.976 7.976 0 012.828-1.414A7.976 7.976 0 0112 1c2.104 0 4.017.806 5.618 2.343A7.976 7.976 0 0119 9c0 1.358-.668 2.592-1.382 3.521C16.29 14.034 14.34 15 12 15z"></path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Arama kriterlerine uygun fatura bulunamadı</h3>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Farklı arama kriterleri deneyin veya filtreleri temizleyin.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($invoices->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
