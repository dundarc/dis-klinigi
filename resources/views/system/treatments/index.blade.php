<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Tedavi Yönetimi
            </h2>
            <x-primary-button-link href="{{ route('system.treatments.create') }}">
                Yeni Tedavi Ekle
            </x-primary-button-link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <x-card>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wide text-gray-500 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-left">Kod</th>
                                <th class="px-4 py-3 text-left">Ad</th>
                                <th class="px-4 py-3 text-right">Varsayılan Fiyat</th>
                                <th class="px-4 py-3 text-right">KDV (%)</th>
                                <th class="px-4 py-3 text-right">Süre (dk)</th>
                                <th class="px-4 py-3 text-left">Durum</th>
                                <th class="px-4 py-3 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($treatments as $treatment)
                                @php
                                    $isDeleted = $treatment->trashed();
                                @endphp
                                <tr class="bg-white dark:bg-gray-800">
                                    <td class="px-4 py-3 font-mono text-xs">{{ $treatment->code }}</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $treatment->name }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($treatment->default_price, 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($treatment->default_vat, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right">{{ $treatment->default_duration_min }}</td>
                                    <td class="px-4 py-3">
                                        @if($isDeleted)
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900/40 dark:text-red-200">Silindi</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <x-secondary-button-link href="{{ route('system.treatments.edit', $treatment) }}" @class(['opacity-50 pointer-events-none' => $isDeleted])>
                                                Düzenle
                                            </x-secondary-button-link>
                                            @if(!$isDeleted)
                                                <form method="POST" action="{{ route('system.treatments.destroy', $treatment) }}" onsubmit="return confirm('Bu tedaviyi silmek istediğinizden emin misiniz?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button>Sil</x-danger-button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                        Henüz kayıtlı bir tedavi bulunmuyor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $treatments->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

