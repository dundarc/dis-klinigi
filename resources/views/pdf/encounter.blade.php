<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ziyaret Raporu - {{ $encounter->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.4; color: #333; }
        .container { width: 100%; margin: 0 auto; border: 1px solid #000; padding: 15px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .section { margin-bottom: 15px; }
        .section-title { font-weight: bold; font-size: 14px; margin-bottom: 8px; background: #f0f0f0; padding: 5px; }
        .info-row { margin-bottom: 3px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .table th, .table td { border: 1px solid #ccc; padding: 4px; text-align: left; }
        .table th { background: #f5f5f5; font-weight: bold; }
        .total-row { font-weight: bold; background: #e8f4f8; }
        .prescription-text { font-family: monospace; white-space: pre-line; margin: 10px 0; padding: 10px; border: 1px solid #ddd; background: #fafafa; }
        .footer { text-align: right; margin-top: 20px; border-top: 1px solid #000; padding-top: 10px; }
        .clinic-info { text-align: center; margin-bottom: 15px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Clinic Header -->
        <div class="clinic-info">
            <h3>{{ $clinicName }}</h3>
            <p>Ziyaret Raporu</p>
        </div>

        <div class="header">
            <h2>ZİYARET RAPORU</h2>
            <p>Ziyaret No: {{ $encounter->id }}</p>
        </div>

        <!-- Patient Information -->
        <div class="section">
            <div class="section-title">HASTA BİLGİLERİ</div>
            <div class="info-row"><strong>Adı Soyadı:</strong> {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</div>
            <div class="info-row"><strong>T.C. Kimlik No:</strong> {{ $encounter->patient->national_id ?? 'Belirtilmemiş' }}</div>
            <div class="info-row"><strong>Telefon:</strong> {{ $encounter->patient->phone_primary ?: $encounter->patient->phone_secondary }}</div>
        </div>

        <!-- Visit Information -->
        <div class="section">
            <div class="section-title">ZİYARET BİLGİLERİ</div>
            <div class="info-row"><strong>Ziyaret Durumu:</strong> {{ $encounter->status->label() }}</div>
            <div class="info-row"><strong>Doktor:</strong> {{ $encounter->dentist?->name ?? 'Atanmamış' }}</div>
            <div class="info-row"><strong>Giriş Saati:</strong> {{ $encounter->arrived_at?->format('d.m.Y H:i') }}</div>
            <div class="info-row"><strong>Çıkış Saati:</strong> {{ $encounter->ended_at?->format('d.m.Y H:i') ?? 'Devam ediyor' }}</div>
            @if($encounter->notes)
            <div class="info-row"><strong>Notlar:</strong> {{ $encounter->notes }}</div>
            @endif
        </div>

        <!-- Treatments from Treatment Plan -->
        @if($treatmentPlanTreatments->isNotEmpty())
        <div class="section">
            <div class="section-title">TEDAVİ PLANINDAN YAPILAN İŞLEMLER</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tedavi</th>
                        <th>Diş No</th>
                        <th>Ücret</th>
                        <th>Tarih</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($treatmentPlanTreatments as $treatment)
                    <tr>
                        <td>{{ $treatment->display_treatment_name }}</td>
                        <td>{{ $treatment->tooth_number ?? '-' }}</td>
                        <td>{{ number_format($treatment->unit_price, 2, ',', '.') }} TL</td>
                        <td>{{ $treatment->performed_at?->format('d.m.Y H:i') }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2"><strong>Tedavi Planı Toplamı</strong></td>
                        <td colspan="2"><strong>{{ number_format($treatmentPlanTreatments->sum('unit_price'), 2, ',', '.') }} TL</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        <!-- Manual Treatments -->
        @if($manualTreatments->isNotEmpty())
        <div class="section">
            <div class="section-title">TEDAVİ PLANI DIŞINDA YAPILAN İŞLEMLER</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tedavi</th>
                        <th>Diş No</th>
                        <th>Ücret</th>
                        <th>Tarih</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($manualTreatments as $treatment)
                    <tr>
                        <td>{{ $treatment->display_treatment_name }}</td>
                        <td>{{ $treatment->tooth_number ?? '-' }}</td>
                        <td>{{ number_format($treatment->unit_price, 2, ',', '.') }} TL</td>
                        <td>{{ $treatment->performed_at?->format('d.m.Y H:i') }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2"><strong>Manuel Tedavi Toplamı</strong></td>
                        <td colspan="2"><strong>{{ number_format($manualTreatments->sum('unit_price'), 2, ',', '.') }} TL</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        <!-- Total Summary -->
        @php
            $grandTotal = $encounter->treatments->sum('unit_price');
        @endphp
        @if($grandTotal > 0)
        <div class="section">
            <div class="section-title">TOPLAM TUTAR</div>
            <div style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px; background: #e8f4f8; border: 1px solid #bee3f8;">
                TOPLAM: {{ number_format($grandTotal, 2, ',', '.') }} TL
            </div>
        </div>
        @endif

        <!-- Prescriptions -->
        @if($encounter->prescriptions->isNotEmpty())
        <div class="section">
            <div class="section-title">REÇETELER</div>
            @foreach($encounter->prescriptions as $prescription)
            <div style="margin-bottom: 15px; border: 1px solid #ddd; padding: 10px;">
                <div class="info-row"><strong>Reçete No:</strong> {{ $prescription->id }}</div>
                <div class="info-row"><strong>Tarih:</strong> {{ $prescription->created_at->format('d.m.Y H:i') }}</div>
                <div class="prescription-text">
                    {!! nl2br(e($prescription->text)) !!}
                </div>
                <div style="text-align: right; margin-top: 10px;">
                    <strong>Hekim:</strong> {{ $prescription->dentist->name }}
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div>Rapor Oluşturulma Tarihi: {{ now()->format('d.m.Y H:i') }}</div>
            <div style="margin-top: 10px;">
                <strong>Hekim İmzası</strong><br>
                {{ $encounter->dentist?->name ?? 'Atanmamış' }}
            </div>
        </div>
    </div>
</body>
</html>