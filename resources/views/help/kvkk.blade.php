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

                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-8">KVKK Yönetimi Detaylı Kılavuzu</h1>

                <div class="space-y-12">
                    <!-- Giriş -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Giriş</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            KVKK (Kişisel Verilerin Korunması Kanunu) yönetimi, hasta verilerinin yasal gereklere uygun olarak işlenmesi ve korunması için zorunludur.
                        </p>
                        <p class="text-slate-700 dark:text-slate-300">
                            Bu bölümde hasta onayları, veri işleme izinleri ve KVKK uyumluluk işlemleri yönetilir.
                        </p>
                    </section>

                    <!-- KVKK Temelleri -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">KVKK Temelleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            KVKK'nın temel prensipleri ve gereklilikleri.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100 mb-3">Temel İlkeler</h3>
                                <ul class="text-sm text-yellow-800 dark:text-yellow-200 space-y-2">
                                    <li><strong>Hukuka Uygunluk:</strong> Veriler yasal olarak işlenir</li>
                                    <li><strong>İyi Niyet:</strong> Veriler iyi niyetle kullanılır</li>
                                    <li><strong>Şeffaflık:</strong> Veri işleme açık şekilde yapılır</li>
                                    <li><strong>Veri Güvenliği:</strong> Veriler korunur</li>
                                    <li><strong>Sorumluluk:</strong> Veri sorumluluğu vardır</li>
                                </ul>
                            </div>

                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100 mb-3">Hasta Hakları</h3>
                                <ul class="text-sm text-yellow-800 dark:text-yellow-200 space-y-2">
                                    <li><strong>Bilgi Hakkı:</strong> Verilerinin nasıl kullanıldığını bilme</li>
                                    <li><strong>Erişim Hakkı:</strong> Verilerine erişme</li>
                                    <li><strong>Düzeltme Hakkı:</strong> Verilerini düzeltme</li>
                                    <li><strong>Silme Hakkı:</strong> Verilerini silme</li>
                                    <li><strong>İtiraz Hakkı:</strong> Veri işlemeye itiraz etme</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Aydınlatma Metni -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Aydınlatma Metni ve Onay Alma</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hasta onayı alma süreci.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Aydınlatma Metni</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Veri sorumlusu kimdir?</li>
                                    <li>Hangi veriler işlenir?</li>
                                    <li>Veriler nasıl kullanılır?</li>
                                    <li>Veriler kimlerle paylaşılır?</li>
                                    <li>Veri saklama süresi nedir?</li>
                                    <li>Hasta hakları nelerdir?</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Onay Alma Adımları</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Hasta detay sayfasını açın</li>
                                    <li>"KVKK Onamı Oluştur" butonuna tıklayın</li>
                                    <li>Aydınlatma metnini gösterin</li>
                                    <li>Hasta bilgilerini doldurun</li>
                                    <li>İmzayı alın (fiziksel/dijital)</li>
                                    <li>Sisteme kaydedin</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Veri İşleme İzinleri -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Veri İşleme İzinleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Farklı veri işleme amaçları için gerekli izinler.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">İzin Türleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Tedavi Hizmetleri:</strong> Tıbbi müdahale için gerekli</li>
                                <li><strong>Faturalandırma:</strong> Ödeme işlemleri için</li>
                                <li><strong>Randevu Hatırlatma:</strong> SMS/e-posta için</li>
                                <li><strong>Kayıt Tutma:</strong> Yasal yükümlülükler için</li>
                                <li><strong>İletişim:</strong> Hasta ile iletişim için</li>
                                <li><strong>İstatistik:</strong> Anonim raporlama için</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Veri Silme ve Güncelleme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Veri Silme ve Güncelleme İşlemleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hasta talepleri üzerine veri işlemleri.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Veri Silme</h3>
                                <ul class="text-sm text-red-800 dark:text-red-200 space-y-2">
                                    <li>Hasta talebiyle veri silme</li>
                                    <li>Yasal saklama süreleri kontrolü</li>
                                    <li>Silme işlemi kayıt altına alma</li>
                                    <li>Hasta bilgilendirmesi</li>
                                    <li>Yedeklerden temizleme</li>
                                </ul>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Veri Güncelleme</h3>
                                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                    <li>Hasta bilgilerini güncelleme</li>
                                    <li>Yanlış verileri düzeltme</li>
                                    <li>Güncelleme kayıtları tutma</li>
                                    <li>Hasta onayını alma</li>
                                    <li>Tüm sistemlerde güncelleme</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Veri Güvenliği -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Veri Güvenliği Önlemleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hasta verilerinin korunması için alınan tedbirler.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Teknik Tedbirler</h4>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-1">
                                    <li>Şifreleme</li>
                                    <li>Güvenlik duvarı</li>
                                    <li>Virüs koruması</li>
                                    <li>Yedekleme</li>
                                </ul>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">İdari Tedbirler</h4>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-1">
                                    <li>Erişim kontrolü</li>
                                    <li>Personel eğitimi</li>
                                    <li>Politika oluşturma</li>
                                    <li>Denetim</li>
                                </ul>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Fiziksel Tedbirler</h4>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-1">
                                    <li>Kilitli dolaplar</li>
                                    <li>Güvenli sunucu</li>
                                    <li>Erişim kısıtlaması</li>
                                    <li>İzleme kameraları</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Veri Sorumlusu ve Temsilci -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Veri Sorumlusu ve Temsilci</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            KVKK'da tanımlanan roller ve sorumluluklar.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Veri Sorumlusu</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Klinik sahibi veya yöneticisi</li>
                                    <li>Veri işleme amaçlarını belirler</li>
                                    <li>Güvenlik tedbirlerini alır</li>
                                    <li>KVKK bildirimlerini yapar</li>
                                    <li>İhlal durumunda sorumludur</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Veri Sorumlusu Temsilcisi</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Yurtdışında ise atanır</li>
                                    <li>KVKK kurumuna başvurur</li>
                                    <li>Başvuruları yanıtlar</li>
                                    <li>Denetimlere yardımcı olur</li>
                                    <li>Hasta başvurularını yönetir</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Veri İhlali Bildirimi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Veri İhlali ve Bildirim</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Veri ihlali durumunda yapılması gerekenler.
                        </p>

                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">İhlal Bildirim Süreci</h3>
                            <ol class="list-decimal list-inside text-sm text-red-800 dark:text-red-200 space-y-2">
                                <li>İhlali tespit edin</li>
                                <li>Riski değerlendirin</li>
                                <li>İhlali durdurun</li>
                                <li>KVKK kurumuna 72 saat içinde bildirin</li>
                                <li>Etkilenen kişileri bilgilendirin</li>
                                <li>Kayıt tutun</li>
                                <li>Önleyici tedbirler alın</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Hasta Başvuruları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Başvuruları ve Yanıtlar</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hasta hakları başvurularının yönetimi.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Başvuru Türleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Bilgi Talebi:</strong> Verilerinin nasıl kullanıldığı</li>
                                <li><strong>Erişim Talebi:</strong> Verilerine erişim</li>
                                <li><strong>Düzeltme Talebi:</strong> Yanlış verileri düzeltme</li>
                                <li><strong>Silme Talebi:</strong> Verilerini silme</li>
                                <li><strong>İtiraz Talebi:</strong> Veri işlemeye itiraz</li>
                                <li><strong>Taşınabilirlik:</strong> Verileri başka yere aktarma</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Raporlama ve Denetim -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Raporlama ve Denetim</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            KVKK uyumluluk raporları ve denetimler.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Zorunlu Raporlar</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Veri envanteri</li>
                                    <li>Veri işleme envanteri</li>
                                    <li>Risk analizi raporu</li>
                                    <li>Kapı açıklaması</li>
                                    <li>Yıllık uyumluluk raporu</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Denetim İşlemleri</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>KVKK kurum denetimleri</li>
                                    <li>İç denetimler</li>
                                    <li>Güvenlik testleri</li>
                                    <li>Personel eğitimleri</li>
                                    <li>Süreç iyileştirmeleri</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Eğitim ve Farkındalık -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Eğitim ve Farkındalık</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Personel eğitimi ve KVKK farkındalığı.
                        </p>

                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Eğitim Konuları</h3>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                <li>KVKK temelleri ve prensipleri</li>
                                <li>Veri güvenliği tedbirleri</li>
                                <li>Hasta hakları ve sorumluluklar</li>
                                <li>Veri ihlali prosedürleri</li>
                                <li>Güncel yasal değişiklikler</li>
                                <li>Pratik uygulama örnekleri</li>
                            </ul>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">KVKK Uyumluluğu İçin İpuçları</h2>
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Uyumluluğu Sağlama</h3>
                            <ul class="text-sm text-green-800 dark:text-green-200 space-y-2">
                                <li><strong>Hukuki Danışmanlık:</strong> KVKK uzmanından yardım alın</li>
                                <li><strong>Politika Oluşturun:</strong> Veri işleme politikası hazırlayın</li>
                                <li><strong>Eğitim Verin:</strong> Tüm personeli eğitin</li>
                                <li><strong>Teknoloji Kullanın:</strong> Güvenli sistemler kullanın</li>
                                <li><strong>Belge Tutun:</strong> Tüm işlemleri kayıt altına alın</li>
                                <li><strong>Güncel Kalın:</strong> Yasal değişiklikleri takip edin</li>
                                <li><strong>Denetim Yapın:</strong> Düzenli iç denetimler gerçekleştirin</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sorun Giderme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sık Karşılaşılan Sorunlar</h2>
                        <div class="space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Hasta Onayı Alınmamış</h3>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Tedavi öncesi KVKK onayı alınmamış.
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Çözüm: Her hasta için KVKK onayı alın ve sisteme kaydedin.
                                </p>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Veri Güvenliği İhlali</h3>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    Hasta verileri yetkisiz kişilerce erişilmiş.
                                </p>
                                <p class="text-sm text-orange-700 dark:text-orange-300">
                                    Çözüm: Güvenlik tedbirlerini gözden geçirin, KVKK'ya bildirin.
                                </p>
                            </div>

                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100 mb-3">Hasta Başvurusu</h3>
                                <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-3">
                                    Hasta veri silme veya düzeltme talebinde bulunmuş.
                                </p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                    Çözüm: Başvuruyu 30 gün içinde yanıtlayın, yasal haklarını kullanın.
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