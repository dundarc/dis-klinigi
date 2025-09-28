@extends('layouts.app')

@section('title', 'Stok Düzeltmesi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Stok Düzeltmesi
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Manuel stok düzeltmesi yapın
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('stock.movements.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri Dön
            </a>
        </div>
    </div>

    <!-- Current Item Info (if pre-selected) -->
    @if(isset($selectedItem))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if($selectedItem->image)
                        <img class="h-12 w-12 rounded-lg object-cover" 
                             src="{{ Storage::url($selectedItem->image) }}" 
                             alt="{{ $selectedItem->name }}">
                    @else
                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-box text-gray-400"></i>
                        </div>
                    @endif
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-medium text-blue-900">{{ $selectedItem->name }}</h3>
                    <p class="text-sm text-blue-700">
                        Mevcut Stok: <span class="font-semibold">{{ number_format($selectedItem->quantity, 2) }} {{ $selectedItem->unit }}</span>
                        @if($selectedItem->minimum_quantity)
                            | Minimum: <span class="font-semibold">{{ number_format($selectedItem->minimum_quantity, 2) }} {{ $selectedItem->unit }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Adjustment Form -->
    <div class="bg-white shadow-sm rounded-lg">
        <form action="{{ route('stock.movements.store-adjustment') }}" method="POST" id="adjustmentForm">
            @csrf
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Düzeltme Bilgileri
                </h3>
            </div>

            <div class="px-6 py-4 space-y-6">
                <!-- Item Selection -->
                @if(!isset($selectedItem))
                    <div>
                        <label for="item_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Ürün Seçimi <span class="text-red-500">*</span>
                        </label>
                        <select name="item_id" id="item_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Ürün seçiniz...</option>
                            @foreach($stockItems as $item)
                                <option value="{{ $item->id }}" 
                                        data-current-stock="{{ $item->quantity }}"
                                        data-unit="{{ $item->unit }}"
                                        data-minimum="{{ $item->minimum_quantity ?? 0 }}"
                                        {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} ({{ number_format($item->quantity, 2) }} {{ $item->unit }})
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Stock Display -->
                    <div id="currentStockInfo" class="hidden bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Mevcut Stok</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900" id="currentStockValue">-</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Minimum Seviye</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900" id="minimumStockValue">-</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Birim</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900" id="unitValue">-</dd>
                            </div>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="item_id" value="{{ $selectedItem->id }}">
                @endif

                <!-- Adjustment Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Düzeltme Türü <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="relative">
                            <input type="radio" name="adjustment_type" value="increase" 
                                   class="sr-only peer" {{ old('adjustment_type', 'increase') == 'increase' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <i class="fas fa-plus-circle text-green-500 text-xl mr-3"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Stok Artışı</h4>
                                        <p class="text-sm text-gray-500">Mevcut stoka ekleme yapın</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="adjustment_type" value="decrease" 
                                   class="sr-only peer" {{ old('adjustment_type') == 'decrease' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <i class="fas fa-minus-circle text-red-500 text-xl mr-3"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Stok Azaltma</h4>
                                        <p class="text-sm text-gray-500">Mevcut stoktan düşme yapın</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative md:col-span-2">
                            <input type="radio" name="adjustment_type" value="set_exact" 
                                   class="sr-only peer" {{ old('adjustment_type') == 'set_exact' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <i class="fas fa-equals text-blue-500 text-xl mr-3"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Kesin Miktar Belirleme</h4>
                                        <p class="text-sm text-gray-500">Stok miktarını belirtilen değere ayarlayın</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('adjustment_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        Miktar <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="quantity" id="quantity" step="0.01" min="0" 
                               value="{{ old('quantity') }}" required
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <span class="text-gray-500 text-sm" id="quantityUnit">
                                {{ isset($selectedItem) ? $selectedItem->unit : '' }}
                            </span>
                        </div>
                    </div>
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <!-- Calculation Preview -->
                    <div id="calculationPreview" class="mt-2 p-3 bg-gray-50 rounded-md hidden">
                        <div class="text-sm">
                            <span class="text-gray-600">Hesaplama: </span>
                            <span id="calculationText" class="font-medium"></span>
                        </div>
                        <div class="text-sm mt-1">
                            <span class="text-gray-600">Yeni Stok: </span>
                            <span id="newStockValue" class="font-semibold"></span>
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Düzeltme Nedeni <span class="text-red-500">*</span>
                    </label>
                    <select name="reason" id="reason" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Neden seçiniz...</option>
                        <option value="sayim_farkı" {{ old('reason') == 'sayim_farkı' ? 'selected' : '' }}>Sayım Farkı</option>
                        <option value="fire_kayıp" {{ old('reason') == 'fire_kayıp' ? 'selected' : '' }}>Fire/Kayıp</option>
                        <option value="son_kullanma_tarihi" {{ old('reason') == 'son_kullanma_tarihi' ? 'selected' : '' }}>Son Kullanma Tarihi</option>
                        <option value="hasar_görme" {{ old('reason') == 'hasar_görme' ? 'selected' : '' }}>Hasarlı Ürün</option>
                        <option value="yanlış_giriş" {{ old('reason') == 'yanlış_giriş' ? 'selected' : '' }}>Yanlış Giriş Düzeltmesi</option>
                        <option value="transfer" {{ old('reason') == 'transfer' ? 'selected' : '' }}>Transfer/Devir</option>
                        <option value="diğer" {{ old('reason') == 'diğer' ? 'selected' : '' }}>Diğer</option>
                    </select>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Açıklama
                    </label>
                    <textarea name="notes" id="notes" rows="4" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Düzeltme ile ilgili detaylı açıklama yazın...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Movement Date -->
                <div>
                    <label for="movement_date" class="block text-sm font-medium text-gray-700 mb-2">
                        İşlem Tarihi <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="movement_date" id="movement_date" 
                           value="{{ old('movement_date', now()->format('Y-m-d\TH:i')) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('movement_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 flex items-center justify-end space-x-3">
                <a href="{{ route('stock.movements.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    İptal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Düzeltmeyi Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_id');
    const currentStockInfo = document.getElementById('currentStockInfo');
    const currentStockValue = document.getElementById('currentStockValue');
    const minimumStockValue = document.getElementById('minimumStockValue');
    const unitValue = document.getElementById('unitValue');
    const quantityUnit = document.getElementById('quantityUnit');
    const quantityInput = document.getElementById('quantity');
    const calculationPreview = document.getElementById('calculationPreview');
    const calculationText = document.getElementById('calculationText');
    const newStockValue = document.getElementById('newStockValue');

    let currentStock = {{ isset($selectedItem) ? $selectedItem->quantity : 0 }};
    let unit = '{{ isset($selectedItem) ? $selectedItem->unit : '' }}';

    // Item selection change
    if (itemSelect) {
        itemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                currentStock = parseFloat(selectedOption.dataset.currentStock);
                const minimum = parseFloat(selectedOption.dataset.minimum);
                unit = selectedOption.dataset.unit;

                currentStockValue.textContent = `${currentStock.toLocaleString('tr-TR', {minimumFractionDigits: 2})} ${unit}`;
                minimumStockValue.textContent = `${minimum.toLocaleString('tr-TR', {minimumFractionDigits: 2})} ${unit}`;
                unitValue.textContent = unit;
                quantityUnit.textContent = unit;
                
                currentStockInfo.classList.remove('hidden');
                updateCalculation();
            } else {
                currentStockInfo.classList.add('hidden');
                calculationPreview.classList.add('hidden');
                quantityUnit.textContent = '';
            }
        });
    }

    // Adjustment type and quantity change
    document.querySelectorAll('input[name="adjustment_type"], #quantity').forEach(element => {
        element.addEventListener('change', updateCalculation);
        element.addEventListener('input', updateCalculation);
    });

    function updateCalculation() {
        const adjustmentType = document.querySelector('input[name="adjustment_type"]:checked');
        const quantity = parseFloat(quantityInput.value) || 0;

        if (!adjustmentType || quantity <= 0 || currentStock === null) {
            calculationPreview.classList.add('hidden');
            return;
        }

        let newStock;
        let calculationDisplay;

        switch (adjustmentType.value) {
            case 'increase':
                newStock = currentStock + quantity;
                calculationDisplay = `${currentStock.toLocaleString('tr-TR', {minimumFractionDigits: 2})} + ${quantity.toLocaleString('tr-TR', {minimumFractionDigits: 2})}`;
                break;
            case 'decrease':
                newStock = currentStock - quantity;
                calculationDisplay = `${currentStock.toLocaleString('tr-TR', {minimumFractionDigits: 2})} - ${quantity.toLocaleString('tr-TR', {minimumFractionDigits: 2})}`;
                break;
            case 'set_exact':
                newStock = quantity;
                calculationDisplay = `Kesin miktar: ${quantity.toLocaleString('tr-TR', {minimumFractionDigits: 2})}`;
                break;
        }

        calculationText.textContent = calculationDisplay;
        newStockValue.textContent = `${newStock.toLocaleString('tr-TR', {minimumFractionDigits: 2})} ${unit}`;
        
        // Add warning for negative stock
        if (newStock < 0) {
            newStockValue.className = 'font-semibold text-red-600';
        } else {
            newStockValue.className = 'font-semibold text-gray-900';
        }

        calculationPreview.classList.remove('hidden');
    }

    // Form validation
    document.getElementById('adjustmentForm').addEventListener('submit', function(e) {
        const adjustmentType = document.querySelector('input[name="adjustment_type"]:checked');
        const quantity = parseFloat(quantityInput.value) || 0;

        if (adjustmentType && adjustmentType.value === 'decrease' && quantity > currentStock) {
            if (!confirm('Bu işlem stoku negatife düşürecek. Devam etmek istediğinizden emin misiniz?')) {
                e.preventDefault();
                return false;
            }
        }

        if (quantity <= 0) {
            alert('Lütfen geçerli bir miktar giriniz.');
            e.preventDefault();
            return false;
        }
    });

    // Initialize calculation if item is pre-selected
    @if(isset($selectedItem))
        updateCalculation();
    @endif
});
</script>
@endpush
@endsection