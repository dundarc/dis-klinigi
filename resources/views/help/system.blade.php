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

                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-8">Sistem Yönetimi Detaylı Kılavuzu</h1>

                <div class="space-y-12">
                    <!-- Giriş -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Giriş</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Sistem yönetimi bölümü, kliniğinizin teknik altyapısını yönetmenizi sağlar.
                            Bu bölüm sadece yönetici yetkisine sahip kullanıcılar tarafından kullanılabilir.
                        </p>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                            <p class="text-yellow-800 dark:text-yellow-200 text-sm">
                                <strong>Uyarı:</strong> Sistem ayarlarında yapılan değişiklikler tüm kullanıcıları etkiler.
                                Değişiklik yapmadan önce yedek alın.
                            </p>
                        </div>
                    </section>

                    <!-- Sistem Genel Ayarları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sistem Genel Ayarları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Kliniğinizin temel bilgilerini ve ayarlarını yönetme.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Klinik Bilgileri -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Klinik Bilgileri</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Klinik adı ve adresi</li>
                                    <li>İletişim bilgileri</li>
                                    <li>Vergi bilgileri</li>
                                    <li>Çalışma saatleri</li>
                                </ul>
                            </div>

                            <!-- Sistem Ayarları -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Sistem Ayarları</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Dil ve bölge ayarları</li>
                                    <li>Tarih ve saat formatı</li>
                                    <li>Para birimi</li>
                                    <li>Güvenlik ayarları</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Kullanıcı Yönetimi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Kullanıcı Yönetimi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistem kullanıcılarını ekleme, düzenleme ve yetkilendirme.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Yeni Kullanıcı Ekleme -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Yeni Kullanıcı Ekleme</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>"Sistem" menüsüne tıklayın</li>
                                    <li>"Kullanıcılar" bölümüne gidin</li>
                                    <li>"Yeni Kullanıcı" butonuna tıklayın</li>
                                    <li>Kullanıcı bilgilerini girin</li>
                                    <li>Rol ve yetkileri belirleyin</li>
                                    <li>"Kaydet" butonuna tıklayın</li>
                                </ol>
                            </div>

                            <!-- Kullanıcı Rolleri -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Kullanıcı Rolleri</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li><strong>Yönetici:</strong> Tüm yetkiler</li>
                                    <li><strong>Doktor:</strong> Hasta ve randevu yönetimi</li>
                                    <li><strong>Resepsiyonist:</strong> Randevu ve hasta kaydı</li>
                                    <li><strong>Muhasebeci:</strong> Finansal işlemler</li>
                                    <li><strong>Stok Sorumlusu:</strong> Malzeme yönetimi</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Yedekleme ve Geri Yükleme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Yedekleme ve Geri Yükleme</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistem verilerini koruma ve geri yükleme işlemleri.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Yedek Alma</h3>
                                <ol class="list-decimal list-inside text-sm text-green-800 dark:text-green-200 space-y-2">
                                    <li>"Sistem" menüsüne tıklayın</li>
                                    <li>"Yedekleme" bölümüne gidin</li>
                                    <li>"Yedek Oluştur" butonuna tıklayın</li>
                                    <li>Yedekleme türünü seçin</li>
                                    <li>İşlem tamamlanana kadar bekleyin</li>
                                </ol>
                            </div>

                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Geri Yükleme</h3>
                                <ol class="list-decimal list-inside text-sm text-red-800 dark:text-red-200 space-y-2">
                                    <li>Yedek dosyasını seçin</li>
                                    <li>"Geri Yükle" butonuna tıklayın</li>
                                    <li>Onaylayın</li>
                                    <li>İşlem tamamlanana kadar bekleyin</li>
                                    <li>Sistem yeniden başlatılır</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- E-posta Ayarları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">E-posta Sistemi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            E-posta gönderme ayarları ve şablon yönetimi.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- SMTP Ayarları -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">SMTP Ayarları</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Sunucu bilgileri</li>
                                    <li>Güvenlik ayarları</li>
                                    <li>Kimlik doğrulama</li>
                                    <li>Test gönderme</li>
                                </ul>
                            </div>

                            <!-- E-posta Şablonları -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">E-posta Şablonları</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Randevu hatırlatma</li>
                                    <li>Fatura gönderimi</li>
                                    <li>KVKK onayı</li>
                                    <li>Özel şablonlar</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Tedavi Türleri Yönetimi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Tedavi Türleri Yönetimi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistemde bulunan tedavi türlerini düzenleme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Tedavi Yönetimi</h3>
                            <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li>"Sistem" menüsüne tıklayın</li>
                                <li>"Tedaviler" bölümüne gidin</li>
                                <li>Mevcut tedavileri görüntüleyin</li>
                                <li>Yeni tedavi ekleyin veya düzenleyin</li>
                                <li>Fiyatları ve açıklamaları güncelleyin</li>
                                <li>"Kaydet" butonuna tıklayın</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Sistem Günlükleri -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sistem Günlükleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistem olaylarını takip etme ve sorun giderme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Günlük Türleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Hata Günlükleri:</strong> Sistem hataları</li>
                                <li><strong>Erişim Günlükleri:</strong> Kullanıcı girişleri</li>
                                <li><strong>İşlem Günlükleri:</strong> Yapılan işlemler</li>
                                <li><strong>E-posta Günlükleri:</strong> Gönderilen e-postalar</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Güvenlik Ayarları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Güvenlik Ayarları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistem güvenliğini sağlama ayarları.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Şifre Politikası</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Minimum şifre uzunluğu</li>
                                    <li>Şifre karmaşıklığı</li>
                                    <li>Şifre değiştirme periyodu</li>
                                    <li>Şifre geçmişi kontrolü</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Erişim Kontrolü</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>IP adresi kısıtlaması</li>
                                    <li>Oturum süresi limiti</li>
                                    <li>Çoklu giriş engelleme</li>
                                    <li>Şüpheli aktivite uyarısı</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Yönetim İpuçları</h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Sistem Yönetimini Etkili Kullanma</h3>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                <li><strong>Düzenli Yedek:</strong> Verilerinizi düzenli aralıklarla yedekleyin</li>
                                <li><strong>Günlük Kontrol:</strong> Sistem günlüklerini günlük inceleyin</li>
                                <li><strong>Güvenlik Güncellemeleri:</strong> Sistemi güncel tutun</li>
                                <li><strong>Kullanıcı Eğitimi:</strong> Personeli sisteme adapte edin</li>
                                <li><strong>İzni Yönetimi:</strong> Kullanıcılara gerekli yetkileri verin</li>
                                <li><strong>Destek Alın:</strong> Sorunlarda teknik destek kullanın</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sorun Giderme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sık Karşılaşılan Sorunlar</h2>
                        <div class="space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Sistem Yavaşlığı</h3>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Sistem yavaş çalışıyor veya yanıt vermiyor.
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Çözüm: Sunucu kaynaklarını kontrol edin, gereksiz işlemleri durdurun.
                                </p>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Veri Kaybı</h3>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    Önemli veriler kayboldu veya silindi.
                                </p>
                                <p class="text-sm text-orange-700 dark:text-orange-300">
                                    Çözüm: Yedekten geri yükleme yapın, silme işlemlerini kontrol edin.
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