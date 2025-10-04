<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <!-- Modern Header -->
    <div class="bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 dark:from-teal-800 dark:via-cyan-800 dark:to-blue-800 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black/10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 0%, transparent 50%);"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg ring-4 ring-white/10">
                            <i class="fas fa-truck text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-white tracking-tight">Tedarikçi Yönetimi</h1>
                        <p class="mt-2 text-teal-100 text-lg">Tedarikçilerinizi yönetin, takip edin ve performanslarını izleyin</p>
                        <div class="mt-3 flex items-center space-x-4 text-sm text-teal-200">
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                Son güncelleme: {{ now()->format('d.m.Y H:i') }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-building mr-1"></i>
                                {{ $suppliers->total() }} aktif tedarikçi
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Quick Actions -->
                    <div class="hidden md:flex items-center space-x-3">
                        <a href="{{ route('stock.suppliers.create') }}"
                           class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-xl transition-all duration-200 border border-white/30 backdrop-blur-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Yeni Tedarikçi
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
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tedarikçi Filtreleme</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Tedarikçilerinizi istediğiniz şekilde filtreleyin</p>
                            </div>
                        </div>
                    </div>

                    <form method="GET" class="p-8" id="filterForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                            <!-- Name Search -->
                            <div class="space-y-3">
                                <label for="q" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                    İsim Ara
                                </label>
                                <div class="relative">
                                    <input type="text" name="q" id="q"
                                           value="{{ $filters['q'] ?? '' }}"
                                           class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm"
                                           placeholder="Tedarikçi adı ile ara...">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Tax Number -->
                            <div class="space-y-3">
                                <label for="tax_number" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                    Vergi No
                                </label>
                                <div class="relative">
                                    <input type="text" name="tax_number" id="tax_number"
                                           value="{{ $filters['tax_number'] ?? '' }}"
                                           class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm"
                                           placeholder="Vergi numarası ile ara...">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="fas fa-hashtag text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- City -->
                            <div class="space-y-3">
                                <label for="city" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                    Şehir
                                </label>
                                <div class="relative">
                                    <input type="text" name="city" id="city"
                                           value="{{ $filters['city'] ?? '' }}"
                                           class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm"
                                           placeholder="Şehir ile ara...">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Type -->
                            <div class="space-y-3">
                                <label for="type" class="block text-lg font-semibold text-gray-900 dark:text-white">
                                    Tür
                                </label>
                                <div class="relative">
                                    <select name="type" id="type"
                                            class="block w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg shadow-sm">
                                        <option value="">Tüm Türler</option>
                                        <option value="supplier" @selected(($filters['type'] ?? '') === 'supplier')>Tedarikçi</option>
                                        <option value="service" @selected(($filters['type'] ?? '') === 'service')>Hizmet</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-info-circle"></i>
                                <span>{{ $suppliers->total() }} kayıt bulundu</span>
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

                <!-- Enhanced Suppliers Table -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/40 dark:to-green-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-list text-green-600 dark:text-green-400 text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tedarikçi Listesi</h2>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $suppliers->total() }} tedarikçi görüntüleniyor</p>
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

                    @if($suppliers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="suppliersTable">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-building mr-1"></i>
                                            Tedarikçi
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-tag mr-1"></i>
                                            Tür
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-phone mr-1"></i>
                                            İletişim
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-hashtag mr-1"></i>
                                            Vergi No
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            Konum
                                        </th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-cogs mr-1"></i>
                                            İşlemler
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($suppliers as $supplier)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 rounded-xl flex items-center justify-center shadow-lg">
                                                            <i class="fas fa-building text-blue-600 dark:text-blue-400 text-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                            {{ $supplier->name }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $supplier->address ?? 'Adres bilgisi yok' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                @if($supplier->type === 'service')
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border-2 border-blue-200 dark:border-blue-800">
                                                        <i class="fas fa-tools mr-1.5"></i>
                                                        Hizmet
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border-2 border-emerald-200 dark:border-emerald-800">
                                                        <i class="fas fa-truck mr-1.5"></i>
                                                        Tedarikçi
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="space-y-1">
                                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                                        <i class="fas fa-phone text-gray-400 mr-2 w-4"></i>
                                                        {{ $supplier->phone ?? '-' }}
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                                        <i class="fas fa-envelope text-gray-400 mr-2 w-4"></i>
                                                        <span class="truncate max-w-32" title="{{ $supplier->email ?? '-' }}">
                                                            {{ $supplier->email ?? '-' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="text-sm font-mono text-gray-600 dark:text-gray-300">
                                                    {{ $supplier->tax_number ?? '-' }}
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                                    {{ $supplier->city ?? '-' }}
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end space-x-1">
                                                    <a href="{{ route('stock.current.show', $supplier) }}"
                                                       class="inline-flex items-center px-3 py-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-150 hover:shadow-sm"
                                                       title="Cari Detay">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                    <a href="{{ route('stock.suppliers.edit', $supplier) }}"
                                                       class="inline-flex items-center px-3 py-2 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all duration-150 hover:shadow-sm"
                                                       title="Düzenle">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                    <button onclick="deleteSupplier({{ $supplier->id }}, '{{ $supplier->name }}')"
                                                            class="inline-flex items-center px-3 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-150 hover:shadow-sm"
                                                            title="Sil">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
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
                                    Sayfa {{ $suppliers->currentPage() }} / {{ $suppliers->lastPage() }}
                                    <span class="font-medium">({{ $suppliers->total() }} toplam kayıt)</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    {{ $suppliers->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Enhanced Empty State -->
                        <div class="text-center py-16 px-8">
                            <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600">
                                <i class="fas fa-building text-6xl"></i>
                            </div>
                            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Tedarikçi bulunmuyor</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                                Henüz hiç tedarikçi eklenmemiş. İlk tedarikçinizi ekleyerek başlayın.
                            </p>
                            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('stock.suppliers.create') }}"
                                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors duration-200 shadow-sm">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    İlk Tedarikçiyi Ekle
                                </a>
                                <button onclick="showImportGuide()"
                                        class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors duration-200 shadow-sm">
                                    <i class="fas fa-upload mr-2"></i>
                                    Toplu İçe Aktar
                                </button>
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
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-chart-pie text-teal-600 dark:text-teal-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-teal-900 dark:text-teal-100">Tedarikçi İstatistikleri</h3>
                                    <p class="text-sm text-teal-600 dark:text-teal-400">Genel bakış</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-4 rounded-xl border border-blue-200 dark:border-blue-800">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $suppliers->where('type', 'supplier')->count() }}</div>
                                    <div class="text-xs text-blue-600 dark:text-blue-400 font-medium">Tedarikçi</div>
                                </div>
                                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-4 rounded-xl border border-purple-200 dark:border-purple-800">
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $suppliers->where('type', 'service')->count() }}</div>
                                    <div class="text-xs text-purple-600 dark:text-purple-400 font-medium">Hizmet</div>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-city mr-1"></i>
                                    {{ $suppliers->unique('city')->count() }} farklı şehir
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Guide -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-question-circle text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100">Kullanım Kılavuzu</h3>
                                    <p class="text-sm text-blue-600 dark:text-blue-400">Tedarikçi yönetimi</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Step 1 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Tedarikçi Ekleme</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    "Yeni Tedarikçi" butonuna tıklayarak tedarikçi bilgilerini girin.
                                </p>
                            </div>

                            <!-- Step 2 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Filtreleme</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    İsim, vergi no, şehir veya tür bilgisine göre tedarikçileri filtreleyin.
                                </p>
                            </div>

                            <!-- Step 3 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Düzenleme</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    Tedarikçi bilgilerini güncellemek için düzenle butonunu kullanın.
                                </p>
                            </div>

                            <!-- Step 4 -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">4</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Cari Takip</h4>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-11">
                                    Cari detay butonu ile tedarikçinin finansal işlemlerini inceleyin.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Supplier Types Guide -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-tags text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-green-900 dark:text-green-100">Tedarikçi Türleri</h3>
                                    <p class="text-sm text-green-600 dark:text-green-400">Farklı kategoriler</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <span class="w-4 h-4 bg-emerald-500 rounded-full"></span>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white text-sm">Tedarikçi</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Malzeme tedarik eden firmalar</div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <span class="w-4 h-4 bg-blue-500 rounded-full"></span>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white text-sm">Hizmet</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Hizmet sağlayan firmalar</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Best Practices -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-amber-600 dark:text-amber-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-amber-900 dark:text-amber-100">İpuçları</h3>
                                    <p class="text-sm text-amber-600 dark:text-amber-400">Verimli kullanım</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                    <div>
                                        <h5 class="font-semibold text-gray-900 dark:text-white text-sm">Düzenli Güncelleme</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Tedarikçi bilgilerini düzenli olarak güncelleyin.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-tags text-blue-500 mt-0.5"></i>
                                    <div>
                                        <h5 class="font-semibold text-gray-900 dark:text-white text-sm">Doğru Kategorileme</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Tedarikçileri doğru türde sınıflandırın.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-phone text-purple-500 mt-0.5"></i>
                                    <div>
                                        <h5 class="font-semibold text-gray-900 dark:text-white text-sm">İletişim Bilgileri</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Güncel telefon ve e-posta bilgilerini tutun.
                                        </p>
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
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Yeni tedarikçi</span>
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs rounded">Ctrl + N</kbd>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Filtre uygula</span>
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs rounded">Enter</kbd>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Arama odakla</span>
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs rounded">Ctrl + F</kbd>
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
    // Clear filters function
    function clearFilters() {
        document.getElementById('q').value = '';
        document.getElementById('tax_number').value = '';
        document.getElementById('city').value = '';
        document.getElementById('type').value = '';

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

    // Delete supplier with confirmation
    function deleteSupplier(id, name) {
        if (confirm(`"${name}" tedarikçisini silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/stock/suppliers/${id}`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';

            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Show import guide
    function showImportGuide() {
        showNotification('Toplu içe aktarma özelliği yakında eklenecek.', 'info');
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
                toggleBtn.className = 'sidebar-toggle fixed bottom-6 right-6 z-40 w-14 h-14 bg-teal-600 hover:bg-teal-700 text-white rounded-full shadow-lg transition-all duration-200 lg:hidden';
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
        // Ctrl + N for new supplier
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            window.location.href = '{{ route("stock.suppliers.create") }}';
        }

        // Ctrl + F to focus search
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            document.getElementById('q').focus();
        }
    });

    // Make functions globally available
    window.clearFilters = clearFilters;
    window.refreshData = refreshData;
    window.deleteSupplier = deleteSupplier;
    window.showImportGuide = showImportGuide;
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
#suppliersTable tbody tr {
    transition: all 0.2s ease;
}

#suppliersTable tbody tr:hover {
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
