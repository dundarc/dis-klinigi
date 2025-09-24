<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Stok Raporlari</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card class="space-y-6">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <x-input-label for="start_date" value="Baslangic" />
                        <x-text-input id="start_date" name="start_date" type="date" value="{{ $period['start'] }}" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="end_date" value="Bitis" />
                        <x-text-input id="end_date" name="end_date" type="date" value="{{ $period['end'] }}" class="mt-1 block w-full" />
                    </div>
                    <div class="col-span-full flex items-end justify-end gap-2">
                        <x-secondary-button type="button" onclick="window.location='{{ route('reports.stock') }}';">Sifirla</x-secondary-button>
                        <x-primary-button type="submit">Raporu Guncelle</x-primary-button>
                    </div>
                </form>
            </x-card>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-card class="p-4">
                    <p class="text-sm text-gray-500">Toplam Gider</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($totalExpense, 2, ',', '.') }} TL</p>
                </x-card>
                <x-card class="p-4">
                    <p class="text-sm text-gray-500">Kritik Kalem</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $criticalItems->count() }}</p>
                </x-card>
                <x-card class="p-4">
                    <p class="text-sm text-gray-500">Negatif Stok</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $negativeStock->count() }}</p>
                </x-card>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-card>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Kategori Bazli Gider</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach($categoryBreakdown as $row)
                            <li class="flex flex-wrap items-center justify-between gap-4">
                                <span>{{ $row['category'] }}</span>
                                <span>{{ number_format($row['total'], 2, ',', '.') }} TL</span>
                            </li>
                        @endforeach
                    </ul>
                </x-card>

                <x-card>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">En Cok Kullanilan Kalemler</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach($usageSummary as $row)
                            <li class="flex flex-wrap items-center justify-between gap-4">
                                <span>{{ $row['item'] }} ({{ $row['unit'] }})</span>
                                <span>{{ number_format($row['quantity'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            </div>

            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Tedarikci Bakiyeleri</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Cari</th>
                                <th class="px-4 py-2 text-left">Bakiye</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($supplierBalances as $row)
                                <tr>
                                    <td class="px-4 py-3">{{ $row['supplier']->name }}</td>
                                    <td class="px-4 py-3">{{ number_format($row['balance'], 2, ',', '.') }} TL</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-gray-500">Cari bakiyesi bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Kritik Stoklar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($criticalItems as $item)
                        <div class="rounded-md border border-amber-300 bg-amber-50 dark:border-amber-700 dark:bg-amber-900/20 p-4">
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $item->name }}</p>
                            <p class="text-xs text-gray-500">Kategori: {{ $item->category?->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">Miktar: {{ number_format($item->quantity, 2) }} {{ $item->unit }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Kritik stok bulunmuyor.</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

