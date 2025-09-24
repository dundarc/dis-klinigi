<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Cari Bakiyeler</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-card class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Cari</th>
                                <th class="px-4 py-2 text-left">Tur</th>
                                <th class="px-4 py-2 text-right">Borc</th>
                                <th class="px-4 py-2 text-right">Alacak</th>
                                <th class="px-4 py-2 text-right">Bakiye</th>
                                <th class="px-4 py-2 text-right">Detay</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($suppliers as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $row['supplier']->name }}</td>
                                    <td class="px-4 py-3">{{ $row['supplier']->type === 'service' ? 'Hizmet' : 'Tedarikci' }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($row['debit'], 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($row['credit'], 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3 text-right {{ $row['balance'] >= 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($row['balance'], 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3 text-right">
                                        <x-secondary-button-link href="{{ route('stock.current.show', $row['supplier']) }}">Detay</x-secondary-button-link>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">Cari hareket bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

