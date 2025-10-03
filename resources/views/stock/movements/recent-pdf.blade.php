<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Son Hareketler - {{ $filterLabel }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        .info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8fafc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #374151;
            font-size: 9px;
        }
        td {
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .incoming {
            color: #059669;
        }
        .outgoing {
            color: #dc2626;
        }
        .adjustment {
            color: #7c3aed;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            font-style: italic;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stok Hareket Raporu</h1>
        <p>{{ $filterLabel }} - Oluşturulma Tarihi: {{ now()->format('d.m.Y H:i') }}</p>
    </div>

    <div class="info">
        <strong>Rapor Bilgileri:</strong><br>
        Dönem: {{ $filterLabel }}<br>
        Toplam Hareket: {{ $movements->count() }}<br>
        Oluşturulma Tarihi: {{ now()->format('d.m.Y H:i:s') }}
    </div>

    @if($movements->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Tarih</th>
                    <th style="width: 25%;">Stok Kalemi</th>
                    <th style="width: 10%;">İşlem</th>
                    <th style="width: 12%;">Miktar</th>
                    <th style="width: 15%;">Kullanıcı</th>
                    <th style="width: 30%;">Not</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movements as $movement)
                <tr>
                    <td>{{ $movement->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <strong>{{ $movement->stockItem?->name ?? 'Kalem Silinmiş' }}</strong>
                        @if($movement->stockItem?->sku)
                            <br><small style="color: #6b7280;">SKU: {{ $movement->stockItem->sku }}</small>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="{{ $movement->direction->value === 'in' ? 'incoming' : ($movement->direction->value === 'out' ? 'outgoing' : 'adjustment') }}">
                            {{ $movement->direction->label() }}
                        </span>
                    </td>
                    <td class="text-right">
                        <span class="{{ $movement->direction->value === 'in' ? 'incoming' : ($movement->direction->value === 'out' ? 'outgoing' : 'adjustment') }}">
                            {{ $movement->direction->value === 'out' ? '-' : '' }}{{ number_format($movement->quantity, 2) }}
                            {{ $movement->stockItem?->unit ?? '' }}
                        </span>
                    </td>
                    <td>{{ $movement->creator?->name ?? 'Sistem' }}</td>
                    <td>{{ $movement->note ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            Seçilen dönemde hiç hareket bulunamadı.
        </div>
    @endif

    <div class="footer">
        Bu rapor {{ config('app.name') }} sistemi tarafından otomatik olarak oluşturulmuştur.
    </div>
</body>
</html>