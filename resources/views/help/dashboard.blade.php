<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <a href="{{ route('help') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Yardım Ana Sayfasına Dön
                    </a>
                </div>

                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-6 shadow-lg">
                        <span class="text-3xl text-white">🏠</span>
                    </div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-300 bg-clip-text text-transparent mb-4">
                        Ana Sayfa (Dashboard) Detaylı Kılavuzu
                    </h1>
                    <p class="text-xl text-slate-600 dark:text-slate-400 max-w-3xl mx-auto leading-relaxed">
                        Sistemin kalbinde yer alan kontrol panelinin tüm özelliklerini ve kullanımını öğrenin.
                    </p>
                </div>

                <div class="space-y-16">
                    <!-- Giriş -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">ℹ️</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Giriş</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-intro"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-intro" class="help-content">
                        <div class="prose prose-slate dark:prose-invert max-w-none">
                            <p class="text-slate-700 dark:text-slate-300 mb-4 text-lg leading-relaxed">
                                Ana sayfa (Dashboard), kliniğinizin günlük faaliyetlerini özetleyen merkezi kontrol panelidir.
                                Burada günün önemli istatistiklerini, bekleyen işlemleri ve son aktiviteleri görebilirsiniz.
                            </p>
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mt-6">
                                <p class="text-blue-800 dark:text-blue-200 text-sm flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Sayfa otomatik olarak yenilenmez, ancak sayfayı yenileyerek en güncel bilgileri görebilirsiniz.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Üst Kartlar -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-white text-xl">📊</span>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Üst Kartlar Bölümü</h2>
                            </div>
                            <button
                                class="help-toggle flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors duration-200"
                                aria-expanded="true"
                                aria-controls="section-top-cards"
                                aria-label="Bölümü aç/kapat"
                            >
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="section-top-cards" class="help-content">
                            <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                                Sayfanın üst kısmında bulunan kartlar, günün en önemli özet bilgilerini gösterir.
                            </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Bugünkü Randevular -->
                            <div class="group relative bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100 mb-4 flex items-center">
                                        <span class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3 text-white text-sm">📅</span>
                                        Bugünkü Randevular
                                    </h3>
                                    <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-3 mb-4">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Toplam Randevu:</strong> Bugün için planlanmış tüm randevu sayısı</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Kabul Edilen:</strong> Bekleme odasına gelen ve kabul edilen hasta sayısı</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>İptal Edilen:</strong> Bugün iptal edilen randevu sayısı</span>
                                        </li>
                                    </ul>
                                    <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3">
                                        <p class="text-xs text-blue-700 dark:text-blue-300">
                                            Bu kart, günün randevu yoğunluğunu gösterir ve doktorların çalışma programını anlamalarına yardımcı olur.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bekleme Odası -->
                            <div class="group relative bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/10 dark:to-rose-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <h3 class="text-lg font-bold text-red-900 dark:text-red-100 mb-4 flex items-center">
                                        <span class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center mr-3 text-white text-sm">🏥</span>
                                        Bekleme Odası
                                    </h3>
                                    <ul class="text-sm text-red-800 dark:text-red-200 space-y-3 mb-4">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-red-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Acil Hasta:</strong> Bekleme odasında bekleyen acil durumlu hasta sayısı</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-red-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Kritik:</strong> Kırmızı kod (en acil) olarak işaretlenen hasta sayısı</span>
                                        </li>
                                    </ul>
                                    <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-3">
                                        <p class="text-xs text-red-700 dark:text-red-300">
                                            Acil durumları önceliklendirmek için kullanılır. Kritik hastalar hemen müdahale gerektirir.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Finans -->
                            <div class="group relative bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <h3 class="text-lg font-bold text-green-900 dark:text-green-100 mb-4 flex items-center">
                                        <span class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3 text-white text-sm">💰</span>
                                        Finans
                                    </h3>
                                    <ul class="text-sm text-green-800 dark:text-green-200 space-y-3 mb-4">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Bugünkü Tahsilat:</strong> Bugün yapılan toplam ödeme miktarı</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Ödenmemiş Fatura:</strong> Henüz ödenmemiş fatura sayısı</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span><strong>Aylık Gider:</strong> Bu ay yapılan toplam harcama</span>
                                        </li>
                                    </ul>
                                    <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-3">
                                        <p class="text-xs text-green-700 dark:text-green-300">
                                            Klinik mali durumunu takip etmek için kullanılır. Sadece yetkili kullanıcılar görebilir.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Stok Kartları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Stok Durumu Kartları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Malzeme stoklarını takip etmek için kullanılan kartlardır.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kritik Stok -->
                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-3">Kritik Stok Seviyesi</h3>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    Minimum seviyenin altına düşen malzemelerin listesi. Bu malzemeler yakında bitecek.
                                </p>
                                <ul class="text-sm text-orange-700 dark:text-orange-300 space-y-1">
                                    <li>Her malzemenin mevcut miktarı gösterilir</li>
                                    <li>Tıklayarak detaylarına gidebilirsiniz</li>
                                    <li>Yeni satın alma işlemi başlatabilirsiniz</li>
                                </ul>
                            </div>

                            <!-- Vadesi Geçmiş Faturalar -->
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Vadesi Geçmiş Faturalar</h3>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Tedarikçilerden alınmış ancak henüz ödenmemiş faturalar.
                                </p>
                                <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                                    <li>Vade tarihi geçmiş faturalar kırmızı renkte görünür</li>
                                    <li>Tedarikçi bilgileri ve tutarlar listelenir</li>
                                    <li>Ödeme işlemleri için muhasebe bölümüne yönlendirir</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    </div>
                </section>

                    <!-- Son İşlemler -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Son İşlemler Bölümü</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Sistemde yapılan son işlemleri takip etmek için kullanılır.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Son Tahsilatlar -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Son Tahsilatlar</h3>
                                <p class="text-sm text-green-800 dark:text-green-200 mb-3">
                                    Yapılan son 5 ödeme işlemi.
                                </p>
                                <ul class="text-sm text-green-700 dark:text-green-300 space-y-1">
                                    <li>Hasta adı ve ödenen tutar</li>
                                    <li>Ödeme yöntemi (nakit, kart, havale vb.)</li>
                                    <li>Tıklayarak fatura detaylarına gidebilirsiniz</li>
                                </ul>
                            </div>

                            <!-- Son Tedaviler -->
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-3">Son Tedaviler</h3>
                                <p class="text-sm text-purple-800 dark:text-purple-200 mb-3">
                                    Tamamlanan son 5 tedavi işlemi.
                                </p>
                                <ul class="text-sm text-purple-700 dark:text-purple-300 space-y-1">
                                    <li>Hasta adı ve yapılan tedavi</li>
                                    <li>Tedavi eden doktor</li>
                                    <li>Hasta dosyasına gitmek için tıklayın</li>
                                </ul>
                            </div>

                            <!-- Son Randevular -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Son Randevular</h3>
                                <p class="text-sm text-blue-800 dark:text-blue-200 mb-3">
                                    Oluşturulan son 5 randevu.
                                </p>
                                <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                    <li>Hasta adı ve randevu tarihi/saati</li>
                                    <li>Randevu durumu (planlandı, onaylandı, tamamlandı vb.)</li>
                                    <li>Takvim görünümüne gitmek için tıklayın</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Bugünkü Randevular Tablosu -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Bugünkü Randevular Tablosu</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Bugün için planlanmış tüm randevuları detaylı olarak listeleyen tablodur.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Tablo İçeriği</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Hasta Adı:</strong> Randevu sahibi hastanın adı</li>
                                <li><strong>Telefon:</strong> Hastanın iletişim numarası</li>
                                <li><strong>Doktor:</strong> Randevu veren doktor</li>
                                <li><strong>Saat:</strong> Randevu saati</li>
                                <li><strong>Durum:</strong> Randevu durumu (renk kodlu)</li>
                                <li><strong>Notlar:</strong> Ek bilgiler veya özel notlar</li>
                            </ul>

                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3 mt-6">Durum Renk Kodları</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-2"></span><strong>Mavi:</strong> Planlandı</li>
                                <li><span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span><strong>Yeşil:</strong> Onaylandı</li>
                                <li><span class="inline-block w-3 h-3 bg-purple-500 rounded-full mr-2"></span><strong>Mor:</strong> Kabul Edildi</li>
                                <li><span class="inline-block w-3 h-3 bg-emerald-500 rounded-full mr-2"></span><strong>Turkuaz:</strong> Tamamlandı</li>
                                <li><span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-2"></span><strong>Kırmızı:</strong> İptal Edildi</li>
                                <li><span class="inline-block w-3 h-3 bg-gray-500 rounded-full mr-2"></span><strong>Gri:</strong> Gelmedi</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Son Aktiviteler -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Son Aktiviteler</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Sistemde yapılan son işlemleri kronolojik olarak listeleyen bölümdür.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Aktivite Türleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><span class="text-green-600">👤</span> <strong>Yeni Hasta Kaydı:</strong> Sisteme yeni hasta eklendiği zaman</li>
                                <li><span class="text-blue-600">📅</span> <strong>Yeni Randevu:</strong> Randevu oluşturulduğu zaman</li>
                                <li><span class="text-purple-600">🦷</span> <strong>Tedavi Tamamlandı:</strong> Bir tedavi işlemi bittiği zaman</li>
                                <li><span class="text-orange-600">💰</span> <strong>Yeni Fatura:</strong> Fatura kesildiği zaman</li>
                            </ul>

                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-4">
                                Her aktiviteye tıklayarak ilgili sayfaya gidebilirsiniz.
                            </p>
                        </div>
                    </section>

                    <!-- İstatistik Kartları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">İstatistik Kartları</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Klinik faaliyetlerinin genel istatistiklerini gösteren kartlardır.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Aylık Gelir -->
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-2">Aylık Gelir</h3>
                                <p class="text-sm text-green-800 dark:text-green-200">
                                    Bu ay yapılan toplam tahsilat miktarı.
                                </p>
                            </div>

                            <!-- Aylık Kar -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Aylık Kar</h3>
                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                    Gelirden gider çıkarıldıktan sonraki net kar miktarı.
                                </p>
                            </div>

                            <!-- Toplam Hasta -->
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-2">Toplam Hasta</h3>
                                <p class="text-sm text-purple-800 dark:text-purple-200">
                                    Sistemde kayıtlı toplam hasta sayısı.
                                </p>
                            </div>

                            <!-- Bu Ay Yeni Hasta -->
                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-2">Bu Ay Yeni Hasta</h3>
                                <p class="text-sm text-orange-800 dark:text-orange-200">
                                    Bu ay sisteme kaydolan yeni hasta sayısı.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Kullanım İpuçları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Kullanım İpuçları</h2>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100 mb-3">Dashboard'u Etkili Kullanma</h3>
                            <ul class="text-sm text-yellow-800 dark:text-yellow-200 space-y-2">
                                <li><strong>Günlük Kontrol:</strong> Her gün başlangıcında dashboard'u kontrol ederek günün programını görün</li>
                                <li><strong>Acil Durumlar:</strong> Bekleme odası kartındaki kırmızı sayıları düzenli olarak takip edin</li>
                                <li><strong>Stok Takibi:</strong> Kritik stok seviyesindeki malzemeleri zamanında yenileyin</li>
                                <li><strong>Finansal Takip:</strong> Günlük tahsilat ve ödenmemiş faturaları gözden geçirin</li>
                                <li><strong>Son Aktiviteler:</strong> Sistemdeki değişiklikleri takip etmek için son aktiviteleri inceleyin</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Hızlı İşlemler -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hızlı İşlemler</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-4">
                            Dashboard'da bulunan hızlı işlem butonları ile sık kullanılan işlemleri hızlıca yapabilirsiniz.
                        </p>
                        <p class="text-slate-700 dark:text-slate-300">
                            Bu butonlar, yeni hasta ekleme, randevu oluşturma, stok girişi gibi işlemleri kolaylaştırır.
                        </p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.help-toggle');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const content = this.closest('section').querySelector('.help-content');
                    const icon = this.querySelector('svg');
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';

                    // Toggle content visibility
                    if (isExpanded) {
                        content.style.display = 'none';
                        this.setAttribute('aria-expanded', 'false');
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        content.style.display = 'block';
                        this.setAttribute('aria-expanded', 'true');
                        icon.style.transform = 'rotate(0deg)';
                    }
                });
            });
        });
    </script>
</x-app-layout>