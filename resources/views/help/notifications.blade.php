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

                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-8">Bildirimler Detaylı Kılavuzu</h1>

                <div class="space-y-12">
                    <!-- Giriş -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Giriş</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Bildirimler bölümü, sistemdeki önemli olayları takip etmenizi ve hastalara hatırlatma göndermenizi sağlar.
                        </p>
                        <p class="text-slate-700 dark:text-slate-300">
                            Otomatik ve manuel bildirimler ile iletişimlerinizi yönetebilirsiniz.
                        </p>
                    </section>

                    <!-- Bildirim Türleri -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Bildirim Türleri</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistemde bulunan farklı bildirim kategorileri.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Sistem Bildirimleri -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Sistem Bildirimleri</h3>
                                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                    <li>Randevu hatırlatmaları</li>
                                    <li>Ödeme hatırlatmaları</li>
                                    <li>Stok uyarıları</li>
                                    <li>Sistem güncellemeleri</li>
                                </ul>
                            </div>

                            <!-- Manuel Bildirimler -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Manuel Bildirimler</h3>
                                <ul class="text-sm text-green-800 dark:text-green-200 space-y-2">
                                    <li>Hasta hatırlatmaları</li>
                                    <li>Duyurular</li>
                                    <li>Özel mesajlar</li>
                                    <li>Kampanya bilgileri</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Bildirim Listesi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Bildirim Listesi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Tüm bildirimlerinizi görüntüleme ve yönetme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Liste Özellikleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Okunmuş/Okunmamış:</strong> Bildirim durumunu gösterir</li>
                                <li><strong>Tarih ve Saat:</strong> Bildirimin gönderildiği zaman</li>
                                <li><strong>Öncelik:</strong> Acil, normal, bilgi</li>
                                <li><strong>İşlemler:</strong> Okundu olarak işaretle, sil</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Bildirim Gönderme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Bildirim Gönderme</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hastalara manuel bildirim gönderme.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- SMS Bildirimi -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">SMS Bildirimi</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>"Bildirimler" menüsüne tıklayın</li>
                                    <li>"Yeni Bildirim Gönder" seçin</li>
                                    <li>"SMS" yöntemini seçin</li>
                                    <li>Alıcıları seçin (tüm hastalar veya belirli)</li>
                                    <li>Mesajınızı yazın</li>
                                    <li>"Gönder" butonuna tıklayın</li>
                                </ol>
                            </div>

                            <!-- E-posta Bildirimi -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">E-posta Bildirimi</h3>
                                <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>"Bildirimler" menüsüne tıklayın</li>
                                    <li>"Yeni Bildirim Gönder" seçin</li>
                                    <li>"E-posta" yöntemini seçin</li>
                                    <li>Alıcıları belirleyin</li>
                                    <li>Konu ve mesajı yazın</li>
                                    <li>"Gönder" butonuna tıklayın</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- Otomatik Bildirimler -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Otomatik Bildirimler</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistem tarafından otomatik olarak gönderilen bildirimler.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Randevu Hatırlatma</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">Randevudan önce otomatik hatırlatma</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Doğum Günü</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">Hasta doğum günlerinde tebrik</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                <h4 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Tedavi Hatırlatma</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300">Kontrol randevusu hatırlatmaları</p>
                            </div>
                        </div>
                    </section>

                    <!-- Bildirim Ayarları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Bildirim Ayarları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Bildirim tercihlerinizi kişiselleştirme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Ayar Seçenekleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>E-posta Bildirimleri:</strong> Sistem e-postalarını alma</li>
                                <li><strong>SMS Bildirimleri:</strong> SMS hatırlatmaları</li>
                                <li><strong>Sesli Uyarılar:</strong> Sesli bildirimler</li>
                                <li><strong>Bildirim Sıklığı:</strong> Ne kadar sık hatırlatma</li>
                                <li><strong>Konular:</strong> Hangi konularda bildirim almak</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Bildirim Geçmişi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Bildirim Geçmişi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Gönderilen tüm bildirimleri takip etme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Geçmiş Bilgileri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Gönderim Tarihi:</strong> Bildirimin gönderildiği zaman</li>
                                <li><strong>Alıcı:</strong> Kime gönderildiği</li>
                                <li><strong>Yöntem:</strong> SMS, e-posta, uygulama içi</li>
                                <li><strong>Durum:</strong> Teslim edildi, okundu, başarısız</li>
                                <li><strong>İçerik:</strong> Gönderilen mesajın önizlemesi</li>
                            </ul>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Kullanım İpuçları</h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Bildirimleri Etkili Kullanma</h3>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                <li><strong>Zamanında Gönder:</strong> Randevu saatinden önce hatırlat</li>
                                <li><strong>Kişiselleştir:</strong> Hasta adını kullanarak samimi ol</li>
                                <li><strong>Kısa Tut:</strong> Mesajları kısa ve net yaz</li>
                                <li><strong>Çok Gönderme:</strong> Hastaları rahatsız etmeyin</li>
                                <li><strong>Takip Et:</strong> Gönderilen bildirimleri kontrol edin</li>
                                <li><strong>Yasal Uy:</strong> KVKK kurallarına uygun hareket edin</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sorun Giderme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sık Karşılaşılan Sorunlar</h2>
                        <div class="space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Bildirim Götürülmüyor</h3>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Gönderilen bildirimler alıcıya ulaşmıyor.
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Çözüm: Telefon numarası veya e-posta adresini kontrol edin.
                                </p>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Spam Olarak Görüntüleniyor</h3>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    Bildirimler spam klasörüne düşüyor.
                                </p>
                                <p class="text-sm text-orange-700 dark:text-orange-300">
                                    Çözüm: Gönderici adresini beyaz listeye ekleyin.
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