<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Randevular - {{ $patient->first_name }} {{ $patient->last_name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; margin: 20px; font-size: 12px; line-height: 1.4; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .patient-info { margin-bottom: 20px; }
        .appointment { margin-bottom: 15px; border: 1px solid #ddd; padding: 10px; border-radius: 5px; }
        .appointment-header { font-weight: bold; margin-bottom: 5px; }
        .appointment-details { display: flex; flex-wrap: wrap; gap: 15px; }
        .detail { flex: 1; min-width: 200px; }
        .label { font-weight: bold; color: #666; }
        .value { margin-top: 2px; }
        .masked { color: #999; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h1>KVKK Veri Export - Randevular</h1>
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

    <h3>Randevu Geçmişi</h3>

    @forelse($patient->appointments ?? [] as $appointment)
        <div class="appointment">
            <div class="appointment-header">
                Randevu #{{ $appointment->id }}
            </div>
            <div class="appointment-details">
                <div class="detail">
                    <div class="label">Tarih:</div>
                    <div class="value">{{ $appointment->start_at->format('d.m.Y H:i') }}</div>
                </div>
                <div class="detail">
                    <div class="label">Bitiş:</div>
                    <div class="value">{{ $appointment->end_at->format('d.m.Y H:i') }}</div>
                </div>
                <div class="detail">
                    <div class="label">Durum:</div>
                    <div class="value">{{ $appointment->status }}</div>
                </div>
                <div class="detail">
                    <div class="label">Oda:</div>
                    <div class="value">{{ $appointment->room ?? 'Belirtilmemiş' }}</div>
                </div>
                <div class="detail">
                    <div class="label">Diş Hekimi:</div>
                    <div class="value">{{ $appointment->dentist?->name ?? 'Belirtilmemiş' }}</div>
                </div>
                @if($appointment->notes)
                    <div class="detail" style="flex: 100%;">
                        <div class="label">Notlar:</div>
                        <div class="value">{{ $masking ? '***MASKED***' : $appointment->notes }}</div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p>Bu hasta için randevu bulunmuyor.</p>
    @endforelse
</body>
</html>