<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Hasta Check-in Bildirimi</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937;">
    <p>Merhaba {{ $dentistName }},</p>

    <p>
        {{ $patientName }} adlı hastanız {{ $appointmentTime }} tarihli randevusu için şu an kliniğe giriş yaptı.
        Hastayı kısa süre içinde görmek için bekleme alanını kontrol edebilirsiniz.
    </p>

    <p>İyi çalışmalar dileriz.<br>{{ $clinicName }}</p>
</body>
</html>
