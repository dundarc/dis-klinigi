<x-app-layout>
 
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <!-- Modern Header with Enhanced Design -->
    <div class="bg-gradient-to-r from-red-600 via-red-700 to-red-800 dark:from-red-800 dark:via-red-900 dark:to-red-950 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black/10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 0%, transparent 50%);"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg ring-4 ring-white/10">
                            <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-white tracking-tight">Kritik Stoklar</h1>
                        <p class="mt-2 text-red-100 text-lg">Minimum seviye altındaki stok kalemleri için acil müdahale</p>
                        <div class="mt-3 flex items-center space-x-4 text-sm text-red-200">
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                Son güncelleme: {{ now()->format('d.m.Y H:i') }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                {{ $criticalItems->count() }} öğe görüntüleniyor
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Quick Actions -->
                    <div class="hidden md:flex items-center space-x-3">
                        <button onclick="refreshData()"
                                class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-xl transition-all duration-200 border border-white/20 backdrop-blur-sm"
                                title="Yenile">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Yenile
                        </button>
                        <a href="{{ route('stock.items.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-xl transition-all duration-200 border border-white/30 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-boxes mr-2"></i>
                            Tüm Stoklar
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
        <!-- Enhanced Stats Cards with Animations -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Critical Items Count -->
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer"
                 onclick="showCriticalItemsDetail()">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/40 dark:to-red-800/40 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 flex-1">
                            <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                Kritik Ürün Sayısı
                            </dt>
                            <dd class="text-4xl font-bold text-gray-900 dark:text-white mt-1 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                                {{ $criticalItems->count() }}
                            </dd>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Aktif takip edilen ürünler</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="w-8 h-8 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center group-hover:bg-red-100 dark:group-hover:bg-red-900/30 transition-colors">
                            <i class="fas fa-chevron-right text-red-400 text-xs group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Value -->
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer"
                 onclick="showValueAnalysis()">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/40 dark:to-orange-800/40 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-chart-line text-orange-600 dark:text-orange-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 flex-1">
                            <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                Toplam Değer
                            </dt>
                            <dd class="text-4xl font-bold text-gray-900 dark:text-white mt-1 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                ₺{{ number_format($totalValue, 2) }}
                            </dd>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Kritik stokların değeri</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="w-8 h-8 bg-orange-50 dark:bg-orange-900/20 rounded-full flex items-center justify-center group-hover:bg-orange-100 dark:group-hover:bg-orange-900/30 transition-colors">
                            <i class="fas fa-chart-bar text-orange-400 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Urgent Items -->
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer"
                 onclick="showUrgentItems()">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-shopping-cart text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 flex-1">
                            <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                Acil Temin Gereken
                            </dt>
                            <dd class="text-4xl font-bold text-gray-900 dark:text-white mt-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $criticalItems->where('quantity', '<=', 0)->count() }}
                            </dd>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Hemen temin edilmesi gereken</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                            <i class="fas fa-exclamation text-blue-400 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Critical Items Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Table Header with Enhanced Controls -->
            <div class="px-6 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-list text-blue-600 dark:text-blue-400 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                Kritik Stok Listesi
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $criticalItems->count() }} öğe • Öncelik sırasına göre sıralanmış
                            </p>
                        </div>
                    </div>

                    <!-- Enhanced Controls -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                        <!-- Search Input -->
                        <div class="relative">
                            <input type="text"
                                   id="searchInput"
                                   placeholder="Ürün ara..."
                                   class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   onkeyup="filterTable(this.value)">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Category Filter -->
                        <select id="categoryFilter"
                                onchange="filterByCategory(this.value)"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Tüm Kategoriler</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Export Button -->
                        <button onclick="exportCriticalStocks()"
                                class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-download mr-2"></i>
                            Dışa Aktar
                        </button>
                    </div>
                </div>

                <!-- Quick Filter Tags -->
                <div class="flex flex-wrap items-center gap-2 mt-4">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Hızlı Filtre:</span>
                    <button onclick="filterBySeverity('critical')"
                            class="inline-flex items-center px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Stok Yok
                    </button>
                    <button onclick="filterBySeverity('warning')"
                            class="inline-flex items-center px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 text-xs font-medium rounded-full hover:bg-orange-200 dark:hover:bg-orange-900/50 transition-colors">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Kritik
                    </button>
                    <button onclick="filterBySeverity('low')"
                            class="inline-flex items-center px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs font-medium rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition-colors">
                        <i class="fas fa-warning mr-1"></i>
                        Düşük
                    </button>
                    <button onclick="clearFilters()"
                            class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 text-xs font-medium rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors ml-2">
                        <i class="fas fa-times mr-1"></i>
                        Temizle
                    </button>
                </div>
            </div>

            @if($criticalItems->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Ürün
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Mevcut Stok
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Minimum Seviye
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Eksik Miktar
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Durumu
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Son Hareket
                                </th>
                                <th scope="col" class="relative px-6 py-4">
                                    <span class="sr-only">İşlemler</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="criticalItemsTable">
                            @foreach($criticalItems as $item)
                                @php
                                    $shortage = $item->minimum_quantity - $item->quantity;
                                    $severityClass = $item->quantity <= 0 ? 'bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400' :
                                        ($shortage > $item->minimum_quantity * 0.5 ? 'bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-400' : 'bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400');
                                    $rowId = 'row-' . $item->id;
                                @endphp
                                <tr class="{{ $severityClass }} hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 hover:shadow-sm"
                                    data-category="{{ $item->category->id ?? '' }}"
                                    data-severity="{{ $item->quantity <= 0 ? 'critical' : ($shortage > $item->minimum_quantity * 0.5 ? 'warning' : 'low') }}"
                                    id="{{ $rowId }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 relative">
                                                @if($item->image)
                                                    <img class="h-12 w-12 rounded-xl object-cover shadow-sm ring-2 ring-gray-100 dark:ring-gray-700"
                                                         src="{{ Storage::url($item->image) }}"
                                                         alt="{{ $item->name }}">
                                                @else
                                                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center shadow-sm ring-2 ring-gray-100 dark:ring-gray-700">
                                                        <i class="fas fa-box text-gray-400 dark:text-gray-500 text-lg"></i>
                                                    </div>
                                                @endif
                                                @if($item->quantity <= 0)
                                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-exclamation text-white text-xs"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $item->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400 font-mono">
                                                    {{ $item->barcode }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                            <i class="fas fa-tag mr-1.5 text-blue-600 dark:text-blue-400"></i>
                                            {{ $item->category->name ?? 'Kategori Yok' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-lg font-bold text-gray-900 dark:text-white">
                                                {{ number_format($item->quantity, 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                                {{ $item->unit }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                                {{ number_format($item->minimum_quantity, 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                                {{ $item->unit }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-lg font-bold {{ $item->quantity <= 0 ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400' }}">
                                                -{{ number_format($shortage, 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                                {{ $item->unit }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->quantity <= 0)
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border-2 border-red-200 dark:border-red-800 animate-pulse">
                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                STOK YOK
                                            </span>
                                        @elseif($shortage > $item->minimum_quantity * 0.5)
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 border-2 border-orange-200 dark:border-orange-800">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                KRITIK
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border-2 border-yellow-200 dark:border-yellow-800">
                                                <i class="fas fa-warning mr-2"></i>
                                                DÜŞÜK
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($item->lastMovement)
                                            <div class="flex items-center space-x-2">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-{{ $item->lastMovement->direction->value == 'in' ? 'arrow-up text-green-500' : 'arrow-down text-red-500' }} text-lg"></i>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-gray-600 dark:text-gray-300 font-medium">
                                                        {{ $item->lastMovement->created_at->diffForHumans() }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                                        {{ $item->lastMovement->quantity }} {{ $item->unit }}
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 italic text-sm">Hareket yok</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end space-x-1">
                                            <button onclick="quickView('{{ $item->id }}')"
                                                    class="inline-flex items-center px-3 py-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-150 hover:shadow-sm"
                                                    title="Hızlı Görüntüle">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                            <a href="{{ route('stock.movements.item-history', $item) }}"
                                               class="inline-flex items-center px-3 py-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-150 hover:shadow-sm"
                                               title="Hareket Geçmişi">
                                                <i class="fas fa-history text-sm"></i>
                                            </a>
                                            <a href="{{ route('stock.items.edit', $item) }}"
                                               class="inline-flex items-center px-3 py-2 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all duration-150 hover:shadow-sm"
                                               title="Düzenle">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <a href="{{ route('stock.movements.create-adjustment', ['item' => $item->id]) }}"
                                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-150 hover:shadow-sm transform hover:-translate-y-0.5"
                                               title="Stok Düzeltmesi">
                                                <i class="fas fa-plus text-sm"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modern Pagination -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                    {{ $criticalItems->links() }}
                </div>
            @else
                <!-- Modern Empty State -->
                <div class="text-center py-16 px-6">
                    <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600">
                        <i class="fas fa-check-circle text-6xl"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Kritik stok bulunmuyor</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                        Tüm ürünlerin stok seviyeleri minimum değerlerin üzerinde. Harika bir yönetim sergiliyorsunuz!
                    </p>
                    <div class="mt-8">
                        <a href="{{ route('stock.items.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors duration-200 shadow-sm">
                            <i class="fas fa-boxes mr-2"></i>
                            Tüm Stokları Görüntüle
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Enhanced JavaScript functionality for modern UI/UX

// Search and filter functionality
function filterTable(searchTerm) {
    const rows = document.querySelectorAll('#criticalItemsTable tr');
    const term = searchTerm.toLowerCase();

    rows.forEach(row => {
        const productName = row.cells[0]?.textContent.toLowerCase() || '';
        const barcode = row.cells[0]?.querySelector('.font-mono')?.textContent.toLowerCase() || '';

        if (productName.includes(term) || barcode.includes(term)) {
            row.style.display = '';
            row.classList.add('animate-fade-in');
        } else {
            row.style.display = 'none';
            row.classList.remove('animate-fade-in');
        }
    });

    updateVisibleCount();
}

function filterByCategory(categoryId) {
    const rows = document.querySelectorAll('#criticalItemsTable tr');

    rows.forEach(row => {
        if (!categoryId || row.dataset.category === categoryId) {
            row.style.display = '';
            row.classList.add('animate-fade-in');
        } else {
            row.style.display = 'none';
            row.classList.remove('animate-fade-in');
        }
    });

    updateVisibleCount();
}

function filterBySeverity(severity) {
    const rows = document.querySelectorAll('#criticalItemsTable tr');

    rows.forEach(row => {
        if (row.dataset.severity === severity) {
            row.style.display = '';
            row.classList.add('animate-fade-in');
        } else {
            row.style.display = 'none';
            row.classList.remove('animate-fade-in');
        }
    });

    updateVisibleCount();
}

function clearFilters() {
    // Reset search input
    document.getElementById('searchInput').value = '';

    // Reset category filter
    document.getElementById('categoryFilter').value = '';

    // Show all rows
    const rows = document.querySelectorAll('#criticalItemsTable tr');
    rows.forEach(row => {
        row.style.display = '';
        row.classList.add('animate-fade-in');
    });

    updateVisibleCount();
}

function updateVisibleCount() {
    const visibleRows = document.querySelectorAll('#criticalItemsTable tr:not([style*="display: none"])').length;
    const totalRows = document.querySelectorAll('#criticalItemsTable tr').length;

    // Update the count display if it exists
    const countDisplay = document.querySelector('.text-xl.font-bold.text-gray-900.dark\\:text-white');
    if (countDisplay) {
        countDisplay.textContent = `${visibleRows} öğe${totalRows !== visibleRows ? ` (toplam ${totalRows})` : ''}`;
    }
}

// Export functionality
function exportCriticalStocks() {
    // Show loading state
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Dışa Aktarılıyor...';
    button.disabled = true;

    // Simulate export process
    setTimeout(() => {
        // Create a simple CSV export
        const rows = document.querySelectorAll('#criticalItemsTable tr:not([style*="display: none"])');
        let csvContent = 'Ürün,Kategori,Mevcut Stok,Minimum Seviye,Eksik Miktar,Durum\n';

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 0) {
                const product = cells[0].textContent.trim().split('\n')[0];
                const category = cells[1].textContent.trim();
                const currentStock = cells[2].textContent.trim().split('\n')[0];
                const minStock = cells[3].textContent.trim().split('\n')[0];
                const shortage = cells[4].textContent.trim().split('\n')[0];
                const status = cells[5].textContent.trim();

                csvContent += `"${product}","${category}","${currentStock}","${minStock}","${shortage}","${status}"\n`;
            }
        });

        // Download the CSV
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `kritik-stoklar-${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;

        // Show success message
        showNotification('Dışa aktarma tamamlandı!', 'success');
    }, 1500);
}

// Quick view functionality
function quickView(itemId) {
    // This would typically open a modal with item details
    showNotification(`Ürün ${itemId} için hızlı görüntüleme açılıyor...`, 'info');
}

// Stats card interactions
function showCriticalItemsDetail() {
    showNotification('Kritik ürünler listesi yenileniyor...', 'info');
    // Could implement detailed view or filtering
}

function showValueAnalysis() {
    showNotification('Değer analizi görüntüleniyor...', 'info');
    // Could implement chart or detailed analysis
}

function showUrgentItems() {
    filterBySeverity('critical');
    showNotification('Acil temin edilmesi gereken ürünler filtrelendi.', 'warning');
}

// Refresh functionality
function refreshData() {
    const button = event.target.closest('button');
    const icon = button.querySelector('i');

    // Add spinning animation
    icon.classList.add('fa-spin');

    // Simulate refresh
    setTimeout(() => {
        icon.classList.remove('fa-spin');
        showNotification('Veriler yenilendi!', 'success');
        // In a real app, this would reload the data
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
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
    } text-white`;

    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${
                type === 'success' ? 'check-circle' :
                type === 'error' ? 'exclamation-circle' :
                type === 'warning' ? 'exclamation-triangle' : 'info-circle'
            } mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Add some CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
`;
document.head.appendChild(style);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to interactive elements
    const interactiveElements = document.querySelectorAll('button, a, .cursor-pointer');
    interactiveElements.forEach(el => {
        el.addEventListener('click', function() {
            if (this.tagName === 'BUTTON' && !this.disabled) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            }
        });
    });

    // Add keyboard navigation support
    document.addEventListener('keydown', function(e) {
        if (e.key === '/' && e.target.tagName !== 'INPUT') {
            e.preventDefault();
            document.getElementById('searchInput').focus();
        }
    });
});
</script>
</x-app-layout>
