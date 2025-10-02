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

                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-8">Profil Yönetimi Detaylı Kılavuzu</h1>

                <div class="space-y-12">
                    <!-- Giriş -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Giriş</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Profil bölümü, kişisel bilgilerinizi yönetmenizi ve hesap güvenliğinizi sağlamanızı sağlar.
                            Burada şifre değiştirme, iletişim bilgileri güncelleme gibi işlemler yapılır.
                        </p>
                        <p class="text-slate-700 dark:text-slate-300">
                            Profil bilgileriniz gizli tutulur ve sadece siz tarafından erişilebilir.
                        </p>
                    </section>

                    <!-- Profil Bilgilerini Görüntüleme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Profil Bilgilerini Görüntüleme</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Mevcut profil bilgilerinizi görme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Erişim</h3>
                            <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li>Sol menüden adınıza tıklayın</li>
                                <li>"Profil" seçeneğini seçin</li>
                                <li>Profil bilgileri sayfası açılır</li>
                                <li>Mevcut bilgilerinizi görüntüleyin</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Profil Bilgilerini Güncelleme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Profil Bilgilerini Güncelleme</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Kişisel bilgilerinizi değiştirme.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Kişisel Bilgiler -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Kişisel Bilgiler</h3>
                                <ol class="list-decimal list-inside text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                    <li>"Bilgileri Düzenle" butonuna tıklayın</li>
                                    <li>Ad, soyad gibi bilgileri güncelleyin</li>
                                    <li>E-posta adresinizi değiştirin</li>
                                    <li>Telefon numaranızı ekleyin</li>
                                    <li>"Kaydet" butonuna tıklayın</li>
                                </ol>
                            </div>

                            <!-- Şifre Değiştirme -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Şifre Değiştirme</h3>
                                <ol class="list-decimal list-inside text-sm text-green-800 dark:text-green-200 space-y-2">
                                    <li>"Şifre Değiştir" bölümüne gidin</li>
                                    <li>Mevcut şifrenizi girin</li>
                                    <li>Yeni şifrenizi belirleyin</li>
                                    <li>Yeni şifreyi tekrar girin</li>
                                    <li>"Şifre Değiştir" butonuna tıklayın</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Profil Fotoğrafı -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Profil Fotoğrafı</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Profil fotoğrafınızı ekleme veya değiştirme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Fotoğraf Yükleme</h3>
                            <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li>"Fotoğraf Seç" butonuna tıklayın</li>
                                <li>Bilgisayarınızdan fotoğraf seçin</li>
                                <li>Kırpma alanını ayarlayın</li>
                                <li>"Yükle" butonuna tıklayın</li>
                                <li>Fotoğraf profilinizde görünür</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Hesap Güvenliği -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hesap Güvenliği</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hesabınızın güvenliğini sağlama.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">İki Faktörlü Doğrulama</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Ek güvenlik katmanı</li>
                                    <li>SMS veya uygulama ile kod</li>
                                    <li>Şüpheli girişlerde uyarı</li>
                                    <li>Hesap güvenliği artışı</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Oturum Yönetimi</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Aktif oturumları görüntüleme</li>
                                    <li>Şüpheli oturumları sonlandırma</li>
                                    <li>Cihaz bazlı giriş geçmişi</li>
                                    <li>Güvenlik uyarısı alma</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Bildirim Tercihleri -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Bildirim Tercihleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistem bildirimlerini kişiselleştirme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Bildirim Ayarları</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>E-posta Bildirimleri:</strong> Sistem e-postalarını alma</li>
                                <li><strong>SMS Bildirimleri:</strong> Telefon mesajları</li>
                                <li><strong>Uygulama Bildirimleri:</strong> Sistem içi uyarılar</li>
                                <li><strong>Randevu Hatırlatmaları:</strong> Randevu öncesi uyarılar</li>
                                <li><strong>Sistem Güncellemeleri:</strong> Yeni özellik duyuruları</li>
                                <li><strong>Güvenlik Uyarıları:</strong> Hesap güvenliği</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Dil ve Bölge Ayarları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Dil ve Bölge Ayarları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistem dilini ve bölgesel tercihleri ayarlama.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Dil Seçimi</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Türkçe</li>
                                    <li>İngilizce</li>
                                    <li>Diğer diller (müsaitse)</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Bölgesel Ayarlar</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Tarih formatı</li>
                                    <li>Saat dilimi</li>
                                    <li>Para birimi</li>
                                    <li>Sayı formatı</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Hesap Silme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hesap Silme</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hesabınızı kalıcı olarak silme.
                        </p>

                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Önemli Uyarı</h3>
                            <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                Hesap silme işlemi geri alınamaz. Tüm verileriniz kalıcı olarak silinir.
                            </p>
                            <ol class="list-decimal list-inside text-sm text-red-800 dark:text-red-200 space-y-2">
                                <li>"Hesabı Sil" bölümüne gidin</li>
                                <li>Şifrenizi girin</li>
                                <li>Silme nedenini belirtin</li>
                                <li>"Hesabı Sil" butonuna tıklayın</li>
                                <li>Onay için e-posta gönderilir</li>
                                <li>E-postadaki linke tıklayarak silmeyi tamamlayın</li>
                            </ol>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Güvenlik İpuçları</h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Güçlü Profil Yönetimi</h3>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                <li><strong>Güçlü Şifre:</strong> Büyük-küçük harf, rakam ve sembol kullanın</li>
                                <li><strong>Şifre Değiştirme:</strong> Düzenli aralıklarla şifre değiştirin</li>
                                <li><strong>İki Faktörlü:</strong> Ek güvenlik için 2FA kullanın</li>
                                <li><strong>E-posta Güncel:</strong> Geçerli e-posta adresi kullanın</li>
                                <li><strong>Oturum Kontrolü:</strong> Aktif oturumları takip edin</li>
                                <li><strong>Gizlilik:</strong> Kişisel bilgileri paylaşmayın</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sorun Giderme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sık Karşılaşılan Sorunlar</h2>
                        <div class="space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Şifre Değiştirilemiyor</h3>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Yeni şifre kabul edilmiyor.
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Çözüm: Şifre politikasına uygun güçlü şifre oluşturun.
                                </p>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">E-posta Değiştirilemiyor</h3>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    E-posta güncellemesi yapılamıyor.
                                </p>
                                <p class="text-sm text-orange-700 dark:text-orange-300">
                                    Çözüm: Geçerli bir e-posta adresi girdiğinizden emin olun.
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