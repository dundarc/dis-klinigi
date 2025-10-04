<x-app-layout>
    <x-slot name="header">
        <div x-data="{ showCancelModal: false }" class="min-h-[120px] bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-700 dark:from-indigo-800 dark:via-purple-800 dark:to-blue-900 relative overflow-hidden rounded-2xl shadow-2xl">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-black/10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 0%, transparent 50%);"></div>
            </div>

            <div class="relative px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4 sm:space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg ring-4 ring-white/10">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white tracking-tight truncate">Fatura Detayı</h1>
                            <p class="mt-1 sm:mt-2 text-indigo-100 text-base sm:text-lg truncate">{{ $invoice->invoice_number ?? 'Numarasız' }} - {{ $invoice->supplier?->name ?? 'Tedarikçi Yok' }}</p>
                            <div class="mt-2 sm:mt-3 flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-indigo-200">
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $invoice->invoice_date?->format('d.m.Y') ?? '-' }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    {{ number_format($invoice->grand_total, 2, ',', '.') }} ₺
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <a href="{{ route('stock.purchases.index') }}"
                           class="inline-flex items-center px-3 sm:px-6 py-2 sm:py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="hidden sm:inline">Listeye Dön</span>
                            <span class="sm:hidden">Liste</span>
                        </a>
                         
                         <a href="{{ route('stock.purchases.edit', $invoice) }}"
                            class="inline-flex items-center px-3 sm:px-6 py-2 sm:py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="hidden sm:inline">Düzenle</span>
                        </a>
                        <a href="#" onclick="window.print()"
                           class="inline-flex items-center px-3 sm:px-6 py-2 sm:py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            <span class="hidden sm:inline">Yazdır</span>
                        </a>
                        @if(!$invoice->is_cancelled)
                             <button type="button" x-on:click="showCancelModal = true" class="inline-flex items-center px-3 sm:px-6 py-2 sm:py-3 bg-red-500/20 hover:bg-red-500/30 text-white font-semibold rounded-xl transition-all duration-200 border border-red-400/30 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">Faturayı İptal Et</button>
                         @endif
                         @can('accessAdminFeatures')
                        <form method="POST" action="{{ route('stock.purchases.destroy', $invoice) }}" onsubmit="return confirm('Bu faturayı silmek istediğinizden emin misiniz?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 sm:px-6 py-2 sm:py-3 bg-red-500/20 hover:bg-red-500/30 text-white font-semibold rounded-xl transition-all duration-200 border border-red-400/30 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span class="hidden sm:inline">Sil</span>
                            </button>
                        </form>

                        @endcan
                    </div>
                </div>
            </div>

            <!-- Cancel Modal -->
            @if(!$invoice->is_cancelled)
                <div x-show="showCancelModal" x-on:keydown.escape.window="showCancelModal = false" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showCancelModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" x-on:click="showCancelModal = false">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <div x-show="showCancelModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form method="POST" action="{{ route('stock.purchases.cancel', $invoice) }}" class="p-6">
                                @csrf
                                <h2 class="text-lg font-bold mb-4">Faturayı iptal etmek üzeresiniz</h2>
                                <textarea required name="cancel_reason" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" rows="4" placeholder="İptal nedeni girin... (Zorunlu)" required></textarea>

                                <div class="mt-4 flex justify-end space-x-3">
                                    <button type="button" x-on:click="showCancelModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">Vazgeç</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">İptal Et</button>
                                </div>
                            </form>
                            <p class="p-6 text-sm text-gray-500">Faturayı iptal etmeden önce lütfen ödeme iadelerini aldığınızdan emin olun ve ödeme hareketlerini iptal edin. Bu işlem geri alınamaz. <strong>İptal işlemini uygulamadan önce dikkatli olun.</strong></p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Ana İçerik -->
            <div class="space-y-6 sm:space-y-8">
                <!-- Fatura Özeti -->
                <div class="bg-gradient-to-br from-white via-blue-50/30 to-indigo-50/30 dark:from-slate-800 dark:via-slate-700 dark:to-slate-600 rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-600/50 overflow-hidden backdrop-blur-sm">
                    <div class="px-8 py-6 border-b border-slate-200/50 dark:border-slate-600/50 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-slate-700 dark:to-slate-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-200 dark:from-blue-900/40 dark:to-indigo-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Fatura Özeti</h3>
                                    <p class="text-slate-600 dark:text-slate-400 mt-1">Temel bilgiler ve durum</p>
                                </div>
                            </div>
                            @php
                                if ($invoice->is_cancelled) {
                                    $statusConfig = ['bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200', '❌ İPTAL EDİLDİ'];
                                } else {
                                    $statusConfig = [
                                        'paid' => ['bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200', '✅ TAM ÖDENDİ'],
                                        'pending' => ['bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200', '⏳ BEKLİYOR'],
                                        'overdue' => ['bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 animate-pulse', '⚠️ GECİKMİŞ'],
                                        'partial' => ['bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200', '🔄 KISMI ÖDEME'],
                                        'installment' => ['bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200', '📅 TAKSİTLİ'],
                                    ];
                                    $status = $invoice->payment_status->value;
                                    $statusConfig = $statusConfig[$status] ?? ['bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200', ucfirst($status)];
                                }
                            @endphp
                            <span class="inline-flex items-center rounded-full {{ $statusConfig[0] }} px-4 py-2 text-sm font-bold border-2 border-current/20">{{ $statusConfig[1] }}</span>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8">
                        <!-- Bilgi Kartları -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 sm:gap-6">
                            <!-- Fatura No -->
                            <div class="group bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-600 p-5 sm:p-6 rounded-2xl border border-slate-200/60 dark:border-slate-600/60 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-600 dark:to-slate-500 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Fatura No</p>
                                        <p class="text-lg sm:text-xl font-bold text-slate-900 dark:text-slate-100 truncate">{{ $invoice->invoice_number ?? 'Numarasız' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tedarikçi -->
                            <div class="group bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-5 sm:p-6 rounded-2xl border border-blue-200/60 dark:border-blue-800/60 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-1">Tedarikçi</p>
                                        <p class="text-lg sm:text-xl font-bold text-blue-900 dark:text-blue-100 truncate">{{ $invoice->supplier?->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Fatura Tarihi -->
                            <div class="group bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-5 sm:p-6 rounded-2xl border border-green-200/60 dark:border-green-800/60 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase tracking-wider mb-1">Fatura Tarihi</p>
                                        <p class="text-lg sm:text-xl font-bold text-green-900 dark:text-green-100">{{ $invoice->invoice_date?->format('d.m.Y') ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Vade Tarihi -->
                            <div class="group bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 p-5 sm:p-6 rounded-2xl border border-orange-200/60 dark:border-orange-800/60 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/30 dark:to-orange-800/30 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-orange-600 dark:text-orange-400 uppercase tracking-wider mb-1">Vade Tarihi</p>
                                        <p class="text-lg sm:text-xl font-bold text-orange-900 dark:text-orange-100">
                                            {{ $invoice->due_date?->format('d.m.Y') ?? '-' }}
                                            @if($invoice->due_date && $invoice->due_date < now() && !in_array($invoice->payment_status->value, ['paid']))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 font-medium mt-2">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                    </svg>
                                                    {{ $invoice->due_date->diffInDays(now()) }} gün gecikmiş
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Genel Toplam -->
                            <div class="group bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-5 sm:p-6 rounded-2xl border border-purple-200/60 dark:border-purple-800/60 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1">Genel Toplam</p>
                                        <p class="text-xl sm:text-2xl font-bold text-purple-900 dark:text-purple-100">{{ number_format($invoice->grand_total, 2, ',', '.') }} ₺</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Toplam Ödenen -->
                            <div class="group bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 p-5 sm:p-6 rounded-2xl border border-emerald-200/60 dark:border-emerald-800/60 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-1">Toplam Ödenen</p>
                                        <p class="text-xl sm:text-2xl font-bold text-emerald-900 dark:text-emerald-100">{{ number_format($invoice->total_paid, 2, ',', '.') }} ₺</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Kalan Tutar -->
                            <div class="group bg-gradient-to-br {{ $invoice->remaining_amount > 0 ? 'from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20' : 'from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20' }} p-5 sm:p-6 rounded-2xl border {{ $invoice->remaining_amount > 0 ? 'border-red-200/60 dark:border-red-800/60' : 'border-green-200/60 dark:border-green-800/60' }} shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br {{ $invoice->remaining_amount > 0 ? 'from-red-100 to-red-200 dark:from-red-900/30 dark:to-red-800/30' : 'from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30' }} rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 {{ $invoice->remaining_amount > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold {{ $invoice->remaining_amount > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} uppercase tracking-wider mb-1">Kalan Tutar</p>
                                        <p class="text-xl sm:text-2xl font-bold {{ $invoice->remaining_amount > 0 ? 'text-red-900 dark:text-red-100' : 'text-green-900 dark:text-green-100' }}">{{ number_format($invoice->remaining_amount, 2, ',', '.') }} ₺</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Ödeme Oranı -->
                            <div class="group bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 p-5 sm:p-6 rounded-2xl border border-indigo-200/60 dark:border-indigo-800/60 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 xl:col-span-2">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-2">Ödeme Oranı</p>
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-lg font-bold text-indigo-900 dark:text-indigo-100">{{ number_format(($invoice->total_paid / $invoice->grand_total) * 100, 1) }}%</span>
                                                <span class="text-sm text-indigo-600 dark:text-indigo-400">{{ number_format($invoice->grand_total, 0, ',', '.') }} ₺ toplam</span>
                                            </div>
                                            <div class="w-full bg-indigo-200 dark:bg-indigo-700 rounded-full h-3 overflow-hidden">
                                                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-3 rounded-full transition-all duration-700 ease-out" style="width: {{ $invoice->grand_total > 0 ? min(($invoice->total_paid / $invoice->grand_total) * 100, 100) : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Açıklama -->
                        @if($invoice->notes)
                            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-600">
                                <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-600 p-6 rounded-xl border border-slate-200 dark:border-slate-600">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">Açıklama</p>
                                            <p class="text-slate-900 dark:text-slate-100 leading-relaxed">{{ $invoice->notes }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Fatura Kalemleri -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Fatura Kalemleri</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                            <thead class="bg-slate-50 dark:bg-slate-800">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kalem</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Miktar</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Birim Fiyat</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">KDV %</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">KDV Tutarı</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Toplam</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($invoice->items as $item)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900 dark:text-slate-100">{{ $item->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ number_format($item->unit_price, 2, ',', '.') }} ₺</td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ number_format($item->vat_rate, 1) }}%</td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ number_format(($item->quantity * $item->unit_price) * ($item->vat_rate / 100), 2, ',', '.') }} ₺</td>
                                        <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($item->line_total, 2, ',', '.') }} ₺</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- OCR Verileri -->
                @if($invoice->parsed_payload)
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">OCR Tarama Sonuçları</h3>
                        </div>
                        <div class="p-6">
                            @if(isset($invoice->parsed_payload['supplier']))
                                <div class="mb-4">
                                    <p class="text-slate-500 dark:text-slate-400">Tespit Edilen Tedarikçi</p>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->parsed_payload['supplier'] }}</p>
                                </div>
                            @endif
                            @if(isset($invoice->parsed_payload['invoice_number']))
                                <div class="mb-4">
                                    <p class="text-slate-500 dark:text-slate-400">Tespit Edilen Fatura No</p>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->parsed_payload['invoice_number'] }}</p>
                                </div>
                            @endif
                            @if(isset($invoice->parsed_payload['invoice_date']))
                                <div class="mb-4">
                                    <p class="text-slate-500 dark:text-slate-400">Tespit Edilen Fatura Tarihi</p>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $invoice->parsed_payload['invoice_date'] }}</p>
                                </div>
                            @endif
                            @if(isset($invoice->parsed_payload['items']) && count($invoice->parsed_payload['items']) > 0)
                                <div class="mt-6">
                                    <h4 class="text-md font-semibold text-slate-900 dark:text-slate-100 mb-4">Tespit Edilen Ürünler ve Öneriler</h4>
                                    <div class="space-y-4">
                                        @foreach($invoice->parsed_payload['items'] as $ocrItem)
                                            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <p class="font-medium text-slate-900 dark:text-slate-100">{{ $ocrItem['description'] }}</p>
                                                        <p class="text-sm text-slate-600 dark:text-slate-300">
                                                            Miktar: {{ $ocrItem['quantity'] }} {{ $ocrItem['unit'] }},
                                                            Fiyat: {{ number_format($ocrItem['unit_price'], 2, ',', '.') }} ₺,
                                                            Toplam: {{ number_format($ocrItem['line_total'], 2, ',', '.') }} ₺
                                                        </p>
                                                    </div>
                                                    <div class="ml-4">
                                                        @if(isset($ocrItem['suggestions']) && count($ocrItem['suggestions']) > 0)
                                                            <div class="text-sm">
                                                                <p class="text-slate-500 dark:text-slate-400 mb-1">Stok Eşleşmeleri:</p>
                                                                <div class="flex flex-wrap gap-1">
                                                                    @foreach($ocrItem['suggestions'] as $suggestion)
                                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                                                            {{ $suggestion }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Ödeme Geçmişi -->
                @if($invoice->payment_history && count($invoice->payment_history) > 0)
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Ödeme Geçmişi</h3>
                        @foreach($invoice->payment_history as $index => $payment)
                            <div class="group bg-gradient-to-r from-white to-slate-50/50 dark:from-slate-700 dark:to-slate-600/50 rounded-2xl border border-slate-200/60 dark:border-slate-600/60 p-6 hover:shadow-xl hover:shadow-emerald-100/50 dark:hover:shadow-emerald-900/20 transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-4 flex-1">
                                        <!-- Payment Icon -->
                                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/40 dark:to-emerald-800/40 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                        </div>

                                        <!-- Payment Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($payment['amount'], 2, ',', '.') }} ₺</h4>
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 border border-blue-200/50 dark:border-blue-800/50">
                                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($payment['date'])->format('d.m.Y') }}
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                                <div class="flex items-center space-x-2 p-2 bg-slate-50 dark:bg-slate-600/30 rounded-lg">
                                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ ucfirst($payment['method']) }}</span>
                                                </div>

                                                

                                                @if(isset($payment['notes']) && $payment['notes'])
                                                    <div class="flex items-start space-x-2 p-2 bg-amber-50 dark:bg-amber-900/20 rounded-lg sm:col-span-2 lg:col-span-1">
                                                        <svg class="w-4 h-4 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        <span class="text-sm text-amber-800 dark:text-amber-200 leading-relaxed">{{ $payment['notes'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <div class="flex-shrink-0 ml-4">
                                        <form method="POST" action="{{ route('stock.purchases.deletePayment', [$invoice, $index]) }}" onsubmit="return confirm('Bu ödemeyi silmek istediğinizden emin misiniz?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center p-3 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200 group/delete" title="Ödemeyi Sil">
                                                <svg class="w-5 h-5 group-hover/delete:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Henüz Ödeme Yapılmamış</h3>
                        <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto">Bu fatura için henüz hiç ödeme kaydedilmemiş. Aşağıdan yeni bir ödeme ekleyebilirsiniz.</p>
                    </div>
                @endif

                @if($invoice->remaining_amount > 0)
                    <!-- Yeni Ödeme Ekle -->
                    <div class="bg-gradient-to-br from-green-50 via-emerald-50/30 to-teal-50/30 dark:from-green-900/10 dark:via-emerald-900/5 dark:to-teal-900/5 rounded-2xl shadow-xl border border-green-200/50 dark:border-green-800/30 overflow-hidden">
                        <div class="px-8 py-6 border-b border-green-200/50 dark:border-green-800/30 bg-gradient-to-r from-green-100/50 to-emerald-100/50 dark:from-green-900/20 dark:to-emerald-900/20">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-emerald-200 dark:from-green-900/40 dark:to-emerald-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-green-900 dark:text-green-100">Yeni Ödeme Ekle</h3>
                                    <p class="text-green-600 dark:text-green-400 mt-1">Kalan tutar: <span class="font-semibold">{{ number_format($invoice->remaining_amount, 2, ',', '.') }} ₺</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 sm:p-8">
                            <form method="POST" action="{{ route('stock.purchases.addPayment', $invoice) }}" enctype="multipart/form-data" class="space-y-6 sm:space-y-8">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                    <!-- Ödeme Tutarı -->
                                    <div class="space-y-3">
                                        <label for="payment_amount" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                <span>Ödeme Tutarı *</span>
                                            </span>
                                        </label>
                                        <div class="relative group">
                                            <input type="number" step="0.01" name="payment_amount" id="payment_amount"
                                                   max="{{ $invoice->remaining_amount }}"
                                                   class="block w-full pl-4 pr-12 py-4 border-2 border-slate-200 dark:border-slate-600 rounded-2xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg font-medium"
                                                   placeholder="0.00" required>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <span class="text-slate-400 dark:text-slate-500 text-lg font-semibold">₺</span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Maksimum: {{ number_format($invoice->remaining_amount, 2, ',', '.') }} ₺
                                        </p>
                                    </div>

                                    <!-- Ödeme Tarihi -->
                                    <div class="space-y-3">
                                        <label for="payment_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>Ödeme Tarihi *</span>
                                            </span>
                                        </label>
                                        <div class="relative group">
                                            <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}"
                                                   class="block w-full px-4 py-4 border-2 border-slate-200 dark:border-slate-600 rounded-2xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-lg"
                                                   required>
                                        </div>
                                    </div>

                                    <!-- Ödeme Yöntemi -->
                                    <div class="space-y-3">
                                        <label for="payment_method" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                                <span>Ödeme Yöntemi *</span>
                                            </span>
                                        </label>
                                        <div class="relative group">
                                            <select name="payment_method" id="payment_method"
                                                    class="block w-full px-4 py-4 border-2 border-slate-200 dark:border-slate-600 rounded-2xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 text-lg appearance-none"
                                                    required>
                                                <option value="">Seçiniz...</option>
                                                <option value="nakit">💵 Nakit</option>
                                                <option value="havale">🏦 Havale/EFT</option>
                                                <option value="kredi_karti">💳 Kredi Kartı</option>
                                                <option value="cek">📄 Çek</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    
                                </div>

                                <!-- Notlar -->
                                <div class="space-y-3">
                                    <label for="notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        <span class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <span>Notlar</span>
                                        </span>
                                    </label>
                                    <div class="relative group">
                                        <textarea name="notes" id="notes" rows="4"
                                                  class="block w-full px-4 py-4 border-2 border-slate-200 dark:border-slate-600 rounded-2xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-300 resize-none text-lg"
                                                  placeholder="Ödeme ile ilgili notlarınızı buraya yazın..."></textarea>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-end pt-6 border-t border-slate-200 dark:border-slate-600">
                                    <button type="submit" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-lg">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Ödemeyi Kaydet
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-lg w-full transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Hızlı Ödeme Ekle</h3>
                    <button onclick="closePaymentModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <form id="paymentForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <input type="hidden" id="modal_schedule_id" name="schedule_id">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <label for="modal_payment_amount" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <span>Ödeme Tutarı *</span>
                            </span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" name="payment_amount" id="modal_payment_amount"
                                   class="block w-full pl-4 pr-12 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 dark:text-slate-400 text-sm font-medium">₺</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label for="modal_payment_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Ödeme Tarihi *</span>
                        </span>
                    </label>
                    <input type="date" name="payment_date" id="modal_payment_date" value="{{ date('Y-m-d') }}"
                           class="block w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           required>
                </div>

                <div class="space-y-3 md:col-span-2">
                    <label for="modal_payment_method" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span>Ödeme Yöntemi *</span>
                        </span>
                    </label>
                    <div class="relative">
                        <select name="payment_method" id="modal_payment_method"
                                class="block w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 appearance-none"
                                required>
                            <option value="nakit">💵 Nakit</option>
                            <option value="havale">🏦 Havale/EFT</option>
                            <option value="kredi_karti">💳 Kredi Kartı</option>
                            <option value="cek">📄 Çek</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 md:col-span-2">
                    <label for="modal_receipt_file" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Dekont Dosyası</span>
                        </span>
                    </label>
                    <input type="file" name="receipt_file" id="modal_receipt_file" accept=".pdf,.jpg,.jpeg,.png"
                           class="block w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 file:transition-colors">
                </div>

                <div class="space-y-3 md:col-span-2">
                    <label for="modal_notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>Notlar</span>
                        </span>
                    </label>
                    <textarea name="notes" id="modal_notes" rows="3"
                              class="block w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200 resize-none"
                              placeholder="Ödeme ile ilgili notlarınızı buraya yazın..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-600">
                <button type="button" onclick="closePaymentModal()"
                        class="px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl transition-all duration-200">
                    İptal
                </button>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ödemeyi Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPaymentModal(scheduleId = null, amount = null, installmentNumber = null) {
        const modal = document.getElementById('paymentModal');
        const modalContent = document.getElementById('modalContent');
        const form = document.getElementById('paymentForm');
        const scheduleInput = document.getElementById('modal_schedule_id');
        const amountInput = document.getElementById('modal_payment_amount');

        form.action = "{{ route('stock.purchases.addPayment', $invoice) }}";

        if (scheduleId) {
            scheduleInput.value = scheduleId;
            amountInput.value = amount;
            amountInput.max = amount;
            document.querySelector('#paymentModal h3').textContent = `Taksit ${installmentNumber} Ödemesi`;
        } else {
            scheduleInput.value = '';
            amountInput.value = {{ $invoice->remaining_amount }};
            amountInput.max = {{ $invoice->remaining_amount }};
            document.querySelector('#paymentModal h3').textContent = 'Hızlı Ödeme Ekle';
        }

        modal.classList.remove('hidden');
        // Trigger animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closePaymentModal() {
        const modal = document.getElementById('paymentModal');
        const modalContent = document.getElementById('modalContent');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Close modal when clicking outside
    document.getElementById('paymentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePaymentModal();
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('paymentModal').classList.contains('hidden')) {
            closePaymentModal();
        }
    });
</script>

</x-app-layout>
