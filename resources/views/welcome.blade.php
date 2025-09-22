<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Klinik Giriş Sayfası</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <style>
        body {
            background-color: #121212; /* Koyu arka plan */
            color: #e0e0e0; /* Açık renkli metin */
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .header {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            padding: 1.5rem;
            position: absolute;
            top: 0;
            right: 0;
        }
        .login-button {
            background-color: #4CAF50; /* Yeşil buton */
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .login-button:hover {
            background-color: #45a049;
        }
        .container {
            max-width: 800px;
            padding: 2rem;
            margin: 2rem;
            background-color: #1e1e1e;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            animation: fadeIn 1s ease-in-out;
            text-align: left; /* Metni sola yasla */
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #bb86fc; /* Mor tonları vurgu rengi */
            text-align: center; /* Başlığı ortala */
        }
        p {
            font-size: 1rem;
            line-height: 1.8;
            color: #b0b0b0;
            margin-bottom: 1.5rem; /* Paragraflar arasına boşluk */
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="header">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="login-button">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="login-button">Giriş</a>
            @endauth
        @endif
    </div>

    <div class="container">
        <h1>Yasal Uyarı</h1>
        <p>
            Bu web sitesi, **yetkili klinik personeli için özel olarak tasarlanmış bir bilgi işlem sistemidir.** Bu sisteme yapılan tüm erişimler ve eylemler, güvenlik amacıyla izlenmekte, kaydedilmekte ve denetlenmektedir.
        </p>
        <p>
            Yetkisiz erişim, bu sistemde bulunan verilerin izinsiz kullanılması, değiştirilmesi veya silinmesi, Türkiye Cumhuriyeti yasaları, özellikle de Türk Ceza Kanunu'nun **"Bilişim Alanında Suçlar"** başlıklı hükümleri uyarınca **cezai yaptırım gerektiren bir suç teşkil etmektedir.**
        </p>
        <p>
            Bu sistemde yetkisiz giriş yapmaya çalışan kişilerin **IP adresleri, erişim saatleri ve diğer ilgili verileri otomatik olarak toplanmaktadır.** Toplanan bu veriler, adli makamlarla paylaşılarak, ilgili kişiler hakkında derhal suç duyurusunda bulunulacaktır. Bu uyarı metni, bu sisteme erişim sağlayan her kullanıcı tarafından okunmuş ve kabul edilmiş sayılır.
        </p>
    </div>
</body>
</html>