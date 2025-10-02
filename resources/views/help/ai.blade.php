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

                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 mb-8">Yapay Zeka Asistanı Detaylı Kılavuzu</h1>

                <div class="space-y-12">
                    <!-- Giriş -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Giriş</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Yapay zeka asistanı, diş hekimliği alanında uzmanlaşmış bir yardımcıdır.
                            Tedavi önerileri, hasta değerlendirmesi ve genel tavsiyelerde size destek olur.
                        </p>
                        <p class="text-slate-700 dark:text-slate-300">
                            AI asistanı tıbbi teşhis koymaz, sadece destekleyici bilgiler sağlar.
                            Her zaman profesyonel tıbbi görüşünüzü ön planda tutun.
                        </p>
                    </section>

                    <!-- AI Asistanına Erişim -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">AI Asistanına Erişim</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            AI asistanını kullanmaya başlama.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Erişim Adımları</h3>
                            <ol class="list-decimal list-inside text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li>Sol menüden "AI Asistanı"na tıklayın</li>
                                <li>Karşılama ekranı açılır</li>
                                <li>Soru sormaya başlayabilirsiniz</li>
                                <li>Yeni sohbet başlatın veya önceki sohbetleri devam ettirin</li>
                            </ol>
                        </div>
                    </section>

                    <!-- Kullanım Alanları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Kullanım Alanları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            AI asistanının hangi konularda yardımcı olabileceği.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Tedavi Önerileri -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Tedavi Önerileri</h3>
                                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                    <li>Diş tedavisi yöntemleri</li>
                                    <li>Malzeme seçimi</li>
                                    <li>Tedavi protokolleri</li>
                                    <li>Alternatif yaklaşımlar</li>
                                </ul>
                            </div>

                            <!-- Hasta Değerlendirmesi -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Hasta Değerlendirmesi</h3>
                                <ul class="text-sm text-green-800 dark:text-green-200 space-y-2">
                                    <li>Risk faktörleri</li>
                                    <li>Hasta geçmişi analizi</li>
                                    <li>Önleyici tedbirler</li>
                                    <li>Takip önerileri</li>
                                </ul>
                            </div>

                            <!-- Genel Bilgiler -->
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-3">Genel Bilgiler</h3>
                                <ul class="text-sm text-purple-800 dark:text-purple-200 space-y-2">
                                    <li>Diş hekimliği terimleri</li>
                                    <li>Protokol hatırlatmaları</li>
                                    <li>Eğitim materyalleri</li>
                                    <li>Güncel gelişmeler</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Soru Sorma Teknikleri -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Etkin Soru Sorma</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            AI'den daha iyi yanıtlar almak için soru sorma teknikleri.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- İyi Sorular -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Etkili Sorular</h3>
                                <ul class="text-sm text-green-800 dark:text-green-200 space-y-2">
                                    <li>"65 yaşındaki hastada implant önerileri?"</li>
                                    <li>"Çocukta süt dişi çekimi sonrası bakım"</li>
                                    <li>"Diabetli hastada periodontal tedavi"</li>
                                    <li>"Kompozit dolgu vs amalgam farkları"</li>
                                </ul>
                            </div>

                            <!-- Sorunlu Sorular -->
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Kaçınılması Gereken</h3>
                                <ul class="text-sm text-red-800 dark:text-red-200 space-y-2">
                                    <li>"Bu hastayı tedavi et" (çok genel)</li>
                                    <li>"En iyi tedavi nedir?" (subjektif)</li>
                                    <li>"Teşhis koy" (yasak)</li>
                                    <li>"İlaç ver" (reçete yetkisi yok)</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Sohbet Yönetimi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Sohbet Yönetimi</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            AI ile sohbet etmeyi öğrenme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Sohbet Özellikleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Yeni Sohbet:</strong> Her seferinde yeni konu için yeni sohbet başlatın</li>
                                <li><strong>Devam Ettirme:</strong> Aynı konuda konuşmak için mevcut sohbeti kullanın</li>
                                <li><strong>Kaydetme:</strong> Önemli sohbetleri favorilere ekleyin</li>
                                <li><strong>Silme:</strong> Eski sohbetleri temizleyin</li>
                                <li><strong>Arama:</strong> Önceki sohbetlerde arama yapın</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Güvenlik ve Gizlilik -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Güvenlik ve Gizlilik</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            AI kullanımı sırasında dikkat edilmesi gerekenler.
                        </p>

                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100 mb-3">Önemli Kurallar</h3>
                            <ul class="text-sm text-yellow-800 dark:text-yellow-200 space-y-2">
                                <li><strong>Hasta Bilgileri:</strong> Kişisel hasta bilgilerini paylaşmayın</li>
                                <li><strong>Teşhis Koyma:</strong> AI teşhis koymaz, sadece öneri verir</li>
                                <li><strong>Gizlilik:</strong> Sohbet geçmişi gizli tutulur</li>
                                <li><strong>Yasal Sorumluluk:</strong> AI önerileri bağlayıcı değildir</li>
                                <li><strong>Doğrulama:</strong> Önerileri kendi bilginizle kontrol edin</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Örnek Kullanım Senaryoları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Örnek Kullanım Senaryoları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Pratik kullanım örnekleri.
                        </p>

                        <div class="space-y-4">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Senaryo 1: Yeni Hasta Değerlendirmesi</h3>
                                <p class="text-sm text-slate-700 dark:text-slate-300 mb-2">
                                    "40 yaşındaki erkek hasta, ön dişlerinde kırık var. Sigara içiyor. Tedavi önerileri?"
                                </p>
                                <p class="text-xs text-slate-600 dark:text-slate-400">
                                    AI: Kompozit dolgu, veneer seçenekleri, sigara bırakma tavsiyeleri
                                </p>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Senaryo 2: Acil Durum</h3>
                                <p class="text-sm text-slate-700 dark:text-slate-300 mb-2">
                                    "Hasta diş ağrısı ile geldi, şişlik var. Acil müdahale protokolü?"
                                </p>
                                <p class="text-xs text-slate-600 dark:text-slate-400">
                                    AI: Antibiyotik, ağrı kesici, kanal tedavisi planı
                                </p>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Senaryo 3: Malzeme Seçimi</h3>
                                <p class="text-sm text-slate-700 dark:text-slate-300 mb-2">
                                    "Ön diş estetiği için hangi malzeme daha uygun?"
                                </p>
                                <p class="text-xs text-slate-600 dark:text-slate-400">
                                    AI: Seramik kron, kompozit veneer karşılaştırması
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- AI Ayarları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">AI Ayarları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            AI asistanını kişiselleştirme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Ayar Seçenekleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Yanıt Uzunluğu:</strong> Kısa veya detaylı yanıtlar</li>
                                <li><strong>Dil Seçimi:</strong> Türkçe veya İngilizce</li>
                                <li><strong>Uzmanlık Alanı:</strong> Genel diş hekimliği veya uzmanlık</li>
                                <li><strong>Kaydetme Tercihi:</strong> Sohbet geçmişini saklama</li>
                            </ul>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Kullanım İpuçları</h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">AI Asistanını Etkili Kullanma</h3>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                                <li><strong>Spesifik Sorular:</strong> Detaylı ve spesifik sorular sorun</li>
                                <li><strong>Bağlam Verin:</strong> Hasta yaşı, durumu gibi bilgiler ekleyin</li>
                                <li><strong>Adım Adım:</strong> Karmaşık konuları parçalara bölün</li>
                                <li><strong>Doğrulayın:</strong> AI önerilerini kendi deneyiminizle kontrol edin</li>
                                <li><strong>Güncel Tutun:</strong> Diş hekimliğinde güncel gelişmeleri takip edin</li>
                                <li><strong>Sorumluluk:</strong> Nihai kararı siz verirsiniz</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sınırlamalar -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">AI Sınırlamaları</h2>
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Yapamayacağı İşler</h3>
                            <ul class="text-sm text-red-800 dark:text-red-200 space-y-2">
                                <li><strong>Teşhis Koyma:</strong> Kesin teşhis koyamaz</li>
                                <li><strong>Reçete Yazma:</strong> İlaç reçetesi yazamaz</li>
                                <li><strong>Tedavi Uygulama:</strong> Fiziksel müdahale yapamaz</li>
                                <li><strong>Yasal Sorumluluk:</strong> Tıbbi sorumluluk üstlenemez</li>
                                <li><strong>Kişisel Bilgi:</strong> Hasta mahremiyetini koruyamaz</li>
                            </ul>
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