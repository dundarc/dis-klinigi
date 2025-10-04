<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Acil Hasta Bildirimi</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937;">
    <p>Merhaba {{  }},</p>

    <p>
        {{  }} isimli acil hasta {{  }} itibarıyla kliniğe kabul edildi.
        @if(!empty())
            Hastanın triyaj seviyesi: <strong>{{  }}</strong>.
        @endif
    </p>

    <p>Lütfen mümkün olan en kısa sürede hastayı değerlendirin.</p>

    <p>İyi çalışmalar dileriz.<br>{{  }}</p>
</body>
</html>
