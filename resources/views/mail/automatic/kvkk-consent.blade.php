<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>KVKK Onay Bildirimi</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937;"> 
    @if(!empty($recipientName))
        <p>Merhaba {{ $recipientName }},</p>
    @else
        <p>Merhaba,</p>
    @endif

    <p>{{ $patientName }} adlı hasta {{ $consentDate }} tarihinde KVKK onam formunu onayladı.</p>

    <p>Bilginize sunulur.</p>

    <p>Saygılarımızla,<br>{{ $clinicName }}</p>
</body>
</html>
