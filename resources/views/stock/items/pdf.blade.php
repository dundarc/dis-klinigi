<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Kalemleri Raporu</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filters {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
        }
        .filters p {
            margin: 2px 0;
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            font-size: 10px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stok Kalemleri Raporu</h1>
        <p>Oluşturulma Tarihi: {{ $generated_at }}</p>
        <p>Toplam Kayıt: {{ $items->count() }}</p>
    </div>

    @if($filters['q'] || $filters['category'] || $filters['status'])
    <div class="filters">
        <h3>Uygulanan Filtreler:</h3>
        @if($filters['q'])
        <p><strong>Arama:</strong> {{ $filters['q'] }}</p>
        @endif
        @if($filters['category'])
        <p><strong>Kategori:</strong> {{ $filters['category'] }}</p>
        @endif
        @if($filters['status'])
        <p><strong>Durum:</strong> {{ $filters['status'] }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Stok Adı</th>
                <th>SKU Kodu</th>
                <th>Mevcut Stok</th>
                <th>Birim</th>
                <th>Minimum Stok</th>
                <th>Kategori</th>
                <th>Durum</th>
                <th>Oluşturulma Tarihi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->sku ?: '-' }}</td>
                <td class="text-right">{{ number_format($item->quantity, 2, ',', '.') }}</td>
                <td>{{ $item->unit }}</td>
                <td class="text-right">{{ number_format($item->minimum_quantity, 2, ',', '.') }}</td>
                <td>{{ $item->category?->name ?? 'Kategori Yok' }}</td>
                <td>
                    <span class="status-badge {{ $item->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $item->is_active ? 'Aktif' : 'Pasif' }}
                    </span>
                </td>
                <td>{{ $item->created_at->format('d.m.Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Bu rapor {{ $generated_at }} tarihinde oluşturulmuştur.</p>
        <p>Diş Hekimi Klinik Yönetim Sistemi</p>
    </div>
</body>
</html>