<x-app-layout>
    <div class="bg-slate-50 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <!-- Hero -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-sky-600 via-indigo-600 to-indigo-700 text-white shadow-xl">
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.6) 0, transparent 40%), radial-gradient(circle at 80% 0%, rgba(241,245,249,0.45) 0, transparent 35%);"></div>
                <div class="relative flex flex-col gap-6 px-8 py-10 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl space-y-3">
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1 text-sm font-semibold backdrop-blur">
                            <i class="fas fa-stream"></i>
                            <span>Stok hareketleri</span>
                        </div>
                        <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Stok D&uuml;zeltmesi</h1>
                        <p class="text-base text-white/80">Manuel stok d&uuml;zeltmesi yaparak envanterinizi g&uuml;ncel ve tutarli tutun. Ger&ccedil;ek zamanli &ouml;n izleme ile yeni stok miktarini kolayca g&ouml;r&uuml;n.</p>
                        <dl class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-white/80">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock"></i>
                                <span>Bug&uuml;n: {{ now()->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-boxes"></i>
                                <span>{{ $stockItems->count() }} aktif &Uuml;r&uuml;n</span>
                            </div>
                        </dl>
                    </div>
                    <a href="{{ route('stock.movements.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/20">
                        <i class="fas fa-arrow-left"></i>
                        <span>Hareket ge&ccedil;mişi</span>
                    </a>
                </div>
            </div>

            @if(isset($selectedItem))
                <div class="rounded-2xl border border-sky-100 bg-white shadow-sm">
                    <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center">
                        <div class="flex-shrink-0">
                            @if($selectedItem->image)
                                <img src="{{ Storage::url($selectedItem->image) }}" alt="{{ $selectedItem->name }}" class="h-16 w-16 rounded-2xl object-cover shadow" />
                            @else
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
                                    <i class="fas fa-box text-xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold text-slate-900">{{ $selectedItem->name }}</h2>
                            <div class="mt-3 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <p class="text-xs uppercase tracking-wider text-slate-500">Mevcut stok</p>
                                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ number_format($selectedItem->quantity, 2) }} <span class="text-sm font-normal text-slate-500">{{ $selectedItem->unit }}</span></p>
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <p class="text-xs uppercase tracking-wider text-slate-500">Minimum seviye</p>
                                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ number_format($selectedItem->minimum_quantity ?? 0, 2) }} <span class="text-sm font-normal text-slate-500">{{ $selectedItem->unit }}</span></p>
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <p class="text-xs uppercase tracking-wider text-slate-500">Durum</p>
                                    <p class="mt-1 text-lg font-semibold {{ $selectedItem->quantity <= ($selectedItem->minimum_quantity ?? 0) ? 'text-rose-600' : 'text-emerald-600' }}">{{ $selectedItem->quantity <= ($selectedItem->minimum_quantity ?? 0) ? 'Kritik' : 'Normal' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-12">
                <!-- Main form -->
                <div class="lg:col-span-8">
                    <div class="rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-100">
                        <form action="{{ route('stock.movements.store-adjustment') }}" method="POST" id="adjustmentForm" class="divide-y divide-slate-200">
                            @csrf

                            <div class="space-y-8 px-6 py-8 sm:px-10">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-medium uppercase tracking-wider text-sky-600">Form</p>
                                        <h2 class="mt-1 text-2xl font-semibold text-slate-900">D&uuml;zeltme bilgileri</h2>
                                    </div>
                                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        <i class="fas fa-info-circle"></i>
                                        Kaydedilen hareketler geri alinmaz
                                    </span>
                                </div>

                                @if(!isset($selectedItem))
                                    <div class="space-y-2">
                                        <label for="item_id" class="text-sm font-semibold text-slate-700">&Uuml;r&uuml;n se&ccedil;imi <span class="text-rose-500">*</span></label>
                                        <select id="item_id" name="item_id" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-900 shadow-sm transition focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                                            <option value="">&Uuml;r&uuml;n se&ccedil;iniz...</option>
                                            @foreach($stockItems as $item)
                                                <option value="{{ $item->id }}" data-current-stock="{{ $item->quantity }}" data-unit="{{ $item->unit }}" data-minimum="{{ $item->minimum_quantity ?? 0 }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }} ({{ number_format($item->quantity, 2) }} {{ $item->unit }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('item_id')
                                            <p class="text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="item_id" value="{{ $selectedItem->id }}">
                                @endif

                                <div id="currentStockInfo" class="hidden rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                                    <div class="grid gap-4 sm:grid-cols-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-wider text-slate-500">Mevcut stok</p>
                                            <p class="mt-1 text-lg font-semibold text-slate-900" id="currentStockValue">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wider text-slate-500">Minimum seviye</p>
                                            <p class="mt-1 text-lg font-semibold text-slate-900" id="minimumStockValue">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wider text-slate-500">Birim</p>
                                            <p class="mt-1 text-lg font-semibold text-slate-900" id="unitValue">-</p>
                                        </div>
                                    </div>
                                </div>

                                <fieldset class="space-y-3">
                                    <legend class="text-sm font-semibold text-slate-700">D&uuml;zeltme t&uuml;r&uuml; <span class="text-rose-500">*</span></legend>
                                    <div class="grid gap-3 md:grid-cols-3">
                                        <label class="flex cursor-pointer flex-col gap-3 rounded-2xl border border-slate-200 p-4 text-sm shadow-sm transition hover:border-sky-400 hover:shadow">
                                            <input class="sr-only" type="radio" name="adjustment_type" value="increase" {{ old('adjustment_type', 'increase') == 'increase' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600"><i class="fas fa-plus"></i></span>
                                                <div>
                                                    <p class="font-semibold text-slate-900">Stok artisi</p>
                                                    <p class="text-xs text-slate-500">Mevcut stoga miktar ekleyin</p>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="flex cursor-pointer flex-col gap-3 rounded-2xl border border-slate-200 p-4 text-sm shadow-sm transition hover:border-sky-400 hover:shadow">
                                            <input class="sr-only" type="radio" name="adjustment_type" value="decrease" {{ old('adjustment_type') == 'decrease' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-rose-600"><i class="fas fa-minus"></i></span>
                                                <div>
                                                    <p class="font-semibold text-slate-900">Stok azaltma</p>
                                                    <p class="text-xs text-slate-500">Stogunuzdan miktar d&uuml;ş&uuml;n</p>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="flex cursor-pointer flex-col gap-3 rounded-2xl border border-slate-200 p-4 text-sm shadow-sm transition hover:border-sky-400 hover:shadow">
                                            <input class="sr-only" type="radio" name="adjustment_type" value="set_exact" {{ old('adjustment_type') == 'set_exact' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600"><i class="fas fa-equals"></i></span>
                                                <div>
                                                    <p class="font-semibold text-slate-900">Kesin miktar</p>
                                                    <p class="text-xs text-slate-500">Stogu belirtilen degere ayarlayin</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @error('adjustment_type')
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </fieldset>

                                <div class="space-y-2">
                                    <label for="quantity" class="text-sm font-semibold text-slate-700">Miktar <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center gap-3">
                                        <input type="number" step="0.01" min="0" id="quantity" name="quantity" value="{{ old('quantity') }}" class="h-12 w-full rounded-2xl border border-slate-300 px-4 text-sm font-medium text-slate-900 shadow-sm transition focus:border-sky-500 focus:ring-2 focus:ring-sky-200" placeholder="0,00">
                                        <span class="text-sm font-semibold text-slate-600" data-role="quantity-unit"></span>
                                    </div>
                                    @error('quantity')
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="reason" class="text-sm font-semibold text-slate-700">D&uuml;zeltme nedeni <span class="text-rose-500">*</span></label>
                                    <select id="reason" name="reason" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-900 shadow-sm transition focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                                        <option value="">Neden se&ccedil;iniz...</option>
                                        <option value="sayim_farki" {{ old('reason') == 'sayim_farki' ? 'selected' : '' }}>&Ccedil;evrim sayim farki</option>
                                        <option value="fire_kayip" {{ old('reason') == 'fire_kayip' ? 'selected' : '' }}>Fire / kayip</option>
                                        <option value="son_kullanma" {{ old('reason') == 'son_kullanma' ? 'selected' : '' }}>Son kullanma tarihi</option>
                                        <option value="hasar" {{ old('reason') == 'hasar' ? 'selected' : '' }}>Hasar g&ouml;ren &Uuml;r&uuml;n</option>
                                        <option value="yanlis_giris" {{ old('reason') == 'yanlis_giris' ? 'selected' : '' }}>Yanlis stok girişi</option>
                                        <option value="transfer" {{ old('reason') == 'transfer' ? 'selected' : '' }}>Transfer / devir</option>
                                        <option value="diger" {{ old('reason') == 'diger' ? 'selected' : '' }}>Diğer</option>
                                    </select>
                                    @error('reason')
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="notes" class="text-sm font-semibold text-slate-700">A&ccedil;iklama</label>
                                    <textarea id="notes" name="notes" rows="4" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-900 shadow-sm transition focus:border-sky-500 focus:ring-2 focus:ring-sky-200" placeholder="D&uuml;zeltme ile ilgili ek not ekleyin...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="movement_date" class="text-sm font-semibold text-slate-700">Islem tarihi <span class="text-rose-500">*</span></label>
                                    <input type="datetime-local" id="movement_date" name="movement_date" value="{{ old('movement_date', now()->format('Y-m-d\TH:i')) }}" required class="h-12 w-full rounded-2xl border border-slate-300 px-4 text-sm font-medium text-slate-900 shadow-sm transition focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                                    @error('movement_date')
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-col gap-4 px-6 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-10 bg-slate-50">
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="fas fa-lock"></i>
                                    <span>Kaydedilen hareketler stok kayitlarini aninda g&uuml;nceller.</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('stock.movements.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-white">Iptal</a>
                                    <button id="submitButton" type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-sky-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-300">
                                        <i class="fas fa-save"></i>
                                        <span id="submitText">D&uuml;zeltmeyi kaydet</span>
                                        <span id="loadingSpinner" class="hidden"><i class="fas fa-spinner fa-spin"></i></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-100">
                        <h3 class="text-lg font-semibold text-slate-900">Hesaplama &amp; &Ouml;nizleme</h3>
                        <p class="mt-1 text-sm text-slate-500">Se&ccedil;tiğiniz &Uuml;r&uuml;n i&ccedil;in yeni stok seviyesi tahminini g&ouml;r&uuml;n.</p>
                        <div id="calculationPreview" class="mt-6 hidden rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-xs uppercase tracking-wider text-slate-500">Hesaplama</p>
                            <p id="calculationText" class="mt-2 text-lg font-semibold text-slate-900"></p>
                            <div class="mt-4 rounded-xl bg-white px-4 py-3 shadow-inner">
                                <p class="text-xs uppercase tracking-wider text-slate-500">Yeni stok</p>
                                <p id="newStockValue" class="mt-2 text-xl font-semibold text-slate-900">-</p>
                            </div>
                            <div id="warningMessage" class="mt-4 hidden rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Bu d&uuml;zeltme stogu negatif seviyeye &ccedil;ekiyor.
                            </div>
                        </div>
                        <ul class="mt-6 space-y-3 text-sm text-slate-600">
                            <li class="flex gap-3"><i class="fas fa-mouse-pointer mt-1 text-sky-500"></i><span>D&uuml;zeltme t&uuml;r&uuml;n&uuml; se&ccedil;in ve miktari girin.</span></li>
                            <li class="flex gap-3"><i class="fas fa-calculator mt-1 text-sky-500"></i><span>Hesaplama &ouml;n izlemesi yeni stok seviyesini otomatik g&ouml;sterir.</span></li>
                            <li class="flex gap-3"><i class="fas fa-bell mt-1 text-sky-500"></i><span>Negatif stok uyarisi aldiginizda bilgileri kontrol edin.</span></li>
                        </ul>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-md">
                        <h3 class="text-lg font-semibold text-slate-900">Ipu&ccedil;lari</h3>
                        <ul class="mt-4 space-y-3 text-sm text-slate-600">
                            <li class="flex gap-3"><i class="fas fa-clipboard-check mt-1 text-emerald-500"></i><span>Sayim sonu&ccedil;larini sisteme aktarmak i&ccedil;in d&uuml;zeltme t&uuml;r&uuml; olarak <strong>Kesin miktar</strong> se&ccedil;in.</span></li>
                            <li class="flex gap-3"><i class="fas fa-shield-alt mt-1 text-emerald-500"></i><span>Hasar veya kayip &uuml;r&uuml;nleri azaltma se&ccedil;eneği ile kayit altina alin.</span></li>
                            <li class="flex gap-3"><i class="fas fa-random mt-1 text-emerald-500"></i><span>Transfer islemlerinde hem g&ouml;nderen hem alan depoda d&uuml;zeltme kaydi olusturun.</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const itemSelect = document.getElementById('item_id');
                const quantityInput = document.getElementById('quantity');
                const adjustmentRadios = document.querySelectorAll('input[name="adjustment_type"]');
                const currentStockInfo = document.getElementById('currentStockInfo');
                const currentStockValue = document.getElementById('currentStockValue');
                const minimumStockValue = document.getElementById('minimumStockValue');
                const unitValue = document.getElementById('unitValue');
                const quantityUnits = document.querySelectorAll('[data-role="quantity-unit"]');
                const calculationPreview = document.getElementById('calculationPreview');
                const calculationText = document.getElementById('calculationText');
                const newStockValue = document.getElementById('newStockValue');
                const warningMessage = document.getElementById('warningMessage');
                const form = document.getElementById('adjustmentForm');
                const submitButton = document.getElementById('submitButton');
                const submitText = document.getElementById('submitText');
                const loadingSpinner = document.getElementById('loadingSpinner');

                let currentStock = {{ isset($selectedItem) ? (float) $selectedItem->quantity : 'null' }};
                let minimumStock = {{ isset($selectedItem) ? (float) ($selectedItem->minimum_quantity ?? 0) : 'null' }};
                let unit = '{{ isset($selectedItem) ? $selectedItem->unit : '' }}';

                const formatQuantity = (value) => value.toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                const applyStockInfo = () => {
                    if (currentStock === null || unit === '') {
                        currentStockInfo?.classList.add('hidden');
                        return;
                    }

                    currentStockInfo?.classList.remove('hidden');
                    currentStockValue.textContent = `${formatQuantity(currentStock)} ${unit}`;
                    minimumStockValue.textContent = `${formatQuantity(minimumStock ?? 0)} ${unit}`;
                    unitValue.textContent = unit;
                    quantityUnits.forEach((label) => label.textContent = unit);
                };

                const updateCalculation = () => {
                    const adjustmentType = document.querySelector('input[name="adjustment_type"]:checked');
                    const quantity = parseFloat(quantityInput?.value || '0');

                    if (!adjustmentType || !quantity || quantity <= 0 || currentStock === null) {
                        calculationPreview?.classList.add('hidden');
                        warningMessage?.classList.add('hidden');
                        return;
                    }

                    let newStock = currentStock;
                    let previewText = '';

                    if (adjustmentType.value === 'increase') {
                        newStock = currentStock + quantity;
                        previewText = `${formatQuantity(currentStock)} + ${formatQuantity(quantity)}`;
                    } else if (adjustmentType.value === 'decrease') {
                        newStock = currentStock - quantity;
                        previewText = `${formatQuantity(currentStock)} - ${formatQuantity(quantity)}`;
                    } else {
                        newStock = quantity;
                        previewText = `Yeni deger: ${formatQuantity(quantity)}`;
                    }

                    calculationText.textContent = previewText;
                    newStockValue.textContent = `${formatQuantity(newStock)} ${unit}`;

                    if (newStock < 0) {
                        warningMessage?.classList.remove('hidden');
                        newStockValue.classList.remove('text-slate-900');
                        newStockValue.classList.add('text-rose-600');
                    } else {
                        warningMessage?.classList.add('hidden');
                        newStockValue.classList.add('text-slate-900');
                        newStockValue.classList.remove('text-rose-600');
                    }

                    calculationPreview?.classList.remove('hidden');
                };

                const handleItemChange = (option) => {
                    if (!option || !option.value) {
                        currentStock = null;
                        minimumStock = null;
                        unit = '';
                        applyStockInfo();
                        calculationPreview?.classList.add('hidden');
                        return;
                    }

                    currentStock = parseFloat(option.dataset.currentStock || '0');
                    minimumStock = parseFloat(option.dataset.minimum || '0');
                    unit = option.dataset.unit || '';
                    applyStockInfo();
                    updateCalculation();
                };

                adjustmentRadios.forEach((radio) => {
                    radio.addEventListener('change', updateCalculation);
                });

                quantityInput?.addEventListener('input', updateCalculation);

                if (itemSelect) {
                    itemSelect.addEventListener('change', (event) => {
                        handleItemChange(event.target.options[event.target.selectedIndex]);
                    });

                    if (itemSelect.value) {
                        handleItemChange(itemSelect.options[itemSelect.selectedIndex]);
                    }
                } else if (currentStock !== null) {
                    applyStockInfo();
                    updateCalculation();
                }

                form?.addEventListener('submit', (event) => {
                    const adjustmentType = document.querySelector('input[name="adjustment_type"]:checked');
                    const quantity = parseFloat(quantityInput?.value || '0');

                    if (!adjustmentType || !quantity || quantity <= 0) {
                        event.preventDefault();
                        alert('L&uuml;tfen gecerli bir miktar ve d&uuml;zeltme t&uuml;r&uuml; se&ccedil;in.');
                        return;
                    }

                    if (adjustmentType.value === 'decrease' && currentStock !== null && quantity > currentStock) {
                        const confirmed = confirm('Bu i&ccedil;lem stogu negatif seviyeye &ccedil;eker. Devam etmek istiyor musunuz?');
                        if (!confirmed) {
                            event.preventDefault();
                            return;
                        }
                    }

                    submitButton?.setAttribute('disabled', 'disabled');
                    submitText.textContent = 'Kaydediliyor...';
                    loadingSpinner?.classList.remove('hidden');
                });
            });
        </script>
    @endpush
</x-app-layout>
