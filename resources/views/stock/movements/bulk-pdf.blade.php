<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toplu Stok İşlemi - {{ $batchInfo['batch_id'] }}</title>
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
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin: 0 0 10px 0;
        }

        .header .batch-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .batch-info-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .batch-info-row {
            display: table-row;
        }

        .batch-info-cell {
            display: table-cell;
            padding: 5px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .batch-info-label {
            font-weight: bold;
            width: 150px;
            color: #64748b;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            text-align: left;
            vertical-align: top;
        }

        .table th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #374151;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .direction-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .direction-in {
            background-color: #dcfce7;
            color: #166534;
        }

        .direction-out {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .direction-adjustment {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }

        .summary {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            color: #2563eb;
            font-size: 16px;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-row {
            display: table-row;
        }

        .summary-cell {
            display: table-cell;
            padding: 5px 10px;
        }

        .summary-label {
            font-weight: bold;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Toplu Stok İşlemi Raporu</h1>
        <p>İşlem ID: {{ $batchInfo['batch_id'] }}</p>

        <div class="batch-info">
            <div class="batch-info-grid">
                <div class="batch-info-row">
                    <div class="batch-info-cell batch-info-label">Oluşturulma Tarihi:</div>
                    <div class="batch-info-cell">{{ $batchInfo['created_at']->format('d.m.Y H:i:s') }}</div>
                </div>
                <div class="batch-info-row">
                    <div class="batch-info-cell batch-info-label">Oluşturan:</div>
                    <div class="batch-info-cell">{{ $batchInfo['creator'] ? $batchInfo['creator']->name : 'Sistem' }}</div>
                </div>
                <div class="batch-info-row">
                    <div class="batch-info-cell batch-info-label">Toplam Hareket:</div>
                    <div class="batch-info-cell">{{ $batchInfo['total_movements'] }} adet</div>
                </div>
                <div class="batch-info-row">
                    <div class="batch-info-cell batch-info-label">Toplam Miktar:</div>
                    <div class="batch-info-cell">{{ number_format($batchInfo['total_quantity'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="summary">
        <h3>İşlem Özeti</h3>
        <div class="summary-grid">
            @php
                $directionCounts = $movements->groupBy('direction.value')->map->count();
                $directionQuantities = $movements->groupBy('direction.value')->map(function($group) {
                    return $group->sum('quantity');
                });
            @endphp

            @foreach($directionCounts as $direction => $count)
            <div class="summary-row">
                <div class="summary-cell summary-label">{{ \App\Enums\MovementDirection::from($direction)->label() }}:</div>
                <div class="summary-cell">{{ $count }} hareket ({{ number_format($directionQuantities[$direction], 2) }} birim)</div>
            </div>
            @endforeach
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Stok Kalemi</th>
                <th>Kategori</th>
                <th>İşlem</th>
                <th>Miktar</th>
                <th>Birim</th>
                <th>Not</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $movement)
            <tr>
                <td>{{ $movement->created_at->format('d.m.Y H:i:s') }}</td>
                <td>
                    <strong>{{ $movement->stockItem->name }}</strong>
                    @if($movement->stockItem->sku)
                    <br><small style="color: #6b7280;">SKU: {{ $movement->stockItem->sku }}</small>
                    @endif
                </td>
                <td>{{ $movement->stockItem->category->name ?? 'Kategorisiz' }}</td>
                <td>
                    <span class="direction-badge direction-{{ strtolower($movement->direction->value) }}">
                        {{ $movement->direction->label() }}
                    </span>
                </td>
                <td style="font-weight: bold; {{ $movement->isOutgoing() ? 'color: #dc2626;' : 'color: #16a34a;' }}">
                    {{ $movement->isOutgoing() ? '-' : '+' }}{{ number_format($movement->quantity, 2) }}
                </td>
                <td>{{ $movement->stockItem->unit }}</td>
                <td>{{ $movement->note ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Bu rapor {{ now()->format('d.m.Y H:i:s') }} tarihinde oluşturulmuştur.</p>
        <p>Toplu Stok İşlemi - {{ $batchInfo['batch_id'] }}</p>
    </div>
</body>
</html>