<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Tedarikçi Raporu</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Analiz etmek istediğiniz tedarikçiyi seçin</p>
            </div>
            <a href="{{ route('reports.stock') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Ana Rapor
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Tedarikçi Seçimi</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Detaylı rapor için bir tedarikçi seçin</p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($suppliers as $supplier)
                            <a href="{{ route('reports.stock.supplier-report', ['supplier_id' => $supplier->id]) }}"
                               class="block bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg p-4 transition-colors border border-slate-200 dark:border-slate-600">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-slate-900 dark:text-slate-100">{{ $supplier->name }}</h4>
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                                <div class="space-y-1 text-sm text-slate-600 dark:text-slate-400">
                                    <p>Telefon: {{ $supplier->phone ?? '-' }}</p>
                                    <p>E-posta: {{ $supplier->email ?? '-' }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if($suppliers->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Tedarikçi bulunmuyor</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Henüz hiç tedarikçi eklenmemiş.</p>
                            <a href="{{ route('stock.suppliers.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                                İlk Tedarikçi Ekle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>