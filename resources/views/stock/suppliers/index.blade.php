<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Cari / Tedarikciler</h2>
            <x-primary-button-link href="{{ route('stock.suppliers.create') }}">Yeni Kayit</x-primary-button-link>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card class="space-y-6">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <x-input-label for="q" value="Arama" />
                        <x-text-input id="q" name="q" type="text" value="{{ $filters['q'] ?? '' }}" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="type" value="Tur" />
                        <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                            <option value="">Tum Kayitlar</option>
                            <option value="supplier" @selected(($filters['type'] ?? '') === 'supplier')>Tedarikci</option>
                            <option value="service" @selected(($filters['type'] ?? '') === 'service')>Hizmet</option>
                        </select>
                    </div>
                    <div class="col-span-full flex items-end justify-end gap-2">
                        <x-secondary-button type="button" onclick="window.location='{{ route('stock.suppliers.index') }}';">Sifirla</x-secondary-button>
                        <x-primary-button type="submit">Filtrele</x-primary-button>
                    </div>
                </form>
            </x-card>

            <x-card class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Ad</th>
                                <th class="px-4 py-2 text-left">Tur</th>
                                <th class="px-4 py-2 text-left">Iletisim</th>
                                <th class="px-4 py-2 text-left">Vergi No</th>
                                <th class="px-4 py-2 text-right">Islemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($suppliers as $supplier)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $supplier->name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $supplier->type === 'service' ? 'Hizmet' : 'Tedarikci' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="text-xs text-gray-500">Tel: {{ $supplier->phone ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">Mail: {{ $supplier->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $supplier->tax_number ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <x-secondary-button-link href="{{ route('stock.suppliers.edit', $supplier) }}">Duzenle</x-secondary-button-link>
                                            <form method="POST" action="{{ route('stock.suppliers.destroy', $supplier) }}" onsubmit="return confirm('Kaydi silmek istiyor musunuz?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button>Sil</x-danger-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">Kayit bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $suppliers->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

