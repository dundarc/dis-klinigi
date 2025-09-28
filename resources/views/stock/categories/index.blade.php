<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">{{ __('stock.categories') }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('stock.manage_categories_description') }}</p>
            </div>
            <x-secondary-button-link href="{{ route('stock.items.index') }}">{{ __('stock.items') }}</x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <x-card class="space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('stock.add_new_category') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('stock.add_category_description') }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('stock.categories.store') }}" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <x-input-label for="name" value="{{ __('stock.category_name') }}" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="name" name="name" type="text" value="{{ old('name') }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-1 xl:col-span-2">
                        <x-input-label for="description" value="{{ __('stock.category_description') }}" class="text-slate-600 dark:text-slate-300" />
                        <x-text-input id="description" name="description" type="text" value="{{ old('description') }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" placeholder="{{ __('stock.optional_description') }}" />
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button type="submit">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('stock.add_category') }}
                        </x-primary-button>
                    </div>
                </form>
            </x-card>

            <x-card class="space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('stock.existing_categories') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('stock.categories_count', ['count' => $categories->count()]) }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-100 dark:bg-slate-800/70 text-slate-600 dark:text-slate-300">
                            <tr>
                                <th class="px-4 py-3 text-left">{{ __('stock.category') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('stock.category_description') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('stock.item_count') }}</th>
                                <th class="px-4 py-3 text-right">{{ __('stock.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($categories as $category)
                                <tr class="bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-slate-800 dark:text-slate-100">{{ $category->name }}</div>
                                        @if($category->name === 'Sağlık Malzemeleri')
                                            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-500/20 px-2 py-1 text-xs font-medium text-blue-600 dark:text-blue-200 mt-1">
                                                {{ __('stock.default_category') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 dark:text-slate-300">{{ $category->description ?? '-' }}</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-1 text-xs font-medium text-slate-600 dark:text-slate-200">
                                            {{ $category->items_count }} {{ __('stock.items') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div x-data="{ open: false }" class="inline-flex flex-col items-end gap-2">
                                            <div class="flex gap-2">
                                                <button type="button" @click="open = !open" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    {{ __('stock.edit') }}
                                                </button>
                                                @if($category->name !== 'Sağlık Malzemeleri')
                                                    <form method="POST" action="{{ route('stock.categories.destroy', $category) }}" onsubmit="return confirm('{{ __('stock.confirm_delete_category') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-danger-button>
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            {{ __('stock.delete') }}
                                                        </x-danger-button>
                                                    </form>
                                                @else
                                                    <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400" title="{{ __('stock.cannot_delete_medical_supplies') }}">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                        </svg>
                                                        {{ __('stock.protected') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div x-show="open" x-transition x-cloak class="w-full mt-2 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700">
                                                <form method="POST" action="{{ route('stock.categories.update', $category) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    @csrf
                                                    @method('PUT')
                                                    <div>
                                                        <x-input-label for="edit_name_{{ $category->id }}" value="{{ __('stock.category_name') }}" class="text-slate-600 dark:text-slate-300" />
                                                        <x-text-input id="edit_name_{{ $category->id }}" name="name" type="text" value="{{ $category->name }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" required />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="edit_description_{{ $category->id }}" value="{{ __('stock.category_description') }}" class="text-slate-600 dark:text-slate-300" />
                                                        <x-text-input id="edit_description_{{ $category->id }}" name="description" type="text" value="{{ $category->description }}" class="mt-1 block w-full bg-white dark:bg-slate-800 dark:text-slate-100" />
                                                    </div>
                                                    <div class="sm:col-span-2 flex justify-end gap-2">
                                                        <x-secondary-button type="button" @click="open = false">{{ __('common.cancel') }}</x-secondary-button>
                                                        <x-primary-button type="submit">{{ __('common.save') }}</x-primary-button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                            <p class="text-lg font-medium">{{ __('stock.no_categories') }}</p>
                                            <p class="text-sm">{{ __('stock.no_categories_description') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

