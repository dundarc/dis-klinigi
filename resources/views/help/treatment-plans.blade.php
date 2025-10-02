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

                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-8">Tedavi Planları Detaylı Kılavuzu</h1>

                <div class="space-y-12">
                    <!-- Giriş -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Giriş</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Tedavi planları, hastalarınızın diş tedavi süreçlerini planlamak, takip etmek ve yönetmek için kullanılan kapsamlı bir sistemdir.
                        </p>
                        <p class="text-slate-700 dark:text-slate-300">
                            Bu bölümde tedavi planları oluşturabilir, düzenleyebilir, ilerlemeyi takip edebilir ve maliyet hesaplaması yapabilirsiniz.
                        </p>
                    </section>

                    <!-- Tedavi Planı Oluşturma -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Tedavi Planı Oluşturma</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Yeni bir tedavi planı oluşturma adımları.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Hasta Seçimi</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Hasta detay sayfasında "Tedavi Planları" bölümüne gidin</li>
                                    <li>"Yeni Plan Oluştur" butonuna tıklayın</li>
                                    <li>Hasta bilgileri otomatik olarak yüklenecektir</li>
                                    <li>Doktor seçimini yapın</li>
                                </ol>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Tedavi Kalemleri Ekleme</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>"Tedavi Kalemi Ekle" butonuna tıklayın</li>
                                    <li>Tedavi türünü seçin (dolgu, çekim, implant vb.)</li>
                                    <li>Diş numarasını belirtin</li>
                                    <li>Tahmini fiyatı girin</li>
                                    <li>Randevu tarihini planlayın</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Tedavi Planı Durumları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Tedavi Planı Durumları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Tedavi planlarının farklı aşamaları.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Taslak</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">
                                    Henüz onaylanmamış planlar
                                </p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 dark:text-green-100 mb-2">Aktif</h4>
                                <p class="text-sm text-green-800 dark:text-green-200">
                                    Tedavi devam eden planlar
                                </p>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Tamamlandı</h4>
                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                    Başarıyla biten tedaviler
                                </p>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <h4 class="font-semibold text-red-900 dark:text-red-100 mb-2">İptal Edildi</h4>
                                <p class="text-sm text-red-800 dark:text-red-200">
                                    Durdurulan planlar
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Tedavi Kalemleri Yönetimi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Tedavi Kalemleri Yönetimi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Bireysel tedavi işlemlerini yönetme.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Kalem Özellikleri</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li><strong>Tedavi Türü:</strong> Yapılacak işlem</li>
                                    <li><strong>Diş Numarası:</strong> Hangi diş</li>
                                    <li><strong>Tahmini Maliyet:</strong> Beklenen ücret</li>
                                    <li><strong>Planlanan Tarih:</strong> Yapılacak tarih</li>
                                    <li><strong>Durum:</strong> Bekliyor/Tamamlandı</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Durum Güncellemeleri</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li><strong>Bekliyor:</strong> Henüz yapılmadı</li>
                                    <li><strong>Devam Ediyor:</strong> İşlem başladı</li>
                                    <li><strong>Tamamlandı:</strong> Başarıyla bitti</li>
                                    <li><strong>İptal Edildi:</strong> Yapılmayacak</li>
                                    <li><strong>Ertelendi:</strong> Tarih değişti</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Maliyet Hesaplama -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Maliyet Hesaplama ve Raporlama</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Tedavi planlarının maliyet analizi.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Maliyet Raporu</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Toplam Tahmini Maliyet:</strong> Planlanan tüm işlemlerin toplamı</li>
                                <li><strong>Yapılan İşlemler:</strong> Tamamlanan tedavilerin maliyeti</li>
                                <li><strong>Kalan Tutar:</strong> Ödenmesi gereken miktar</li>
                                <li><strong>Kar Marjı:</strong> Maliyet ve satış fiyatı karşılaştırması</li>
                                <li><strong>PDF Raporu:</strong> Hasta için detaylı maliyet raporu</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Randevu Entegrasyonu -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Randevu Entegrasyonu</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Tedavi planlarının takvimle bağlantısı.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Otomatik Randevu Oluşturma</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Tedavi kalemi eklendiğinde randevu otomatik oluşur</li>
                                    <li>Doktor müsaitliğine göre tarih önerilir</li>
                                    <li>Hasta onayından sonra takvime eklenir</li>
                                    <li>Randevu hatırlatmaları otomatik gönderilir</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Randevu Takibi</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Tedavi ilerlemesi randevularla takip edilir</li>
                                    <li>Gelmeyen hastalar için hatırlatma yapılır</li>
                                    <li>Randevu değişiklikleri otomatik güncellenir</li>
                                    <li>Tedavi planı raporlarına yansır</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Fatura Entegrasyonu -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Fatura ve Ödeme Takibi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Tedavi planlarının finansal yönetimi.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Otomatik Fatura Oluşturma</h3>
                            <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li>Tedavi tamamlandığında fatura otomatik oluşur</li>
                                <li>Hasta bilgileri ve tedavi detayları eklenir</li>
                                <li>Tahmini maliyetler gerçek maliyetlere dönüştürülür</li>
                                <li>Hasta onayından sonra gönderilir</li>
                                <li>Ödeme takibi başlar</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Arama ve Filtreleme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Arama ve Filtreleme</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Tedavi planlarını hızlı bulma.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Arama Seçenekleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Hasta Adı:</strong> Hasta adına göre arama</li>
                                <li><strong>Doktor:</strong> Doktor adına göre filtreleme</li>
                                <li><strong>Durum:</strong> Aktif, tamamlandı, iptal edildi</li>
                                <li><strong>Tarih Aralığı:</strong> Oluşturulma tarihine göre</li>
                                <li><strong>Tedavi Türü:</strong> Belirli tedavilere göre</li>
                            </ul>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Kullanım İpuçları</h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Tedavi Planlarını Etkili Kullanma</h3>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                <li><strong>Detaylı Planlama:</strong> Tüm tedavi aşamalarını önceden planlayın</li>
                                <li><strong>Maliyet Hesaplama:</strong> Gerçekçi fiyatlar belirleyin</li>
                                <li><strong>Hasta Onayı:</strong> Planı hastaya açıklayıp onay alın</li>
                                <li><strong>Düzenli Takip:</strong> Tedavi ilerlemesini düzenli kontrol edin</li>
                                <li><strong>Belgeleme:</strong> Tüm değişiklikleri not alın</li>
                                <li><strong>Yedekleme:</strong> Önemli planları yedekleyin</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sorun Giderme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sık Karşılaşılan Sorunlar</h2>
                        <div class="space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Tedavi Kalemi Eklenemiyor</h3>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Yeni tedavi kalemi eklenirken hata alınıyor.
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Çözüm: Tedavi türlerinin sistemde tanımlandığından emin olun.
                                </p>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Maliyet Hesaplaması Yanlış</h3>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    Toplam maliyet yanlış hesaplanıyor.
                                </p>
                                <p class="text-sm text-orange-700 dark:text-orange-300">
                                    Çözüm: Bireysel tedavi kalemlerinin fiyatlarını kontrol edin.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 border-t border-slate-200 dark:border-slate-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-center md:text-left mb-4 md:mb-0">
                        <p class="text-slate-600 dark:text-slate-400 text-sm">
                            Geliştirici: <span class="font-semibold text-slate-900 dark:text-slate-100">dundarc</span>
                        </p>
                        <a href="mailto:developer@dundarc.com.tr" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                            developer@dundarc.com.tr
                        </a>
                    </div>
                    <div class="text-center md:text-right">
                        <p class="text-slate-900 dark:text-slate-100 font-semibold">
                            KYS - Klinik Yönetim Sistemi
                        </p>
                        <p class="text-slate-600 dark:text-slate-400 text-sm">
                            Versiyon: 1.0 (Stable)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>