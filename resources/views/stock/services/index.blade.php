<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Hizmet Giderleri</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Elektrik, su, internet, reklam vb. gider yönetimi</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('stock.services.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Yeni Hizmet Gideri
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Hizmet Türü</label>
                        <select name="service_type" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                            <option value="">Tümü</option>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type }}" {{ request('service_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Durum</label>
                        <select name="status" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                            <option value="">Tümü</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Ödendi</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ödenecek</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Gecikmiş</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Başlangıç Tarihi</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Bitiş Tarihi</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                    </div>
                    <div class="md:col-span-4 flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Filtrele</button>
                        <a href="{{ route('stock.services.index') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg">Temizle</a>
                    </div>
                </form>
            </div>

            <!-- Expenses List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hizmet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Sağlayıcı</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tutar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Vade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Durum</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($expenses as $expense)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ ucfirst($expense->service_type) }}</div>
                                        @if($expense->invoice_number)
                                            <div class="text-sm text-slate-500 dark:text-slate-400">Fatura: {{ $expense->invoice_number }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                                        {{ $expense->service_provider ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                                        {{ number_format($expense->amount, 2, ',', '.') }} TL
                                        @if($expense->remaining_amount > 0)
                                            <div class="text-xs text-red-600 dark:text-red-400">
                                                Kalan: {{ number_format($expense->remaining_amount, 2, ',', '.') }} TL
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                                        {{ $expense->due_date ? $expense->due_date->format('d.m.Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            <a href="{{ route('stock.services.show', $expense) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Görüntüle</a>
                                            <a href="{{ route('stock.services.edit', $expense) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Düzenle</a>
                                            @if($expense->remaining_amount > 0)
                                                <button onclick="openPaymentModal({{ $expense->id }}, '{{ $expense->remaining_amount }}')" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Ödeme Ekle</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">
                                        Hizmet gideri bulunmuyor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($expenses->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-slate-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" x-data="{ open: false }">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-slate-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">Ödeme Ekle</h3>
                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ödeme Tutarı</label>
                        <input type="number" step="0.01" name="payment_amount" id="paymentAmount" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100" required>
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
        function openPaymentModal(expenseId, remainingAmount) {
            document.getElementById('paymentAmount').value = remainingAmount;
            document.getElementById('paymentForm').action = `/stock/services/${expenseId}/payments`;
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }
    </script>
</x-app-layout>