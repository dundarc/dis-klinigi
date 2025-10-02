<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fatura - {{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; }
        .header, .footer { text-align: center; }
        .content { margin-top: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #f2f2f2; text-align: left; }
        .text-right { text-align: right; }
        .totals { float: right; width: 40%; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- <img src="{{ public_path('logo.png') }}" alt="Logo"> --}}
            <h1>MAKBUZ</h1>
                <p>Buradaki MAKBUZ resmi bir fatura bilgisi icermez. Resmi fatura icin lutfen klinik ile iletisim kurun.</p>

        </div>

        <table style="width:100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 50%;">
                    <strong>Hasta Bilgileri:</strong><br>
                    {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}<br>
                    {{ $invoice->patient->phone_primary }}<br>
                    {{ $invoice->patient->email }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Makbuz No:</strong> {{ $invoice->invoice_no }}<br>
                    <strong>Tarih:</strong> {{ $invoice->issue_date->format('d.m.Y') }}<br>
                    <strong>Durum:</strong> {{ $invoice->status->value }}
                </td>
            </tr>
        </table>

        <div class="content">
            <table class="table">
                <thead>
                    <tr>
                        <th>Aciklama</th>
                        <th>Miktar</th>
                        <th>Birim Fiyat</th>
                        <th>KDV (%)</th>
                        <th>Toplam</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2, ',', '.') }} TL</td>
                        <td class="text-right">{{ number_format($item->vat, 2, ',', '.') }}%</td>
                        <td class="text-right">{{ number_format($item->line_total, 2, ',', '.') }} TL</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="totals">
                <tr>
                    <td><strong>Ara Toplam:</strong></td>
                    <td class="text-right">{{ number_format($invoice->subtotal, 2, ',', '.') }} TL</td>
                </tr>
                <tr>
                    <td><strong>Toplam KDV:</strong></td>
                    <td class="text-right">{{ number_format($invoice->vat_total, 2, ',', '.') }} TL</td>
                </tr>
                <tr>
                    <td><strong>Genel Toplam:</strong></td>
                    <td class="text-right">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</td>
                </tr>
            </table>

            @if(!empty($invoice->payment_details['installments']))
                <h3 style="margin-top: 40px;">Odeme Plani</h3>
                <table class="table" style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Vade Tarihi</th>
                            <th class="text-right">Tutar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payment_details['installments'] as $row)
                            <tr>
                                <td>{{ $row['sequence'] ?? $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($row['due_date'])->format('d.m.Y') }}</td>
                                <td class="text-right">{{ number_format($row['amount'], 2, ',', '.') }} TL</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>

</body>
</html>