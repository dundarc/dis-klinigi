<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KVKK Onam Belgesi - {{ $patient->first_name }} {{ $patient->last_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
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
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #f9fafb;
        }
        .section h2 {
            color: #1f2937;
            font-size: 16px;
            margin: 0 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #d1d5db;
            font-weight: bold;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
            color: #374151;
            padding: 4px 0;
        }
        .info-value {
            display: table-cell;
            padding: 4px 0;
            color: #111827;
        }
        .consent-content {
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
            line-height: 1.6;
        }
        .consent-content h3 {
            color: #1f2937;
            font-size: 14px;
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        .consent-content p {
            margin: 8px 0;
        }
        .consent-content ul, .consent-content ol {
            margin: 8px 0;
            padding-left: 20px;
        }
        .consent-content li {
            margin: 4px 0;
        }
        .verification {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 12px;
            margin-top: 20px;
        }
        .verification h3 {
            color: #92400e;
            font-size: 14px;
            margin: 0 0 8px 0;
            font-weight: bold;
        }
        .verification p {
            margin: 4px 0;
            color: #78350f;
            font-size: 11px;
        }
        .hash-display {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 8px;
            font-family: 'Courier New', monospace;
            font-size: 10px;
            word-break: break-all;
            margin: 8px 0;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Kişisel Verilerin Korunması Kanunu (KVKK)</h1>
        <p><strong>Aydınlatma Metni ve Onam Belgesi</strong></p>
        <p>Belge No: KVKK-{{ $patient->id }}-{{ $consent->id ?? 'N/A' }}</p>
        <p>Oluşturulma Tarihi: {{ now()->format('d.m.Y H:i') }}</p>
    </div>

    <!-- Hasta Bilgileri -->
    <div class="section">
        <h2>Hasta Bilgileri</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Ad Soyad:</div>
                <div class="info-value">{{ $patient->first_name }} {{ $patient->last_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">TC Kimlik No:</div>
                <div class="info-value">{{ $patient->national_id ? '*** *** ** ' . substr($patient->national_id, -2) : 'Belirtilmemiş' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Doğum Tarihi:</div>
                <div class="info-value">{{ $patient->birth_date?->format('d.m.Y') ?? 'Belirtilmemiş' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">İletişim:</div>
                <div class="info-value">
                    {{ $patient->phone_primary ? '*** *** ' . substr($patient->phone_primary, -4) : 'Belirtilmemiş' }}
                    @if($patient->email)
                        | {{ substr($patient->email, 0, 2) }}***@{{ explode('@', $patient->email)[1] }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Onam Durumu -->
    <div class="section">
        <h2>Onam Durumu</h2>
        @if($consent)
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Onam Tarihi:</div>
                    <div class="info-value">{{ $consent->accepted_at?->format('d.m.Y H:i:s') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Durum:</div>
                    <div class="info-value">
                        @if($consent->status === 'accepted')
                            <strong style="color: #059669;">✓ ONAYLI</strong>
                        @elseif($consent->status === 'withdrawn')
                            <strong style="color: #dc2626;">✗ GERİ ÇEKİLDİ</strong>
                        @else
                             <strong style="color: #6b7280;">{{ ucfirst($consent->status->value ?? (string)$consent->status) }}</strong>
                         @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Versiyon:</div>
                    <div class="info-value">{{ $consent->version }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">IP Adresi:</div>
                    <div class="info-value">{{ $consent->ip_address }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tarayıcı:</div>
                    <div class="info-value">{{ substr($consent->user_agent, 0, 50) }}...</div>
                </div>
            </div>

            <!-- Onam Metni -->
            @if($consent->snapshot && isset($consent->snapshot['content']))
                <div class="consent-content">
                    <h3>Kabul Edilen Aydınlatma Metni</h3>
                    {!! $consent->snapshot['content'] !!}
                </div>
            @endif
        @else
            <div style="text-align: center; padding: 20px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px;">
                <strong style="color: #dc2626;">KVKK Onamı Bulunmuyor</strong>
                <p style="margin: 10px 0 0 0; color: #7f1d1d;">
                    Bu hasta için henüz KVKK onamı alınmamış veya kayıtlı değildir.
                </p>
            </div>
        @endif
    </div>

    <!-- Doğrulama Bilgileri -->
    @if($consent)
        <div class="verification">
            <h3>Belge Doğrulama</h3>
            <p><strong>Bu belge, aşağıdaki bilgiler kullanılarak oluşturulmuştur:</strong></p>
            <p><strong>Onam ID:</strong> {{ $consent->id }}</p>
            <p><strong>Kriptografik Hash (SHA-256):</strong></p>
            <div class="hash-display">{{ $consent->hash ?? 'Hash bilgisi bulunmuyor' }}</div>
            <p><strong>Hash Doğrulama:</strong> Yukarıdaki hash değeri, onam metninin ve zaman damgasının kriptografik özeti olup, belgenin bütünlüğünü garanti eder.</p>
            <p><strong>Önemli:</strong> Bu belgenin herhangi bir değişiklik yapılması halinde hash değeri değişecek ve belge geçersiz hale gelecektir.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Bu belge KVKK uyumluluğu kapsamında elektronik ortamda oluşturulmuştur.</p>
        <p>Sistem tarafından otomatik olarak üretilmiştir: {{ config('app.name') }} - {{ now()->format('d.m.Y H:i:s') }}</p>
    </div>

</body>
</html>