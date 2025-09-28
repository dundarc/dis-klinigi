<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Tedarikçi Listesi</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Tedarikçilerinizi yönetin ve takip edin.</p>
            </div>
            <a href="{{ route('stock.suppliers.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Yeni Tedarikçi
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <x-card class="space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('stock.supplier_filter') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('stock.filter_suppliers_description') }}</p>
                    </div>
                </div>

                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <x-input-label for="q" value="İsim Ara" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="q" name="q" type="text" value="{{ $filters['q'] ?? '' }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" placeholder="Tedarikçi adı ile ara..." />
                    </div>
                    <div>
                        <x-input-label for="tax_number" value="Vergi No" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="tax_number" name="tax_number" type="text" value="{{ $filters['tax_number'] ?? '' }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" placeholder="Vergi numarası ile ara..." />
                    </div>
                    <div>
                        <x-input-label for="city" value="Şehir" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="city" name="city" type="text" value="{{ $filters['city'] ?? '' }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" placeholder="Şehir ile ara..." />
                    </div>
                    <div>
                        <x-input-label for="type" value="Tür" class="text-slate-600 dark:text-slate-300" />
                        <select id="type" name="type" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Tüm Türler</option>
                            <option value="supplier" @selected(($filters['type'] ?? '') === 'supplier')>Tedarikçi</option>
                            <option value="service" @selected(($filters['type'] ?? '') === 'service')>Hizmet</option>
                        </select>
                    </div>
                    <div class="col-span-full flex flex-wrap items-end justify-end gap-2">
                        <x-secondary-button type="button" onclick="window.location='{{ route('stock.suppliers.index') }}';">
                            Sıfırla
                        </x-secondary-button>
                        <x-primary-button type="submit">Filtrele</x-primary-button>
                    </div>
                </form>
            </x-card>

            <x-card class="space-y-4">
                <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tedarikçi Adı</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tür</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İletişim</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Vergi No</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($suppliers as $index => $supplier)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800' }} hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900 dark:text-slate-100">{{ $supplier->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $supplier->address ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($supplier->type === 'service')
                                            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">Hizmet</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-1 text-xs font-medium text-emerald-800 dark:text-emerald-200">Tedarikçi</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                        <div class="text-sm">{{ $supplier->phone ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $supplier->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $supplier->tax_number ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('stock.current.show', $supplier) }}" class="inline-flex items-center p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Cari Detay">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('stock.suppliers.edit', $supplier) }}" class="inline-flex items-center p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Düzenle">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('stock.suppliers.destroy', $supplier) }}" onsubmit="return confirm('Bu tedarikçiyi silmek istediğinizden emin misiniz?');" class="inline">
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
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Tedarikçi bulunmuyor</h3>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Henüz hiç tedarikçi eklenmemiş. Yeni bir tedarikçi ekleyerek başlayın.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $suppliers->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

