<x-modal name="add-treatment-modal" title="Yeni Tedavi Ekle">
    <form @submit.prevent="submitTreatment($event.target)" class="p-6">
        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="add_treatment_id" value="Tedavi Türü" />
                <x-select-input id="add_treatment_id" name="treatment_id" class="w-full" @change="updatePrice($event.target)" required>
                    <option value="">Seçiniz...</option>
                    @foreach($treatmentsList as $treatment)
                        <option value="{{ $treatment->id }}" data-price="{{ $treatment->default_price }}" data-vat="{{ $treatment->default_vat }}">{{ $treatment->name }}</option>
                    @endforeach
                </x-select-input>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="tooth_number" value="Diş Numarası (Opsiyonel)" />
                    <x-text-input id="tooth_number" name="tooth_number" type="number" class="w-full" />
                </div>
                <div>
                    <x-input-label for="unit_price" value="Birim Fiyat (TL)" />
                    <x-text-input id="unit_price" name="unit_price" type="number" step="0.01" class="w-full" required/>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" @click="$dispatch('close')">İptal</x-secondary-button>
            <x-primary-button class="ms-3">Kaydet</x-primary-button>
        </div>
    </form>
</x-modal>
