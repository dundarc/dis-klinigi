<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-8 mb-8 text-white">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Stok Kalemleri Yönetimi</h1>
                        <p class="text-emerald-100 text-lg">Tüm stok kalemlerinizi modern arayüz ile yönetin ve takip edin</p>
                        <div class="flex items-center mt-4 space-x-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-emerald-400 rounded-full mr-2"></div>
                                <span class="text-sm text-emerald-100">{{ $items->total() }} Toplam Kalem</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-amber-400 rounded-full mr-2"></div>
                                <span class="text-sm text-emerald-100">{{ $items->where('quantity', '<=', \DB::raw('minimum_quantity'))->count() }} Kritik</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-rose-400 rounded-full mr-2"></div>
                                <span class="text-sm text-emerald-100">{{ $items->where('quantity', '<', 0)->count() }} Negatif</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <!-- Export Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('stock.items.export.pdf', request()->query()) }}" target="_blank"
                               class="inline-flex items-center px-4 py-3 text-sm font-semibold text-red-600 bg-white rounded-xl hover:bg-red-50 focus:ring-4 focus:ring-white/30 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                PDF
                            </a>
                            <a href="{{ route('stock.items.export.excel', request()->query()) }}" target="_blank"
                               class="inline-flex items-center px-4 py-3 text-sm font-semibold text-green-600 bg-white rounded-xl hover:bg-green-50 focus:ring-4 focus:ring-white/30 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Excel
                            </a>
                            <a href="{{ route('stock.items.print', request()->query()) }}" target="_blank"
                               class="inline-flex items-center px-4 py-3 text-sm font-semibold text-gray-600 bg-white rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-white/30 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Yazdır
                            </a>
                        </div>

                        <a href="{{ route('stock.bulk-movements') }}"
                           class="inline-flex items-center px-6 py-3 text-sm font-semibold text-emerald-600 bg-white rounded-xl hover:bg-emerald-50 focus:ring-4 focus:ring-white/30 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Toplu İşlem
                        </a>

                        <a href="{{ route('stock.items.create') }}"
                           class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl hover:from-blue-700 hover:to-purple-700 focus:ring-4 focus:ring-white/30 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Yeni Kalem
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ showCategoryForm: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Advanced Filters -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 p-8 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Gelişmiş Filtreler</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-sm">Stok kalemlerinizi kategoriye ve duruma göre filtreleyin</p>
                        </div>
                    </div>
                    <button type="button" @click="showCategoryForm = !showCategoryForm"
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Kategori Ekle
                    </button>
                </div>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label for="q" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            İsim Ara
                        </label>
                        <input id="q" name="q" type="text" value="{{ $filters['q'] ?? '' }}"
                               class="w-full px-4 py-3 text-lg border-2 border-slate-200 dark:border-slate-600 rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 dark:bg-slate-700 dark:text-slate-100 transition-all duration-200"
                               placeholder="Kalem adı ile ara...">
                    </div>

                    <div class="space-y-2">
                        <label for="category" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Kategori
                        </label>
                        <select id="category" name="category"
                                class="w-full px-4 py-3 text-lg border-2 border-slate-200 dark:border-slate-600 rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 dark:bg-slate-700 dark:text-slate-100 transition-all duration-200">
                            <option value="">Tüm Kategoriler</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Stok Durumu
                        </label>
                        <select id="status" name="status"
                                class="w-full px-4 py-3 text-lg border-2 border-slate-200 dark:border-slate-600 rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 dark:bg-slate-700 dark:text-slate-100 transition-all duration-200">
                            <option value="">Tüm Durumlar</option>
                            <option value="normal" @selected(($filters['status'] ?? '') === 'normal')>Normal</option>
                            <option value="critical" @selected(($filters['status'] ?? '') === 'critical')>Kritik</option>
                            <option value="negative" @selected(($filters['status'] ?? '') === 'negative')>Negatif</option>
                        </select>
                    </div>

                    <div class="flex items-end space-x-3">
                        <button type="button" onclick="window.location='{{ route('stock.items.index') }}';"
                                class="flex-1 px-6 py-3 text-sm font-semibold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all duration-200">
                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                        <button type="submit"
                                class="flex-1 px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-4 focus:ring-emerald-500/30 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filtrele
                        </button>
                    </div>
                </form>

                <!-- Category Add Form -->
                <div x-show="showCategoryForm" x-transition class="mt-6 rounded-2xl border border-emerald-200/50 dark:border-emerald-700/50 bg-gradient-to-r from-emerald-50/50 to-teal-50/50 dark:from-emerald-900/10 dark:to-teal-900/10 p-6 backdrop-blur-sm">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-slate-100">Yeni Kategori Ekle</h4>
                    </div>
                    <form method="POST" action="{{ route('stock.categories.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        <input type="hidden" name="return_to" value="{{ url()->current() }}">
                        <div>
                            <label for="item_category_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Kategori Adı
                            </label>
                            <input id="item_category_name" name="name" type="text"
                                   class="w-full px-4 py-3 text-lg border-2 border-slate-200 dark:border-slate-600 rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 dark:bg-slate-700 dark:text-slate-100 transition-all duration-200"
                                   required>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <label for="item_category_description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                Açıklama
                            </label>
                            <input id="item_category_description" name="description" type="text"
                                   class="w-full px-4 py-3 text-lg border-2 border-slate-200 dark:border-slate-600 rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 dark:bg-slate-700 dark:text-slate-100 transition-all duration-200">
                        </div>
                        <div class="md:col-span-2 flex justify-end space-x-3">
                            <button type="button" @click="showCategoryForm = false"
                                    class="px-6 py-3 text-sm font-semibold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                İptal
                            </button>
                            <button type="submit"
                                    class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-4 focus:ring-emerald-500/30 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Kategori Ekle
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stock Items Grid -->
            <div class="space-y-6">
                @forelse($items as $item)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 p-6 hover:shadow-2xl transition-all duration-300 backdrop-blur-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br {{ $item->quantity < 0 ? 'from-rose-500 to-pink-600' : ($item->isBelowMinimum() ? 'from-amber-500 to-orange-600' : 'from-emerald-500 to-teal-600') }} rounded-2xl shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $item->name }}</h3>
                                    <p class="text-slate-600 dark:text-slate-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        {{ $item->category?->name ?? 'Kategorisiz' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-6">
                                <!-- Stock Status -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold {{ $item->quantity < 0 ? 'text-rose-600 dark:text-rose-400' : ($item->isBelowMinimum() ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                                        {{ number_format($item->quantity, 2) }}
                                    </div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ $item->unit }}</div>
                                </div>

                                <!-- Status Badge -->
                                <div class="flex flex-col items-center space-y-2">
                                    @if(!$item->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Pasif
                                        </span>
                                    @elseif($item->quantity < 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-rose-100 to-pink-100 dark:from-rose-900/30 dark:to-pink-900/30 text-rose-800 dark:text-rose-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            Negatif
                                        </span>
                                    @elseif($item->isBelowMinimum())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 text-amber-800 dark:text-amber-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            Kritik
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 text-emerald-800 dark:text-emerald-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Normal
                                        </span>
                                    @endif

                                    <!-- Critical Level -->
                                    <div class="text-center">
                                        <div class="text-xs text-slate-500 dark:text-slate-400">Min. Seviye</div>
                                        <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ number_format($item->minimum_quantity, 2) }}</div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('stock.items.show', $item) }}"
                                       class="inline-flex items-center justify-center w-10 h-10 text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-all duration-200"
                                       title="Hareketleri Görüntüle">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('stock.items.edit', $item) }}"
                                       class="inline-flex items-center justify-center w-10 h-10 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-all duration-200"
                                       title="Düzenle">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('stock.items.destroy', $item) }}" onsubmit="return confirm('Bu kalemi silmek istediğinizden emin misiniz?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-10 h-10 text-slate-400 hover:text-red-600 dark:hover:text-red-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/30 transition-all duration-200"
                                                title="Sil">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 p-16 text-center backdrop-blur-sm">
                        <div class="flex items-center justify-center w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 rounded-2xl mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2">Stok kalemi bulunmuyor</h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-8 max-w-md mx-auto">Henüz hiç stok kalemi eklenmemiş. Modern stok yönetim sisteminizi yeni kalemler ekleyerek başlatın.</p>
                        <a href="{{ route('stock.items.create') }}"
                           class="inline-flex items-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl hover:from-emerald-700 hover:to-teal-700 focus:ring-4 focus:ring-emerald-500/30 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            İlk Stok Kalemini Ekle
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Modern Pagination -->
            @if($items->hasPages() or 1==1)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 p-6 backdrop-blur-sm">
                    <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            <span class="font-medium">{{ $items->firstItem() }}</span> -
                            <span class="font-medium">{{ $items->lastItem() }}</span>
                            arası gösteriliyor,
                            toplam <span class="font-medium">{{ $items->total() }}</span> öğe
                        </div>

                        <div class="flex items-center space-x-2">
                            <!-- Previous Button -->
                            @if($items->onFirstPage())
                                <button disabled class="inline-flex items-center px-3 py-2 text-sm font-medium text-slate-400 bg-slate-100 dark:bg-slate-700 dark:text-slate-500 rounded-xl cursor-not-allowed">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Önceki
                                </button>
                            @else
                                <a href="{{ $items->previousPageUrl() }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Önceki
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            <div class="flex items-center space-x-1">
                                @foreach($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                                    @if($page == $items->currentPage())
                                        <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl shadow-lg">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Next Button -->
                            @if($items->hasMorePages())
                                <a href="{{ $items->nextPageUrl() }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                    Sonraki
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @else
                                <button disabled class="inline-flex items-center px-3 py-2 text-sm font-medium text-slate-400 bg-slate-100 dark:bg-slate-700 dark:text-slate-500 rounded-xl cursor-not-allowed">
                                    Sonraki
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
