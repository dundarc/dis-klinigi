<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Giderler</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Gider kayıtlarını yönetin ve kategori bazlı analiz yapın.</p>
            </div>
            <x-primary-button-link href="{{ route('stock.expenses.create') }}">Yeni Gider</x-primary-button-link>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <x-card class="space-y-6" x-data="{ showCategoryForm: false }">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">Gider Filtresi</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Kategori, cari veya tarih aralığına göre listeyi daraltın.</p>
                    </div>
                    <x-secondary-button type="button" @click="showCategoryForm = !showCategoryForm">
                        Kategori Ekle
                    </x-secondary-button>
                </div>

                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4">
                    <div>
                        <x-input-label for="category_id" value="Kategori" class="text-slate-600 dark:text-slate-300" />
                        <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Tüm Kategoriler</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="supplier_id" value="Cari" class="text-slate-600 dark:text-slate-300" />
                        <select id="supplier_id" name="supplier_id" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Tüm Kayıtlar</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(($filters['supplier_id'] ?? '') == $supplier->id)>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="payment_status" value="Durum" class="text-slate-600 dark:text-slate-300" />
                        <select id="payment_status" name="payment_status" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Tüm Durumlar</option>
                            <option value="pending" @selected(($filters['payment_status'] ?? '') === 'pending')>Bekleyen</option>
                            <option value="partial" @selected(($filters['payment_status'] ?? '') === 'partial')>Kısmi</option>
                            <option value="paid" @selected(($filters['payment_status'] ?? '') === 'paid')>Ödendi</option>
                            <option value="overdue" @selected(($filters['payment_status'] ?? '') === 'overdue')>Gecikmiş</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="date_from" value="Başlangıç" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" />
                    </div>
                    <div>
                        <x-input-label for="date_to" value="Bitiş" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" />
                    </div>
                    <div class="col-span-full flex flex-wrap items-end justify-end gap-2">
                        <x-secondary-button type="button" onclick="window.location='{{ route('stock.expenses.index') }}';">
                            Sıfırla
                        </x-secondary-button>
                        <x-primary-button type="submit">Filtrele</x-primary-button>
                    </div>
                </form>

                <div x-show="showCategoryForm" x-transition class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 p-4">
                    <form method="POST" action="{{ route('stock.categories.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @csrf
                        <input type="hidden" name="return_to" value="{{ url()->current() }}">
                        <div class="sm:col-span-1">
                            <x-input-label for="new_category_name" value="Kategori Adı" class="text-slate-600 dark:text-slate-300" />
                            <x-text-input id="new_category_name" name="name" type="text" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-1">
                            <x-input-label for="new_category_description" value="Açıklama" class="text-slate-600 dark:text-slate-300" />
                            <x-text-input id="new_category_description" name="description" type="text" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" />
                        </div>
                        <div class="sm:col-span-1 flex items-end justify-end gap-2">
                            <x-secondary-button type="button" @click="showCategoryForm = false">Vazgeç</x-secondary-button>
                            <x-primary-button type="submit">Kategori Kaydet</x-primary-button>
                        </div>
                    </form>
                </div>
            </x-card>

            <x-card class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-100 dark:bg-slate-800/70 text-slate-600 dark:text-slate-300">
                            <tr>
                                <th class="px-4 py-2 text-left">Başlık</th>
                                <th class="px-4 py-2 text-left">Kategori</th>
                                <th class="px-4 py-2 text-left">Cari</th>
                                <th class="px-4 py-2 text-left">Tarih</th>
                                <th class="px-4 py-2 text-left">Durum</th>
                                <th class="px-4 py-2 text-right">Tutar</th>
                                <th class="px-4 py-2 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($expenses as $expense)
                                <tr class="bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800">
                                    <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-100">{{ $expense->title }}</td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $expense->category?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $expense->supplier?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $expense->expense_date?->format('d.m.Y') ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ ucfirst($expense->payment_status) }}</td>
                                    <td class="px-4 py-3 text-right text-slate-800 dark:text-slate-100">{{ number_format($expense->total_amount, 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <x-secondary-button-link href="{{ route('stock.expenses.edit', $expense) }}">Düzenle</x-secondary-button-link>
                                            <form method="POST" action="{{ route('stock.expenses.destroy', $expense) }}" onsubmit="return confirm('Gider silinsin mi?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button>Sil</x-danger-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">Kayıt bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $expenses->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
