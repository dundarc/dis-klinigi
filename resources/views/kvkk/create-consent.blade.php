<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">KVKK Onamı Oluştur</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $patient->first_name }} {{ $patient->last_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('kvkk.show', $patient) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('kvkk.store-consent', $patient) }}" method="POST" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
                @csrf

                <div class="mb-8 text-center">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2">KVKK Onamı Oluştur</h3>
                    <p class="text-slate-600 dark:text-slate-400">
                        {{ $patient->first_name }} {{ $patient->last_name }} için yeni KVKK onamı oluşturun.
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Consent Method Selection -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-4">
                            Onam Yöntemi <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="radio" id="method_wet" name="consent_method" value="wet_signature"
                                       {{ old('consent_method', 'wet_signature') === 'wet_signature' ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                <label for="method_wet" class="ml-3 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    <span class="font-semibold">Islak İmza</span> - PDF oluştur ve ıslak imza al (e-posta gönderilmez)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="method_email" name="consent_method" value="email_verification"
                                       {{ old('consent_method') === 'email_verification' ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                <label for="method_email" class="ml-3 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    <span class="font-semibold">E-posta Doğrulaması</span> - PDF oluştur ve e-posta ile doğrulama yap (ıslak imza alınmaz)
                                </label>
                            </div>
                        </div>
                        @error('consent_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Version -->
                    <div>
                        <label for="version" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Onam Versiyonu <span class="text-red-500">*</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">(olduğu gibi bırakılacaktır)</span>
                        </label>
                        <input type="text" id="version" name="version" value="{{ old('version', '1.0') }}"
                               class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                        @error('version')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Snapshot Data -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Onam İçeriği <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="personal_data" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Kişisel Veriler</label>
                                    <textarea id="personal_data" name="snapshot[personal_data]" rows="3"
                                              class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="İşlenecek kişisel veriler...">{{ old('snapshot.personal_data', 'Kimlik bilgileri, iletişim bilgileri, sağlık bilgileri, finansal bilgiler') }}</textarea>
                                </div>
                                <div>
                                    <label for="purpose" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Amaç</label>
                                    <textarea id="purpose" name="snapshot[purpose]" rows="3"
                                              class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="Veri işleme amacı...">{{ old('snapshot.purpose', 'Sağlık hizmeti sunma, tıbbi teşhis ve tedavi, idari süreçlerin yürütülmesi') }}</textarea>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="legal_basis" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Hukuki Dayanak</label>
                                    <textarea id="legal_basis" name="snapshot[legal_basis]" rows="3"
                                              class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="KVKK madde 5/2...">{{ old('snapshot.legal_basis', '6698 sayılı Kişisel Verilerin Korunması Kanunu Madde 5/2 ve 6/3') }}</textarea>
                                </div>
                                <div>
                                    <label for="retention_period" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Saklama Süresi</label>
                                    <input type="text" id="retention_period" name="snapshot[retention_period]" value="{{ old('snapshot.retention_period', 'İlişki süresi + 10 yıl (KVKK gereği)') }}"
                                           class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Veri saklama süresi...">
                                </div>
                            </div>
                        </div>
                        @error('snapshot')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- KVKK Legal Text Reference -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            KVKK Açık Rıza Metni Referansı
                        </label>
                        <div class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-4 max-h-60 overflow-y-auto">
                            <div class="text-sm text-slate-600 dark:text-slate-400 space-y-4">
                                <div>
                                    <strong>a) Kişisel Verilerin Hangi Amaçla İşleneceği</strong><br>
                                    Kişisel verilerin işlenmesinde, kanunda "Genel İlkeler" başlığı ile düzenleme altına alınan 4. Maddesinden belirtilen hukuka ve dürüstlük kurallarına uygun olma, doğru ve gerektiğinde güncel olma, belirli, açık ve meşru amaçlar için işlenme, işlendikleri amaçla bağlantılı, sınırlı ve ölçülü olma, ilgili mevzuatta öngörülen veya işlendikleri amaç için gerekli olan süre kadar muhafaza edilme ilkelerine uyulmaktadır.
                                </div>
                                <div>
                                    <strong>b) İşlenen Kişisel Verilerin Kimlere ve Hangi Amaçla Aktarılabileceği</strong><br>
                                    Kişisel verileriniz; Sağlık Kuruluşumuz tarafından 6698 sayılı Kanun'un 5. Maddesinin 2. fıkrasında ve 6. Maddenin 3. Fıkrasından belirtilen şartlar oluşması durumunda yeterli önlemler alınması şartı ile ilgili kurumlara kişisel veri işleme şartları ve amaçları ile sınırlı olmak kaydıyla aktarılabilir.
                                </div>
                                <div>
                                    <strong>c) Kişisel Veri Toplamanın Yöntemi ve Hukuki Sebebi</strong><br>
                                    Sağlık Kuruluşumuz kişisel verileri; teşhis ve tedavi süreçlerinde doğrudan elde etmekte veya elden teslim, posta, kargo aracılığı ile; manuel, sayısal, otomatik, kısmi otomatik veya entegrasyon yöntemleri ile toplamaktadır.
                                </div>
                                <div>
                                    <strong>d) Kişisel Veri Sahibinin 6698 sayılı Kanun'un 11. Maddesinde Sayılan Hakları</strong><br>
                                    Kişisel veri sahipleri olarak, haklarınıza ilişkin taleplerinizi Sağlık Kuruluşumuza iletmeniz durumunda en kısa sürede ve en geç otuz gün içinde ücretsiz olarak sonuçlandıracaktır.
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            Yukarıdaki bilgiler KVKK açık rıza metninin özetidir. Detaylı metin için lütfen tam KVKK dokümantasyonunu inceleyin.
                        </p>
                    </div>


                    <!-- Submit -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('kvkk.show', $patient) }}"
                           class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Onamı Oluştur
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>