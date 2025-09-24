<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fatura Çöp Kutusu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                @if (session('success'))
                    <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fatura No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hasta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Silinme Tarihi</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($trashedInvoices as $invoice)
                                <tr>
                                    <td class="px-6 py-4">{{ $invoice->invoice_no }}</td>
                                    <td class="px-6 py-4">{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</td>
                                    <td class="px-6 py-4">{{ $invoice->deleted_at->format('d.m.Y H:i') }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <form action="{{ route('accounting.trash.restore', $invoice->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT') {{-- RESTful prensiplere uygunluk için PUT metodu eklendi --}}
                                            <x-secondary-button type="submit">Geri Yükle</x-secondary-button>
                                        </form>
                                        <form action="{{ route('accounting.trash.force-delete', $invoice->id) }}" method="POST" class="inline" onsubmit="return confirm('Bu faturayı KALICI OLARAK silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!');">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button type="submit">Kalıcı Sil</x-danger-button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-gray-500">Çöp kutusu boş.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
