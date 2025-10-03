@extends('layouts.app')

@section('title', $item->name . ' - Hareket Geçmişi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('stock.items.index') }}" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-boxes"></i>
                            <span class="sr-only">Stoklar</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                            <a href="{{ route('stock.movements.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                                Hareketler
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                            <span class="text-sm font-medium text-gray-500">{{ $item->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                {{ $item->name }} - Hareket Geçmişi
            </h2>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 space-x-2">
            <a href="{{ route('stock.movements.create-adjustment', ['item' => $item->id]) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-plus mr-2"></i>
                Stok Düzeltmesi
            </a>
            <a href="{{ route('stock.movements.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Tüm Hareketler
            </a>
        </div>
    </div>

    <!-- Item Info Card -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    @if($item->image)
                        <img class="h-16 w-16 rounded-lg object-cover" 
                             src="{{ Storage::url($item->image) }}" 
                             alt="{{ $item->name }}">
                    @else
                        <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-box text-gray-400 text-xl"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-medium text-gray-900">{{ $item->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $item->barcode ?? 'Barkod Yok' }}</p>
                    @if($item->category)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                            {{ $item->category->name }}
                        </span>
                    @endif
                </div>
                <div class="flex-shrink-0">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Mevcut Stok</dt>
                            <dd class="mt-1 text-xl font-semibold {{ $item->quantity <= 0 ? 'text-red-600' : ($item->quantity <= ($item->minimum_quantity ?? 0) ? 'text-orange-600' : 'text-gray-900') }}">
                                {{ number_format($item->quantity, 2) }} {{ $item->unit }}
                            </dd>
                        </div>
                        @if($item->minimum_quantity)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Minimum Seviye</dt>
                                <dd class="mt-1 text-xl font-semibold text-gray-900">
                                    {{ number_format($item->minimum_quantity, 2) }} {{ $item->unit }}
                                </dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Toplam Hareket</dt>
                            <dd class="mt-1 text-xl font-semibold text-gray-900">
                                {{ $movements->total() }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-arrow-up text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Toplam Giriş
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ number_format($statistics['total_in'], 2) }} {{ $item->unit }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-arrow-down text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Toplam Çıkış
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ number_format($statistics['total_out'], 2) }} {{ $item->unit }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-edit text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Düzeltmeler
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $statistics['total_adjustments'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Son Hareket
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $statistics['last_movement'] ? $statistics['last_movement']->diffForHumans() : 'Hareket Yok' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="direction" class="block text-sm font-medium text-gray-700 mb-1">Hareket Türü</label>
                    <select name="direction" id="direction" 
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Tümü</option>
                        <option value="in" {{ request('direction') == 'in' ? 'selected' : '' }}>Giriş</option>
                        <option value="out" {{ request('direction') == 'out' ? 'selected' : '' }}>Çıkış</option>
                        <option value="adjustment" {{ request('direction') == 'adjustment' ? 'selected' : '' }}>Düzeltme</option>
                    </select>
                </div>

                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı</label>
                    <select name="user_id" id="user_id" 
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Tümü</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Başlangıç</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Bitiş</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>
                        Filtrele
                    </button>
                    <a href="{{ route('stock.movements.item-history', $item) }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            @if($movements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tarih
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hareket
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Miktar
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kalan Stok
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kullanıcı
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Referans
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Açıklama
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($movements as $movement)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>
                                            {{ $movement->movement_date ? $movement->movement_date->format('d/m/Y H:i') : $movement->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $movement->movement_date ? $movement->movement_date->diffForHumans() : $movement->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $movement->direction->getColor() }}">
                                            <i class="fas fa-{{ $movement->direction->getIcon() }} mr-1"></i>
                                            {{ $movement->direction->getLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium {{ $movement->direction->value == 'in' ? 'text-green-600' : ($movement->direction->value == 'out' ? 'text-red-600' : 'text-blue-600') }}">
                                            {{ $movement->direction->value == 'in' ? '+' : ($movement->direction->value == 'out' ? '-' : '±') }}{{ number_format($movement->quantity, 2) }} {{ $item->unit }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($movement->remaining_stock ?? 0, 2) }} {{ $item->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-gray-700">
                                                        {{ substr($movement->creator->name ?? 'S', 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $movement->creator->name ?? 'Sistem' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if($movement->reference_type && $movement->reference_id)
                                            <div class="flex items-center">
                                                @switch($movement->reference_type)
                                                    @case('App\Models\Stock\StockPurchaseInvoice')
                                                        <i class="fas fa-file-invoice text-blue-500 mr-1"></i>
                                                        <span>Fatura #{{ $movement->reference_id }}</span>
                                                        @break
                                                    @case('App\Models\Patient\StockUsage')
                                                        <i class="fas fa-user-injured text-green-500 mr-1"></i>
                                                        <span>Tedavi #{{ $movement->reference_id }}</span>
                                                        @break
                                                    @default
                                                        <i class="fas fa-edit text-gray-500 mr-1"></i>
                                                        <span>Manuel</span>
                                                @endswitch
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="max-w-xs truncate" title="{{ $movement->notes }}">
                                            {{ $movement->notes ?: '-' }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $movements->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <i class="fas fa-history text-4xl"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Hareket kaydı bulunamadı</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Bu ürün için henüz hareket kaydı bulunmuyor.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('stock.movements.create-adjustment', ['item' => $item->id]) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-plus mr-2"></i>
                            İlk Düzeltmeyi Yap
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh functionality
    let refreshInterval;
    
    function startAutoRefresh() {
        refreshInterval = setInterval(() => {
            // Check if user is actively interacting
            if (document.visibilityState === 'visible') {
                window.location.reload();
            }
        }, 300000); // 5 minutes
    }
    
    function stopAutoRefresh() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }
    
    // Start auto-refresh
    startAutoRefresh();
    
    // Stop refresh when page is not visible
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            startAutoRefresh();
        } else {
            stopAutoRefresh();
        }
    });
    
    // Stop refresh when user is interacting with filters
    document.querySelectorAll('select, input').forEach(element => {
        element.addEventListener('focus', stopAutoRefresh);
        element.addEventListener('blur', () => {
            setTimeout(startAutoRefresh, 5000); // Resume after 5 seconds
        });
    });
});
</script>
@endpush
@endsection