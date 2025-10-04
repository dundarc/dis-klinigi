<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <!-- Modern Header -->
    <div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-purple-600 dark:from-indigo-800 dark:via-blue-800 dark:to-purple-800 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black/10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 0%, transparent 50%);"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg ring-4 ring-white/10">
                            <i class="fas fa-exchange-alt text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-white tracking-tight">Stok Hareketleri</h1>
                        <p class="mt-2 text-indigo-100 text-lg">Tüm stok giriş, çıkış ve düzeltme hareketlerini görüntüleyin ve yönetin</p>
                        <div class="mt-3 flex items-center space-x-4 text-sm text-indigo-200">
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                Son güncelleme: {{ now()->format('d.m.Y H:i') }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-database mr-1"></i>
                                {{ $movements->total() }} toplam hareket
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Quick Actions -->
                    <div class="hidden md:flex items-center space-x-3">
                        <a href="{{ route('stock.movements.export.pdf', request()->query()) }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm">
                            <i class="fas fa-file-pdf mr-2"></i>
                            PDF
                        </a>
                        <a href="{{ route('stock.movements.print', request()->query()) }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm">
                            <i class="fas fa-print mr-2"></i>
                            Yazdır
                        </a>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('stock.movements.create-adjustment') }}"
                           class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-xl transition-all duration-200 border border-white/30 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Düzeltme
                        </a>
                        <a href="{{ route('stock.movements.critical') }}"
                           class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Kritik Stok
                        </a>
                    </div>

                    <!-- Mobile Menu -->
                    <div class="md:hidden">
                        <button onclick="toggleMobileMenu()"
                                class="inline-flex items-center p-3 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 -mt-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Enhanced Filters Section -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-filter text-blue-600 dark:text-blue-400 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Filtreler</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Hareket geçmişini istediğiniz şekilde filtreleyin</p>
                            </div>
                        </div>
                    </div>

                    <form method="GET" class="p-8" id="filterForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            <!-- Movement Type -->
                            <div class="space-y-3">
                                <label for="direction" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                    Hareket Tipi
                                </label>
                                <div class="relative">
                                    <select name="direction" id="direction"
                                            class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm">
                                        <option value="">Tüm Hareketler</option>
                                        @foreach($directions as $direction)
                                            <option value="{{ $direction->value }}" @selected(($filters['direction'] ?? null) === $direction->value)>
                                                {{ $direction->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Item -->
                            <div class="space-y-3">
                                <label for="item_id" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                    Stok Kalemi
                                </label>
                                <div class="relative">
                                    <select name="item_id" id="item_id"
                                            class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm">
                                        <option value="">Tüm Ürünler</option>
                                        @foreach($stockItems as $item)
                                            <option value="{{ $item->id }}" @selected(($filters['item_id'] ?? null) == $item->id)>
                                                {{ $item->name }} ({{ number_format($item->quantity, 2) }} {{ $item->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- User -->
                            <div class="space-y-3">
                                <label for="user_id" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                    Kullanıcı
                                </label>
                                <div class="relative">
                                    <select name="user_id" id="user_id"
                                            class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm">
                                        <option value="">Tüm Kullanıcılar</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" @selected(($filters['user_id'] ?? null) == $user->id)>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Date Range -->
                            <div class="space-y-3 xl:col-span-2">
                                <label class="block text-lg font-semibold text-gray-900 dark:text-white">
                                    Tarih Aralığı
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <input type="date" name="start_date" id="start_date"
                                               value="{{ $filters['start_date'] ?? '' }}"
                                               class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm">
                                        <label for="start_date" class="block text-sm text-gray-600 dark:text-gray-400 mt-1">Başlangıç</label>
                                    </div>
                                    <div>
                                        <input type="date" name="end_date" id="end_date"
                                               value="{{ $filters['end_date'] ?? '' }}"
                                               class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm">
                                        <label for="end_date" class="block text-sm text-gray-600 dark:text-gray-400 mt-1">Bitiş</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Date Filters -->
                            <div class="space-y-3">
                                <label class="block text-lg font-semibold text-gray-900 dark:text-white">
                                    Hızlı Tarihler
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" onclick="setDateRange('today')" class="px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-sm font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                        Bugün
                                    </button>
                                    <button type="button" onclick="setDateRange('week')" class="px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-sm font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                        Bu Hafta
                                    </button>
                                    <button type="button" onclick="setDateRange('month')" class="px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-sm font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                        Bu Ay
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-info-circle"></i>
                                <span>{{ $movements->total() }} kayıt bulundu</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <button type="button" onclick="clearFilters()"
                                        class="inline-flex items-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm">
                                    <i class="fas fa-times mr-2"></i>
                                    Temizle
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-search mr-2"></i>
                                    Filtrele
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Enhanced Movements Table -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/40 dark:to-green-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-history text-green-600 dark:text-green-400 text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Hareket Geçmişi</h2>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $movements->total() }} kayıt görüntüleniyor</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button onclick="refreshData()"
                                        class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    Yenile
                                </button>
                            </div>
                        </div>
                    </div>

                    @if($movements->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="movementsTable">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Tarih
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-box mr-1"></i>
                                            Ürün
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-exchange-alt mr-1"></i>
                                            Hareket
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-hashtag mr-1"></i>
                                            Miktar
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-link mr-1"></i>
                                            Referans
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-user mr-1"></i>
                                            Kullanıcı
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-sticky-note mr-1"></i>
                                            Notlar
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($movements as $movement)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                        {{ ($movement->movement_date ?? $movement->created_at)->format('d.m.Y') }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ ($movement->movement_date ?? $movement->created_at)->format('H:i') }}
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-shrink-0">
                                                        @if($movement->stockItem?->image)
                                                            <img class="h-10 w-10 rounded-lg object-cover shadow-sm"
                                                                 src="{{ Storage::url($movement->stockItem->image) }}"
                                                                 alt="{{ $movement->stockItem->name }}">
                                                        @else
                                                            <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center shadow-sm">
                                                                <i class="fas fa-box text-gray-400 dark:text-gray-500"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                            {{ $movement->stockItem?->name ?? 'Kalem Silinmiş' }}
                                                        </div>
                                                        @if($movement->stockItem?->category)
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ $movement->stockItem->category->name }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold {{ $movement->direction->bgClass() }} border-2 {{ $movement->direction->borderClass() }}">
                                                    <i class="fas fa-{{ $movement->direction->iconClass() }} mr-1.5"></i>
                                                    {{ $movement->direction->label() }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <div class="text-lg font-bold {{ $movement->isOutgoing() ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                                        {{ $movement->isOutgoing() ? '-' : '+' }}{{ number_format(abs($movement->quantity), 2) }}
                                                    </div>
                                                    @if($movement->stockItem)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                                            {{ $movement->stockItem->unit }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                                    {{ $movement->reference_display }}
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xs"></i>
                                                    </div>
                                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                                        {{ $movement->creator?->name ?? 'Sistem' }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate" title="{{ $movement->note ?? '-' }}">
                                                    {{ $movement->note ?? '-' }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Enhanced Pagination -->
                        <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                    Sayfa {{ $movements->currentPage() }} / {{ $movements->lastPage() }}
                                    <span class="font-medium">({{ $movements->total() }} toplam kayıt)</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    {{ $movements->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Enhanced Empty State -->
                        <div class="text-center py-16 px-8">
                            <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600">
                                <i class="fas fa-inbox text-6xl"></i>
                            </div>
                            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Hareket bulunamadı</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                                Belirtilen kriterlere uygun stok hareketi bulunmuyor. Filtreleri değiştirerek farklı sonuçlar elde edebilirsiniz.
                            </p>
                            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <button onclick="clearFilters()"
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors duration-200 shadow-sm">
                                    <i class="fas fa-filter mr-2"></i>
                                    Filtreleri Temizle
                                </button>
                                <a href="{{ route('stock.movements.create-adjustment') }}"
                                   class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors duration-200 shadow-sm">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Yeni Düzeltme
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Instructional Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-chart-bar text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100">Hızlı İstatistikler</h3>
                                    <p class="text-sm text-blue-600 dark:text-blue-400">Son durum</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl border border-green-200 dark:border-green-800">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $movements->where('direction.value', 'in')->count() }}</div>
                                    <div class="text-xs text-green-600 dark:text-green-400 font-medium">Giriş</div>
                                </div>
                                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-xl border border-red-200 dark:border-red-800">
                                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $movements->where('direction.value', 'out')->count() }}</div>
                                    <div class="text-xs text-red-600 dark:text-red-400 font-medium">Çıkış</div>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-clock mr-1"></i>
                                    Son 30 gün: {{ $movements->where('created_at', '>=', now()->subDays(30))->count() }} hareket
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Guide -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-question-circle text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-green-900 dark:text-green-100">Kullanım Kılavuzu</h3>
                                    <p class="text-sm text-green-600 dark:text-green-400">Nasıl kullanılır?</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Step 1 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Filtreleme</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    Hareket tipini, ürünü, kullanıcıyı veya tarih aralığını seçerek sonuçları filtreleyin.
                                </p>
                            </div>

                            <!-- Step 2 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Hareket İnceleme</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    Her hareketin detaylarını inceleyin: tarih, miktar, referans ve açıklama bilgileri.
                                </p>
                            </div>

                            <!-- Step 3 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Dışa Aktarma</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    PDF veya yazdırma seçeneklerini kullanarak raporlarınızı dışa aktarın.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Movement Types Guide -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-info-circle text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-purple-900 dark:text-purple-100">Hareket Tipleri</h3>
                                    <p class="text-sm text-purple-600 dark:text-purple-400">Anlamları</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <span class="w-4 h-4 bg-green-500 rounded-full"></span>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white text-sm">Giriş</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Stoka ürün ekleme</div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <span class="w-4 h-4 bg-red-500 rounded-full"></span>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white text-sm">Çıkış</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Stoktan ürün çıkarma</div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <span class="w-4 h-4 bg-blue-500 rounded-full"></span>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white text-sm">Düzeltme</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Manuel stok düzeltmesi</div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <span class="w-4 h-4 bg-orange-500 rounded-full"></span>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white text-sm">Transfer</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Şube arası transfer</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keyboard Shortcuts -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-keyboard text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-100">Kısayollar</h3>
                                    <p class="text-sm text-indigo-600 dark:text-indigo-400">Hızlı işlemler</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Filtre uygula</span>
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs rounded">Enter</kbd>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Filtre temizle</span>
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs rounded">Ctrl + Del</kbd>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Yenile</span>
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs rounded">F5</kbd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced date range functions
    function setDateRange(range) {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const today = new Date();

        switch(range) {
            case 'today':
                startDate.value = today.toISOString().split('T')[0];
                endDate.value = today.toISOString().split('T')[0];
                break;
            case 'week':
                const weekStart = new Date(today);
                weekStart.setDate(today.getDate() - today.getDay());
                startDate.value = weekStart.toISOString().split('T')[0];
                endDate.value = today.toISOString().split('T')[0];
                break;
            case 'month':
                const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                startDate.value = monthStart.toISOString().split('T')[0];
                endDate.value = today.toISOString().split('T')[0];
                break;
        }
    }

    // Clear all filters
    function clearFilters() {
        document.getElementById('direction').value = '';
        document.getElementById('item_id').value = '';
        document.getElementById('user_id').value = '';
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';

        // Submit form to clear filters
        document.getElementById('filterForm').submit();
    }

    // Refresh data
    function refreshData() {
        const button = event.target.closest('button');
        const icon = button.querySelector('i');

        // Add spinning animation
        icon.classList.add('fa-spin');

        // Simulate refresh
        setTimeout(() => {
            icon.classList.remove('fa-spin');
            location.reload();
        }, 1000);
    }

    // Mobile menu toggle
    function toggleMobileMenu() {
        // This would show/hide mobile menu
        showNotification('Mobil menü yakında eklenecek.', 'info');
    }

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-2xl transform translate-x-full transition-all duration-300 max-w-md ${
            type === 'success' ? 'bg-green-500 border border-green-400' :
            type === 'error' ? 'bg-red-500 border border-red-400' :
            type === 'warning' ? 'bg-yellow-500 border border-yellow-400' : 'bg-blue-500 border border-blue-400'
        } text-white`;

        notification.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-${
                        type === 'success' ? 'check-circle' :
                        type === 'error' ? 'exclamation-circle' :
                        type === 'warning' ? 'exclamation-triangle' : 'info-circle'
                    } text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium">${message}</p>
                </div>
                <button class="flex-shrink-0 close-btn ml-4">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Add close functionality
        notification.querySelector('.close-btn').addEventListener('click', () => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        });

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (document.body.contains(notification)) {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }
        }, 3000);
    }

    // Sidebar responsiveness
    function handleSidebarResponsiveness() {
        const sidebar = document.querySelector('.lg\\:col-span-1');
        const mainContent = document.querySelector('.lg\\:col-span-3');

        if (window.innerWidth < 1024) { // lg breakpoint
            // On mobile/tablet, make sidebar collapsible
            if (!document.querySelector('.sidebar-toggle')) {
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'sidebar-toggle fixed bottom-6 right-6 z-40 w-14 h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-lg transition-all duration-200 lg:hidden';
                toggleBtn.innerHTML = '<i class="fas fa-question-circle"></i>';
                toggleBtn.onclick = toggleSidebar;

                // Initially hide sidebar on mobile
                sidebar.classList.add('hidden');

                document.body.appendChild(toggleBtn);
            }
        } else {
            // On desktop, always show sidebar
            sidebar.classList.remove('hidden');
            const toggleBtn = document.querySelector('.sidebar-toggle');
            if (toggleBtn) {
                toggleBtn.remove();
            }
        }
    }

    function toggleSidebar() {
        const sidebar = document.querySelector('.lg\\:col-span-1');
        const toggleBtn = document.querySelector('.sidebar-toggle');
        const icon = toggleBtn.querySelector('i');

        if (sidebar.classList.contains('hidden')) {
            sidebar.classList.remove('hidden');
            sidebar.classList.add('animate-slide-in-right');
            icon.className = 'fas fa-times';
            toggleBtn.classList.add('rotate-180');
        } else {
            sidebar.classList.add('animate-slide-out-right');
            setTimeout(() => {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('animate-slide-out-right');
            }, 300);
            icon.className = 'fas fa-question-circle';
            toggleBtn.classList.remove('rotate-180');
        }
    }

    // Initialize sidebar responsiveness
    handleSidebarResponsiveness();
    window.addEventListener('resize', handleSidebarResponsiveness);

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl + Delete to clear filters
        if (e.ctrlKey && e.key === 'Delete') {
            e.preventDefault();
            clearFilters();
        }
    });

    // Make functions globally available
    window.setDateRange = setDateRange;
    window.clearFilters = clearFilters;
    window.refreshData = refreshData;
    window.toggleMobileMenu = toggleMobileMenu;
});
</script>

<style>
@keyframes slide-in-right {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slide-out-right {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

.animate-slide-in-right {
    animation: slide-in-right 0.3s ease-out;
}

.animate-slide-out-right {
    animation: slide-out-right 0.3s ease-in;
}

/* Enhanced focus states */
input:focus, select:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Sidebar toggle button animations */
.sidebar-toggle {
    transition: all 0.3s ease;
}

.sidebar-toggle:hover {
    transform: scale(1.1);
}

.sidebar-toggle.rotate-180 i {
    transform: rotate(180deg);
    transition: transform 0.3s ease;
}

/* Enhanced table row hover effects */
#movementsTable tbody tr {
    transition: all 0.2s ease;
}

#movementsTable tbody tr:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Responsive design improvements */
@media (max-width: 1023px) {
    .sidebar-toggle {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
}

/* Loading animation for refresh button */
.fa-spin {
    animation: fa-spin 1s infinite linear;
}

@keyframes fa-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush
</x-app-layout>