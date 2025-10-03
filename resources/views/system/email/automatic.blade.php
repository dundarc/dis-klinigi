<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Otomatik E-posta Bildirimleri</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Sistem olaylarında otomatik e-posta gönderimi ayarları</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('system.email.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    E-posta Ana Sayfasına Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Automatic Email Settings Form -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Otomatik E-posta Ayarları</h3>

                <form action="{{ route('system.email.automatic.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Patient Check-in to Dentist -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-slate-900 dark:text-slate-100">Hasta Check-in Bildirimi</h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                Hasta randevuya check-in yaptığında ilgili doktora e-posta gönderilsin mi?
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="patient_checkin_to_dentist" value="1"
                                       {{ ($settings->patient_checkin_to_dentist ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                            </label>
                            <button type="button" data-test="patient-checkin"
                                    class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors test-btn">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Test Et
                            </button>
                        </div>
                    </div>

                    <!-- Emergency Patient to Dentist -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-slate-900 dark:text-slate-100">Acil Hasta Bildirimi</h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                Acile yeni hasta kaydı yapıldığında ilgili doktora e-posta gönderilsin mi?
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="emergency_patient_to_dentist" value="1"
                                       {{ ($settings->emergency_patient_to_dentist ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                            </label>
                            <button type="button" data-test="emergency-patient"
                                    class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors test-btn">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Test Et
                            </button>
                        </div>
                    </div>

                    <!-- KVKK Consent to Admin -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-slate-900 dark:text-slate-100">KVKK Onay Bildirimi</h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                Yeni bir KVKK onam formu onaylandığında sistem yöneticisine bilgi e-postası gönderilsin mi?
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="kvkk_consent_to_admin" value="1"
                                       {{ ($settings->kvkk_consent_to_admin ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                            </label>
                            <button type="button" data-test="kvkk-consent"
                                    class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors test-btn">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Test Et
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Ayarları Kaydet
                        </button>
                    </div>
                </form>
            </div>

            <!-- Test Results -->
            <div id="testResult" class="hidden">
                <div id="testSuccess" class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg hidden">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-green-800 dark:text-green-200">Test e-postası başarıyla gönderildi!</span>
                    </div>
                </div>

                <div id="testError" class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hidden">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="errorMessage" class="text-red-800 dark:text-red-200"></span>
                    </div>
                </div>
            </div>

            <!-- Information -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Bilgilendirme</h4>
                <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                    <li>• Test butonları ile ayarlarınızın doğru çalıştığını kontrol edebilirsiniz</li>
                    <li>• Test e-postaları giriş yapmış olan kullanıcıya gönderilir</li>
                    <li>• Otomatik e-postalar sadece ayar aktif edildiğinde gönderilir</li>
                    <li>• E-posta şablonları ayrı olarak yönetilir ve özelleştirilebilir</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const testButtons = document.querySelectorAll('.test-btn');

            testButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const testType = this.getAttribute('data-test');
                    let routeName = '';

                    switch(testType) {
                        case 'patient-checkin':
                            routeName = '{{ route("system.email.automatic.test-patient-checkin") }}';
                            break;
                        case 'emergency-patient':
                            routeName = '{{ route("system.email.automatic.test-emergency-patient") }}';
                            break;
                        case 'kvkk-consent':
                            routeName = '{{ route("system.email.automatic.test-kvkk-consent") }}';
                            break;
                    }

                    if (!routeName) return;

                    const originalText = this.innerHTML;
                    this.disabled = true;
                    this.innerHTML = '<svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Gönderiliyor...';

                    fetch(routeName, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const resultDiv = document.getElementById('testResult');
                        const successDiv = document.getElementById('testSuccess');
                        const errorDiv = document.getElementById('testError');
                        const errorMessage = document.getElementById('errorMessage');

                        resultDiv.classList.remove('hidden');
                        successDiv.classList.add('hidden');
                        errorDiv.classList.add('hidden');

                        if (data.success) {
                            successDiv.classList.remove('hidden');
                        } else {
                            errorDiv.classList.remove('hidden');
                            errorMessage.textContent = data.message;
                        }
                    })
                    .catch(error => {
                        const resultDiv = document.getElementById('testResult');
                        const errorDiv = document.getElementById('testError');
                        const errorMessage = document.getElementById('errorMessage');

                        resultDiv.classList.remove('hidden');
                        document.getElementById('testSuccess').classList.add('hidden');
                        errorDiv.classList.remove('hidden');
                        errorMessage.textContent = 'Bir hata oluştu: ' + error.message;
                    })
                    .finally(() => {
                        this.disabled = false;
                        this.innerHTML = originalText;
                    });
                });
            });
        });
    </script>
</x-app-layout>