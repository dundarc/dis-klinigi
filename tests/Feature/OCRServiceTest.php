<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\OCRService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class OCRServiceTest extends TestCase
{
    /** @test */
    public function it_can_parse_complex_turkish_invoice()
    {
        // Create a mock uploaded file with the invoice content
        $invoiceContent = "SAYIN
İLAYDA SARIDEDE
Dikey kağıt İlayda SARIDEDE Körfez mah.Yosun sok.
Kotko toptancılar sitesi C Blok No:6/12 İzmit/Kocaeli No:
Kapı No:
Körfez/ Kocaeli Türkiye
Web Sitesi:
E-Posta:
Tel: Fax:
Vergi Dairesi: TEPECİK VERGİ DAİRESİ
TCKN: 51106807222
Özelleştirme No: TR1.2
Senaryo: EARSIVFATURA
Fatura Tipi: SATIS
Fatura No: GIB2023000000008
Fatura Tarihi: 23-02-2023 13:47
Not: Fatura Tutarı: YIRMI BIN SEKSEN ÜÇ TÜRK LIRASI OTUZ SEKIZ KURUŞ
Ödeme Şekli: 42 / 27/02/2023 / Banka / try / TR200011100000000097328202 / QNB Finansbank, Kolay Adres Bilgisi: 4691226680,
Açıklamaya fatura numarası ya da cari hesaba mahsuben ifadesi yazmanız rica olunur. (IBAN ile transferde firma ünvanını tam olarak yazmanız
gerekmektedir.)
İBER ENDÜSTRİYEL İTHALAT İHRACAT VE TİCARET
LİMİTED ŞİRKETİ
Kapı No:
/ Mersin / Türkiye
Tel: Fax:
Web Sitesi:
E-Posta:
Vergi Dairesi: URAY VERGİ DAİRESİ MÜD.
VKN: 4691226680 e-Arşiv Fatura
ETTN: 598c51ed-e60c-4e79-b9a5-e40e4009e73b
Sıra
No Mal Hizmet Miktar Birim
Fiyat
İskonto/
Arttırım
Oranı
İskonto/
Arttırım
Tutarı
İskonto/
Arttırım
Nedeni
KDV
Oranı KDV Tutarı Diğer Vergiler
Mal
Hizmet
Tutarı
1 Jumbo Çöp Poşeti 5 Adet 295 TL %0,00 0,00 TL İskonto - %18,00 265,50 TL 1.475,00
TL
2 End. Jumbo Çöp Poşeti
(500 Gr-20li) 10 260 TL %0,00 0,00 TL İskonto - %18,00 468,00 TL 2.600,00
TL
3 Plastik Bardak 3000li
Koli 1 296 TL %0,00 0,00 TL İskonto - %18,00 53,28 TL 296,00
TL
4 JUMBO TUVALET KAĞIDI 20 Adet 124 TL %0,00 0,00 TL İskonto - %8,00 198,40 TL 2.480,00
TL
5 OTOMATİK MAKİNE
HAVLUSU 20 Adet 118 TL %0,00 0,00 TL İskonto - %8,00 188,80 TL 2.360,00
TL
6 Z KATLI HAVLU 20 Adet 124 TL %0,00 0,00 TL İskonto - %8,00 198,40 TL 2.480,00
TL
7 İÇTEN ÇEKMELİ HAVLU
21 CM 20 Adet 138 TL %0,00 0,00 TL İskonto - %8,00 220,80 TL 2.760,00
TL
8 RULO HAVLU 24LÜ 10 Adet 138 TL %0,00 0,00 TL İskonto - %8,00 110,40 TL 1.380,00
TL
9 DİSPENCER PEÇETE 20 Adet 118 TL %0,00 0,00 TL İskonto - %8,00 188,80 TL 2.360,00
TL
Mal Hizmet Toplam Tutarı 18.191,00 TL
Toplam İskonto 0,00 TL
Hesaplanan KDV(%18) 786,78 TL
Hesaplanan KDV(%8) 1.105,60 TL
Vergiler Dahil Toplam Tutar 20.083,38 TL
Ödenecek Tutar 20.083,38 TL";

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'test_invoice');
        file_put_contents($tempFile, $invoiceContent);
        
        // Create an uploaded file instance
        $uploadedFile = new UploadedFile(
            $tempFile,
            'test_invoice.txt',
            'text/plain',
            null,
            true
        );

        $ocrService = new OCRService();
        $result = $ocrService->parseDocument($uploadedFile);

        // Test that we can extract key information
        $this->assertNotNull($result['invoice_number']);
        $this->assertNotNull($result['invoice_date']);
        $this->assertNotNull($result['grand_total']);
        $this->assertNotEmpty($result['line_items']);
        
        // Clean up
        unlink($tempFile);
    }
}