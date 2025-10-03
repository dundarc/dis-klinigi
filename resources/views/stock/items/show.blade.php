<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Stok Hareketleri</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $item->name }} - {{ $item->sku ?? 'SKU Yok' }}</p>
            </div>
            <a href="{{ route('stock.items.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Listeye Dön
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Stok Kalemi Özeti -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Stok Adı</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->name }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">SKU</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->sku ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Mevcut Stok</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100" id="current-stock">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Kategori</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->category?->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Hareket Ekle -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Hareket Ekle</h3>
                <form id="addMovementForm" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="direction" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Hareket Türü</label>
                            <select id="direction" name="direction" class="block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="in">Giriş</option>
                                <option value="out">Çıkış</option>
                                <option value="adjustment">Düzeltme</option>
                            </select>
                        </div>
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Miktar</label>
                            <input type="number" id="quantity" name="quantity" step="0.01" min="0" class="block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        <div>
                            <label for="note" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Not</label>
                            <input type="text" id="note" name="note" class="block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Hareket açıklaması">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Hareket Ekle
                        </button>
                    </div>
                </form>
            </div>

            <!-- Hareket Geçmişi -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Hareket Geçmişi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlem</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Miktar</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Referans</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kullanıcı</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Not</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($movements as $movement)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-4 text-slate-900 dark:text-slate-100">{{ $movement->created_at->format('d.m.Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full {{ $movement->direction->bgClass() }} px-2.5 py-1 text-xs font-medium">
                                            {{ $movement->direction->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-900 dark:text-slate-100 font-medium">
                                        @if($movement->direction->value === 'in')
                                            <span class="text-green-600 dark:text-green-400">+{{ number_format($movement->quantity, 2) }}</span>
                                        @elseif($movement->direction->value === 'out')
                                            <span class="text-red-600 dark:text-red-400">-{{ number_format($movement->quantity, 2) }}</span>
                                        @else
                                            <span class="text-blue-600 dark:text-blue-400">{{ number_format($movement->quantity, 2) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                        {{ $movement->reference_display }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $movement->creator?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $movement->note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Hareket bulunmuyor</h3>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Bu stok kalemi için henüz hareket kaydı yok.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 px-6 pb-6">
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Movement form handling
            const movementForm = document.getElementById('addMovementForm');
            if (movementForm) {
                movementForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;

                    // Disable button and show loading
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Yükleniyor...';

                    fetch(`{{ route('stock.items.add-movement', $item) }}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update current stock display
                            document.getElementById('current-stock').textContent = data.new_quantity + ' {{ $item->unit }}';

                            // Reset form
                            movementForm.reset();

                            // Show success message
                            showNotification('Hareket başarıyla eklendi.', 'success');

                            // Reload the page to update movements list
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showNotification(data.message || 'Bir hata oluştu.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Bir hata oluştu.', 'error');
                    })
                    .finally(() => {
                        // Re-enable button
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    });
                });
            }

            function showNotification(message, type = 'info') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
                    type === 'success' ? 'bg-green-500 text-white' :
                    type === 'error' ? 'bg-red-500 text-white' :
                    'bg-blue-500 text-white'
                }`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                                type === 'success' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' :
                                type === 'error' ? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' :
                                'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                            }"></path>
                        </svg>
                        <span>${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                // Remove after 3 seconds
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        });
    </script>
</x-app-layout>