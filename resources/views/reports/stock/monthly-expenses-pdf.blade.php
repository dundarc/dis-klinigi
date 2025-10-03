<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aylık Gider Raporu - PDF</title>
    <style>
        @page {
            margin: 1cm;
            size: A4;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin: 0 0 5px 0;
        }

        .header p {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
        }

        .summary-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .summary-card {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            vertical-align: top;
        }

        .summary-card h3 {
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            margin: 0 0 8px 0;
        }

        .summary-amount {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin: 0;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin: 20px 0 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 9px;
            color: #374151;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .amount-cell {
            font-weight: bold;
            color: #1e40af;
        }

        .category-badge {
            display: inline-block;
            padding: 2px 6px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
        }

        .page-break {
            page-break-before: always;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Aylık Gider Raporu</h1>
        <p>{{ $period['start_formatted'] }} - {{ $period['end_formatted'] }} dönemi</p>
        <p>Oluşturulma tarihi: {{ now()->format('d.m.Y H:i') }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-section">
        <div class="summary-card">
            <h3>Toplam Stok Gideri</h3>
            <p class="summary-amount">{{ number_format($stockTotal, 2, ',', '.') }} TL</p>
        </div>
        <div class="summary-card">
            <h3>Toplam Hizmet Gideri</h3>
            <p class="summary-amount">{{ number_format($serviceTotal, 2, ',', '.') }} TL</p>
        </div>
        <div class="summary-card">
            <h3>Genel Toplam</h3>
            <p class="summary-amount">{{ number_format($totalAmount, 2, ',', '.') }} TL</p>
        </div>
    </div>

    <!-- Category Breakdown -->
    @if($categoryBreakdown->count() > 0)
    <h2 class="section-title">Kategori Dağılımı</h2>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Tür</th>
                <th class="text-center">İşlem Sayısı</th>
                <th class="text-right">Toplam Tutar</th>
                <th class="text-right">Ortalama</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryBreakdown as $category)
            <tr>
                <td>{{ $category['category'] }}</td>
                <td>
                    <span class="category-badge">{{ $category['type'] === 'stock' ? 'Stok' : 'Hizmet' }}</span>
                </td>
                <td class="text-center">{{ $category['count'] }} adet</td>
                <td class="text-right amount-cell">{{ number_format($category['total'], 2, ',', '.') }} TL</td>
                <td class="text-right">{{ $category['count'] > 0 ? number_format($category['total'] / $category['count'], 2, ',', '.') : 0 }} TL</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Stock Expenses -->
    @if($stockExpenses->count() > 0)
    <h2 class="section-title">Stok Giderleri</h2>
    <table>
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Başlık</th>
                <th>Kategori</th>
                <th>Tedarikçi</th>
                <th class="text-right">Tutar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockExpenses as $expense)
            <tr>
                <td>{{ optional($expense->expense_date)->format('d.m.Y') }}</td>
                <td>{{ $expense->title }}</td>
                <td>{{ $expense->category?->name ?? 'Kategorisiz' }}</td>
                <td>{{ $expense->supplier?->name ?? '-' }}</td>
                <td class="text-right amount-cell">{{ number_format($expense->total_amount, 2, ',', '.') }} TL</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Toplam:</strong></td>
                <td class="text-right amount-cell"><strong>{{ number_format($stockTotal, 2, ',', '.') }} TL</strong></td>
            </tr>
        </tfoot>
    </table>
    @endif

    <!-- Service Expenses -->
    @if($serviceExpenses->count() > 0)
    <h2 class="section-title">Hizmet Giderleri</h2>
    <table>
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Hizmet Türü</th>
                <th>Açıklama</th>
                <th class="text-right">Tutar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($serviceExpenses as $expense)
            <tr>
                <td>{{ optional($expense->invoice_date)->format('d.m.Y') }}</td>
                <td>{{ $expense->service_type }}</td>
                <td>{{ $expense->description ?? '-' }}</td>
                <td class="text-right amount-cell">{{ number_format($expense->amount, 2, ',', '.') }} TL</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Toplam:</strong></td>
                <td class="text-right amount-cell"><strong>{{ number_format($serviceTotal, 2, ',', '.') }} TL</strong></td>
            </tr>
        </tfoot>
    </table>
    @endif

    <!-- No Data Message -->
    @if($stockExpenses->count() == 0 && $serviceExpenses->count() == 0)
    <div class="no-data">
        <p>Seçilen tarih aralığında gider verisi bulunmamaktadır.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Bu rapor {{ now()->format('d.m.Y H:i:s') }} tarihinde oluşturulmuştur.</p>
        <p>Dis Hekimi Klinik Yönetim Sistemi</p>
    </div>
</body>
</html>