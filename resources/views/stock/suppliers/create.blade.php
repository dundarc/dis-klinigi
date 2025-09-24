<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Yeni Cari / Tedarikci</h2>
            <x-secondary-button-link href="{{ route('stock.suppliers.index') }}">Listeye Don</x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-card class="space-y-6">
                <form method="POST" action="{{ route('stock.suppliers.store') }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="name" value="Ad" />
                            <x-text-input id="name" name="name" type="text" value="{{ old('name') }}" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="type" value="Tur" />
                            <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <option value="supplier" @selected(old('type') === 'supplier')>Tedarikci</option>
                                <option value="service" @selected(old('type') === 'service')>Hizmet</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="email" value="E-posta" />
                            <x-text-input id="email" name="email" type="email" value="{{ old('email') }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Telefon" />
                            <x-text-input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="tax_number" value="Vergi No" />
                            <x-text-input id="tax_number" name="tax_number" type="text" value="{{ old('tax_number') }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="tax_office" value="Vergi Dairesi" />
                            <x-text-input id="tax_office" name="tax_office" type="text" value="{{ old('tax_office') }}" class="mt-1 block w-full" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="address" value="Adres" />
                            <x-text-input id="address" name="address" type="text" value="{{ old('address') }}" class="mt-1 block w-full" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="notes" value="Notlar" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <x-secondary-button type="reset">Temizle</x-secondary-button>
                        <x-primary-button type="submit">Kaydet</x-primary-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>


