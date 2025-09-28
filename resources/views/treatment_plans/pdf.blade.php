<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tedavi Planı #{{ $plan->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 20px;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 28px;
            margin: 0;
            color: #333;
        }
        .header .subtitle {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        h2 {
            font-size: 16px;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 8px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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
            font-size: 10px;
            text-transform: uppercase;
        }
        .info-table {
            margin-bottom: 20px;
            width: 100%;
        }
        .info-table td {
            border: none;
            padding: 6px 0;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 200px;
        }
        .status-planned { background-color: #e3f2fd; }
        .status-in_progress { background-color: #fff3e0; }
        .status-done { background-color: #e8f5e8; }
        .status-invoiced { background-color: #f3e5f5; }
        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .summary-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #495057;
        }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Tedavi Planı #{{ $plan->id }}</h1>
        <div class="subtitle">
            Oluşturulma Tarihi: {{ $plan->created_at ? $plan->created_at->format('d.m.Y H:i') : 'Tarih Yok' }}
        </div>
    </div>

    <h2>Hasta ve Plan Bilgileri</h2>
    <table class="info-table">
        <tr>
            <td>Hasta Adı Soyadı:</td>
            <td>{{ optional($plan->patient)->first_name }} {{ optional($plan->patient)->last_name }}</td>
        </tr>
        <tr>
            <td>Hasta TC Kimlik No:</td>
            <td>{{ optional($plan->patient)->national_id ?? '-' }}</td>
        </tr>
        <tr>
            <td>Hasta Telefon:</td>
            <td>{{ optional($plan->patient)->phone_primary ?? '-' }}</td>
        </tr>
        <tr>
            <td>Sorumlu Diş Hekimi:</td>
            <td>{{ optional($plan->dentist)->name ?? 'Hekim Atanmamış' }}</td>
        </tr>
        <tr>
            <td>Plan Durumu:</td>
            <td>{{ optional($plan->status)->label() ?? 'Durum Yok' }}</td>
        </tr>
        <tr>
            <td>Plan Oluşturulma:</td>
            <td>{{ $plan->created_at ? $plan->created_at->format('d.m.Y H:i') : 'Tarih Yok' }}</td>
        </tr>
        <tr>
            <td>Son Güncelleme:</td>
            <td>{{ $plan->updated_at ? $plan->updated_at->format('d.m.Y H:i') : 'Tarih Yok' }}</td>
        </tr>
        <tr>
            <td>Tahmini Toplam Maliyet:</td>
            <td>{{ number_format($plan->total_estimated_cost, 2, ',', '.') }} TL</td>
        </tr>
        @if($plan->notes)
        <tr>
            <td>Notlar:</td>
            <td>{{ $plan->notes }}</td>
        </tr>
        @endif
    </table>

    <h2>Tedavi Kalemleri Detayları</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Tedavi Adı</th>
                <th style="width: 10%;">Diş No</th>
                <th style="width: 15%;">Durum</th>
                <th style="width: 20%;">Randevu Bilgileri</th>
                <th style="width: 15%;">Sorumlu Hekim</th>
                <th style="width: 10%;">Ücret</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse($plan->items->sortBy('created_at') as $index => $item)
                <tr class="status-{{ $item->status->value }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ optional($item->treatment)->name ?? 'Tedavi Bilgisi Silinmiş' }}</td>
                    <td>{{ $item->tooth_number ?? '-' }}</td>
                    <td>{{ optional($item->status)->label() ?? 'Durum Yok' }}</td>
                    <td>
                        @if($item->appointment)
                            <strong>Tarih:</strong> {{ $item->appointment->start_at ? $item->appointment->start_at->format('d.m.Y H:i') : 'Tarih Yok' }}<br>
                            <strong>Durum:</strong>
                            @if($item->appointment->status === \App\Enums\AppointmentStatus::COMPLETED)
                                Tamamlandı
                            @elseif($item->appointment->status === \App\Enums\AppointmentStatus::CANCELLED)
                                İptal Edildi ({{ $item->appointment->updated_at ? $item->appointment->updated_at->format('d.m.Y') : 'Tarih Yok' }})
                            @elseif($item->appointment->status === \App\Enums\AppointmentStatus::NO_SHOW)
                                Gelinmedi
                            @else
                                {{ optional($item->appointment->status)->label() ?? 'Durum Yok' }}
                            @endif
                        @else
                            Randevu atanmamış
                        @endif
                    </td>
                    <td>{{ optional(optional($item->appointment)->dentist)->name ?? '-' }}</td>
                    <td style="text-align: right;">{{ number_format($item->estimated_price, 2, ',', '.') }} TL</td>
                </tr>
                @php $total += $item->estimated_price; @endphp
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; font-style: italic;">Bu tedavi planında henüz kalem bulunmuyor.</td>
                </tr>
            @endforelse
            @if($plan->items->count() > 0)
            <tr class="total-row">
                <td colspan="6" style="text-align: right; font-weight: bold;">TOPLAM:</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($total, 2, ',', '.') }} TL</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="summary-box">
        <h3>Tedavi Planı Özeti</h3>
        <table style="width: 100%; border: none; margin: 0;">
            <tr>
                <td style="border: none; padding: 5px 0;"><strong>Toplam Tedavi Kalemi:</strong></td>
                <td style="border: none; padding: 5px 0;">{{ $plan->items->count() }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px 0;"><strong>Tamamlanan Tedaviler:</strong></td>
                <td style="border: none; padding: 5px 0;">{{ $plan->items->where('status', \App\Enums\TreatmentPlanItemStatus::DONE)->count() }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px 0;"><strong>Devam Eden Tedaviler:</strong></td>
                <td style="border: none; padding: 5px 0;">{{ $plan->items->where('status', \App\Enums\TreatmentPlanItemStatus::IN_PROGRESS)->count() }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px 0;"><strong>Planlanan Tedaviler:</strong></td>
                <td style="border: none; padding: 5px 0;">{{ $plan->items->where('status', \App\Enums\TreatmentPlanItemStatus::PLANNED)->count() }}</td>
            </tr>
        </table>
    </div>

    <h2>Tedavi Geçmişi ve Durum Değişiklikleri</h2>
    @php
        $timelineEvents = collect();
        foreach($plan->items as $item) {
            // Item creation
            $timelineEvents->push([
                'date' => $item->created_at,
                'type' => 'created',
                'title' => 'Tedavi Kalemi Oluşturuldu',
                'description' => (optional($item->treatment)->name ?: 'Tedavi Silinmiş') . ' tedavisi planlandı',
                'item' => $item
            ]);

            // Status history from histories table
            foreach($item->histories as $history) {
                $timelineEvents->push([
                    'date' => $history->created_at,
                    'type' => 'status_change',
                    'title' => 'Durum Değişikliği',
                    'description' => (optional($item->treatment)->name ?: 'Tedavi Silinmiş') . ' - ' .
                                    (optional($history->old_status)->label() ?? 'Yeni') . ' → ' . (optional($history->new_status)->label() ?? 'Durum Yok') .
                                    ($history->user ? ' (Tarafından: ' . $history->user->name . ')' : ''),
                    'item' => $item,
                    'history' => $history
                ]);
            }

            // Appointment history
            foreach($item->appointmentHistory as $history) {
                $actionValue = optional($history->action)->value ?? 'unknown';
                $actionLabel = optional($history->action)->label() ?? 'Bilinmeyen İşlem';
                $timelineEvents->push([
                    'date' => $history->created_at,
                    'type' => 'appointment_' . $actionValue,
                    'title' => $actionLabel . ' Randevu',
                    'description' => (optional($item->treatment)->name ?: 'Tedavi Silinmiş') . ' için randevu ' . $actionLabel .
                                   ($history->user ? ' (Tarafından: ' . $history->user->name . ')' : ''),
                    'item' => $item,
                    'appointment' => $history->appointment
                ]);
            }
        }

        $timelineEvents = $timelineEvents->sortByDesc('date');
    @endphp

    @if($timelineEvents->isNotEmpty())
        <table style="margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width: 20%;">Tarih</th>
                    <th style="width: 25%;">Olay</th>
                    <th style="width: 35%;">Açıklama</th>
                    <th style="width: 20%;">Tedavi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timelineEvents as $event)
                    <tr>
                        <td>{{ $event['date'] ? $event['date']->format('d.m.Y H:i') : 'Tarih Yok' }}</td>
                        <td>{{ $event['title'] }}</td>
                        <td>{{ $event['description'] }}</td>
                        <td>{{ optional($event['item']->treatment)->name ?? 'Tedavi Silinmiş' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="font-style: italic; text-align: center; margin: 20px 0;">Bu tedavi planında henüz bir aktivite gerçekleşmemiş.</p>
    @endif

    <div class="footer">
        <p>Bu rapor {{ now()->format('d.m.Y H:i') }} tarihinde oluşturulmuştur.</p>
        <p>Diş Hekimliği Yönetim Sistemi</p>
    </div>
</body>
</html>