<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">{{ $category->name }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Gider Kategorisi Detayları') }}</p>
            </div>
            <div class="flex gap-2">
                <x-secondary-button-link href="{{ route('stock.expense-categories.edit', $category) }}">{{ __('Düzenle') }}</x-secondary-button-link>
                <x-secondary-button-link href="{{ route('stock.expense-categories.index') }}">{{ __('Geri Dön') }}</x-secondary-button-link>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Category Info -->
            <x-card>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Kategori Adı') }}</h3>
                        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $category->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Slug') }}</h3>
                        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $category->slug }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Toplam Gider') }}</h3>
                        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $expenses->total() }}</p>
                    </div>
                </div>
            </x-card>

            <!-- Expenses List -->
            <x-card class="space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('Bu Kategorideki Giderler') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Kategoriye ait tüm gider kayıtları') }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-100 dark:bg-slate-800/70 text-slate-600 dark:text-slate-300">
                            <tr>
                                <th class="px-4 py-3 text-left">{{ __('Gider Adı') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('Tedarikçi') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('Tarih') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('Tutar') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('Durum') }}</th>
                                <th class="px-4 py-3 text-right">{{ __('İşlemler') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($expenses as $expense)
                                <tr class="bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-slate-800 dark:text-slate-100">{{ $expense->title }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">
                                        {{ $expense->supplier?->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">
                                        {{ $expense->expense_date->format('d.m.Y') }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">
                                        ₺{{ number_format($expense->total_amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            @if($expense->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                            @elseif($expense->payment_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @endif">
                                            @if($expense->payment_status === 'paid') Ödendi
                                            @elseif($expense->payment_status === 'pending') Bekliyor
                                            @else Gecikmiş @endif
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <a href="{{ route('stock.expenses.show', $expense) }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ __('Görüntüle') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-lg font-medium">{{ __('Bu kategoride gider bulunamadı') }}</p>
                                            <p class="text-sm">{{ __('Henüz bu kategoriye ait gider eklenmemiş') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($expenses->hasPages())
                    <div class="mt-4">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>