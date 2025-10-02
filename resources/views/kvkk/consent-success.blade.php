<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">KVKK Onam Başarıyla Oluşturuldu</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $patient->first_name }} {{ $patient->last_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('kvkk.show', $patient) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    KVKK Ana Sayfa
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Message -->
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6 mb-8">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-2">KVKK Onamınız Başarıyla Oluşturuldu!</h3>
                        <p class="text-green-700 dark:text-green-300">
                            Kişisel verilerinizin işlenmesi için verdiğiniz onam başarıyla kaydedildi.
                            @if($consent->status === \App\Enums\ConsentStatus::PENDING)
                                Onamınız e-posta doğrulaması bekliyor.
                            @else
                                Onamınız aktif durumda ve sistemde kullanılabilir.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Operation Summary -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-8">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">İşlem Özeti</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-slate-200 dark:border-slate-700">
                        <span class="text-slate-600 dark:text-slate-400">Hasta Adı:</span>
                        <span class="font-medium text-slate-900 dark:text-slate-100">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-200 dark:border-slate-700">
                        <span class="text-slate-600 dark:text-slate-400">Onam Tarihi:</span>
                        <span class="font-medium text-slate-900 dark:text-slate-100">{{ $consent->accepted_at?->format('d.m.Y H:i') ?? 'Henüz aktif değil' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-200 dark:border-slate-700">
                        <span class="text-slate-600 dark:text-slate-400">Onam Durumu:</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            @if($consent->status === \App\Enums\ConsentStatus::ACTIVE) bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200
                            @elseif($consent->status === \App\Enums\ConsentStatus::PENDING) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200
                            @else bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200 @endif">
                            @if($consent->status === \App\Enums\ConsentStatus::ACTIVE) Aktif
                            @elseif($consent->status === \App\Enums\ConsentStatus::PENDING) E-posta Doğrulaması Bekleniyor
                            @else {{ $consent->status->label() }} @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-200 dark:border-slate-700">
                        <span class="text-slate-600 dark:text-slate-400">Onam Yöntemi:</span>
                        <span class="font-medium text-slate-900 dark:text-slate-100">
                            @if($consent->consent_method === 'wet_signature') Islak İmza
                            @elseif($consent->consent_method === 'email_verification') E-posta Doğrulaması
                            @else Bilinmiyor @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <span class="text-slate-600 dark:text-slate-400">IP Adresi:</span>
                        <span class="font-medium text-slate-900 dark:text-slate-100">{{ $consent->ip_address ?? 'Bilinmiyor' }}</span>
                    </div>
                </div>
            </div>

            <!-- Consent PDF -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-8">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">KVKK Onam Belgesi</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 dark:text-slate-400 mb-2">Oluşturulan onam belgesini indirebilirsiniz.</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Belge PDF formatında oluşturulmuştur.</p>
                    </div>
                    <a href="{{ route('kvkk.consent-pdf', $patient) }}"
                       target="_blank"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        PDF İndir
                    </a>
                </div>
            </div>

            <!-- System Activations Info -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 mb-8">
                <h3 class="text-xl font-semibold text-blue-900 dark:text-blue-100 mb-4">Sistemde Etkinleşen Özellikler</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-900 dark:text-blue-100">Randevu Yönetimi</h4>
                            <p class="text-blue-700 dark:text-blue-300 text-sm">Hastanın randevu bilgileri sisteme kaydedilebilir ve yönetilebilir.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-900 dark:text-blue-100">Tedavi Kayıtları</h4>
                            <p class="text-blue-700 dark:text-blue-300 text-sm">Tedavi planları, uygulanan işlemler ve tedavi geçmişi saklanabilir.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-900 dark:text-blue-100">Fatura ve Ödeme İşlemleri</h4>
                            <p class="text-blue-700 dark:text-blue-300 text-sm">Faturalar oluşturulabilir ve ödeme bilgileri işlenebilir.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-900 dark:text-blue-100">Dosya ve Görüntü Yönetimi</h4>
                            <p class="text-blue-700 dark:text-blue-300 text-sm">Röntgen görüntüleri, tedavi fotoğrafları ve diğer tıbbi dosyalar saklanabilir.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-900 dark:text-blue-100">İletişim ve Bildirimler</h4>
                            <p class="text-blue-700 dark:text-blue-300 text-sm">Randevu hatırlatmaları ve tedavi bilgileri için iletişim kurulabilir.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-900 dark:text-blue-100">Raporlama ve Analiz</h4>
                            <p class="text-blue-700 dark:text-blue-300 text-sm">Tedavi sonuçları ve hasta geçmişi analiz edilebilir.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-100 dark:bg-blue-800/30 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Önemli:</strong> Verdiğiniz onam, 6698 sayılı KVKK kapsamında kişisel verilerinizin işlenmesi için gereklidir.
                        Onamınızı istediğiniz zaman iptal edebilirsiniz. İptal durumunda verileriniz silinecek veya anonimleştirilecektir.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('kvkk.show', $patient) }}" class="inline-flex items-center justify-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    KVKK Ana Sayfa
                </a>
                <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Hasta Detayları
                </a>
            </div>
        </div>
    </div>
</x-app-layout>