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

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Cari Özet</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Toplam Borç</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($summary['total_debt'], 2, ',', '.') }} TL</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Toplam Ödenen</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($summary['total_paid'], 2, ',', '.') }} TL</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Kalan Borç</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($summary['remaining_debt'], 2, ',', '.') }} TL</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Gecikmiş Tutar</p>
                            <p class="font-semibold text-red-600 dark:text-red-400">{{ number_format($summary['overdue_amount'], 2, ',', '.') }} TL</p>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Faturalar</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Fatura No</th>
                                <th class="px-4 py-2 text-left">Tarih</th>
                                <th class="px-4 py-2 text-left">Vade</th>
                                <th class="px-4 py-2 text-right">Toplam</th>
                                <th class="px-4 py-2 text-right">Ödenen</th>
                                <th class="px-4 py-2 text-right">Kalan</th>
                                <th class="px-4 py-2 text-left">Durum</th>
                                <th class="px-4 py-2 text-left">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td class="px-4 py-3">{{ $invoice->invoice_number ?? 'Numarasız' }}</td>
                                    <td class="px-4 py-3">{{ optional($invoice->invoice_date)->format('d.m.Y') ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ optional($invoice->due_date)->format('d.m.Y') ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($invoice->total_paid, 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($invoice->remaining_amount, 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3">
                                        @if($invoice->payment_status === 'paid')
                                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">Ödendi</span>
                                        @elseif($invoice->payment_status === 'partial')
                                            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">Kısmi</span>
                                        @elseif($invoice->payment_status === 'overdue')
                                            <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-200">Gecikti</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-900/30 px-2.5 py-1 text-xs font-medium text-gray-800 dark:text-gray-200">Bekliyor</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('stock.purchases.show', $invoice) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Görüntüle</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-gray-500">Fatura bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>


