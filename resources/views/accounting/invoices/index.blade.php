<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fatura Yönetimi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <!-- Filtreleme Formu -->
                <form method="GET" action="{{ route('accounting.invoices.index') }}" class="mb-6 md:flex md:items-end md:space-x-4 space-y-4 md:space-y-0">
                    <div class="flex-1">
                        <x-input-label for="search" value="Ara (Fatura No, Hasta Adı)" />
                        <x-text-input id="search" name="search" type="text" class="block w-full" value="{{ request('search') }}" />
                    </div>
                    <div class="flex-1">
                        <x-input-label for="status" value="Durum" />
                        <x-select-input id="status" name="status" class="block w-full">
                            <option value="">Tümü</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" @selected(request('status') == $status->value)>
                                    {{ ucfirst($status->value) }}
                                </option>
                            @endforeach
                        </x-select-input>
                    </div>
                    <div>
                        <x-primary-button>Filtrele</x-primary-button>
                    </div>
                </form>

                <!-- Fatura Tablosu -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fatura No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hasta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tarih</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tutar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Durum</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($invoices as $invoice)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                        <a href="{{ route('accounting.invoices.show', $invoice) }}">
                                            {{ $invoice->invoice_no }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $invoice->issue_date->format('d.m.Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @php
                                            $statusColor = match($invoice->status) {
                                                \App\Enums\InvoiceStatus::PAID => 'green',
                                                \App\Enums\InvoiceStatus::POSTPONED => 'yellow',
                                                default => 'red',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{$statusColor}}-100 text-{{$statusColor}}-800 dark:bg-{{$statusColor}}-900 dark:text-{{$statusColor}}-300">
                                            {{ $invoice->status->value }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Fatura bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                 @if ($invoices->hasPages())
                    <div class="mt-4">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
