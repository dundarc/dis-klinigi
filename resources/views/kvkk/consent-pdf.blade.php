<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KVKK Aydınlatma Metni</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 15px;
        }
        .page-break {
            page-break-before: always;
        }
        .pdf-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .pdf-header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 12px;
            color: #000;
        }
        .pdf-header p {
            margin: 4px 0;
            text-align: left;
            font-size: 10px;
        }
        h2 {
            font-size: 12px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
            color: #000;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }
        h3 {
            font-size: 11px;
            font-weight: bold;
            margin-top: 12px;
            margin-bottom: 6px;
            color: #000;
        }
        p {
            margin: 6px 0;
            text-align: justify;
        }
        ul {
            margin: 6px 0;
            padding-left: 15px;
        }
        li {
            margin: 3px 0;
            line-height: 1.2;
        }
        .consent-declaration {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #333;
            background-color: #f9f9f9;
        }
        .signature-fields {
            margin-top: 15px;
            padding: 10px;
            border-top: 1px solid #666;
        }
        .signature-fields p {
            margin: 6px 0;
            font-size: 10px;
        }
        .email-consent-notice {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #0066cc;
            background-color: #e6f3ff;
            font-size: 10px;
        }
    </style>
</head>
<body>
<!-- Page 1 -->
<div class="pdf-header">
    <h1>KVKK AYDINLATMA METNİ VE AÇIK RIZA BEYANI</h1>
    <p><strong>Klinik Adı:</strong> [KLİNİK ADI]</p>
    <p><strong>Hasta:</strong> {{ $consent->patient->first_name }} {{ $consent->patient->last_name }}</p>
    <p><strong>TC Kimlik:</strong> {{ $consent->patient->national_id }}</p>
    <p><strong>Onam Tarihi:</strong> {{ $consent->accepted_at?->format('d.m.Y H:i') ?: 'Beklemede' }}</p>
    <p><strong>Versiyon:</strong> {{ $consent->version }}</p>
    <p><strong>Onam Yöntemi:</strong> {{ $consent->consent_method === 'email_verification' ? 'E-posta Doğrulaması' : 'Islak İmza' }}</p>
</div>

<div class="consent-content">
    <h2>I. Veri Sorumlusu ve Temsilci</h2>
    <p>6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca, kişisel verileriniz [KLİNİK ADI] tarafından veri sorumlusu sıfatıyla işlenmektedir.</p>

    <h2>II. İşlenen Kişisel Veri Kategorileri</h2>
    <p>Kişisel verileriniz, sağlık hizmetlerinin sunulması kapsamında;</p>
    <ul>
        <li>Kimlik bilgileriniz (ad, soyad, T.C. kimlik numarası, doğum tarihi vb.),</li>
        <li>İletişim bilgileriniz (adres, telefon, e-posta),</li>
        <li>Sağlık verileriniz (muayene, teşhis, tedavi planı, laboratuvar ve görüntüleme sonuçları, reçete bilgileri, tıbbi geçmiş),</li>
        <li>Finansal verileriniz (ödeme bilgileri, fatura kayıtları),</li>
        <li>İşlem güvenliği verileriniz (sistem kayıtları, IP adresi vb.),</li>
    </ul>
    <p>işlenmektedir.</p>

    <h2>III. Kişisel Verilerin İşlenme Amaçları</h2>
    <p>Kişisel verileriniz, KVKK m.5 ve m.6 hükümleri uyarınca;</p>
    <ul>
        <li>Sağlık hizmetlerinin sunulması, muayene, teşhis ve tedavi süreçlerinin yürütülmesi,</li>
        <li>Randevu ve hasta takip süreçlerinin yürütülmesi,</li>
        <li>Faturalandırma ve ödeme işlemlerinin gerçekleştirilmesi,</li>
        <li>İlgili mevzuatta öngörülen yükümlülüklerin yerine getirilmesi,</li>
        <li>Hasta haklarının korunması, tıbbi kayıtların arşivlenmesi,</li>
    </ul>
    <p>amaçlarıyla işlenecektir.</p>

    <h2>IV. Kişisel Verilerin Aktarılması</h2>
    <p>Kişisel verileriniz, yalnızca mevzuatın öngördüğü hallerde ve ilgili mercilerle sınırlı olmak üzere;</p>
    <ul>
        <li>T.C. Sağlık Bakanlığı ve bağlı kuruluşları,</li>
        <li>Sosyal Güvenlik Kurumu,</li>
        <li>Yetkili adli ve idari merciler,</li>
        <li>Yetkilendirilmiş laboratuvar ve görüntüleme merkezleri,</li>
    </ul>
    <p>ile paylaşılabilecektir.</p>
</div>

<!-- Page 2 -->
<div class="page-break">
    <div class="consent-content">
        <h2>V. Hukuki Sebepler</h2>
        <p>Kişisel verileriniz;</p>
        <ul>
            <li>6698 sayılı KVKK,</li>
            <li>3359 sayılı Sağlık Hizmetleri Temel Kanunu,</li>
            <li>Hasta Hakları Yönetmeliği ve ilgili sair sağlık mevzuatı</li>
        </ul>
        <p>çerçevesinde işlenmektedir.</p>

        <h2>VI. Saklama Süresi</h2>
        <p>Kişisel verileriniz, ilgili mevzuatta öngörülen saklama süreleri boyunca muhafaza edilecek, sürenin sona ermesi halinde ise silinecek, yok edilecek veya anonim hale getirilecektir.</p>

        <h2>VII. KVKK Kapsamındaki Haklarınız</h2>
        <p>KVKK'nın 11. maddesi uyarınca;</p>
        <ul>
            <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme,</li>
            <li>İşlenmişse buna ilişkin bilgi talep etme,</li>
            <li>İşleme amacını ve amacına uygun kullanılıp kullanılmadığını öğrenme,</li>
            <li>Eksik veya yanlış işlenmişse düzeltilmesini talep etme,</li>
            <li>Kanuni şartlar çerçevesinde silinmesini veya yok edilmesini talep etme,</li>
            <li>Aktarıldığı üçüncü kişileri öğrenme,</li>
            <li>İşlenen verilerin münhasıran otomatik sistemler vasıtasıyla analiz edilmesi suretiyle aleyhinize bir sonucun ortaya çıkmasına itiraz etme,</li>
            <li>Kanuna aykırı işleme sebebiyle zarara uğramanız hâlinde zararın giderilmesini talep etme,</li>
        </ul>
        <p>haklarına sahipsiniz.</p>

        <p>Başvurularınızı, kimliğinizi ispat edici belgeler ile birlikte kliniğimize yazılı olarak veya [KLİNİK E-POSTA ADRESİ] üzerinden iletebilirsiniz.</p>
    </div>

    <div class="consent-declaration">
        <h2>AÇIK RIZA BEYANI</h2>
        <p>İşbu Aydınlatma Metni'ni okuduğumu, anladığımı ve 6698 sayılı KVKK kapsamında tarafıma yapılan bilgilendirme çerçevesinde; kimlik, iletişim, sağlık ve finansal verilerimin, teşhis ve tedavi hizmetlerinin yürütülmesi, faturalandırma ve mevzuattan kaynaklanan yükümlülüklerin yerine getirilmesi amacıyla işlenmesine ve ilgili kişi/kurumlara aktarılmasına açık rıza verdiğimi beyan ederim.</p>

        <div class="signature-fields">
            <p><strong>Hasta Adı Soyadı:</strong> {{ $consent->patient->first_name }} {{ $consent->patient->last_name }}</p>
            <p><strong>T.C. Kimlik No:</strong> {{ $consent->patient->national_id }}</p>
            <p><strong>İmza:</strong> _______________________________</p>
            <p><strong>Tarih:</strong> {{ $consent->accepted_at?->format('d.m.Y') ?: '____/____/________' }}</p>
        </div>
    </div>

    @if($consent->consent_method === 'email_verification')
    <div class="email-consent-notice">
        <h3>E-posta Onamı Bildirimi</h3>
        <p>Bu KVKK onamı, hasta tarafından e-posta yoluyla dijital olarak verilmiştir. Onam linkine tıklanarak elektronik imza ile onaylanmıştır.</p>
        @if($consent->email_verified_at)
            <p><strong>E-posta Doğrulama Tarihi:</strong> {{ $consent->email_verified_at->format('d.m.Y H:i') }}</p>
        @else
            <p><strong>Durum:</strong> E-posta doğrulaması bekleniyor</p>
        @endif
    </div>
    @endif
</div>
</body>
</html>