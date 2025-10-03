@props([
    'treatment' => null,
    'action',
    'method' => 'POST',
])

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if(strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="code" value="Kod" />
            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" value="{{ old('code', $treatment->code ?? '') }}" required />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="name" value="Tedavi Adı" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $treatment->name ?? '') }}" required />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <x-input-label for="default_price" value="Varsayılan Fiyat (TL)" />
            <x-text-input id="default_price" name="default_price" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('default_price', $treatment->default_price ?? '') }}" required />
            <x-input-error :messages="$errors->get('default_price')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="default_vat" value="KDV Oranı (%)" />
            <select id="default_vat" name="default_vat" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <option value="0" {{ old('default_vat', $treatment->default_vat ?? 10) == 0 ? 'selected' : '' }}>0% - KDV Muaf</option>
                <option value="1" {{ old('default_vat', $treatment->default_vat ?? 10) == 1 ? 'selected' : '' }}>1% - Özel Oran</option>
                <option value="8" {{ old('default_vat', $treatment->default_vat ?? 10) == 8 ? 'selected' : '' }}>8% - İndirimli Oran</option>
                <option value="10" {{ old('default_vat', $treatment->default_vat ?? 10) == 10 ? 'selected' : '' }}>10% - Tıbbi Hizmetler</option>
                <option value="18" {{ old('default_vat', $treatment->default_vat ?? 10) == 18 ? 'selected' : '' }}>18% - Genel Oran</option>
                <option value="20" {{ old('default_vat', $treatment->default_vat ?? 10) == 20 ? 'selected' : '' }}>20% - Özel Tüketim</option>
            </select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Türkiye'de tıbbi hizmetler için %10 KDV uygulanır</p>
            <x-input-error :messages="$errors->get('default_vat')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="default_duration_min" value="Varsayılan Süre (dk)" />
            <x-text-input id="default_duration_min" name="default_duration_min" type="number" class="mt-1 block w-full" value="{{ old('default_duration_min', $treatment->default_duration_min ?? 30) }}" required />
            <x-input-error :messages="$errors->get('default_duration_min')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="description" value="Açıklama" />
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">{{ old('description', $treatment->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end gap-3">
        <x-secondary-button-link href="{{ route('system.treatments.index') }}">İptal</x-secondary-button-link>
        <x-primary-button>Kaydet</x-primary-button>
    </div>
</form>
