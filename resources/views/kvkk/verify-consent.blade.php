<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-4xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-6 text-center">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">KVKK Açık Rıza Onayı</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Aşağıdaki onam metnini inceleyin ve onaylamak için butona tıklayın.
                </p>
            </div>

            @if($consent)
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Bilgilendirme</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                Bu onam metni [KLİNİK ADI] tarafından hazırlanmıştır. Onamı incelemek için PDF'yi indirebilirsiniz.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Consent Details -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Hasta</div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $consent->patient->first_name }} {{ $consent->patient->last_name }}
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Versiyon</div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $consent->version }}
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Oluşturulma</div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $consent->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Consent Content Preview -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Onam Metni Önizlemesi</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg max-h-96 overflow-y-auto">
                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-4">
                            <div>
                                <strong>I. Veri Sorumlusu ve Temsilci</strong><br>
                                6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca, kişisel verileriniz [KLİNİK ADI] tarafından veri sorumlusu sıfatıyla işlenmektedir.
                            </div>
                            <div>
                                <strong>II. İşlenen Kişisel Veri Kategorileri</strong><br>
                                Kişisel verileriniz, sağlık hizmetlerinin sunulması kapsamında; kimlik, iletişim, sağlık ve finansal verileriniz işlenmektedir.
                            </div>
                            <div>
                                <strong>III. Kişisel Verilerin İşlenme Amaçları</strong><br>
                                Kişisel verileriniz; sağlık hizmetlerinin sunulması, teşhis ve tedavi süreçlerinin yürütülmesi, faturalandırma ve mevzuattan kaynaklanan yükümlülüklerin yerine getirilmesi amaçlarıyla işlenecektir.
                            </div>
                            <div>
                                <strong>AÇIK RIZA BEYANI</strong><br>
                                İşbu Aydınlatma Metni'ni okuduğumu, anladığımı ve 6698 sayılı KVKK kapsamında tarafıma yapılan bilgilendirme çerçevesinde; kimlik, iletişim, sağlık ve finansal verilerimin, teşhis ve tedavi hizmetlerinin yürütülmesi, faturalandırma ve mevzuattan kaynaklanan yükümlülüklerin yerine getirilmesi amacıyla işlenmesine ve ilgili kişi/kurumlara aktarılmasına açık rıza verdiğimi beyan ederim.
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        Bu önizlemedir. Tam metin için PDF'yi indirin.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('kvkk.consent-pdf', $consent->patient) }}"
                       target="_blank"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        PDF'yi İndir
                    </a>

                    <form action="{{ route('kvkk.verify-consent', $token) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Onamı Onayla
                        </button>
                    </form>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Onamı onayladıktan sonra bu işlem geri alınamaz. Sorularınız için [KLİNİK E-POSTA ADRESİ] adresinden iletişime geçebilirsiniz.
                    </p>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Geçersiz Bağlantı</h3>
                    <p class="text-gray-600 dark:text-gray-400">Bu doğrulama bağlantısı geçersiz veya süresi dolmuş.</p>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>