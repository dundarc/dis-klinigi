<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
                <div class="mb-6">
                    <a href="{{ route('help') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Yardım Ana Sayfasına Dön
                    </a>
                </div>

                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-8">Raporlar Detaylı Kılavuzu</h1>

                <div class="space-y-12">
                    <!-- Giriş -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Giriş</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Raporlar bölümü, kliniğinizin performansını, finansal durumunu ve operasyonel verilerini analiz etmenizi sağlar.
                            Farklı kategorilerdeki raporlar ile işletmenizi daha iyi anlayabilirsiniz.
                        </p>
                        <p class="text-slate-700 dark:text-slate-300">
                            Raporlar Excel veya PDF formatında dışa aktarılabilir ve tarih filtreleme ile özelleştirilebilir.
                        </p>
                    </section>

                    <!-- Rapor Türleri -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Rapor Türleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistemde bulunan rapor kategorileri.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Finansal Raporlar -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Finansal Raporlar</h3>
                                <ul class="text-sm text-green-800 dark:text-green-200 space-y-2">
                                    <li>Gelir-gider analizi</li>
                                    <li>Kar-zarar durumu</li>
                                    <li>Fatura takip</li>
                                    <li>Ödeme geçmişi</li>
                                </ul>
                            </div>

                            <!-- Operasyonel Raporlar -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Operasyonel Raporlar</h3>
                                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                    <li>Randevu istatistikleri</li>
                                    <li>Tedavi başarı oranları</li>
                                    <li>Hasta memnuniyeti</li>
                                    <li>Kullanım oranları</li>
                                </ul>
                            </div>

                            <!-- Stok Raporları -->
                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Stok Raporları</h3>
                                <ul class="text-sm text-orange-800 dark:text-orange-200 space-y-2">
                                    <li>Stok hareketleri</li>
                                    <li>Kritik stok seviyeleri</li>
                                    <li>Malzeme maliyeti</li>
                                    <li>Tedarikçi performansı</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Finansal Özet Raporu -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Finansal Özet Raporu</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Kliniğinizin mali durumunu genel olarak gösteren rapor.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Rapor İçeriği</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Toplam Gelir:</strong> Belirtilen dönemde elde edilen toplam tutar</li>
                                <li><strong>Toplam Gider:</strong> Yapılan tüm harcamalar</li>
                                <li><strong>Net Kar/Zarar:</strong> Gelir eksi gider</li>
                                <li><strong>Ödenmemiş Faturalar:</strong> Tahsil edilmemiş alacaklar</li>
                                <li><strong>Ortalama Fatura Tutarı:</strong> Hasta başı ortalama harcama</li>
                                <li><strong>En Çok Kazandıran Tedaviler:</strong> Gelir sıralaması</li>
                            </ul>

                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3 mt-6">Kullanım</h3>
                            <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li>"Raporlar" menüsüne tıklayın</li>
                                <li>"Finansal Özet" seçeneğini seçin</li>
                                <li>Tarih aralığı belirleyin</li>
                                <li>"Raporu Görüntüle" butonuna tıklayın</li>
                                <li>PDF veya Excel olarak dışa aktarın</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Doktor Performans Raporu -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Doktor Performans Raporu</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Doktorların çalışma performanslarını değerlendiren rapor.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Rapor İçeriği</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Randevu Sayısı:</strong> Her doktorun yaptığı randevu miktarı</li>
                                <li><strong>Tedavi Sayısı:</strong> Tamamlanan tedavi işlemleri</li>
                                <li><strong>Gelir Getirisi:</strong> Doktor bazlı gelir</li>
                                <li><strong>Hasta Memnuniyeti:</strong> Değerlendirme puanları</li>
                                <li><strong>Çalışma Saatleri:</strong> Aktif çalışma süresi</li>
                                <li><strong>Randevu Doluluk Oranı:</strong> Takvim kullanım oranı</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Tedavi Gelir Raporu -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Tedavi Gelir Raporu</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Yapılan tedavilerin gelir analizini gösteren rapor.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Rapor İçeriği</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Tedavi Türleri:</strong> En çok yapılan tedaviler</li>
                                <li><strong>Gelir Dağılımı:</strong> Tedavi bazlı gelir</li>
                                <li><strong>Ortalama Tutar:</strong> Tedavi başı ortalama gelir</li>
                                <li><strong>Popüler Tedaviler:</strong> Talep sıralaması</li>
                                <li><strong>Mevsimsel Değişim:</strong> Aylık/sezonsal analiz</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Randevu Analiz Raporu -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Randevu Analiz Raporu</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Randevu istatistiklerini inceleyen rapor.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Rapor İçeriği</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Toplam Randevu:</strong> Belirtilen dönemde verilen randevu sayısı</li>
                                <li><strong>Gelme Oranı:</strong> Randevuya gelen hasta yüzdesi</li>
                                <li><strong>İptal Oranı:</strong> İptal edilen randevu yüzdesi</li>
                                <li><strong>Günlük Dağılım:</strong> Haftanın günlerine göre randevu dağılımı</li>
                                <li><strong>Saatlik Dağılım:</strong> Günün saatlerine göre yoğunluk</li>
                                <li><strong>Doktor Dağılımı:</strong> Doktor bazlı randevu sayısı</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Hasta Kazanım Raporu -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Kazanım Raporu</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Yeni hasta kazanım trendlerini analiz eden rapor.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Rapor İçeriği</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Yeni Hasta Sayısı:</strong> Kazanılmış yeni hasta miktarı</li>
                                <li><strong>Kazanım Kaynakları:</strong> Hastaların nereden geldiği</li>
                                <li><strong>Aylık Trend:</strong> Zaman içindeki kazanım grafiği</li>
                                <li><strong>Referans Oranı:</strong> Mevcut hastaların getirdiği yeni hasta</li>
                                <li><strong>Bölgesel Dağılım:</strong> Hastaların coğrafi dağılımı</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Stok Raporları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Stok Raporları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Malzeme stokları ile ilgili analizler.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Aylık Gider Raporu</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Aylık harcama toplamı</li>
                                    <li>Kategori bazlı dağılım</li>
                                    <li>Tedarikçi harcamaları</li>
                                    <li>Gider trendleri</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Kritik Stok Raporu</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Minimum seviyenin altındaki malzemeler</li>
                                    <li>Yeniden sipariş gereken ürünler</li>
                                    <li>Stok değeri analizi</li>
                                    <li>Tedarikçi iletişim bilgileri</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Rapor Filtreleme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Rapor Filtreleme ve Özelleştirme</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Raporları ihtiyaçlarınıza göre özelleştirme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Filtre Seçenekleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Tarih Aralığı:</strong> Başlangıç ve bitiş tarihi seçimi</li>
                                <li><strong>Doktor Filtresi:</strong> Belirli doktorların verileri</li>
                                <li><strong>Tedavi Filtresi:</strong> Belirli tedavi türleri</li>
                                <li><strong>Hasta Grubu:</strong> Yeni/eski hasta ayrımı</li>
                                <li><strong>Maliyet Aralığı:</strong> Tutar bazlı filtreleme</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Rapor Dışa Aktarma -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Rapor Dışa Aktarma</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Raporları farklı formatlarda kaydetme ve paylaşma.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">PDF Formatı</h4>
                                <p class="text-sm text-blue-800 dark:text-blue-200">Yazdırılabilir, paylaşılabilir</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 dark:text-green-100 mb-2">Excel Formatı</h4>
                                <p class="text-sm text-green-800 dark:text-green-200">Veri analizi için uygun</p>
                            </div>
                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                                <h4 class="font-semibold text-orange-900 dark:text-orange-100 mb-2">Ekran Görüntüsü</h4>
                                <p class="text-sm text-orange-800 dark:text-orange-200">Hızlı paylaşım için</p>
                            </div>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Kullanım İpuçları</h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Raporları Etkili Kullanma</h3>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                <li><strong>Düzenli İnceleme:</strong> Raporları haftalık/aylık inceleyin</li>
                                <li><strong>Trend Takibi:</strong> Zaman içindeki değişimleri gözleyin</li>
                                <li><strong>Karşılaştırma:</strong> Farklı dönemleri karşılaştırın</li>
                                <li><strong>Paylaşım:</strong> Önemli raporları ekip ile paylaşın</li>
                                <li><strong>Yedekleme:</strong> Önemli raporları saklayın</li>
                                <li><strong>Analiz:</strong> Verilerden çıkarımlar yapın</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sorun Giderme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sık Karşılaşılan Sorunlar</h2>
                        <div class="space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Veri Bulunamıyor</h3>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Seçilen tarih aralığında veri yok.
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Çözüm: Tarih aralığını genişletin veya farklı filtreler deneyin.
                                </p>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Rapor Yüklenmiyor</h3>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    Rapor çok büyük olduğu için yüklenmiyor.
                                </p>
                                <p class="text-sm text-orange-700 dark:text-orange-300">
                                    Çözüm: Tarih aralığını kısaltın veya daha spesifik filtreler uygulayın.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>