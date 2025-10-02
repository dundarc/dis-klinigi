<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KVKK Onam İptal Talebi</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .content {
            margin: 20px 0;
        }
        .info-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .info-table .label {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 30%;
        }
        .statement {
            margin: 30px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-left: 4px solid #333;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #333;
            width: 200px;
            text-align: center;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">KVKK ONAM İPTAL TALEBİ</div>
        <div>Talep Tarihi: {{ now()->format('d.m.Y H:i') }}</div>
    </div>

    <div class="content">
        <p>Değerli Yetkili,</p>

        <p>Kişisel verilerimin korunması hakkındaki 6698 sayılı Kişisel Verilerin Korunması Kanunu (KVKK) kapsamında vermiş olduğum onamı iptal etmek istiyorum.</p>

        <table class="info-table">
            <tr>
                <td class="label">Ad Soyad:</td>
                <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
            </tr>
            <tr>
                <td class="label">TC Kimlik No:</td>
                <td>{{ $patient->national_id }}</td>
            </tr>
            <tr>
                <td class="label">Tarih/Saat:</td>
                <td>{{ now()->format('d.m.Y H:i:s') }}</td>
            </tr>
            <tr>
                <td class="label">IP Adresi:</td>
                <td>{{ request()->ip() }}</td>
            </tr>
        </table>

        <div class="statement">
            <strong>KVKK Onam İptal Beyanı:</strong><br><br>
            Yukarıda bilgileri verilen kişi olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu kapsamında vermiş olduğum kişisel veri işleme onamının iptal edilmesini ve kişisel verilerimin işlenmesinin durdurulmasını talep ederim.
        </div>

        <p>Bu talebin işleme alınmasını rica ederim.</p>

        <p>Saygılarımla,</p>

        <div class="signature-line">
            {{ $patient->first_name }} {{ $patient->last_name }}
        </div>
    </div>

    <div class="footer">
        Bu belge {{ now()->format('d.m.Y H:i:s') }} tarihinde oluşturulmuştur.<br>
        Belge No: {{ $consent->id }}
    </div>
</body>
</html>