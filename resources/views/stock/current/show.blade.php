<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Cari Detayi: {{ $supplier->name }}</h2>
            <x-secondary-button-link href="{{ route('stock.current.index') }}">Listeye Don</x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Tur</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $supplier->type === 'service' ? 'Hizmet' : 'Tedarikci' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Telefon</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $supplier->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">E-posta</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $supplier->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Vergi No</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $supplier->tax_number ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-gray-500">Adres</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $supplier->address ?? '-' }}</p>
                    </div>
                </div>
            </x-card>

            <x-card class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Cari Hareketleri</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Tarih</th>
                                <th class="px-4 py-2 text-left">Aciklama</th>
                                <th class="px-4 py-2 text-left">Yontem</th>
                                <th class="px-4 py-2 text-left">Yon</th>
                                <th class="px-4 py-2 text-right">Tutar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($movements as $movement)
                                <tr>
                                    <td class="px-4 py-3">{{ optional($movement->movement_date)->format('d.m.Y') ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $movement->description ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $movement->payment_method ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($movement->direction) }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($movement->amount, 2, ',', '.') }} TL</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">Hareket bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $movements->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>


