<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reçete - {{ $prescription->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; line-height: 1.6; }
        .container { width: 100%; margin: 0 auto; border: 1px solid #000; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .patient-info, .doctor-info { margin-bottom: 20px; }
        .prescription-body { min-height: 300px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 15px 0; }
        .footer { text-align: right; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>E-REÇETE</h2>
        </div>

        <div class="patient-info">
            <strong>Hasta Adı Soyadı:</strong> {{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}<br>
            <strong>T.C. Kimlik No:</strong> {{ $prescription->patient->national_id ?? 'Belirtilmemiş' }}<br>
            <strong>Tarih:</strong> {{ $prescription->created_at->format('d.m.Y') }}
        </div>

        <div class="prescription-body">
            <p>Rp.</p>
            <div style="padding-left: 20px;">
                {!! nl2br(e($prescription->text)) !!}
            </div>
        </div>

        <div class="footer">
            <strong>Hekim Adı Soyadı</strong><br>
            {{ $prescription->dentist->name }}<br>
            (İmza)
        </div>
    </div>
</body>
</html>