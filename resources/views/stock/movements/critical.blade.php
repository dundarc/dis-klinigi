@extends('layouts.app')

@section('title', 'Kritik Stoklar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Kritik Stoklar
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Minimum seviye altındaki stok kalemleri
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('stock.items.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-boxes mr-2"></i>
                Tüm Stoklar
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Kritik Ürün Sayısı
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $criticalItems->count() }}
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
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-line text-orange-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Toplam Değer
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                ₺{{ number_format($totalValue, 2) }}
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
                            <i class="fas fa-shopping-cart text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Acil Temin Gereken
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $criticalItems->where('quantity', '<=', 0)->count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Critical Items Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Kritik Stok Listesi
                </h3>
                <div class="flex space-x-2">
                    <select class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Kategori Seç</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <button onclick="exportCriticalStocks()" 
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-download mr-1"></i>
                        Dışa Aktar
                    </button>
                </div>
            </div>

            @if($criticalItems->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ürün
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mevcut Stok
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Minimum Seviye
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Eksik Miktar
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Durumu
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Son Hareket
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">İşlemler</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($criticalItems as $item)
                                @php
                                    $shortage = $item->minimum_quantity - $item->quantity;
                                    $severityClass = $item->quantity <= 0 ? 'bg-red-50 border-red-200' : 
                                                   ($shortage > $item->minimum_quantity * 0.5 ? 'bg-orange-50 border-orange-200' : 'bg-yellow-50 border-yellow-200');
                                @endphp
                                <tr class="{{ $severityClass }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($item->image)
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                         src="{{ Storage::url($item->image) }}" 
                                                         alt="{{ $item->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <i class="fas fa-box text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $item->barcode }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $item->category->name ?? 'Kategori Yok' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ number_format($item->quantity, 2) }} {{ $item->unit }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ number_format($item->minimum_quantity, 2) }} {{ $item->unit }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium {{ $item->quantity <= 0 ? 'text-red-600' : 'text-orange-600' }}">
                                            {{ number_format($shortage, 2) }} {{ $item->unit }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->quantity <= 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Stok Yok
                                            </span>
                                        @elseif($shortage > $item->minimum_quantity * 0.5)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Kritik Seviye
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-warning mr-1"></i>
                                                Düşük Seviye
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($item->lastMovement)
                                            <div class="flex items-center">
                                                <i class="fas fa-{{ $item->lastMovement->direction->value == 'in' ? 'arrow-up text-green-500' : 'arrow-down text-red-500' }} mr-1"></i>
                                                {{ $item->lastMovement->created_at->diffForHumans() }}
                                            </div>
                                        @else
                                            <span class="text-gray-400">Hareket Yok</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('stock.movements.item-history', $item) }}" 
                                               class="text-blue-600 hover:text-blue-900" 
                                               title="Hareket Geçmişi">
                                                <i class="fas fa-history"></i>
                                            </a>
                                            <a href="{{ route('stock.items.edit', $item) }}" 
                                               class="text-indigo-600 hover:text-indigo-900" 
                                               title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('stock.movements.create-adjustment', ['item' => $item->id]) }}" 
                                               class="text-green-600 hover:text-green-900" 
                                               title="Stok Düzeltmesi">
                                                <i class="fas fa-plus-circle"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $criticalItems->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <i class="fas fa-check-circle text-4xl"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Kritik stok bulunmuyor</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Tüm ürünlerin stok seviyeleri minimum değerlerin üzerinde.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('stock.items.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-boxes mr-2"></i>
                            Tüm Stokları Görüntüle
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportCriticalStocks() {
    // Create CSV content
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Ürün Adı,Kategori,Mevcut Stok,Minimum Seviye,Eksik Miktar,Durumu\n";
    
    // Add table data
    @foreach($criticalItems as $item)
        @php
            $shortage = $item->minimum_quantity - $item->quantity;
            $status = $item->quantity <= 0 ? 'Stok Yok' : 
                     ($shortage > $item->minimum_quantity * 0.5 ? 'Kritik Seviye' : 'Düşük Seviye');
        @endphp
        csvContent += "{{ $item->name }},{{ $item->category->name ?? 'Kategori Yok' }},{{ number_format($item->quantity, 2) }} {{ $item->unit }},{{ number_format($item->minimum_quantity, 2) }} {{ $item->unit }},{{ number_format($shortage, 2) }} {{ $item->unit }},{{ $status }}\n";
    @endforeach
    
    // Create and trigger download
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "kritik_stoklar_" + new Date().toISOString().slice(0, 10) + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Auto-refresh functionality
document.addEventListener('DOMContentLoaded', function() {
    // Refresh every 5 minutes
    setInterval(() => {
        window.location.reload();
    }, 300000);
});
</script>
@endpush
@endsection