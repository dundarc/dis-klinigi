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
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full mb-6 shadow-lg">
                        <span class="text-3xl text-white">👥</span>
                    </div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-300 bg-clip-text text-transparent mb-4">
                        Hasta Yönetimi Detaylı Kılavuzu
                    </h1>
                    <p class="text-xl text-slate-600 dark:text-slate-400 max-w-3xl mx-auto leading-relaxed">
                        Hasta kayıtlarının nasıl yönetileceğini, KVKK işlemlerini ve hasta bilgilerini nasıl kullanacağınızı öğrenin.
                    </p>
                </div>

                <div class="space-y-16">
                    <!-- Giriş -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                                <span class="text-white text-xl">ℹ️</span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Giriş</h2>
                        </div>
                        <div class="prose prose-slate dark:prose-invert max-w-none">
                            <p class="text-slate-700 dark:text-slate-300 mb-4 text-lg leading-relaxed">
                                Hasta yönetimi bölümü, kliniğinizin en önemli parçasıdır. Hastalarınızın tüm bilgilerini merkezi olarak yönetebilirsiniz.
                            </p>
                            <p class="text-slate-700 dark:text-slate-300 text-lg leading-relaxed">
                                Bu bölümde hasta kayıtları oluşturabilir, güncelleyebilir, arama yapabilir ve hasta geçmişini takip edebilirsiniz.
                            </p>
                        </div>
                    </section>

                    <!-- Yeni Hasta Kaydı -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                                <span class="text-white text-xl">➕</span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Yeni Hasta Kaydı Oluşturma</h2>
                        </div>
                        <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                            Sisteme yeni hasta ekleme adımları.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">📝</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100">Zorunlu Bilgiler</h3>
                                </div>
                                <ol class="text-sm text-blue-800 dark:text-blue-200 space-y-3">
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">1</span>
                                        <span>"Hastalar" menüsüne tıklayın</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">2</span>
                                        <span>"Yeni Hasta" butonuna basın</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">3</span>
                                        <span>Ad ve soyad girin</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">4</span>
                                        <span>Doğum tarihi belirtin</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">5</span>
                                        <span>Ana telefon numarası ekleyin</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">6</span>
                                        <span>E-posta adresi girin (varsa)</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">7</span>
                                        <span>"Kaydet" butonuna tıklayın</span>
                                    </li>
                                </ol>
                            </div>

                            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">📋</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-green-900 dark:text-green-100">İsteğe Bağlı Bilgiler</h3>
                                </div>
                                <ul class="text-sm text-green-800 dark:text-green-200 space-y-3">
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>İkincil Telefon:</strong> Ek iletişim numarası</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Adres:</strong> Ev veya iş adresi</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>TC Kimlik No:</strong> Kimlik numarası</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Sigorta Bilgileri:</strong> Özel sağlık sigortası</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Acil Durum Kontaktı:</strong> Yakın bilgisi</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Tıbbi Geçmiş:</strong> Alerjiler, hastalıklar</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Hasta Arama ve Filtreleme -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                <span class="text-white text-xl">🔍</span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Hasta Arama ve Filtreleme</h2>
                        </div>
                        <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                            Kayıtlı hastalarınızı hızlıca bulma yöntemleri.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-800 rounded-xl p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">🔎</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-purple-900 dark:text-purple-100">Arama Seçenekleri</h3>
                                </div>
                                <ul class="text-sm text-purple-800 dark:text-purple-200 space-y-3">
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>İsim Arama:</strong> Ad veya soyada göre</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Telefon Arama:</strong> Telefon numarasına göre</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>TC Kimlik:</strong> Kimlik numarasına göre</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>E-posta:</strong> E-posta adresine göre</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Hızlı Arama:</strong> Dashboard'dan arama</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 border border-indigo-200 dark:border-indigo-800 rounded-xl p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">⚙️</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-100">Filtreleme Seçenekleri</h3>
                                </div>
                                <ul class="text-sm text-indigo-800 dark:text-indigo-200 space-y-3">
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-indigo-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Kayıt Tarihi:</strong> Ne zaman kayıt olduğu</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-indigo-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Son Ziyaret:</strong> En son ne zaman geldi</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-indigo-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Doktor:</strong> Hangi doktora gitti</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-indigo-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Aktif/Pasif:</strong> Hasta durumu</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-indigo-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        <span><strong>Sigorta:</strong> Sigorta durumu</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Hasta Detay Sayfası -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                                <span class="text-white text-xl">👤</span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Hasta Detay Sayfası</h2>
                        </div>
                        <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                            Hasta bilgilerini görüntüleme ve yönetme.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="group relative bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100 mb-4 flex items-center">
                                        <span class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3 text-white text-sm">📋</span>
                                        Hasta Bilgileri
                                    </h3>
                                    <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-3">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Kişisel bilgiler</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>İletişim bilgileri</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Sigorta detayları</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Acil durum kontaktları</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="group relative bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <h3 class="text-lg font-bold text-green-900 dark:text-green-100 mb-4 flex items-center">
                                        <span class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3 text-white text-sm">🦷</span>
                                        Tedavi Geçmişi
                                    </h3>
                                    <ul class="text-sm text-green-800 dark:text-green-200 space-y-3">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Randevu geçmişi</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Tedavi planları</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Yapılan işlemler</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Tedavi notları</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="group relative bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-800 rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/10 dark:to-violet-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <h3 class="text-lg font-bold text-purple-900 dark:text-purple-100 mb-4 flex items-center">
                                        <span class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3 text-white text-sm">💰</span>
                                        Finansal Bilgiler
                                    </h3>
                                    <ul class="text-sm text-purple-800 dark:text-purple-200 space-y-3">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Fatura geçmişi</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Ödeme durumu</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Bakiye bilgileri</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>İndirimler</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Hasta Bilgilerini Güncelleme -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Bilgilerini Güncelleme</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hasta bilgilerini değiştirme ve güncelleme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Güncelleme İşlemleri</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>İletişim Bilgileri:</strong> Telefon, e-posta, adres değişiklikleri</li>
                                <li><strong>Tıbbi Bilgiler:</strong> Alerjiler, ilaçlar, hastalık geçmişi</li>
                                <li><strong>Sigorta Bilgileri:</strong> Sigorta şirketi ve poliçe numarası</li>
                                <li><strong>Acil Durum:</strong> Yakın bilgileri güncelleme</li>
                                <li><strong>Notlar:</strong> Doktor notları ve gözlemler</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Hasta Dosyaları -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Dosyaları ve Belgeler</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hasta ile ilgili tüm belgelerin yönetimi.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Dosya Türleri</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li><strong>Röntgen Görüntüleri:</strong> Diş röntgenleri ve panoramik</li>
                                    <li><strong>Klinik Fotoğraflar:</strong> Tedavi öncesi/sonrası fotoğraflar</li>
                                    <li><strong>Belgeler:</strong> Raporlar, reçeteler, formlar</li>
                                    <li><strong>Tetkik Sonuçları:</strong> Laboratuvar ve diğer tetkikler</li>
                                    <li><strong>Diğer:</strong> Sözleşmeler, onaylar vb.</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Dosya İşlemleri</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li><strong>Yükleme:</strong> Yeni dosya ekleme</li>
                                    <li><strong>İndirme:</strong> Dosyaları bilgisayarınıza kaydetme</li>
                                    <li><strong>Görüntüleme:</strong> Dosyaları tarayıcıda açma</li>
                                    <li><strong>Silme:</strong> Gereksiz dosyaları kaldırma</li>
                                    <li><strong>Kategorize Etme:</strong> Dosyaları türlerine göre ayırma</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- KVKK Yönetimi -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mr-4">
                                <span class="text-white text-xl">🔒</span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">KVKK ve Veri Koruma</h2>
                        </div>
                        <p class="text-slate-700 dark:text-slate-300 mb-8 text-lg leading-relaxed">
                            Kişisel verilerin korunması ve hasta hakları.
                        </p>

                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-white text-sm">⚖️</span>
                                </div>
                                <h3 class="text-lg font-bold text-yellow-900 dark:text-yellow-100">KVKK İşlemleri</h3>
                            </div>
                            <ul class="text-sm text-yellow-800 dark:text-yellow-200 space-y-3">
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Aydınlatma Metni:</strong> Hasta onayı için bilgilendirme</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Veri İşleme İzni:</strong> Açık rıza alma</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Veri Güncelleme:</strong> Hasta bilgilerini güncelleme hakkı</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Veri Silme:</strong> Hasta talebiyle veri silme</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Veri Taşıma:</strong> Verileri başka yere aktarma</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Raporlama:</strong> KVKK uyumluluk raporları</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- Hasta Geçmişi -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Geçmişi ve Takip</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hastanın klinik geçmişini görüntüleme.
                        </p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Ziyaret Geçmişi</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Tüm randevu kayıtları</li>
                                    <li>Ziyaret tarihleri ve saatleri</li>
                                    <li>Gördüğü doktorlar</li>
                                    <li>Ziyaret notları</li>
                                </ul>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Tedavi Geçmişi</h3>
                                <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                    <li>Yapılan tedaviler</li>
                                    <li>Kullanılan malzemeler</li>
                                    <li>Tedavi maliyetleri</li>
                                    <li>Tedavi sonuçları</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Hasta Etiketleri ve Kategoriler -->
                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Etiketleri ve Kategoriler</h2>
                        <p class="text-slate-700 dark:text-slate-300 mb-6">
                            Hastaları gruplandırma ve etiketleme.
                        </p>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-3">Kullanım Alanları</h3>
                            <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2">
                                <li><strong>Risk Grupları:</strong> Yüksek riskli hastalar</li>
                                <li><strong>VIP Hastalar:</strong> Özel takip gereken hastalar</li>
                                <li><strong>Düzenli Kontrol:</strong> Periyodik kontrol hastaları</li>
                                <li><strong>Acil Durum:</strong> Acil müdahale gerekenler</li>
                                <li><strong>Referans:</strong> Başkalarından gelen hastalar</li>
                            </ul>
                        </div>
                    </section>

                    <!-- İpuçları -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                                <span class="text-white text-xl">💡</span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Kullanım İpuçları</h2>
                        </div>

                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-white text-sm">🎯</span>
                                </div>
                                <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100">Hasta Yönetimini Etkili Kullanma</h3>
                            </div>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-3">
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Güncel Bilgiler:</strong> Hasta bilgilerini her ziyarette güncelleyin</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Tam Bilgi:</strong> Tüm zorunlu alanları doldurun</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Dosyaları Düzenli:</strong> Belgeleri tarih ve türe göre organize edin</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>KVKK Uyumu:</strong> Onayları zamanında alın ve kaydedin</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Gizlilik:</strong> Hasta bilgilerini korumak önceliktir</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>Yedekleme:</strong> Önemli hasta verilerini yedekleyin</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><strong>İletişim:</strong> Hasta ile sürekli iletişim halinde olun</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sorun Giderme -->
                    <section class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
                                <span class="text-white text-xl">🔧</span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Sık Karşılaşılan Sorunlar</h2>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">❓</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-red-900 dark:text-red-100">Hasta Bulunamıyor</h3>
                                </div>
                                <p class="text-sm text-red-800 dark:text-red-200 mb-3">
                                    Kayıtlı hasta aranırken bulunamıyor.
                                </p>
                                <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-3">
                                    <p class="text-sm text-red-700 dark:text-red-300">
                                        <strong>Çözüm:</strong> Farklı arama kriterleri deneyin (telefon, TC, e-posta).
                                    </p>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border border-orange-200 dark:border-orange-800 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">⚠️</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-orange-900 dark:text-orange-100">Bilgiler Güncellenmiyor</h3>
                                </div>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                    Hasta bilgileri kaydedilmiyor.
                                </p>
                                <div class="bg-orange-100 dark:bg-orange-900/30 rounded-lg p-3">
                                    <p class="text-sm text-orange-700 dark:text-orange-300">
                                        <strong>Çözüm:</strong> Tüm zorunlu alanların doldurulduğundan emin olun.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white text-sm">⚖️</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-yellow-900 dark:text-yellow-100">KVKK Onayı Eksik</h3>
                                </div>
                                <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-3">
                                    Hasta KVKK onayı alınmamış.
                                </p>
                                <div class="bg-yellow-100 dark:bg-yellow-900/30 rounded-lg p-3">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                        <strong>Çözüm:</strong> KVKK bölümünden onay alın ve kaydedin.
                                    </p>
                                </div>
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