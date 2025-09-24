<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Gider Düzenle</h2>
            <x-secondary-button-link href="{{ route('stock.expenses.index') }}">Listeye Dön</x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-card class="space-y-6">
                <form method="POST" action="{{ route('stock.expenses.update', $expense) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="title" value="Başlık" />
                            <x-text-input id="title" name="title" type="text" value="{{ old('title', $expense->title) }}" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="category_id" value="Kategori" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <option value="">Seçiniz</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $expense->category_id) == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="supplier_id" value="Cari" />
                            <select id="supplier_id" name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <option value="">Seçiniz</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id', $expense->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="expense_date" value="Tarih" />
                            <x-text-input id="expense_date" name="expense_date" type="date" value="{{ old('expense_date', optional($expense->expense_date)->toDateString()) }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="amount" value="Tutar" />
                            <x-text-input id="amount" name="amount" type="number" step="0.01" value="{{ old('amount', $expense->amount) }}" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="vat_rate" value="KDV %" />
                            <x-text-input id="vat_rate" name="vat_rate" type="number" step="0.01" value="{{ old('vat_rate', $expense->vat_rate) }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="payment_status" value="Durum" />
                            <select id="payment_status" name="payment_status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <option value="pending" @selected(old('payment_status', $expense->payment_status) === 'pending')>Bekleyen</option>
                                <option value="partial" @selected(old('payment_status', $expense->payment_status) === 'partial')>Kısmi</option>
                                <option value="paid" @selected(old('payment_status', $expense->payment_status) === 'paid')>Ödendi</option>
                                <option value="overdue" @selected(old('payment_status', $expense->payment_status) === 'overdue')>Gecikmiş</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="payment_method" value="Ödeme Yöntemi" />
                            <x-text-input id="payment_method" name="payment_method" type="text" value="{{ old('payment_method', $expense->payment_method) }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="due_date" value="Vade" />
                            <x-text-input id="due_date" name="due_date" type="date" value="{{ old('due_date', optional($expense->due_date)->toDateString()) }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="paid_at" value="Ödeme Tarihi" />
                            <x-text-input id="paid_at" name="paid_at" type="date" value="{{ old('paid_at', optional($expense->paid_at)->toDateString()) }}" class="mt-1 block w-full" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="notes" value="Notlar" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">{{ old('notes', $expense->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <x-secondary-button type="reset">Temizle</x-secondary-button>
                        <x-primary-button type="submit">Güncelle</x-primary-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
