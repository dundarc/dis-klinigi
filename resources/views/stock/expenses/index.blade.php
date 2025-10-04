<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Gider Listesi</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Gider kayıtlarını yönetin ve takip edin.</p>
            </div>
            <a href="{{ route('stock.expenses.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Yeni Gider
            </a>
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

                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <x-input-label for="category_id" value="Kategori" class="text-slate-600 dark:text-slate-300" />
                        <select id="category_id" name="category_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Tüm Kategoriler</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="date_from" value="Tarih Başlangıç" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    </div>
                    <div>
                        <x-input-label for="date_to" value="Tarih Bitiş" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    </div>
                    <div>
                        <x-input-label for="amount_min" value="Tutar Min (TL)" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="amount_min" name="amount_min" type="number" step="0.01" value="{{ $filters['amount_min'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="0.00" />
                    </div>
                    <div>
                        <x-input-label for="amount_max" value="Tutar Max (TL)" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="amount_max" name="amount_max" type="number" step="0.01" value="{{ $filters['amount_max'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="0.00" />
                    </div>
                    <div class="col-span-full flex flex-wrap items-end justify-end gap-2">
                        <x-secondary-button type="button" onclick="window.location='{{ route('stock.expenses.index') }}';">
                            Sıfırla
                        </x-secondary-button>
                        <x-primary-button type="submit">Filtrele</x-primary-button>
                    </div>
                </form>

                <div x-show="showCategoryForm" x-transition class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 p-4">
                    <form method="POST" action="{{ route('stock.expense-categories.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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
                <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Gider Adı</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tutar</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ödeme Durumu</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Açıklama</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($expenses as $index => $expense)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800' }} hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900 dark:text-slate-100">{{ $expense->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $expense->category?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ number_format($expense->total_amount, 2, ',', '.') }} TL</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $expense->expense_date?->format('d.m.Y') ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($expense->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($expense->payment_status === 'partial') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                            @if($expense->payment_status === 'paid') Ödendi
                                            @elseif($expense->payment_status === 'partial') Kısmi
                                            @else Bekliyor @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300 max-w-xs truncate">{{ $expense->notes ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('stock.expenses.show', $expense) }}" class="inline-flex items-center p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Göster">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('stock.expenses.edit', $expense) }}" class="inline-flex items-center p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Düzenle">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('stock.expenses.destroy', $expense) }}" onsubmit="return confirm('Bu gideri silmek istediğinizden emin misiniz?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center p-2 text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors" title="Sil">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Gider bulunmuyor</h3>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Henüz hiç gider eklenmemiş. Yeni bir gider ekleyerek başlayın.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $expenses->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
