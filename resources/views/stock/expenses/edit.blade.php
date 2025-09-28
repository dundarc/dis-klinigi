<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Gider Düzenle</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $expense->title }} - Bilgilerini güncelleyin.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('stock.expenses.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri Dön
                </a>
                <form method="POST" action="{{ route('stock.expenses.destroy', $expense) }}" onsubmit="return confirm('Bu gideri silmek istediğinizden emin misiniz?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Sil
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Gider Bilgileri</h3>
                </div>
                <form method="POST" action="{{ route('stock.expenses.update', $expense) }}" x-data="{ hasChanges: false }" @change="hasChanges = true" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gider Adı <span class="text-red-500">*</span></label>
                            <input id="title" name="title" type="text" value="{{ old('title', $expense->title) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kategori <span class="text-red-500">*</span></label>
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">Kategori Seçin</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $expense->category_id) == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>
                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tedarikçi</label>
                            <select id="supplier_id" name="supplier_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Tedarikçi Seçin (Opsiyonel)</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id', $expense->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                        </div>
                        <div>
                            <label for="amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tutar (TL) <span class="text-red-500">*</span></label>
                            <input id="amount" name="amount" type="number" step="0.01" value="{{ old('amount', $expense->amount) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>
                        <div>
                            <label for="vat_rate" class="block text-sm font-medium text-slate-700 dark:text-slate-300">KDV Oranı (%)</label>
                            <input id="vat_rate" name="vat_rate" type="number" step="0.01" min="0" max="100" value="{{ old('vat_rate', $expense->vat_rate) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error :messages="$errors->get('vat_rate')" class="mt-2" />
                        </div>
                        <div>
                            <label for="expense_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tarih <span class="text-red-500">*</span></label>
                            <input id="expense_date" name="expense_date" type="date" value="{{ old('expense_date', optional($expense->expense_date)->toDateString()) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                            <x-input-error :messages="$errors->get('expense_date')" class="mt-2" />
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Vade Tarihi</label>
                            <input id="due_date" name="due_date" type="date" value="{{ old('due_date', optional($expense->due_date)->toDateString()) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Yöntemi</label>
                            <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Ödeme Yöntemi Seçin</option>
                                <option value="nakit" @selected(old('payment_method', $expense->payment_method) == 'nakit')>Nakit</option>
                                <option value="havale" @selected(old('payment_method', $expense->payment_method) == 'havale')>Havale</option>
                                <option value="kredi_karti" @selected(old('payment_method', $expense->payment_method) == 'kredi_karti')>Kredi Kartı</option>
                                <option value="cek" @selected(old('payment_method', $expense->payment_method) == 'cek')>Çek</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                        </div>
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Durumu <span class="text-red-500">*</span></label>
                            <select id="payment_status" name="payment_status" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="pending" @selected(old('payment_status', $expense->payment_status) == 'pending')>Bekliyor</option>
                                <option value="paid" @selected(old('payment_status', $expense->payment_status) == 'paid')>Ödendi</option>
                                <option value="overdue" @selected(old('payment_status', $expense->payment_status) == 'overdue')>Gecikmiş</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_status')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Açıklama</label>
                            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $expense->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('stock.expenses.index') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <button type="submit" :disabled="!hasChanges" :class="hasChanges ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-400 cursor-not-allowed'" class="px-6 py-2 text-white font-medium rounded-lg transition-colors">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
