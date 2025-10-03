<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Toplu Stok Hareketleri</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Birden fazla stok kalemi için giriş, çıkış veya düzeltme işlemleri yapın.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('stock.movements.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Hareket Geçmişi
                </a>
            </div>
        </div>
    </x-slot>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bulkMovements', () => ({
                bulkItems: [{ item_id: '', direction: 'out', quantity: '', note: '' }],
                processing: false,
                confirmed: false,
                timeFilter: '24h',
                warnings: [],
                recentBulkOperations: [],
                bulkOperationsLoading: false,
                addRow() { this.bulkItems.push({ item_id: '', direction: 'out', quantity: '', note: '' }); },
                removeRow(index) { if (this.bulkItems.length > 1) { this.bulkItems.splice(index, 1); } },
                canSubmit() { return this.confirmed && !this.processing; },
                exportRecentMovementsPDF() {
                    const url = `/stock/movements/export/recent-pdf?filter=${this.timeFilter}`;
                    window.open(url, '_blank');
                },
                exportBulkOperationPDF(batchId) {
                    if (!batchId) {
                        alert('Batch ID bulunamadı!');
                        return;
                    }
                    const url = `/stock/bulk-operations/${batchId}/export-pdf`;
                    window.open(url, '_blank');
                },
                loadRecentBulkOperations() {
                    this.bulkOperationsLoading = true;
                    fetch('/stock/bulk-operations/recent')
                        .then(response => response.json())
                        .then(data => {
                            this.recentBulkOperations = data;
                        })
                        .catch(error => {
                            console.error('Error loading bulk operations:', error);
                        })
                        .finally(() => {
                            this.bulkOperationsLoading = false;
                        });
                },
                loadBulkOperationsPage(page) {
                    if (page < 1 || page > this.recentBulkOperations.last_page) return;

                    this.bulkOperationsLoading = true;
                    fetch(`/stock/bulk-operations/recent?page=${page}`)
                        .then(response => response.json())
                        .then(data => {
                            this.recentBulkOperations = data;
                        })
                        .catch(error => {
                            console.error('Error loading bulk operations page:', error);
                        })
                        .finally(() => {
                            this.bulkOperationsLoading = false;
                        });
                },
                init() {
                    this.loadRecentBulkOperations();
                },
                async submitBulk() {
                    if (!this.confirmed) { alert('Lütfen işlemin geri alınamayacağını onaylayın.'); return; }

                    // Validate that all items have required fields
                    const invalidItems = this.bulkItems.filter(item =>
                        !item.item_id || !item.direction || !item.quantity || item.quantity <= 0
                    );

                    if (invalidItems.length > 0) {
                        alert('Lütfen tüm alanları doldurun ve miktarları 0\'dan büyük girin.');
                        return;
                    }

                    this.processing = true;
                    try {
                        const dataToSend = {
                            items: this.bulkItems,
                            confirm_irreversible: 1 // Laravel expects 1 for 'accepted' validation
                        };

                        console.log('Sending data:', dataToSend);

                        const response = await fetch('/stock/bulk-movements', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                            body: JSON.stringify(dataToSend)
                        });

                        const result = await response.json();
                        console.log('Server response:', result);

                        if (result.success) {
                            alert(result.message);
                            this.bulkItems = [{ item_id: '', direction: 'out', quantity: '', note: '' }];
                            this.confirmed = false;

                            // Show warnings if any
                            if (result.warnings && result.warnings.length > 0) {
                                let warningMessage = 'Uyarılar:\n\n';
                                result.warnings.forEach((warning, index) => {
                                    warningMessage += `${index + 1}. ${warning.message}\n`;
                                });
                                setTimeout(() => alert(warningMessage), 100);
                            }

                            // Reload recent bulk operations
                            this.loadRecentBulkOperations();
                        } else {
                            let errorMessage = 'Hata: ' + (result.message || 'Bilinmeyen hata');

                            if (result.errors && result.errors.length > 0) {
                                console.log('Validation errors:', result.errors);
                                errorMessage += '\n\nHata detayları:';
                                result.errors.forEach((error, index) => {
                                    errorMessage += `\n${index + 1}. ${error.error}`;
                                });
                            }

                            alert(errorMessage);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Bir hata oluştu');
                    } finally {
                        this.processing = false;
                    }
                }
            }));
        });
    </script>
    <div class="py-10" x-data="bulkMovements()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <form @submit.prevent="submitBulk()">
                    <div class="space-y-4 mb-6">
                        <template x-for="(item, index) in bulkItems" :key="index">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 p-4 border border-slate-200 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Stok Kalemi</label>
                                    <select x-model="item.item_id" class="block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Seçin...</option>
                                        @foreach($stockItems as $stockItem)
                                            <option value="{{ $stockItem->id }}">{{ $stockItem->name }} ({{ number_format($stockItem->quantity, 2) }} {{ $stockItem->unit }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">İşlem</label>
                                    <select x-model="item.direction" class="block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="in">Giriş</option>
                                        <option value="out">Çıkış</option>
                                        <option value="adjustment">Düzeltme</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Miktar</label>
                                    <input type="number" x-model="item.quantity" step="0.01" min="0" class="block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Not</label>
                                    <input type="text" x-model="item.note" class="block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-blue-500 focus:border-blue-500" placeholder="Açıklama">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" @click="removeRow(index)" :disabled="bulkItems.length === 1" class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 disabled:bg-slate-400 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-between items-center mb-6">
                        <button type="button" @click="addRow()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Satır Ekle
                        </button>

                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="confirm_irreversible" name="confirm_irreversible" x-model="confirmed" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="confirm_irreversible" class="text-sm text-slate-700 dark:text-slate-300">
                                Bu işlemlerin geri alınamayacağını kabul ediyorum
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('stock.items.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <button type="submit" :disabled="!canSubmit()" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-slate-400 text-white font-medium rounded-lg transition-colors">
                            <span x-show="!processing">İşlemleri Uygula</span>
                            <span x-show="processing" x-cloak>
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                İşleniyor...
                            </span>
                        </button>
                    </div>
                </form>
                    </div>

                    <!-- Recent Bulk Operations Section -->
                    <div class="mt-8 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Son Yapılan Toplu İşlemler</h3>

                        <div x-show="bulkOperationsLoading" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <p class="mt-2 text-slate-600 dark:text-slate-400">Yükleniyor...</p>
                        </div>

                        <div x-show="!bulkOperationsLoading">
                            <div x-show="recentBulkOperations.data && recentBulkOperations.data.length > 0" class="space-y-4">
                                <div x-for="(operation, index) in recentBulkOperations.data" :key="operation.batch_id" x-data="{ currentOperation: operation }" class="border border-slate-200 dark:border-slate-600 rounded-lg p-4 bg-slate-50 dark:bg-slate-700/50">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-medium text-slate-900 dark:text-slate-100">
                                                Toplu İşlem #<span x-text="recentBulkOperations.from + index"></span>
                                            </h4>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                                <span x-text="new Date(operation.batch_created_at).toLocaleString('tr-TR')"></span>
                                            </p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                İşlem ID: <code class="bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded text-xs" x-text="operation.batch_id"></code>
                                            </p>
                                        </div>
                                        <div class="text-right flex flex-col items-end gap-2">
                                            <button @click="exportBulkOperationPDF(currentOperation.batch_id)" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                PDF
                                            </button>
                                            <div>
                                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                                    <span x-text="operation.creator ? operation.creator.name : 'Sistem'"></span>
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                                    <span x-text="operation.movement_count + ' hareket'"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <div x-for="item in operation.items" class="flex justify-between items-center text-sm">
                                            <span class="text-slate-700 dark:text-slate-300" x-text="item.name"></span>
                                            <span class="px-2 py-1 rounded text-xs font-medium"
                                                  :class="item.direction === 'Giriş' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                                         item.direction === 'Çıkış' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                                         'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'">
                                                <span x-text="item.direction + ': ' + item.quantity"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pagination -->
                                <div x-show="recentBulkOperations.last_page > 1" class="flex justify-center mt-6">
                                    <div class="flex space-x-2">
                                        <button @click="loadBulkOperationsPage(recentBulkOperations.current_page - 1)"
                                                :disabled="recentBulkOperations.current_page <= 1"
                                                class="px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                            Önceki
                                        </button>

                                        <span class="px-3 py-2 text-sm text-slate-700 dark:text-slate-300">
                                            <span x-text="`Sayfa ${recentBulkOperations.current_page} / ${recentBulkOperations.last_page}`"></span>
                                        </span>

                                        <button @click="loadBulkOperationsPage(recentBulkOperations.current_page + 1)"
                                                :disabled="recentBulkOperations.current_page >= recentBulkOperations.last_page"
                                                class="px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                            Sonraki
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div x-show="!recentBulkOperations.data || recentBulkOperations.data.length === 0" class="text-center py-8 text-slate-500 dark:text-slate-400">
                                Henüz toplu işlem bulunmuyor.
                            </div>
                        </div>
                    </div>

                    <!-- Recent Movements Section -->
            <div class="mt-8 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Son Hareketler</h3>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select x-model="timeFilter" class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="24h">Son 24 Saat</option>
                            <option value="1w">Son 1 Hafta</option>
                            <option value="1m">Son 1 Ay</option>
                            <option value="6m">Son 6 Ay</option>
                            <option value="1y">Son 1 Yıl</option>
                        </select>
                        <button @click="exportRecentMovementsPDF()" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            PDF Oluştur
                        </button>
                    </div>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Bu rapor dönem bazlı filtrelenebilir. Son 24 saat, son 1 hafta, son 1 ay, son 6 ay, son 1 yıl olarak.</p>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Stok Kalemi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlem</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Miktar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kullanıcı</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">PDF</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @php
                                $recentMovements = \App\Models\Stock\StockMovement::with(['stockItem', 'creator'])
                                    ->latest('created_at')
                                    ->take(20)
                                    ->get();
                            @endphp
                            @forelse($recentMovements as $movement)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                                    {{ $movement->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium text-slate-900 dark:text-slate-100">
                                        {{ $movement->stockItem?->name ?? 'Kalem Silinmiş' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $movement->direction->bgClass() }}">
                                        {{ $movement->direction->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="font-medium {{ $movement->isOutgoing() ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                        {{ $movement->formatted_quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                    {{ $movement->creator?->name ?? 'Sistem' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('stock.movements.export.pdf', ['item_id' => $movement->stock_item_id]) }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                    Henüz hareket bulunmuyor.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Usage Instructions -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6 mb-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Nasıl Kullanılır?</h3>
                        </div>
                        <div class="space-y-3 text-sm text-blue-800 dark:text-blue-200">
                            <div>
                                <strong class="block mb-1">1. Stok Kalemi Seçin</strong>
                                <p>İşlem yapmak istediğiniz stok kalemini listeden seçin.</p>
                            </div>
                            <div>
                                <strong class="block mb-1">2. İşlem Türü Belirleyin</strong>
                                <p>Giriş, Çıkış veya Düzeltme işleminden birini seçin.</p>
                            </div>
                            <div>
                                <strong class="block mb-1">3. Miktar Girin</strong>
                                <p>İşlem miktarını ondalık sayı olarak girin (örn: 1.5, 2.0).</p>
                            </div>
                            <div>
                                <strong class="block mb-1">4. Not Ekleyin</strong>
                                <p>İsteğe bağlı olarak işlem açıklaması ekleyin.</p>
                            </div>
                            <div>
                                <strong class="block mb-1">5. Onaylayın</strong>
                                <p>İşlemin geri alınamayacağını onaylayın ve gönderin.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Warnings -->
                    <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800 p-6 mb-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-amber-900 dark:text-amber-100">Önemli Uyarılar</h3>
                        </div>
                        <div class="space-y-3 text-sm text-amber-800 dark:text-amber-200">
                            <div class="flex items-start">
                                <span class="text-amber-600 mr-2">⚠️</span>
                                <p><strong>Geri Alınamaz:</strong> İşlemler gönderildikten sonra geri alınamaz. Lütfen verilerinizi dikkatlice kontrol edin.</p>
                            </div>
                            <div class="flex items-start">
                                <span class="text-amber-600 mr-2">⚠️</span>
                                <p><strong>Stok Kontrolü:</strong> Çıkış işlemleri için yeterli stok olduğundan emin olun.</p>
                            </div>
                            <div class="flex items-start">
                                <span class="text-amber-600 mr-2">⚠️</span>
                                <p><strong>Toplu İşlem:</strong> Birden fazla kalem için işlem yaparken hepsini aynı anda kontrol edin.</p>
                            </div>
                            <div class="flex items-start">
                                <span class="text-amber-600 mr-2">⚠️</span>
                                <p><strong>Yetkilendirme:</strong> Sadece yetkili kullanıcılar stok hareketleri yapabilir.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Best Practices -->
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800 p-6 mb-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-green-900 dark:text-green-100">En İyi Uygulamalar</h3>
                        </div>
                        <div class="space-y-3 text-sm text-green-800 dark:text-green-200">
                            <div>
                                <strong class="block mb-1">✅ Veri Doğrulama</strong>
                                <p>Göndermeden önce tüm alanları kontrol edin.</p>
                            </div>
                            <div>
                                <strong class="block mb-1">✅ Açıklamalar</strong>
                                <p>Her işlem için açıklayıcı notlar ekleyin.</p>
                            </div>
                            <div>
                                <strong class="block mb-1">✅ Düzenli Kontrol</strong>
                                <p>Son Hareketler bölümünü düzenli olarak inceleyin.</p>
                            </div>
                            <div>
                                <strong class="block mb-1">✅ Yedekleme</strong>
                                <p>Önemli işlemler için PDF raporları alın.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Troubleshooting -->
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Sorun Giderme</h3>
                        </div>
                        <div class="space-y-3 text-sm text-slate-700 dark:text-slate-300">
                            <div>
                                <strong class="block mb-1 text-slate-900 dark:text-slate-100">Gönderim Hatası</strong>
                                <p>Tüm zorunlu alanları doldurun ve miktarın 0'dan büyük olduğundan emin olun.</p>
                            </div>
                            <div>
                                <strong class="block mb-1 text-slate-900 dark:text-slate-100">Stok Yetersiz</strong>
                                <p>Çıkış işlemleri için yeterli stok kontrolü yapın.</p>
                            </div>
                            <div>
                                <strong class="block mb-1 text-slate-900 dark:text-slate-100">Yetkilendirme Hatası</strong>
                                <p>Sadece yetkili kullanıcılar işlem yapabilir.</p>
                            </div>
                            <div>
                                <strong class="block mb-1 text-slate-900 dark:text-slate-100">Sayfa Yenileme</strong>
                                <p>Problemler yaşarsanız sayfayı yenileyin.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>