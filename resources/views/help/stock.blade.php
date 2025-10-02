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

                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-8">Stok Yönetimi Detaylı Kılavuzu</h1>

                <div class="space-y-12">
                    <!-- Giriş -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Giriş</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Stok yönetimi, kliniğinizde kullanılan malzemelerin takibini sağlar.
                            Malzeme giriş-çıkışlarını, kritik stok seviyelerini ve maliyetleri yönetir.
                        </p>
                        <p class="text-slate-700 dark:text-slate-300">
                            Düzenli stok kontrolü ile malzemelerinizin tükenmesini önler ve maliyetleri optimize eder.
                        </p>
                    </section>

                    <!-- Stok Ana Sayfası -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Stok Ana Sayfası</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Stok bölümüne girdiğinizde karşınıza çıkan genel görünüm.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Sol Panel -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Sol Panel - Hızlı Erişim</h3>
                                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                    <li><strong>Malzemeler:</strong> Tüm malzemelerin listesi</li>
                                    <li><strong>Kategoriler:</strong> Malzeme grupları</li>
                                    <li><strong>Tedarikçiler:</strong> Malzeme sağlayan firmalar</li>
                                    <li><strong>Satın Almalar:</strong> Yapılan alımlar</li>
                                    <li><strong>Kullanım:</strong> Malzeme çıkışları</li>
                                </ul>
                            </div>

                            <!-- Sağ Panel -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Sağ Panel - Uyarılar</h3>
                                <ul class="text-sm text-green-800 dark:text-green-200 space-y-2">
                                    <li><strong>Kritik Stok:</strong> Azalan malzemeler</li>
                                    <li><strong>Vadesi Geçen:</strong> Ödenmemiş faturalar</li>
                                    <li><strong>Yaklaşan Vade:</strong> Yakında ödenmesi gerekenler</li>
                                    <li><strong>Stok Değer:</strong> Toplam malzeme maliyeti</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Malzeme Yönetimi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Malzeme Yönetimi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Kliniğinizde kullandığınız malzemeleri kayıt altına alma ve takip etme.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Yeni Malzeme Ekleme -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Yeni Malzeme Ekleme</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>"Malzemeler" bölümüne gidin</li>
                                    <li>"Yeni Malzeme" butonuna tıklayın</li>
                                    <li>Malzeme adını girin</li>
                                    <li>Kategori seçin</li>
                                    <li>Birim (adet, kutu, paket vb.) belirleyin</li>
                                    <li>Minimum stok seviyesini ayarlayın</li>
                                    <li>"Kaydet" butonuna tıklayın</li>
                                </ol>
                            </div>

                            <!-- Malzeme Bilgilerini Güncelleme -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Malzeme Bilgilerini Güncelleme</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Malzeme listesinden düzenlemek istediğinizi bulun</li>
                                    <li>"Düzenle" butonuna tıklayın</li>
                                    <li>Gerekli değişiklikleri yapın</li>
                                    <li>"Güncelle" butonuna tıklayın</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Stok Giriş İşlemleri -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Stok Giriş İşlemleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Yeni malzeme alındığında stok girişi yapma.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Satın Alma İşlemi</h3>
                            <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li>"Satın Almalar" bölümüne gidin</li>
                                <li>"Yeni Satın Alma" butonuna tıklayın</li>
                                <li>Tedarikçi seçin</li>
                                <li>Malzemeleri ve miktarları ekleyin</li>
                                <li>Fiyatları girin</li>
                                <li>Fatura bilgilerini ekleyin</li>
                                <li>"Kaydet" butonuna tıklayın</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Stok Çıkış İşlemleri -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Stok Çıkış İşlemleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Malzeme kullanıldığında stok düşümü yapma.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Kullanım Kaydı</h3>
                            <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li>"Kullanım" bölümüne gidin</li>
                                <li>"Yeni Kullanım" butonuna tıklayın</li>
                                <li>Hasta veya işlem seçin</li>
                                <li>Kullanılan malzemeleri belirtin</li>
                                <li>Miktarları girin</li>
                                <li>"Kaydet" butonuna tıklayın</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Kritik Stok Takibi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Kritik Stok Takibi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Stok seviyesi azalan malzemeleri takip etme.
                        </p>

                        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Kritik Stok Uyarıları</h3>
                            <ul class="text-sm text-orange-800 dark:text-orange-200 space-y-2">
                                <li><strong>Minimum Seviye:</strong> Her malzeme için belirlenen alt sınır</li>
                                <li><strong>Otomatik Uyarı:</strong> Stok azaldığında sistem uyarır</li>
                                <li><strong>Yeniden Sipariş:</strong> Tedarikçiye sipariş verme zamanı</li>
                                <li><strong>Alternatifler:</strong> Stok bittiğinde kullanılabilecek malzemeler</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Tedarikçi Yönetimi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Tedarikçi Yönetimi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Malzeme sağlayan firmaları kayıt etme ve takip etme.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Yeni Tedarikçi Ekleme</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>"Tedarikçiler" bölümüne gidin</li>
                                    <li>"Yeni Tedarikçi" butonuna tıklayın</li>
                                    <li>Firma bilgilerini girin</li>
                                    <li>İletişim bilgilerini ekleyin</li>
                                    <li>"Kaydet" butonuna tıklayın</li>
                                </ol>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Tedarikçi Geçmişi</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Önceki alımlar</li>
                                    <li>Ödeme geçmişi</li>
                                    <li>Teslim süreleri</li>
                                    <li>Kalite değerlendirmesi</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Stok Hareketleri -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Stok Hareketleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Malzemelerin giriş ve çıkış kayıtlarını görüntüleme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Hareket Takibi</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Giriş Hareketleri:</strong> Satın almalar ve iadeler</li>
                                <li><strong>Çıkış Hareketleri:</strong> Kullanımlar ve fireler</li>
                                <li><strong>Stok Düzeltmeleri:</strong> Sayım farkları</li>
                                <li><strong>Tarih Filtreleme:</strong> Belirli dönem hareketleri</li>
                                <li><strong>Malzeme Geçmişi:</strong> Tek malzemenin tüm hareketleri</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Maliyet Takibi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Maliyet Takibi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Malzeme maliyetlerini ve giderleri takip etme.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Satın Alma Maliyetleri</h3>
                                <ul class="text-sm text-green-800 dark:text-green-200 space-y-2">
                                    <li>Birim fiyatlar</li>
                                    <li>Toplam harcama</li>
                                    <li>Tedarikçi karşılaştırması</li>
                                    <li>En ekonomik seçenekler</li>
                                </ul>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Stok Değeri</h3>
                                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                    <li>Toplam stok maliyeti</li>
                                    <li>Kategori bazlı dağılım</li>
                                    <li>En değerli malzemeler</li>
                                    <li>Stok devir hızı</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Raporlar -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Stok Raporları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Stok işlemleriniz hakkında detaylı raporlar.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Stok Durum Raporu</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">Mevcut stok seviyeleri</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Hareket Raporu</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">Giriş-çıkış hareketleri</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Maliyet Raporu</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">Harcama analizleri</p>
                            </div>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Kullanım İpuçları</h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Stok Yönetimini Etkili Kullanma</h3>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                <li><strong>Düzenli Sayım:</strong> Stoklarınızı periyodik olarak sayın</li>
                                <li><strong>Minimum Seviye:</strong> Her malzeme için alt sınır belirleyin</li>
                                <li><strong>Tedarikçi Değerlendirmesi:</strong> Kalite ve fiyatı karşılaştırın</li>
                                <li><strong>Fire Takibi:</strong> Malzeme kayıplarını kaydedin</li>
                                <li><strong>Maliyet Kontrolü:</strong> Fiyat değişimlerini takip edin</li>
                                <li><strong>Yedek Stok:</strong> Kritik malzemeler için yedek bulundurun</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sorun Giderme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sık Karşılaşılan Sorunlar</h2>
                        <div class="space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Stok Eksiği</h3>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Malzeme stoğu eksiye düşüyor.
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Çözüm: Stok girişini kontrol edin veya minimum seviyeyi ayarlayın.
                                </p>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Fiyat Farklılığı</h3>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    Sistemdeki fiyatlar gerçek fiyatlarla uyuşmuyor.
                                </p>
                                <p class="text-sm text-orange-700 dark:text-orange-300">
                                    Çözüm: Malzeme fiyatlarını güncelleyin veya yeni satın alma kaydı oluşturun.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>