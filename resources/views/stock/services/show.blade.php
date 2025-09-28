<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ ucfirst($expense->service_type) }} Gideri</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">
                    {{ $expense->service_provider ?? 'Hizmet Sağlayıcı Belirtilmemiş' }}
                    @if($expense->invoice_number)
                        - Fatura: {{ $expense->invoice_number }}
                    @endif
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('stock.services.edit', $expense) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
                @if($expense->remaining_amount > 0)
                    <button onclick="openPaymentModal()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ödeme Ekle
                    </button>
                @endif
                <a href="{{ route('stock.services.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Expense Details -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Gider Bilgileri</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Hizmet Türü</dt>
                                <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ ucfirst($expense->service_type) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Hizmet Sağlayıcı</dt>
                                <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $expense->service_provider ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Fatura Numarası</dt>
                                <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $expense->invoice_number ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Fatura Tarihi</dt>
                                <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $expense->invoice_date->format('d.m.Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Vade Tarihi</dt>
                                <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $expense->due_date ? $expense->due_date->format('d.m.Y') : '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Ödeme Yöntemi</dt>
                                <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $expense->payment_method ? ucfirst(str_replace('_', ' ', $expense->payment_method)) : '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Payment History -->
                    @if($expense->payment_history && count($expense->payment_history) > 0)
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Ödeme Geçmişi</h3>
                            <div class="space-y-4">
                                @foreach($expense->payment_history as $payment)
                                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                                        <div>
                                            <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                                {{ number_format($payment['amount'], 2, ',', '.') }} TL
                                            </div>
                                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                                {{ \Carbon\Carbon::parse($payment['date'])->format('d.m.Y') }} - {{ ucfirst(str_replace('_', ' ', $payment['method'])) }}
                                            </div>
                                            @if(isset($payment['notes']) && $payment['notes'])
                                                <div class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $payment['notes'] }}</div>
                                            @endif
                                        </div>
                                        @if(isset($payment['receipt_path']) && $payment['receipt_path'])
                                            <a href="{{ Storage::url($payment['receipt_path']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($expense->notes)
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Notlar</h3>
                            <p class="text-slate-700 dark:text-slate-300">{{ $expense->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Durum</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600 dark:text-slate-400">Durum</span>
                                @if($expense->status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Ödendi
                                    </span>
                                @elseif($expense->status === 'overdue' || $expense->isOverdue())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        Gecikmiş
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        Ödenecek
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600 dark:text-slate-400">Toplam Tutar</span>
                                <span class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ number_format($expense->amount, 2, ',', '.') }} TL</span>
                            </div>
                            @if($expense->total_paid > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600 dark:text-slate-400">Ödenen Tutar</span>
                                    <span class="text-sm font-medium text-green-600 dark:text-green-400">{{ number_format($expense->total_paid, 2, ',', '.') }} TL</span>
                                </div>
                            @endif
                            @if($expense->remaining_amount > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600 dark:text-slate-400">Kalan Tutar</span>
                                    <span class="text-sm font-medium text-red-600 dark:text-red-400">{{ number_format($expense->remaining_amount, 2, ',', '.') }} TL</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Invoice File -->
                    @if($expense->invoice_path)
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Fatura Dosyası</h3>
                            <a href="{{ Storage::url($expense->invoice_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Faturayı Görüntüle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-slate-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">Ödeme Ekle</h3>
                <form method="POST" action="{{ route('stock.services.addPayment', $expense) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ödeme Tutarı</label>
                        <input type="number" step="0.01" name="payment_amount" value="{{ $expense->remaining_amount }}" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ödeme Tarihi</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ödeme Yöntemi</label>
                        <select name="payment_method" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100" required>
                            <option value="nakit">Nakit</option>
                            <option value="kredi_karti">Kredi Kartı</option>
                            <option value="havale">Havale</option>
                            <option value="cek">Çek</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Dekont (İsteğe bağlı)</label>
                        <input type="file" name="receipt_file" accept="image/*,application/pdf" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Notlar</label>
                        <textarea name="notes" rows="3" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closePaymentModal()" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg">İptal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Ödemeyi Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openPaymentModal() {
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }
    </script>
</x-app-layout>