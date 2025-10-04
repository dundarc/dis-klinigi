<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $expense->title }}</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Gider detayları ve ödeme bilgileri</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('stock.expenses.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri Dön
                </a>
                <a href="{{ route('stock.expenses.edit', $expense) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Payment Summary -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-6">Ödeme Özeti</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($expense->total_amount, 2, ',', '.') }} TL</div>
                        <div class="text-sm text-slate-500 dark:text-slate-400">Toplam Tutar</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($expense->total_paid, 2, ',', '.') }} TL</div>
                        <div class="text-sm text-slate-500 dark:text-slate-400">Ödenen</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($expense->remaining_amount, 2, ',', '.') }} TL</div>
                        <div class="text-sm text-slate-500 dark:text-slate-400">Kalan</div>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($expense->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($expense->payment_status === 'partial') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                            @if($expense->payment_status === 'paid') Ödendi
                            @elseif($expense->payment_status === 'partial') Kısmi Ödeme
                            @else Bekliyor @endif
                        </span>
                        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">Durum</div>
                    </div>
                </div>
            </div>

            <!-- Expense Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Gider Bilgileri</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Kategori</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100">{{ $expense->category?->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Tedarikçi</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100">{{ $expense->supplier?->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Tarih</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100">{{ $expense->expense_date?->format('d.m.Y') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Vade Tarihi</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100">{{ $expense->due_date?->format('d.m.Y') ?? '-' }}</dd>
                        </div>
                    </dl>
                    @if($expense->notes)
                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Açıklama</h4>
                        <p class="text-sm text-slate-900 dark:text-slate-100">{{ $expense->notes }}</p>
                    </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Vade Tarihi Değiştir</h3>
                    <form method="POST" action="{{ route('stock.expenses.update-due-date', $expense) }}">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Yeni Vade Tarihi</label>
                                <input id="due_date" name="due_date" type="date" value="{{ old('due_date', optional($expense->due_date)->toDateString()) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Vade Tarihini Güncelle
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payments History -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Ödeme Geçmişi</h3>
                    <button type="button" onclick="document.getElementById('payment-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ödeme Ekle
                    </button>
                </div>

                @if($expense->payments->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tutar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ödeme Yöntemi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Açıklama</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($expense->payments as $payment)
                            <tr>
                                <td class="px-6 py-4 text-slate-900 dark:text-slate-100">{{ $payment->payment_date->format('d.m.Y') }}</td>
                                <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($payment->amount, 2, ',', '.') }} TL</td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $payment->payment_method?->label() ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $payment->notes ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('stock.expenses.payments.destroy', [$expense, $payment]) }}" onsubmit="return confirm('Bu ödemeyi silmek istediğinizden emin misiniz?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center p-2 text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors" title="Sil">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Henüz ödeme yapılmamış</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Bu gider için henüz hiç ödeme kaydedilmemiş.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="payment-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-500 bg-opacity-75" onclick="document.getElementById('payment-modal').classList.add('hidden')" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form method="POST" action="{{ route('stock.expenses.payments.store', $expense) }}">
                        @csrf
                        <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-slate-100" id="modal-title">
                                        Ödeme Ekle
                                    </h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="payment_amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Tutarı (TL)</label>
                                            <input id="payment_amount" name="amount" type="number" step="0.01" max="{{ $expense->remaining_amount }}" value="{{ old('amount') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Maksimum: {{ number_format($expense->remaining_amount, 2, ',', '.') }} TL</p>
                                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="payment_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Tarihi</label>
                                            <input id="payment_date" name="payment_date" type="date" value="{{ old('payment_date', now()->toDateString()) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                                            <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="payment_method" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Yöntemi</label>
                                            <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value="cash" @selected(old('payment_method') == 'cash')>Nakit</option>
                                                <option value="bank_transfer" @selected(old('payment_method') == 'bank_transfer')>Havale</option>
                                                <option value="credit_card" @selected(old('payment_method') == 'credit_card')>Kredi Kartı</option>
                                                <option value="check" @selected(old('payment_method') == 'check')>Çek</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="payment_notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Açıklama</label>
                                            <textarea id="payment_notes" name="notes" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Ödeme Ekle
                            </button>
                            <button type="button" onclick="document.getElementById('payment-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                İptal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>