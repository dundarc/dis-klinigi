<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ ucfirst($expense->service_type) }} Giderini Düzenle</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $expense->service_provider ?? 'Hizmet Sağlayıcı' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('stock.services.show', $expense) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Görüntüle
                </a>
                <a href="{{ route('stock.services.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <form method="POST" action="{{ route('stock.services.update', $expense) }}" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Service Provider -->
                    <div>
                        <label for="service_provider" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hizmet Sağlayıcı</label>
                        <input type="text" id="service_provider" name="service_provider" value="{{ old('service_provider', $expense->service_provider) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Örn: İstanbul Elektrik Dağıtım A.Ş.">
                        <x-input-error :messages="$errors->get('service_provider')" class="mt-2" />
                    </div>

                    <!-- Service Type -->
                    <div>
                        <label for="service_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hizmet Türü <span class="text-red-500">*</span></label>
                        <select id="service_type" name="service_type" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="electricity" {{ old('service_type', $expense->service_type) == 'electricity' ? 'selected' : '' }}>Elektrik</option>
                            <option value="water" {{ old('service_type', $expense->service_type) == 'water' ? 'selected' : '' }}>Su</option>
                            <option value="internet" {{ old('service_type', $expense->service_type) == 'internet' ? 'selected' : '' }}>İnternet</option>
                            <option value="phone" {{ old('service_type', $expense->service_type) == 'phone' ? 'selected' : '' }}>Telefon</option>
                            <option value="advertising" {{ old('service_type', $expense->service_type) == 'advertising' ? 'selected' : '' }}>Reklam</option>
                            <option value="insurance" {{ old('service_type', $expense->service_type) == 'insurance' ? 'selected' : '' }}>Sigorta</option>
                            <option value="maintenance" {{ old('service_type', $expense->service_type) == 'maintenance' ? 'selected' : '' }}>Bakım</option>
                            <option value="other" {{ old('service_type', $expense->service_type) == 'other' ? 'selected' : '' }}>Diğer</option>
                        </select>
                        <x-input-error :messages="$errors->get('service_type')" class="mt-2" />
                    </div>

                    <!-- Invoice Number -->
                    <div>
                        <label for="invoice_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Fatura Numarası</label>
                        <input type="text" id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $expense->invoice_number) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Örn: FAT-2025-001">
                        <x-input-error :messages="$errors->get('invoice_number')" class="mt-2" />
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tutar (TL) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount', $expense->amount) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="0.00" required>
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <!-- Invoice Date -->
                    <div>
                        <label for="invoice_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Fatura Tarihi <span class="text-red-500">*</span></label>
                        <input type="date" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', $expense->invoice_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <x-input-error :messages="$errors->get('invoice_date')" class="mt-2" />
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Vade Tarihi</label>
                        <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $expense->due_date ? $expense->due_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Yöntemi</label>
                        <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Ödeme yöntemi seçin</option>
                            <option value="nakit" {{ old('payment_method', $expense->payment_method) == 'nakit' ? 'selected' : '' }}>Nakit</option>
                            <option value="kredi_karti" {{ old('payment_method', $expense->payment_method) == 'kredi_karti' ? 'selected' : '' }}>Kredi Kartı</option>
                            <option value="havale" {{ old('payment_method', $expense->payment_method) == 'havale' ? 'selected' : '' }}>Havale/EFT</option>
                            <option value="cek" {{ old('payment_method', $expense->payment_method) == 'cek' ? 'selected' : '' }}>Çek</option>
                            <option value="otomatik_odeme" {{ old('payment_method', $expense->payment_method) == 'otomatik_odeme' ? 'selected' : '' }}>Otomatik Ödeme</option>
                        </select>
                        <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Durum <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="pending" {{ old('status', $expense->status) == 'pending' ? 'selected' : '' }}>Ödenecek</option>
                            <option value="paid" {{ old('status', $expense->status) == 'paid' ? 'selected' : '' }}>Ödendi</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <!-- Payment Date (only show if status is paid) -->
                    <div id="payment_date_field" style="{{ old('status', $expense->status) == 'paid' ? '' : 'display: none;' }}">
                        <label for="payment_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ödeme Tarihi</label>
                        <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', $expense->payment_date ? $expense->payment_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                    </div>

                    <!-- Invoice File -->
                    <div>
                        <label for="invoice_file" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Fatura Dosyası</label>
                        <input type="file" id="invoice_file" name="invoice_file" accept="image/*,application/pdf" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            @if($expense->invoice_path)
                                Mevcut dosya: <a href="{{ Storage::url($expense->invoice_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">Görüntüle</a>
                            @else
                                PDF veya resim dosyası yükleyebilirsiniz.
                            @endif
                        </p>
                        <x-input-error :messages="$errors->get('invoice_file')" class="mt-2" />
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notlar</label>
                        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="İsteğe bağlı notlar...">{{ old('notes', $expense->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('stock.services.show', $expense) }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <button type="submit" class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('status').addEventListener('change', function() {
            const paymentDateField = document.getElementById('payment_date_field');
            if (this.value === 'paid') {
                paymentDateField.style.display = 'block';
                document.getElementById('payment_date').required = true;
            } else {
                paymentDateField.style.display = 'none';
                document.getElementById('payment_date').required = false;
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('status').dispatchEvent(new Event('change'));
        });
    </script>
</x-app-layout>