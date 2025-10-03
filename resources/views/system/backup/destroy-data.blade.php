<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-red-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Verileri Sil - Onay
                </h2>
            </div>
            <a href="{{ route('system.backup') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                İptal Et
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Warning Alert --}}
            <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-2">
                            🚨 KRİTİK UYARI
                        </h3>
                        <div class="text-sm text-red-700 dark:text-red-300 space-y-2">
                            <p><strong>Bu işlem geri alınamaz!</strong> Aşağıda listelenen tüm veriler kalıcı olarak silinecektir.</p>
                            <p><strong>Önemli:</strong> Bu işlemden önce mutlaka bir yedek alın. Sistem yedeği olmadan verilerinizi geri getiremezsiniz.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Summary --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Silinecek Verilerin Özeti</h3>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $totalRecords = array_sum($recordCounts);
                            $tableCategories = [
                                'patients' => ['patients', 'patient_kvkk', 'consents', 'kvkk_audit_logs'],
                                'appointments' => ['appointments', 'treatment_plan_items', 'treatment_plan_item_appointments', 'treatment_plan_item_history'],
                                'treatments' => ['patient_treatments', 'treatment_plans'],
                                'invoices' => ['invoices', 'invoice_items', 'payments'],
                                'stock' => ['stock_items', 'stock_movements', 'stock_purchases', 'stock_expenses', 'stock_expense_categories', 'stock_suppliers', 'stock_categories'],
                                'files' => ['files', 'patient_xrays'],
                                'other' => ['notifications', 'prescriptions', 'service_expenses', 'user_unavailabilities', 'working_hours']
                            ];
                        @endphp

                        {{-- Summary Stats --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ count($tablesToDelete) }}</div>
                                <div class="text-sm text-red-600 dark:text-red-400">Tablo</div>
                            </div>
                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                                <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($totalRecords) }}</div>
                                <div class="text-sm text-orange-600 dark:text-orange-400">Kayıt</div>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ count(array_filter($recordCounts, fn($count) => $count > 0)) }}</div>
                                <div class="text-sm text-blue-600 dark:text-blue-400">Dolu Tablo</div>
                            </div>
                        </div>

                        {{-- Detailed Table List --}}
                        <div class="space-y-4">
                            @foreach($tableCategories as $category => $tables)
                                @php
                                    $categoryTables = array_intersect($tablesToDelete, $tables);
                                    $categoryRecords = array_sum(array_intersect_key($recordCounts, array_flip($categoryTables)));
                                @endphp

                                @if(!empty($categoryTables))
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3 capitalize">
                                        {{ $category === 'patients' ? 'Hasta Bilgileri' :
                                           ($category === 'appointments' ? 'Randevu Bilgileri' :
                                           ($category === 'treatments' ? 'Tedavi Bilgileri' :
                                           ($category === 'invoices' ? 'Fatura Bilgileri' :
                                           ($category === 'stock' ? 'Stok Bilgileri' :
                                           ($category === 'files' ? 'Dosya Bilgileri' : 'Diğer'))))) }}
                                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                            ({{ count($categoryTables) }} tablo, {{ number_format($categoryRecords) }} kayıt)
                                        </span>
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                        @foreach($categoryTables as $table)
                                            <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-800 px-3 py-2 rounded text-sm">
                                                <span class="text-gray-700 dark:text-gray-300">{{ $table }}</span>
                                                <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-xs font-medium">
                                                    {{ number_format($recordCounts[$table] ?? 0) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('system.backup') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    İptal Et
                </a>
                <form method="POST" action="{{ route('system.backup.destroy-data.confirm') }}" class="inline-block" onsubmit="return confirmDeletion()">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Tüm Verileri Sil
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDeletion() {
            const totalRecords = {{ $totalRecords }};
            const totalTables = {{ count($tablesToDelete) }};

            return confirm(`🚨 SON UYARI: Bu işlem geri alınamaz!\n\n• ${totalTables} tablo silinecek\n• ${totalRecords.toLocaleString('tr-TR')} kayıt kalıcı olarak kaybedilecek\n\nBu işlemden emin misiniz?`);
        }
    </script>
</x-app-layout>