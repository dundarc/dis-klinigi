<x-mail::message>
# KVKK Açık Rıza Onayı

Merhaba **{{ $consent->patient->first_name }} {{ $consent->patient->last_name }}**,

[KLİNİK ADI] tarafından size KVKK açık rıza onamı gönderilmiştir. Onamı incelemek ve onaylamak için aşağıdaki bağlantıya tıklayın.

<x-mail::button :url="$verificationUrl" color="primary">
    Onamı İncele ve Onayla
</x-mail::button>

## Onam Detayları
- **Versiyon:** {{ $consent->version }}
- **Oluşturulma Tarihi:** {{ $consent->created_at->format('d.m.Y H:i') }}

## Önemli Not
Bu e-posta bağlantısı 24 saat geçerlidir. Süresi dolmuş bağlantılar çalışmayacaktır.

Güvenliğiniz için bu bağlantıyı kimseyle paylaşmayın.

<x-mail::panel>
**KVKK Hakkınızda:** Kişisel verileriniz 6698 sayılı KVKK kapsamında işlenmektedir. Detaylı bilgi için oluşturulan onam metnini inceleyebilirsiniz.
</x-mail::panel>

Sorularınız için [KLİNİK E-POSTA ADRESİ] adresinden bizimle iletişime geçebilirsiniz.

Saygılarımla,<br>
[KLİNİK ADI] Ekibi
</x-mail::message>