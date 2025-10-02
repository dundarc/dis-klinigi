<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tedavi Planları - {{ $patient->first_name }} {{ $patient->last_name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; margin: 20px; font-size: 12px; line-height: 1.4; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .patient-info { margin-bottom: 20px; }
        .treatment-plan { margin-bottom: 25px; border: 1px solid #ddd; padding: 15px; border-radius: 5px; page-break-inside: avoid; }
        .treatment-plan-header { font-weight: bold; font-size: 16px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .plan-details { display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 15px; }
        .detail { flex: 1; min-width: 200px; }
        .label { font-weight: bold; color: #666; }
        .value { margin-top: 2px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table th { background-color: #f5f5f5; font-weight: bold; }
        .masked { color: #999; font-style: italic; }
        .status-planned { background-color: #e3f2fd; }
        .status-in_progress { background-color: #fff3e0; }
        .status-done { background-color: #e8f5e8; }
        .status-invoiced { background-color: #f3e5f5; }
    </style>
</head>
<body>
    <div class="header">
        <h1>KVKK Veri Export - Tedavi Planları</h1>
        <h2>{{ $patient->first_name }} {{ $patient->last_name }}</h2>
        @if($masking)
            <p class="masked">Hassas veriler maskelenmiştir</p>
        @endif
    </div>

    <div class="patient-info">
        <p><strong>Hasta ID:</strong> {{ $patient->id }}</p>
        <p><strong>Ad Soyad:</strong> {{ $patient->first_name }} {{ $patient->last_name }}</p>
        <p><strong>Export Tarihi:</strong> {{ now()->format('d.m.Y H:i') }}</p>
    </div>

    <h3>Tedavi Planları</h3>

    @forelse($treatmentPlans ?? [] as $plan)
        <div class="treatment-plan">
            <div class="treatment-plan-header">
                Tedavi Planı #{{ $plan->id }}
            </div>

            <div class="plan-details">
                <div class="detail">
                    <div class="label">Durum:</div>
                    <div class="value">{{ $plan->status ? $plan->status->label() : 'Durum Yok' }}</div>
                </div>
                <div class="detail">
                    <div class="label">Tahmini Toplam Maliyet:</div>
                    <div class="value">{{ number_format($plan->total_estimated_cost, 2, ',', '.') }} TL</div>
                </div>
                <div class="detail">
                    <div class="label">Sorumlu Diş Hekimi:</div>
                    <div class="value">{{ $plan->dentist?->name ?? 'Hekim Atanmamış' }}</div>
                </div>
                <div class="detail">
                    <div class="label">Oluşturulma:</div>
                    <div class="value">{{ $plan->created_at ? $plan->created_at->format('d.m.Y H:i') : 'Tarih Yok' }}</div>
                </div>
                @if($plan->notes)
                    <div class="detail" style="flex: 100%;">
                        <div class="label">Notlar:</div>
                        <div class="value">{{ $masking ? '***MASKED***' : $plan->notes }}</div>
                    </div>
                @endif
            </div>

            <h4>Tedavi Kalemleri</h4>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 30%;">Tedavi Adı</th>
                        <th style="width: 10%;">Diş No</th>
                        <th style="width: 15%;">Durum</th>
                        <th style="width: 20%;">Randevu</th>
                        <th style="width: 10%;">Ücret</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plan->items ?? [] as $index => $item)
                        <tr class="status-{{ $item->status?->value ?? 'unknown' }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->treatment?->name ?? 'Tedavi Bilgisi Silinmiş' }}</td>
                            <td>{{ $item->tooth_number ?? '-' }}</td>
                            <td>{{ $item->status ? $item->status->label() : 'Durum Yok' }}</td>
                            <td>
                                @if($item->appointment)
                                    {{ $item->appointment->start_at ? $item->appointment->start_at->format('d.m.Y H:i') : 'Tarih Yok' }}
                                @else
                                    Randevu atanmamış
                                @endif
                            </td>
                            <td style="text-align: right;">{{ number_format($item->estimated_price, 2, ',', '.') }} TL</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; font-style: italic;">Bu tedavi planında henüz kalem bulunmuyor.</td>
                        </tr>
                    @endforelse
                    @if($plan->items->count() > 0)
                    <tr style="background-color: #f5f5f5; font-weight: bold;">
                        <td colspan="5" style="text-align: right;">TOPLAM:</td>
                        <td style="text-align: right;">{{ number_format($plan->total_estimated_cost, 2, ',', '.') }} TL</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @empty
        <p>Bu hasta için tedavi planı bulunmuyor.</p>
    @endforelse
</body>
</html>