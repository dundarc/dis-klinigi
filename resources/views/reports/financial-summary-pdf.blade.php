<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finansal Özet Raporu</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1, h2, h3 { margin-bottom: 5px; }
        .summary-card { border: 1px solid #eee; padding: 10px; margin-bottom: 10px; display: inline-block; width: 30%; margin-right: 2%; box-sizing: border-box; }
        .summary-card:last-child { margin-right: 0; }
        .header { text-align: center; margin-bottom: 20px; }
        .date-range { text-align: center; margin-bottom: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Finansal Özet ve Gelir Raporu</h1>
        <p>Rapor Tarihi: {{ \Carbon\Carbon::now()->format('d.m.Y H:i') }}</p>
    </div>

    <div class="date-range">
        <h3>Rapor Dönemi: {{ $startDate }} - {{ $endDate }}</h3>
    </div>

    @isset($summary)
    <h2>Özet Bilgiler</h2>
    <table>
        <thead>
            <tr>
                <th>Toplam Ciro</th>
                <th>Tahsil Edilen Tutar</th>
                <th>Sigortadan Beklenen</th>
                <th>Vadeli Alacaklar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ number_format($summary['total_revenue'], 2) }} TL</td>
                <td>{{ number_format($summary['collected_amount'], 2) }} TL</td>
                <td>{{ number_format($summary['insurance_pending'], 2) }} TL</td>
                <td>{{ number_format($summary['postponed_amount'], 2) }} TL</td>
            </tr>
        </tbody>
    </table>
    @endisset

    @isset($dailyBreakdown)
    <h2>Günlük Gelir Dökümü</h2>
    <table>
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Tahsil Edilen Tutar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dailyBreakdown as $entry)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($entry->date)->format('d.m.Y') }}</td>
                    <td>{{ number_format($entry->total, 2) }} TL</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Seçilen aralıkta veri bulunamadı.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @endisset
</body>
</html>
