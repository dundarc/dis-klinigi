<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Stok Kullanimi</h2>
            <x-primary-button-link href="{{ route('stock.usage.create') }}">Yeni Kayit</x-primary-button-link>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <x-card class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Tarih</th>
                                <th class="px-4 py-2 text-left">Kayit Sahibi</th>
                                <th class="px-4 py-2 text-left">Kalem</th>
                                <th class="px-4 py-2 text-left">Miktar</th>
                                <th class="px-4 py-2 text-left">Not</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($usages as $usage)
                                @foreach($usage->items as $usageItem)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-4 py-3">{{ optional($usage->used_at)->format('d.m.Y H:i') }}</td>
                                        <td class="px-4 py-3">{{ $usage->recordedBy?->name ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $usageItem->stockItem?->name ?? 'Kalem Silinmis' }}</td>
                                        <td class="px-4 py-3">{{ number_format($usageItem->quantity, 2) }}</td>
                                        <td class="px-4 py-3">{{ $usageItem->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">Kullanim kaydi bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $usages->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

