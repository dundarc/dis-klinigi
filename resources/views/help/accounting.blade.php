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

                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-8">Muhasebe Detaylı Kılavuzu</h1>

                <div class="space-y-12">
                    <!-- Giriş -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Giriş</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Muhasebe bölümü, kliniğinizin tüm finansal işlemlerini yönetmenizi sağlar.
                            Faturalar, ödemeler, alacaklar ve borçlar burada takip edilir.
                        </p>
                        <p class="text-slate-700 dark:text-slate-300">
                            Düzenli muhasebe kayıtları, işletmenizin mali sağlığını korumak için önemlidir.
                        </p>
                    </section>

                    <!-- Muhasebe Ana Sayfası -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Muhasebe Ana Sayfası</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Muhasebe bölümüne girdiğinizde genel mali durumu görebilirsiniz.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Sol Panel -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Sol Panel - Hızlı İşlemler</h3>
                                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                    <li><strong>Yeni Fatura:</strong> Hasta için fatura oluştur</li>
                                    <li><strong>Fatura Listesi:</strong> Tüm faturaları görüntüle</li>
                                    <li><strong>Ödenmemiş Faturalar:</strong> Takip edilmesi gereken alacaklar</li>
                                    <li><strong>Çöp Kutusu:</strong> Silinen faturalar</li>
                                </ul>
                            </div>

                            <!-- Sağ Panel -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Sağ Panel - İstatistikler</h3>
                                <ul class="text-sm text-green-800 dark:text-green-200 space-y-2">
                                    <li><strong>Toplam Alacak:</strong> Tahsil edilmesi gereken tutar</li>
                                    <li><strong>Bu Ayki Gelir:</strong> Aylık tahsilat</li>
                                    <li><strong>Vadesi Geçen:</strong> Gecikmiş ödemeler</li>
                                    <li><strong>Ortalama Tahsilat:</strong> Hasta başı ortalama</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Fatura Oluşturma -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Fatura Oluşturma</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hasta tedavileri için fatura kesme işlemi.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Manuel Fatura -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Manuel Fatura Oluşturma</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>"Muhasebe" menüsüne tıklayın</li>
                                    <li>"Yeni Fatura" butonuna tıklayın</li>
                                    <li>Hasta seçin veya yeni hasta ekleyin</li>
                                    <li>Tedavi kalemlerini ekleyin</li>
                                    <li>Fiyatları ve miktarları girin</li>
                                    <li>Vade tarihi belirleyin</li>
                                    <li>"Kaydet" butonuna tıklayın</li>
                                </ol>
                            </div>

                            <!-- Tedavi Planından -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Tedavi Planından Fatura</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Tedavi planı detayına gidin</li>
                                    <li>"Fatura Oluştur" butonuna tıklayın</li>
                                    <li>Tedavi kalemleri otomatik gelir</li>
                                    <li>Fiyatları kontrol edin</li>
                                    <li>Gerekli düzenlemeleri yapın</li>
                                    <li>Faturayı kaydedin</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Fatura Yönetimi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Fatura Yönetimi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Oluşturulan faturaları düzenleme ve takip etme.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Fatura Düzenleme -->
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100 mb-3">Fatura Düzenleme</h3>
                                <ol class="list-decimal list-inside text-sm text-yellow-800 dark:text-yellow-200 space-y-2">
                                    <li>Fatura detayına gidin</li>
                                    <li>"Düzenle" butonuna tıklayın</li>
                                    <li>Gerekli değişiklikleri yapın</li>
                                    <li>"Güncelle" butonuna tıklayın</li>
                                </ol>
                            </div>

                            <!-- Ödeme Kaydı -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Ödeme Kaydı</h3>
                                <ol class="list-decimal list-inside text-sm text-green-800 dark:text-green-200 space-y-2">
                                    <li>Fatura detayına gidin</li>
                                    <li>"Ödeme Ekle" butonuna tıklayın</li>
                                    <li>Ödeme tutarını girin</li>
                                    <li>Ödeme yöntemini seçin</li>
                                    <li>Tarih ve açıklama ekleyin</li>
                                    <li>"Kaydet" butonuna tıklayın</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Fatura Durumları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Fatura Durumları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Faturaların ödeme durumlarını gösteren renk kodları.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Taslak</h4>
                                <p class="text-sm text-blue-800 dark:text-blue-200">Henüz kaydedilmemiş</p>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                <h4 class="font-semibold text-yellow-900 dark:text-yellow-100 mb-2">Ödenmemiş</h4>
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">Henüz ödenmemiş</p>
                            </div>
                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                                <h4 class="font-semibold text-orange-900 dark:text-orange-100 mb-2">Vadesi Geçmiş</h4>
                                <p class="text-sm text-orange-800 dark:text-orange-200">Ödeme tarihi geçmiş</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 dark:text-green-100 mb-2">Ödenmiş</h4>
                                <p class="text-sm text-green-800 dark:text-green-200">Tamamen ödenmiş</p>
                            </div>
                        </div>
                    </section>

                    <!-- Ödeme Yöntemleri -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Ödeme Yöntemleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistemde kayıtlı ödeme yöntemleri.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Nakit</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">Peşin nakit ödeme</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Kredi Kartı</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">POS üzerinden kart</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Havale/EFT</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">Banka transferi</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Çek</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">Çek ile ödeme</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Senet</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">Vadeli senet</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Taksit</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">Taksitli ödeme</p>
                            </div>
                        </div>
                    </section>

                    <!-- Fatura Arama ve Filtreleme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Fatura Arama ve Filtreleme</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Faturaları hızlıca bulmak için arama özellikleri.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Arama Seçenekleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Hasta Adı:</strong> Hasta adına göre arama</li>
                                <li><strong>Fatura Numarası:</strong> Sistem fatura numarası</li>
                                <li><strong>Tarih Aralığı:</strong> Belirli tarihler arası</li>
                                <li><strong>Durum Filtresi:</strong> Ödeme durumuna göre</li>
                                <li><strong>Tutar Aralığı:</strong> Belirli tutar arası</li>
                                <li><strong>Doktor Filtresi:</strong> Doktor bazlı faturalar</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Fatura PDF ve Yazdırma -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Fatura PDF ve Yazdırma</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Faturaları PDF olarak kaydetme ve yazdırma.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">PDF İndirme</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Fatura detayına gidin</li>
                                    <li>"PDF İndir" butonuna tıklayın</li>
                                    <li>Dosya bilgisayarınıza inecek</li>
                                    <li>Hasta ile paylaşabilirsiniz</li>
                                </ol>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Yazdırma</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>PDF dosyasını açın</li>
                                    <li>Yazdırma komutunu kullanın</li>
                                    <li>Yazıcı ayarlarını kontrol edin</li>
                                    <li>Faturayı yazdırın</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Taksit ve Vadeli Ödemeler -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Taksit ve Vadeli Ödemeler</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Uzun vadeli ödemeleri takip etme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Taksit Planı Oluşturma</h3>
                            <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li>Yüksek tutarlı faturada taksit seçeneği belirleyin</li>
                                <li>Taksit sayısını girin</li>
                                <li>İlk ödeme tarihini ayarlayın</li>
                                <li>Her taksit tutarı otomatik hesaplanır</li>
                                <li>Taksit planını kaydedin</li>
                                <li>Her ödeme geldiğinde kaydedin</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Alacak Takibi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Alacak Takibi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Ödenmemiş faturaları takip etme ve hatırlatma.
                        </p>

                        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Vadesi Geçmiş Faturalar</h3>
                            <ul class="text-sm text-orange-800 dark:text-orange-200 space-y-2">
                                <li><strong>Hatırlatma:</strong> Otomatik SMS/e-posta gönderimi</li>
                                <li><strong>Takip:</strong> Düzenli borç sorgulama</li>
                                <li><strong>Yasal İşlem:</strong> Gerektiğinde hukuki yollara başvurma</li>
                                <li><strong>Reeskont:</strong> Vadeyi uzatma veya indirim</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Vergi ve Beyan -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Vergi ve Beyan İşlemleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Vergi mükellefi olarak yapılması gereken işlemler.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Vergi Raporları</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>KDV Beyannamesi:</strong> Aylık KDV hesaplaması</li>
                                <li><strong>Gelir Vergisi:</strong> Yıllık gelir vergisi</li>
                                <li><strong>Muhtasar Beyanname:</strong> Aylık serbest meslek kazançları</li>
                                <li><strong>Defter Tutma:</strong> Düzenli kayıt sistemi</li>
                            </ul>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Kullanım İpuçları</h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Muhasebeyi Etkili Kullanma</h3>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                <li><strong>Düzenli Kayıt:</strong> Tüm işlemleri aynı gün kaydedin</li>
                                <li><strong>Doğru Faturalandırma:</strong> Fatura bilgilerini eksiksiz girin</li>
                                <li><strong>Ödeme Takibi:</strong> Vadesi gelen faturaları takip edin</li>
                                <li><strong>Yedekleme:</strong> Mali kayıtları düzenli yedekleyin</li>
                                <li><strong>Raporlama:</strong> Aylık mali raporları inceleyin</li>
                                <li><strong>Vergi Takibi:</strong> Vergi dönemlerini kaçırmayın</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sorun Giderme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sık Karşılaşılan Sorunlar</h2>
                        <div class="space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Fatura Düzenlenemiyor</h3>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Fatura oluştururken hata alınıyor.
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Çözüm: Zorunlu alanları doldurun ve hasta bilgilerini kontrol edin.
                                </p>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Ödeme Kaydedilmiyor</h3>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    Yapılan ödeme sisteme kaydedilmiyor.
                                </p>
                                <p class="text-sm text-orange-700 dark:text-orange-300">
                                    Çözüm: Ödeme yöntemini ve tutarı doğru girdiğinizden emin olun.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>