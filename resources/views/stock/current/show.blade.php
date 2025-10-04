<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <!-- Modern Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 dark:from-emerald-800 dark:via-teal-800 dark:to-cyan-800 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black/10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 0%, transparent 50%);"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg ring-4 ring-white/10">
                            <i class="fas fa-building text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-white tracking-tight">Cari Hesap: {{ $supplier->name }}</h1>
                        <p class="mt-2 text-emerald-100 text-lg">Finansal durum ve işlemler</p>
                        <div class="mt-3 flex items-center space-x-4 text-sm text-emerald-200">
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                Son güncelleme: {{ $supplier->updated_at->format('d.m.Y H:i') }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $supplier->type === 'service' ? 'Hizmet' : 'Tedarikçi' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Quick Actions -->
                    <div class="hidden md:flex items-center space-x-3">
                        <a href="{{ route('stock.suppliers.edit', $supplier) }}"
                           class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-edit mr-2"></i>
                            Düzenle
                        </a>
                        <a href="{{ route('stock.current.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-xl transition-all duration-200 border border-white/30 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Cari Listesi
                        </a>
                    </div>

                    <!-- Mobile Menu -->
                    <div class="md:hidden">
                        <button onclick="toggleMobileMenu()"
                                class="inline-flex items-center p-3 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 -mt-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Enhanced Supplier Information -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/40 dark:to-emerald-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-id-card text-emerald-600 dark:text-emerald-400 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tedarikçi Bilgileri</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">İletişim ve temel bilgiler</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Supplier Type -->
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-6 rounded-xl border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-tag text-blue-600 dark:text-blue-400 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wide">Tür</p>
                                        <p class="text-xl font-bold text-blue-900 dark:text-blue-100">
                                            {{ $supplier->type === 'service' ? 'Hizmet' : 'Tedarikçi' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-6 rounded-xl border border-green-200 dark:border-green-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-phone text-green-600 dark:text-green-400 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-green-600 dark:text-green-400 uppercase tracking-wide">Telefon</p>
                                        <p class="text-xl font-bold text-green-900 dark:text-green-100">
                                            {{ $supplier->phone ?? 'Belirtilmemiş' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-6 rounded-xl border border-purple-200 dark:border-purple-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-envelope text-purple-600 dark:text-purple-400 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-purple-600 dark:text-purple-400 uppercase tracking-wide">E-posta</p>
                                        <p class="text-xl font-bold text-purple-900 dark:text-purple-100 truncate">
                                            {{ $supplier->email ?? 'Belirtilmemiş' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tax Number -->
                            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 p-6 rounded-xl border border-orange-200 dark:border-orange-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-hashtag text-orange-600 dark:text-orange-400 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-orange-600 dark:text-orange-400 uppercase tracking-wide">Vergi No</p>
                                        <p class="text-xl font-bold text-orange-900 dark:text-orange-100 font-mono">
                                            {{ $supplier->tax_number ?? 'Belirtilmemiş' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- City -->
                            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 p-6 rounded-xl border border-indigo-200 dark:border-indigo-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-indigo-600 dark:text-indigo-400 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 uppercase tracking-wide">Şehir</p>
                                        <p class="text-xl font-bold text-indigo-900 dark:text-indigo-100">
                                            {{ $supplier->city ?? 'Belirtilmemiş' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 p-6 rounded-xl border border-emerald-200 dark:border-emerald-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wide">Durum</p>
                                        <p class="text-xl font-bold text-emerald-900 dark:text-emerald-100">
                                            Aktif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        @if($supplier->address)
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start space-x-3">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-map text-gray-600 dark:text-gray-400 text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-2">Adres</p>
                                        <p class="text-gray-900 dark:text-gray-100 leading-relaxed">
                                            {{ $supplier->address }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Enhanced Financial Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/40 dark:to-amber-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-chart-line text-amber-600 dark:text-amber-400 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Finansal Özet</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Borç durumu ve ödeme bilgileri</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Total Debt -->
                            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 p-6 rounded-xl border border-red-200 dark:border-red-800">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-red-600 dark:text-red-400 uppercase tracking-wide">Toplam Borç</p>
                                        <p class="text-3xl font-bold text-red-900 dark:text-red-100 mt-2">
                                            ₺{{ number_format($summary['total_debt'], 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-arrow-up text-red-600 dark:text-red-400 text-lg"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Paid -->
                            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-6 rounded-xl border border-green-200 dark:border-green-800">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-green-600 dark:text-green-400 uppercase tracking-wide">Toplam Ödenen</p>
                                        <p class="text-3xl font-bold text-green-900 dark:text-green-100 mt-2">
                                            ₺{{ number_format($summary['total_paid'], 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Remaining Debt -->
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-6 rounded-xl border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wide">Kalan Borç</p>
                                        <p class="text-3xl font-bold text-blue-900 dark:text-blue-100 mt-2">
                                            ₺{{ number_format($summary['remaining_debt'], 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-calculator text-blue-600 dark:text-blue-400 text-lg"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Overdue Amount -->
                            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 p-6 rounded-xl border border-orange-200 dark:border-orange-800">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-orange-600 dark:text-orange-400 uppercase tracking-wide">Gecikmiş Tutar</p>
                                        <p class="text-3xl font-bold text-orange-900 dark:text-orange-100 mt-2">
                                            ₺{{ number_format($summary['overdue_amount'], 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-exclamation-triangle text-orange-600 dark:text-orange-400 text-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Progress Bar -->
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Ödeme Durumu</h4>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $summary['total_debt'] > 0 ? round(($summary['total_paid'] / $summary['total_debt']) * 100, 1) : 0 }}% tamamlandı
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-4 rounded-full transition-all duration-500"
                                     style="width: {{ $summary['total_debt'] > 0 ? min(($summary['total_paid'] / $summary['total_debt']) * 100, 100) : 0 }}%">
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                                <span>₺0</span>
                                <span>₺{{ number_format($summary['total_debt'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Invoices Table -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900/40 dark:to-indigo-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-file-invoice text-indigo-600 dark:text-indigo-400 text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Faturalar</h2>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $invoices->total() }} fatura görüntüleniyor</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button onclick="exportInvoices()"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-download mr-2"></i>
                                    Dışa Aktar
                                </button>
                            </div>
                        </div>
                    </div>

                    @if($invoices->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm" id="invoicesTable">
                                <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0 z-10">
                                    <tr>
                                        @if($supplier->type === 'service')
                                            <!-- Expenses format for service suppliers -->
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Fatura Adı</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kategori</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tutar</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ödeme Durumu</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Açıklama</th>
                                        @else
                                            <!-- Purchases format for supplier type -->
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Fatura No</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Vade</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tutar</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ödenen</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kalan</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Durum</th>
                                        @endif
                                        <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($invoices as $index => $invoice)
                                        <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800' }} hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                            @if($supplier->type === 'service')
                                                <!-- Expenses format for service suppliers -->
                                                <td class="px-6 py-4">
                                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $invoice->invoice_number ?? 'Numarasız Fatura' }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $supplier->name }}</td>
                                                <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->invoice_date?->format('d.m.Y') ?? '-' }}</td>
                                                <td class="px-6 py-4">
                                                    @if($invoice->payment_status === 'paid')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Ödendi</span>
                                                    @elseif($invoice->payment_status === 'partial')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Kısmi</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Bekliyor</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300 max-w-xs truncate">{{ $invoice->notes ?? '-' }}</td>
                                            @else
                                                <!-- Purchases format for supplier type -->
                                                <td class="px-6 py-4">
                                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $invoice->invoice_number ?? 'Numarasız' }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->invoice_date?->format('d.m.Y') ?? '-' }}</td>
                                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $invoice->due_date?->format('d.m.Y') ?? '-' }}</td>
                                                <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                                                <td class="px-6 py-4 text-green-600 dark:text-green-400 font-medium">{{ number_format($invoice->total_paid, 2, ',', '.') }} TL</td>
                                                <td class="px-6 py-4 {{ $invoice->remaining_amount > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400' }} font-medium">{{ number_format($invoice->remaining_amount, 2, ',', '.') }} TL</td>
                                                <td class="px-6 py-4">
                                                    @if($invoice->payment_status === 'paid')
                                                        <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">Ödendi</span>
                                                    @elseif($invoice->payment_status === 'pending')
                                                        <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">Bekliyor</span>
                                                    @elseif($invoice->payment_status === 'overdue')
                                                        <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-200">Gecikti</span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-900/30 px-2.5 py-1 text-xs font-medium text-amber-800 dark:text-amber-200">Kısmi</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end gap-1">
                                                    <a href="{{ route('stock.purchases.show', $invoice) }}" class="inline-flex items-center p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Görüntüle">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    @if($invoice->remaining_amount > 0)
                                                    <button onclick="makePayment({{ $invoice->id }})" class="inline-flex items-center p-2 text-green-400 hover:text-green-600 dark:hover:text-green-300 transition-colors" title="Ödeme Yap">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                        </svg>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Enhanced Pagination -->
                        <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                    Sayfa {{ $invoices->currentPage() }} / {{ $invoices->lastPage() }}
                                    <span class="font-medium">({{ $invoices->total() }} toplam fatura)</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    {{ $invoices->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Enhanced Empty State -->
                        <div class="text-center py-16 px-8">
                            <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600">
                                <i class="fas fa-file-invoice text-6xl"></i>
                            </div>
                            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Fatura bulunmuyor</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                                Bu tedarikçi için henüz hiç fatura oluşturulmamış. Yeni bir satın alma işlemi yaparak fatura oluşturabilirsiniz.
                            </p>
                            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('stock.purchases.create') }}"
                                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors duration-200 shadow-sm">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Satın Alma Oluştur
                                </a>
                                <button onclick="showInvoiceHistory()"
                                        class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors duration-200 shadow-sm">
                                    <i class="fas fa-history mr-2"></i>
                                    Geçmiş Faturalar
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Enhanced Expenses Table (for service suppliers) -->
                @if($supplier->type === 'service' && $expenses->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/40 dark:to-purple-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-receipt text-purple-600 dark:text-purple-400 text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Gider Kalemleri</h2>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $expenses->total() }} gider kalemi görüntüleniyor</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="expensesTable">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <i class="fas fa-hashtag mr-1"></i>
                                        Gider No
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <i class="fas fa-tag mr-1"></i>
                                        Kategori
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Tarih
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <i class="fas fa-clock mr-1"></i>
                                        Vade
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <i class="fas fa-dollar-sign mr-1"></i>
                                        Tutar
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Ödenen
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <i class="fas fa-calculator mr-1"></i>
                                        Kalan
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Durum
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        <i class="fas fa-cogs mr-1"></i>
                                        İşlemler
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($expenses as $expense)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-receipt text-purple-600 dark:text-purple-400"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                        {{ $expense->title }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        Gider #{{ $expense->id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                                {{ $expense->category->name ?? 'Kategori Yok' }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                                {{ optional($expense->expense_date)->format('d.m.Y') ?? '-' }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                                {{ optional($expense->due_date)->format('d.m.Y') ?? '-' }}
                                            </div>
                                            @if($expense->due_date && $expense->due_date->isPast() && $expense->remaining_amount > 0)
                                                <div class="text-xs text-red-600 dark:text-red-400 font-medium">
                                                    {{ $expense->due_date->diffInDays(now()) }} gün gecikmiş
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-lg font-bold text-gray-900 dark:text-white">
                                                ₺{{ number_format($expense->total_amount, 2, ',', '.') }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-lg font-semibold text-green-600 dark:text-green-400">
                                                ₺{{ number_format($expense->total_paid, 2, ',', '.') }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-lg font-bold {{ $expense->remaining_amount > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400' }}">
                                                ₺{{ number_format($expense->remaining_amount, 2, ',', '.') }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            @if($expense->payment_status === 'paid')
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border-2 border-green-200 dark:border-green-800">
                                                    <i class="fas fa-check-circle mr-1.5"></i>
                                                    ÖDENDİ
                                                </span>
                                            @elseif($expense->payment_status === 'partial')
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border-2 border-blue-200 dark:border-blue-800">
                                                    <i class="fas fa-clock mr-1.5"></i>
                                                    KISMI
                                                </span>
                                            @elseif($expense->payment_status === 'overdue')
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border-2 border-red-200 dark:border-red-800 animate-pulse">
                                                    <i class="fas fa-exclamation-triangle mr-1.5"></i>
                                                    GECİKMİŞ
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-2 border-gray-200 dark:border-gray-600">
                                                    <i class="fas fa-hourglass-half mr-1.5"></i>
                                                    BEKLİYOR
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-1">
                                                <a href="{{ route('stock.expenses.show', $expense) }}"
                                                   class="inline-flex items-center px-3 py-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-150 hover:shadow-sm"
                                                   title="Gideri Görüntüle">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                                @if($expense->remaining_amount > 0)
                                                <button onclick="makeExpensePayment({{ $expense->id }})"
                                                        class="inline-flex items-center px-3 py-2 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-all duration-150 hover:shadow-sm"
                                                        title="Ödeme Yap">
                                                    <i class="fas fa-credit-card text-sm"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                Sayfa {{ $expenses->currentPage() }} / {{ $expenses->lastPage() }}
                                <span class="font-medium">({{ $expenses->total() }} toplam gider)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                {{ $expenses->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Instructional Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-bolt text-emerald-600 dark:text-emerald-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-emerald-900 dark:text-emerald-100">Bilgilendirme</h3>
                                    <p class="text-sm text-emerald-600 dark:text-emerald-400">{{$supplier->name}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            Tedarikçiler için "Faturalar" alanını, gider işlemleri için "Gider Kalemleri" alanını kullanabilirsiniz.
                        </div>
                    </div>

                    <!-- Financial Overview -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-chart-pie text-amber-600 dark:text-amber-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-amber-900 dark:text-amber-100">Finansal Durum</h3>
                                    <p class="text-sm text-amber-600 dark:text-amber-400">Genel bakış</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                    <span class="text-sm font-medium text-red-700 dark:text-red-300">Acil Ödemeler</span>
                                    <span class="text-lg font-bold text-red-800 dark:text-red-200">{{ $invoices->where('payment_status', 'overdue')->count() }}</span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Bekleyen</span>
                                    <span class="text-lg font-bold text-blue-800 dark:text-blue-200">{{ $invoices->where('payment_status', 'pending')->count() }}</span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <span class="text-sm font-medium text-green-700 dark:text-green-300">Tamamlanan</span>
                                    <span class="text-lg font-bold text-green-800 dark:text-green-200">{{ $invoices->where('payment_status', 'paid')->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Guide -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-question-circle text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-100">Cari Hesap Rehberi</h3>
                                    <p class="text-sm text-indigo-600 dark:text-indigo-400">Nasıl kullanılır?</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Step 1 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Bilgileri İnceleyin</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    Tedarikçi bilgileri ve finansal durumu kartlarda görüntülenir.
                                </p>
                            </div>

                            <!-- Step 2 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Faturaları Takip Edin</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    Tüm faturalar tablosunda listelenir. Durumlarına göre renk kodlaması vardır.
                                </p>
                            </div>

                            <!-- Step 3 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Ödemeleri Yönetin</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    Ödeme butonları ile hızlı ödeme işlemleri yapın.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-address-book text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-green-900 dark:text-green-100">İletişim</h3>
                                    <p class="text-sm text-green-600 dark:text-green-400">Hızlı erişim</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                @if($supplier->phone)
                                <button onclick="callSupplier('{{ $supplier->phone }}')"
                                        class="w-full flex items-center space-x-3 p-3 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-lg transition-colors">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-phone text-green-600 dark:text-green-400"></i>
                                    </div>
                                    <span class="text-sm font-medium text-green-800 dark:text-green-200">{{ $supplier->phone }}</span>
                                </button>
                                @endif

                                @if($supplier->email)
                                <button onclick="emailSupplier('{{ $supplier->email }}')"
                                        class="w-full flex items-center space-x-3 p-3 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition-colors">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-envelope text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <span class="text-sm font-medium text-blue-800 dark:text-blue-200 truncate">{{ $supplier->email }}</span>
                                </button>
                                @endif

                                <button onclick="showLocation()"
                                        class="w-full flex items-center space-x-3 p-3 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 rounded-lg transition-colors">
                                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-purple-600 dark:text-purple-400"></i>
                                    </div>
                                    <span class="text-sm font-medium text-purple-800 dark:text-purple-200">{{ $supplier->city ?? 'Konum' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-slate-50 dark:from-gray-700 dark:to-slate-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-history text-gray-600 dark:text-gray-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Son Hareketler</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Aktivite geçmişi</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="space-y-3">
                                @if($invoices->count() > 0)
                                    @foreach($invoices->take(3) as $invoice)
                                    <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-file-invoice text-blue-600 dark:text-blue-400 text-xs"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                Fatura {{ $invoice->invoice_number ?? '#' . $invoice->id }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ optional($invoice->invoice_date)->format('d.m.Y') ?? 'Tarih yok' }}
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Henüz hareket yok</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Export invoices functionality
    function exportInvoices() {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Dışa Aktarılıyor...';
        button.disabled = true;

        // Simulate export process
        setTimeout(() => {
            // Create CSV content
            const rows = document.querySelectorAll('#invoicesTable tbody tr');
            let csvContent = 'Fatura No,Tarih,Vade,Tutar,Ödenen,Kalan,Durum\n';

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 7) {
                    const invoiceNo = cells[0].textContent.trim().split('\n')[0];
                    const date = cells[1].textContent.trim();
                    const dueDate = cells[2].textContent.trim().split('\n')[0];
                    const amount = cells[3].textContent.trim();
                    const paid = cells[4].textContent.trim();
                    const remaining = cells[5].textContent.trim();
                    const status = cells[6].textContent.trim();
                    csvContent += `${invoiceNo},${date},${dueDate},${amount},${paid},${remaining},${status}\n`;
                }
            });

            // Create and download CSV
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'faturalar.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Reset button
            button.innerHTML = originalText;
            button.disabled = false;
        }, 1000);
    }

    // Mobile menu toggle
    function toggleMobileMenu() {
        // Implement mobile menu toggle
        alert('Mobile menu functionality not implemented yet');
    }

    // Create new invoice
    function createNewInvoice() {
        window.location.href = '{{ route("stock.purchases.create") }}';
    }

    // Record payment
    function recordPayment() {
        alert('Payment recording functionality not implemented yet');
    }

    // Send reminder
    function sendReminder() {
        alert('Reminder sending functionality not implemented yet');
    }

    // Make payment for specific invoice
    function makePayment(invoiceId) {
        alert('Payment for invoice ' + invoiceId + ' not implemented yet');
    }

    // Make payment for specific expense
    function makeExpensePayment(expenseId) {
        alert('Payment for expense ' + expenseId + ' not implemented yet');
    }

    // Show invoice history
    function showInvoiceHistory() {
        alert('Invoice history functionality not implemented yet');
    }

    // Call supplier
    function callSupplier(phone) {
        window.location.href = 'tel:' + phone;
    }

    // Email supplier
    function emailSupplier(email) {
        window.location.href = 'mailto:' + email;
    }

    // Show location
    function showLocation() {
        alert('Location functionality not implemented yet');
    }
    }

});
</script>
@endpush

</x-app-layout>
