<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">{{ __('stock.usage_records') }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('stock.manage_usage_description') }}</p>
            </div>
            <x-primary-button-link href="{{ route('stock.usage.create') }}">{{ __('stock.record_usage') }}</x-primary-button-link>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-card class="space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('stock.usage_history') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('stock.usage_records_count', ['count' => $usages->total()]) }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-100 dark:bg-slate-800/70 text-slate-600 dark:text-slate-300">
                            <tr>
                                <th class="px-4 py-3 text-left">{{ __('stock.date_time') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('stock.recorded_by') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('stock.item') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('stock.quantity') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('stock.notes') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($usages as $usage)
                                @foreach($usage->items as $usageItem)
                                    <tr class="bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-500/20 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ optional($usage->used_at)->format('d.m.Y') }}</div>
                                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ optional($usage->used_at)->format('H:i') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $usage->recordedBy?->name ?? __('stock.unknown') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $usageItem->stockItem?->name ?? __('stock.item_deleted') }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $usageItem->stockItem?->category?->name ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-1 text-xs font-medium text-slate-600 dark:text-slate-200">
                                                {{ number_format($usageItem->quantity, 2) }} {{ $usageItem->stockItem?->unit ?? __('stock.unit') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-slate-600 dark:text-slate-300">
                                            {{ $usageItem->notes ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-lg font-medium">{{ __('stock.no_usage_records') }}</p>
                                            <p class="text-sm">{{ __('stock.no_usage_records_description') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $usages->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

