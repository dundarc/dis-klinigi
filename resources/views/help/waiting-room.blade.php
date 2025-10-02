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

                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-8">Bekleme Odası Detaylı Kılavuzu</h1>

                <div class="space-y-12">
                    <!-- Giriş -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Giriş</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Bekleme odası, kliniğinize gelen hastaları karşılamak, önceliklendirmek ve tedavi sürecini başlatmak için kullanılan merkezidir.
                        </p>
                        <p class="text-slate-700 dark:text-slate-300">
                            Acil durumları hızlıca tespit edip müdahale etmenizi sağlar ve hasta akışını düzenler.
                        </p>
                    </section>

                    <!-- Bekleme Odası Görünümü -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Bekleme Odası Ana Sayfası</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Bekleme odası açıldığında karşınıza çıkan ana görünüm.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Sol Panel -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Sol Panel - Hasta Listesi</h3>
                                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                    <li><strong>Bekleyen Hastalar:</strong> Randevulu gelen hastalar</li>
                                    <li><strong>Yürüyen:</strong> Tedavi devam eden hastalar</li>
                                    <li><strong>Acil:</strong> Acil durumlu hastalar (kırmızı)</li>
                                    <li><strong>Tamamlanan:</strong> İşlemi biten hastalar</li>
                                </ul>
                            </div>

                            <!-- Sağ Panel -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Sağ Panel - Hızlı İşlemler</h3>
                                <ul class="text-sm text-green-800 dark:text-green-200 space-y-2">
                                    <li><strong>Yeni Randevu:</strong> Acil hasta için randevu oluştur</li>
                                    <li><strong>Acil Hasta Ekle:</strong> Bekleme listesine acil hasta ekle</li>
                                    <li><strong>Hasta Ara:</strong> Sistemdeki hastaları bul</li>
                                    <li><strong>Günlük Özet:</strong> Günün istatistikleri</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Hasta Kabulü -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Kabulü</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Kliniğinize gelen hastaları sisteme almak için yapılan işlemler.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Randevulu Hasta -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Randevulu Hasta Kabulü</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Hasta geldiğinde ismini sorun</li>
                                    <li>Bekleme odasında "Bekleyen" listesinden bulun</li>
                                    <li>"Kabul Et" butonuna tıklayın</li>
                                    <li>Hasta bilgilerini kontrol edin</li>
                                    <li>Triaj seviyesini belirleyin (varsa)</li>
                                    <li>Doktor odasına yönlendirin</li>
                                </ol>
                            </div>

                            <!-- Randevusuz Hasta -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Randevusuz Hasta Kabulü</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Hasta bilgilerini alın (ad, telefon, şikayet)</li>
                                    <li>"Acil Hasta Ekle" butonuna tıklayın</li>
                                    <li>Hasta kaydı oluşturun veya mevcut kaydı bulun</li>
                                    <li>Acil durum seviyesini belirleyin</li>
                                    <li>Doktor değerlendirmesi için hazırlayın</li>
                                    <li>Gerekirse randevu oluşturun</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Triaj Sistemi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Triaj (Önceliklendirme) Sistemi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hastaların aciliyet durumuna göre önceliklendirilmesi.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <h4 class="font-semibold text-red-900 dark:text-red-100 mb-2">Kırmızı Kod (Acil)</h4>
                                <ul class="text-sm text-red-800 dark:text-red-200 space-y-1">
                                    <li>Hemen müdahale gerekli</li>
                                    <li>Hayati tehlike var</li>
                                    <li>Derhal doktor müdahalesi</li>
                                </ul>
                            </div>
                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                                <h4 class="font-semibold text-orange-900 dark:text-orange-100 mb-2">Sarı Kod (Önemli)</h4>
                                <ul class="text-sm text-orange-800 dark:text-orange-200 space-y-1">
                                    <li>Ağrı veya rahatsızlık var</li>
                                    <li>Acil müdahale gerekmiyor</li>
                                    <li>Yakın zamanda bakılmalı</li>
                                </ul>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 dark:text-green-100 mb-2">Yeşil Kod (Normal)</h4>
                                <ul class="text-sm text-green-800 dark:text-green-200 space-y-1">
                                    <li>Acil durum yok</li>
                                    <li>Rutin kontrol</li>
                                    <li>Randevu sırasına göre</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Tedavi Süreci -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Tedavi Süreci Yönetimi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hastanın kliniğinizde bulunduğu süre boyunca yapılan işlemler.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Tedavi Adımları</h3>
                            <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Kabul:</strong> Hasta bekleme odasına alınır</li>
                                <li><strong>Triaj:</strong> Aciliyet durumu belirlenir</li>
                                <li><strong>Doktor Muayenesi:</strong> Doktor hastayı değerlendirir</li>
                                <li><strong>Tedavi Planı:</strong> Gerekli işlemler belirlenir</li>
                                <li><strong>Tedavi Uygulaması:</strong> İşlemler gerçekleştirilir</li>
                                <li><strong>Tamamlandı:</strong> İşlem başarıyla bitirilir</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Hasta Detay Sayfası -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Detay Sayfası</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Her hasta için ayrı detay sayfası bulunur.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Hasta Bilgileri</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Ad, soyad, telefon</li>
                                    <li>Randevu tarihi ve saati</li>
                                    <li>Şikayet ve notlar</li>
                                    <li>Önceki tedavi geçmişi</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Tedavi İşlemleri</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Tedavi planı oluşturma</li>
                                    <li>Tedavi kalemleri ekleme</li>
                                    <li>Fatura oluşturma</li>
                                    <li>Tedavi notları</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Arama ve Filtreleme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Arama ve Filtreleme</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Bekleme odasında hastaları hızlıca bulmak için.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Arama Seçenekleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Hasta Adı:</strong> Ad veya soyada göre arama</li>
                                <li><strong>Telefon:</strong> Telefon numarası ile bulma</li>
                                <li><strong>Randevu Saati:</strong> Belirli saatlerdeki hastalar</li>
                                <li><strong>Durum:</strong> Bekleyen, tedavi gören, tamamlanan</li>
                                <li><strong>Doktor:</strong> Belirli doktorun hastaları</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Acil Durum Yönetimi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Acil Durum Yönetimi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Beklenmedik acil durumlar için hazırlık.
                        </p>

                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Acil Durum Protokolü</h3>
                            <ol class="list-decimal list-inside text-sm text-red-800 dark:text-red-200 space-y-2">
                                <li>Hastayı hemen kabul edin</li>
                                <li>Kırmızı kod olarak işaretleyin</li>
                                <li>Doktoru bilgilendirin</li>
                                <li>Gerekli müdahaleyi başlatın</li>
                                <li>Aileye bilgi verin</li>
                                <li>Kayıtları eksiksiz tutun</li>
                            </ol>
                        </div>
                    </section>

                    <!-- İstatistikler -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Günlük İstatistikler</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Günün performansını takip etmek için.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Toplam Hasta</h4>
                                <p class="text-sm text-blue-800 dark:text-blue-200">Günde kabul edilen hasta sayısı</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 dark:text-green-100 mb-2">Tamamlanan</h4>
                                <p class="text-sm text-green-800 dark:text-green-200">Tedavisi biten hasta sayısı</p>
                            </div>
                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                                <h4 class="font-semibold text-orange-900 dark:text-orange-100 mb-2">Bekleyen</h4>
                                <p class="text-sm text-orange-800 dark:text-orange-200">Henüz işlem görmeyen hasta sayısı</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                <h4 class="font-semibold text-purple-900 dark:text-purple-100 mb-2">Ortalama Süre</h4>
                                <p class="text-sm text-purple-800 dark:text-purple-200">Hasta başı ortalama işlem süresi</p>
                            </div>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Kullanım İpuçları</h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Bekleme Odasını Etkili Kullanma</h3>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                <li><strong>Düzenli Kontrol:</strong> Bekleme odasını sürekli takip edin</li>
                                <li><strong>Hasta Memnuniyeti:</strong> Hastaları bekletmeyin</li>
                                <li><strong>İletişim:</strong> Hasta ve aile ile iyi iletişim kurun</li>
                                <li><strong>Önceliklendirme:</strong> Acil durumları doğru değerlendirin</li>
                                <li><strong>Kayıt Tutma:</strong> Tüm işlemleri sisteme kaydedin</li>
                                <li><strong>Ekip Çalışması:</strong> Doktor ve personel ile koordineli çalışın</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sorun Giderme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sık Karşılaşılan Sorunlar</h2>
                        <div class="space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Hasta Bulunamıyor</h3>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Sistemde hasta kaydı bulunamıyor.
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Çözüm: Yeni hasta kaydı oluşturun veya arama filtrelerini kontrol edin.
                                </p>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Sistem Yavaşlığı</h3>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    Bekleme odası sayfası yavaş açılıyor.
                                </p>
                                <p class="text-sm text-orange-700 dark:text-orange-300">
                                    Çözüm: Sayfayı yenileyin veya internet bağlantınızı kontrol edin.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>