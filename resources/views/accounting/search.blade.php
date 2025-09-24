<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Fatura Arama</h2>
            <x-secondary-button-link href="{{ route('accounting.index') }}">Muhasebeye Don</x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <form method="GET" action="{{ route('accounting.search') }}" class="grid gap-4 md:grid-cols-4">
                    <div class="md:col-span-2">
                        <x-input-label for="patient_id" value="Hasta" />
                        <x-select-input id="patient_id" name="patient_id" class="mt-1 block w-full">
                            <option value="">Tum Hastalar</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" @selected($filters['patient_id'] == $patient->id)>
                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                </option>
                            @endforeach
                        </x-select-input>
                    </div>
                    <div>
                        <x-input-label for="date_from" value="Baslangic Tarihi" />
                        <x-text-input id="date_from" name="date_from" type="date" class="mt-1 block w-full"
                                      value="{{ $filters['date_from'] }}" />
                    </div>
                    <div>
                        <x-input-label for="date_to" value="Bitis Tarihi" />
                        <x-text-input id="date_to" name="date_to" type="date" class="mt-1 block w-full"
                                      value="{{ $filters['date_to'] }}" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="status" value="Odeme Durumu" />
                        <x-select-input id="status" name="status" class="mt-1 block w-full">
                            <option value="">Tum Durumlar</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>
                                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                </option>
                            @endforeach
                        </x-select-input>
                    </div>
                    <div class="md:col-span-2 flex items-center justify-end gap-3">
                        @if($filters['patient_id'] || $filters['status'] || $filters['date_from'] || $filters['date_to'])
                            <x-secondary-button-link href="{{ route('accounting.search') }}">Filtreleri Temizle</x-secondary-button-link>
                        @endif
                        <x-primary-button>Arama</x-primary-button>
                    </div>
                </form>
            </x-card>

            <x-card>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Arama Sonuclari</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-2 text-left">Fatura No</th>
                                <th class="px-4 py-2 text-left">Hasta</th>
                                <th class="px-4 py-2 text-left">Tarih</th>
                                <th class="px-4 py-2 text-left">Durum</th>
                                <th class="px-4 py-2 text-left">Tutar</th>
                                <th class="px-4 py-2 text-left">Islem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-600 dark:text-gray-300">
                            @forelse($invoices as $invoice)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                    <td class="px-4 py-3 font-semibold text-gray-800 dark:text-gray-100">{{ $invoice->invoice_no }}</td>
                                    <td class="px-4 py-3">{{ $invoice->patient?->first_name }} {{ $invoice->patient?->last_name }}</td>
                                    <td class="px-4 py-3">{{ $invoice->issue_date?->format('d.m.Y') }}</td>
                                    <td class="px-4 py-3 capitalize">{{ str_replace('_', ' ', $invoice->status->value) }}</td>
                                    <td class="px-4 py-3">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                                    <td class="px-4 py-3">
                                        <x-secondary-button-link href="{{ route('accounting.invoices.action', $invoice) }}">Incele</x-secondary-button-link>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Kriterlere uygun fatura bulunamadi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($invoices->hasPages())
                    <div class="mt-4">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
