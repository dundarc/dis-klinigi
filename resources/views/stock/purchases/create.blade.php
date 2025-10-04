    <x-app-layout>
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-gray-100 dark:to-gray-400 bg-clip-text text-transparent">
                            Fatura Oluştur
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Yeni bir satın alma faturası oluşturun</span>
                        </p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('stock.purchases.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Faturalar Listesi
                    </a>
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-r from-white via-blue-50/30 to-indigo-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden backdrop-blur-sm">
                    <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Fatura Bilgileri</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Temel fatura detaylarını girin</p>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('stock.purchases.store') }}" x-data="invoiceForm()" class="p-8 space-y-8">
                        @csrf
                        
                        <!-- Hidden Fields -->
                        <input type="hidden" name="mode" value="manual">
                        <input type="hidden" name="payment_type" x-bind:value="paymentType">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label for="invoice_number" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span>Fatura No</span>
                                        <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                <input id="invoice_number" name="invoice_number" type="text" value="{{ old('invoice_number') }}" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="örn: FTR-2024-001" required />
                                <x-input-error :messages="$errors->get('invoice_number')" class="mt-2" />
                            </div>
                            <div class="space-y-3">
                                <label for="invoice_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>Fatura Tarihi</span>
                                        <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                <input id="invoice_date" name="invoice_date" type="date" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required />
                                <x-input-error :messages="$errors->get('invoice_date')" class="mt-2" />
                            </div>
                            <div class="space-y-3">
                                <label for="supplier_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span>Tedarikçi</span>
                                        <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                <select id="supplier_id" name="supplier_id" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                                    <option value="">Tedarikçi Seçin</option>
                                    @foreach($suppliers->where('type', 'supplier') as $supplier)
                                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                            </div>
                            <div class="space-y-3">
                                <label for="due_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Vade Tarihi</span>
                                    </span>
                                </label>
                                <input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>
                            <div class="space-y-3">
                                <label for="payment_status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Ödeme Durumu</span>
                                    </span>
                                </label>
                                <select id="payment_status" name="payment_status" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="pending" @selected(old('payment_status', 'pending') == 'pending')>⏳ Bekliyor</option>
                                    <option value="partial" @selected(old('payment_status') == 'partial')>🔄 Kısmi Ödeme</option>
                                    <option value="paid" @selected(old('payment_status') == 'paid')>✅ Ödendi</option>
                                </select>
                                <x-input-error :messages="$errors->get('payment_status')" class="mt-2" />
                            </div>
                            <div class="space-y-3">
                                <label for="payment_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span>Ödeme Tipi</span>
                                    </span>
                                </label>
                                <select id="payment_type" name="payment_type" x-model="paymentType" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="single">💳 Tek Seferlik</option>
                                    <option value="installment">📅 Taksitli</option>
                                </select>
                            </div>
                            <div x-show="paymentType === 'installment'" class="space-y-3">
                                <label for="installments" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4"></path>
                                        </svg>
                                        <span>Taksit Sayısı</span>
                                    </span>
                                </label>
                                <input id="installments" name="installments" type="number" min="2" max="24" value="{{ old('installments', 3) }}" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                <x-input-error :messages="$errors->get('installments')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2 space-y-3">
                                <label for="notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Açıklama</span>
                                    </span>
                                </label>
                                <textarea id="notes" name="notes" rows="4" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none" placeholder="Fatura ile ilgili genel notlar...">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Fatura Kalemleri -->
                        <div class="border-t border-gray-200/50 dark:border-gray-600/50 pt-8 mt-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center space-x-4">
                                    <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">Fatura Kalemleri</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Faturaya ürün kalemleri ekleyin</p>
                                    </div>
                                </div>
                                <button type="button" @click="addItem()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Yeni Kalem Ekle
                                </button>
                            </div>

                            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Stok Kalemi</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Açıklama</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Miktar</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Birim</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Birim Fiyat</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">KDV %</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Toplam</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">İşlem</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-600">
                                        <template x-for="(item, index) in items" :key="'item-' + index">
                                            <tr class="hover:bg-slate-100/50 dark:hover:bg-slate-700/20 transition-colors duration-200">
                                                <td class="px-6 py-4">
                                                    <div class="relative">
                                                        <input type="text"
                                                               x-model="item.stock_item_search"
                                                               @input="searchStockItems(index)"
                                                               @focus="showStockDropdown(index)"
                                                               @blur="hideStockDropdown(index)"
                                                               class="w-full border-0 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-blue-50 dark:focus:bg-indigo-900/30 rounded-lg px-3 py-2 text-sm text-slate-900 dark:text-slate-100 transition-all duration-200"
                                                               placeholder="Stok kalemi ara..."
                                                               autocomplete="off">
                                                        <input type="hidden" x-bind:name="'items[' + index + '][stock_item_id]'" x-model="item.stock_item_id">

                                                        <!-- Stock Items Dropdown -->
                                                        <div x-show="item.showStockDropdown && item.filteredStockItems && item.filteredStockItems.length > 0"
                                                             class="absolute z-20 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg shadow-xl mt-1 max-h-40 overflow-y-auto w-full"
                                                             x-transition>
                                                            <template x-for="stockItem in item.filteredStockItems" :key="'stock-' + stockItem.id">
                                                                <div @click="selectStockItem(index, stockItem)"
                                                                     class="px-4 py-3 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 cursor-pointer text-sm border-b border-slate-200 dark:border-slate-600 last:border-b-0 transition-colors duration-150">
                                                                    <div class="flex justify-between items-center">
                                                                        <div class="flex-1">
                                                                            <div class="font-medium text-slate-900 dark:text-slate-100" x-text="stockItem.name"></div>
                                                                            <div class="text-xs text-slate-600 dark:text-slate-400 mt-1" x-text="stockItem.description || 'Açıklama yok'"></div>
                                                                        </div>
                                                                        <div class="text-xs text-slate-500 dark:text-slate-400 ml-2" x-text="'#' + stockItem.id"></div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>

                                                    <label class="flex items-center mt-2" x-show="!item.stock_item_id">
                                                        <input type="checkbox" x-bind:name="'items[' + index + '][create_item]'" x-model="item.create_item" class="mr-2 text-sm rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500">
                                                        <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">Yeni Stok Kalemi Oluştur</span>
                                                    </label>
                                                </td>
                                                <td class="px-6 py-4 relative">
                                                    <input type="text"
                                                            x-model="item.description"
                                                            x-bind:name="'items[' + index + '][description]'"
                                                            class="w-full border-0 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-blue-50 dark:focus:bg-indigo-900/30 rounded-lg px-3 py-2 text-sm text-slate-900 dark:text-slate-100 transition-all duration-200"
                                                            placeholder="Ürün açıklaması"
                                                            @input="suggestItems(index)"
                                                            required>
                                                    <div x-show="item.suggestions && item.suggestions.length > 0"
                                                        class="absolute z-20 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg shadow-xl mt-1 max-h-40 overflow-y-auto w-full">
                                                        <template x-for="suggestion in item.suggestions">
                                                            <div @click="selectSuggestion(index, suggestion)"
                                                                class="px-4 py-3 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 cursor-pointer text-sm text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-600 last:border-b-0 transition-colors duration-150"
                                                                x-text="suggestion.name"></div>
                                                        </template>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="number" step="0.01" min="0" x-model="item.quantity" x-bind:name="'items[' + index + '][quantity]'" class="w-full border-0 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-blue-50 dark:focus:bg-indigo-900/30 rounded-lg px-3 py-2 text-sm text-center text-slate-900 dark:text-slate-100 transition-all duration-200" @input="updateCalculations()" required>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="text" x-model="item.unit" x-bind:name="'items[' + index + '][unit]'" class="w-full border-0 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-blue-50 dark:focus:bg-indigo-900/30 rounded-lg px-3 py-2 text-sm text-center text-slate-900 dark:text-slate-100 transition-all duration-200" placeholder="adet">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="number" step="0.01" min="0" x-model="item.unit_price" x-bind:name="'items[' + index + '][unit_price]'" class="w-full border-0 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-blue-50 dark:focus:bg-indigo-900/30 rounded-lg px-3 py-2 text-sm text-right text-slate-900 dark:text-slate-100 transition-all duration-200" @input="updateCalculations()" required>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="number" step="0.01" min="0" max="100" x-model="item.vat_rate" x-bind:name="'items[' + index + '][vat_rate]'" class="w-full border-0 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-blue-50 dark:focus:bg-indigo-900/30 rounded-lg px-3 py-2 text-sm text-center text-slate-900 dark:text-slate-100 transition-all duration-200" @input="updateCalculations()" placeholder="18">
                                                </td>
                                                <td class="px-6 py-4 text-sm text-right font-semibold text-emerald-700 dark:text-emerald-300" x-text="formatCurrency(getItemTotal(item))"></td>
                                                <td class="px-6 py-4">
                                                    <button type="button" @click="removeItem(index)" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-100 hover:bg-rose-200 dark:bg-rose-900/30 dark:hover:bg-rose-900/50 text-rose-700 hover:text-rose-800 dark:text-rose-300 dark:hover:text-rose-200 transition-all duration-200" x-show="items.length > 1" title="Kaldır">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Toplamlar -->
                        <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 dark:from-blue-900/20 dark:via-indigo-900/20 dark:to-purple-900/20 border border-blue-200/50 dark:border-blue-700/50 rounded-xl p-8 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                    <div class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Ara Toplam</div>
                                    <div class="text-xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(subtotal)"></div>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                    <div class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">KDV Toplamı</div>
                                    <div class="text-xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(vatTotal)"></div>
                                </div>
                                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg p-4 shadow-sm">
                                    <div class="text-white text-sm font-medium mb-2">Genel Toplam</div>
                                    <div class="text-2xl font-bold text-white" x-text="formatCurrency(grandTotal)"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-8 border-t border-gray-200/50 dark:border-gray-600/50">
                            <a href="{{ route('stock.purchases.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-xl transition-all duration-300 shadow-sm hover:shadow-md">
                                İptal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Faturayı Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function invoiceForm() {
                return {
                    paymentType: 'single',
                    items: [{
                        description: '',
                        stock_item_id: '',
                        stock_item_search: '',
                        quantity: 1,
                        unit: 'adet',
                        unit_price: 0,
                        vat_rate: 18,
                        create_item: false,
                        suggestions: [],
                        showStockDropdown: false,
                        filteredStockItems: []
                    }],
                    stockItems: @js($items->map(function($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                            'description' => $item->description ?? ''
                        ];
                    })),
                    subtotal: 0,
                    vatTotal: 0,
                    grandTotal: 0,

                    init() {
                        this.updateCalculations();
                    },


                    addItem() {
                        this.items.push({
                            description: '',
                            stock_item_id: '',
                            stock_item_search: '',
                            quantity: 1,
                            unit: 'adet',
                            unit_price: 0,
                            vat_rate: 18,
                            create_item: false,
                            suggestions: [],
                            showStockDropdown: false,
                            filteredStockItems: []
                        });
                        this.updateCalculations();
                    },

                    removeItem(index) {
                        if (this.items.length > 1) {
                            this.items.splice(index, 1);
                            this.updateCalculations();
                        }
                    },

                    getItemTotal(item) {
                        const quantity = parseFloat(item.quantity) || 0;
                        const unitPrice = parseFloat(item.unit_price) || 0;
                        const vatRate = parseFloat(item.vat_rate) || 0;
                        const subtotal = quantity * unitPrice;
                        const vat = subtotal * (vatRate / 100);
                        return subtotal + vat;
                    },

                    updateCalculations() {
                        this.subtotal = this.items.reduce((sum, item) => {
                            const quantity = parseFloat(item.quantity) || 0;
                            const unitPrice = parseFloat(item.unit_price) || 0;
                            return sum + (quantity * unitPrice);
                        }, 0);
                        
                        this.vatTotal = this.items.reduce((sum, item) => {
                            const quantity = parseFloat(item.quantity) || 0;
                            const unitPrice = parseFloat(item.unit_price) || 0;
                            const vatRate = parseFloat(item.vat_rate) || 0;
                            const itemSubtotal = quantity * unitPrice;
                            return sum + (itemSubtotal * (vatRate / 100));
                        }, 0);
                        
                        this.grandTotal = this.subtotal + this.vatTotal;
                    },

                    async suggestItems(index) {
                        const description = this.items[index].description;
                        if (description.length < 3) {
                            this.items[index].suggestions = [];
                            return;
                        }

                        try {
                            const response = await fetch(`{{ route('stock.purchases.suggest-items') }}?description=${encodeURIComponent(description)}`);
                            const suggestions = await response.json();
                            this.items[index].suggestions = suggestions;
                        } catch (error) {
                            console.error('Error fetching suggestions:', error);
                            this.items[index].suggestions = [];
                        }
                    },

                    selectSuggestion(index, suggestion) {
                        this.items[index].stock_item_id = suggestion.id;
                        this.items[index].description = suggestion.name;
                        this.items[index].suggestions = [];
                    },

                    searchStockItems(index) {
                        const searchTerm = this.items[index].stock_item_search.toLowerCase().trim();
                        if (searchTerm.length === 0) {
                            this.items[index].filteredStockItems = [];
                            this.items[index].showStockDropdown = false;
                            return;
                        }

                        this.items[index].filteredStockItems = this.stockItems.filter(item =>
                            item.name.toLowerCase().includes(searchTerm) ||
                            (item.description && item.description.toLowerCase().includes(searchTerm))
                        ).slice(0, 10); // Limit to 10 results

                        this.items[index].showStockDropdown = this.items[index].filteredStockItems.length > 0;
                    },

                    showStockDropdown(index) {
                        if (this.items[index].stock_item_search.length > 0) {
                            this.searchStockItems(index);
                        }
                    },

                    hideStockDropdown(index) {
                        // Delay hiding to allow click events
                        setTimeout(() => {
                            this.items[index].showStockDropdown = false;
                        }, 150);
                    },

                    selectStockItem(index, stockItem) {
                        this.items[index].stock_item_id = stockItem.id;
                        this.items[index].stock_item_search = stockItem.name;
                        this.items[index].description = stockItem.name;
                        this.items[index].showStockDropdown = false;
                        this.items[index].filteredStockItems = [];
                    },


                    formatCurrency(amount) {
                        if (amount === null || amount === undefined || isNaN(amount)) {
                            return '₺0,00';
                        }
                        return new Intl.NumberFormat('tr-TR', {
                            style: 'currency',
                            currency: 'TRY'
                        }).format(amount);
                    }
                }
            }
        </script>
    </x-app-layout>
