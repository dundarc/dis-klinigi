<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Hareketleri Raporu</title>
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
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2563eb;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filters {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
        }
        .filters p {
            margin: 2px 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        .incoming {
            color: #059669;
            font-weight: bold;
        }
        .outgoing {
            color: #dc2626;
            font-weight: bold;
        }
        .adjustment {
            color: #7c3aed;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-incoming {
            background: #dcfce7;
            color: #166534;
        }
        .badge-outgoing {
            background: #fef2f2;
            color: #991b1b;
        }
        .badge-adjustment {
            background: #f3e8ff;
            color: #6b21a8;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            text-align: center;
        }
        .summary-item strong {
            display: block;
            font-size: 18px;
            color: #2563eb;
        }
        .summary-item span {
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stok Hareketleri Raporu</h1>
        <p>Oluşturulma Tarihi: {{ now()->format('d.m.Y H:i') }}</p>
        <p>Rapor Dönemi: {{ $filters['start_date'] ?? 'Tümü' }} - {{ $filters['end_date'] ?? 'Tümü' }}</p>
    </div>

    @if($filters)
    <div class="filters">
        <h3>Uygulanan Filtreler:</h3>
        @if(isset($filters['direction']))
            <p><strong>İşlem Türü:</strong> {{ \App\Enums\MovementDirection::from($filters['direction'])->label() }}</p>
        @endif
        @if(isset($filters['item_id']))
            <p><strong>Stok Kalemi:</strong> {{ \App\Models\Stock\StockItem::find($filters['item_id'])->name ?? 'Bilinmiyor' }}</p>
        @endif
        @if(isset($filters['user_id']))
            <p><strong>Kullanıcı:</strong> {{ \App\Models\User::find($filters['user_id'])->name ?? 'Bilinmiyor' }}</p>
        @endif
        @if(isset($filters['reference_type']))
            <p><strong>Referans Türü:</strong> {{ $filters['reference_type'] }}</p>
        @endif
    </div>
    @endif

    <div class="summary">
        <h3>Rapor Özeti</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <strong>{{ $movements->count() }}</strong>
                <span>Toplam Hareket</span>
            </div>
            <div class="summary-item">
                <strong>{{ $movements->where('direction.value', 'in')->sum('quantity') }}</strong>
                <span>Toplam Giriş</span>
            </div>
            <div class="summary-item">
                <strong>{{ $movements->where('direction.value', 'out')->sum('quantity') }}</strong>
                <span>Toplam Çıkış</span>
            </div>
            <div class="summary-item">
                <strong>{{ $movements->where('direction.value', 'adjustment')->count() }}</strong>
                <span>Düzeltme Sayısı</span>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Stok Kalemi</th>
                <th>İşlem</th>
                <th>Miktar</th>
                <th>Referans</th>
                <th>Kullanıcı</th>
                <th>Not</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movements as $movement)
            <tr>
                <td>{{ $movement->created_at->format('d.m.Y H:i') }}</td>
                <td>{{ $movement->stockItem->name }}</td>
                <td>
                    <span class="badge badge-{{ $movement->direction->value }}">
                        {{ $movement->direction->label() }}
                    </span>
                </td>
                <td class="text-right">
                    @if($movement->direction->value === 'in')
                        <span class="incoming">+{{ number_format($movement->quantity, 2) }}</span>
                    @elseif($movement->direction->value === 'out')
                        <span class="outgoing">-{{ number_format($movement->quantity, 2) }}</span>
                    @else
                        <span class="adjustment">{{ number_format($movement->quantity, 2) }}</span>
                    @endif
                </td>
                <td>{{ $movement->reference_display }}</td>
                <td>{{ $movement->creator?->name ?? '-' }}</td>
                <td>{{ $movement->note ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Hareket bulunmuyor.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Bu rapor {{ now()->format('d.m.Y H:i:s') }} tarihinde oluşturulmuştur.</p>
        <p>KYS - Stok Yönetim Sistemi</p>
    </div>
</body>
</html>