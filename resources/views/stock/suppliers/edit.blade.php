<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Tedarikçi Düzenle</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $supplier->name }} - Bilgilerini güncelleyin.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('stock.suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri Dön
                </a>
                <form method="POST" action="{{ route('stock.suppliers.destroy', $supplier) }}" onsubmit="return confirm('Bu tedarikçiyi silmek istediğinizden emin misiniz?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Sil
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Tedarikçi Bilgileri</h3>
                </div>
                <form method="POST" action="{{ route('stock.suppliers.update', $supplier) }}" x-data="{ hasChanges: false }" @change="hasChanges = true" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ad <span class="text-red-500">*</span></label>
                            <input id="name" name="name" type="text" value="{{ old('name', $supplier->name) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tür</label>
                            <select id="type" name="type" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="supplier" @selected(old('type', $supplier->type) === 'supplier')>Tedarikçi</option>
                                <option value="service" @selected(old('type', $supplier->type) === 'service')>Hizmet</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">E-posta</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $supplier->email) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Telefon</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone', $supplier->phone) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                        <div>
                            <label for="tax_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Vergi No</label>
                            <input id="tax_number" name="tax_number" type="text" value="{{ old('tax_number', $supplier->tax_number) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error :messages="$errors->get('tax_number')" class="mt-2" />
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Şehir</label>
                            <input id="city" name="city" type="text" value="{{ old('city', $supplier->city) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Adres</label>
                            <textarea id="address" name="address" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address', $supplier->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('stock.suppliers.index') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <button type="submit" :disabled="!hasChanges" :class="hasChanges ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-400 cursor-not-allowed'" class="px-6 py-2 text-white font-medium rounded-lg transition-colors">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
