<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">{{ __('Gider Kategorisini Düzenle') }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Kategori bilgilerini güncelleyin') }}</p>
            </div>
            <x-secondary-button-link href="{{ route('stock.expense-categories.index') }}">{{ __('Geri Dön') }}</x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-card>
                <form method="POST" action="{{ route('stock.expense-categories.update', $category) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" value="{{ __('Kategori Adı') }}" />
                            <x-text-input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <x-secondary-button-link href="{{ route('stock.expense-categories.index') }}">{{ __('İptal') }}</x-secondary-button-link>
                        <x-primary-button type="submit">{{ __('Güncelle') }}</x-primary-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>