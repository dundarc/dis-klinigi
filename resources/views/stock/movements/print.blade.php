<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Hareketleri Raporu - Yazdır</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
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
            color: #008000;
            font-weight: bold;
        }
        .outgoing {
            color: #ff0000;
            font-weight: bold;
        }
        .adjustment {
            color: #800080;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #000;
            font-size: 9px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stok Hareketleri Raporu</h1>
        <p>Oluşturulma Tarihi: {{ now()->format('d.m.Y H:i') }}</p>
        <p>Rapor Dönemi: {{ $filters['start_date'] ?? 'Tümü' }} - {{ $filters['end_date'] ?? 'Tümü' }}</p>
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
                    <span class="badge">{{ $movement->direction->label() }}</span>
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

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>