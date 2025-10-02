<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">E-posta Ayarları</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">SMTP ve e-posta gönderme ayarları</p>
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

            <!-- Email Settings Form -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">SMTP Ayarları</h3>

                <form action="{{ route('system.email.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mailer -->
                        <div>
                            <label for="mailer" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Mailer Türü
                            </label>
                            <select id="mailer" name="mailer"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200">
                                <option value="smtp" {{ ($settings->mailer ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="mailgun" {{ ($settings->mailer ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                <option value="ses" {{ ($settings->mailer ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                <option value="postmark" {{ ($settings->mailer ?? '') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                                <option value="sendmail" {{ ($settings->mailer ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            </select>
                        </div>

                        <!-- Host -->
                        <div>
                            <label for="host" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                SMTP Sunucusu
                            </label>
                            <input type="text" id="host" name="host" value="{{ $settings->host ?? '' }}"
                                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                                   placeholder="smtp.gmail.com">
                        </div>

                        <!-- Port -->
                        <div>
                            <label for="port" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Port
                            </label>
                            <input type="number" id="port" name="port" value="{{ $settings->port ?? 587 }}"
                                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200">
                        </div>

                        <!-- Encryption -->
                        <div>
                            <label for="encryption" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Şifreleme
                            </label>
                            <select id="encryption" name="encryption"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200">
                                <option value="tls" {{ ($settings->encryption ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ ($settings->encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Kullanıcı Adı
                            </label>
                            <input type="text" id="username" name="username" value="{{ $settings->username ?? '' }}"
                                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                                   placeholder="your-email@gmail.com">
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Şifre
                            </label>
                            <input type="password" id="password" name="password" value="{{ $settings->password ?? '' }}"
                                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                                   placeholder="••••••••">
                        </div>

                        <!-- From Address -->
                        <div>
                            <label for="from_address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Gönderen E-posta
                            </label>
                            <input type="email" id="from_address" name="from_address" value="{{ $settings->from_address ?? '' }}"
                                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                                   placeholder="noreply@clinic.com">
                        </div>

                        <!-- From Name -->
                        <div>
                            <label for="from_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Gönderen Adı
                            </label>
                            <input type="text" id="from_name" name="from_name" value="{{ $settings->from_name ?? '' }}"
                                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                                   placeholder="Klinik Sistemi">
                        </div>

                        <!-- DKIM Domain -->
                        <div>
                            <label for="dkim_domain" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                DKIM Domain
                            </label>
                            <input type="text" id="dkim_domain" name="dkim_domain" value="{{ $settings->dkim_domain ?? '' }}"
                                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                                   placeholder="example.com">
                        </div>

                        <!-- DKIM Selector -->
                        <div>
                            <label for="dkim_selector" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                DKIM Selector
                            </label>
                            <input type="text" id="dkim_selector" name="dkim_selector" value="{{ $settings->dkim_selector ?? '' }}"
                                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                                   placeholder="default">
                        </div>

                        <!-- DKIM Private Key -->
                        <div>
                            <label for="dkim_private_key" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                DKIM Private Key
                            </label>
                            <textarea id="dkim_private_key" name="dkim_private_key" rows="4"
                                      class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                                      placeholder="-----BEGIN PRIVATE KEY-----...">{{ $settings->dkim_private_key ?? '' }}</textarea>
                        </div>

                        <!-- SPF Record -->
                        <div>
                            <label for="spf_record" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                SPF Record
                            </label>
                            <input type="text" id="spf_record" name="spf_record" value="{{ $settings->spf_record ?? '' }}"
                                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                                   placeholder="v=spf1 include:_spf.example.com ~all">
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

            <!-- Test Email Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Test E-postası Gönder</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="test_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Test E-posta Adresi
                        </label>
                        <input type="email" id="test_email"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                               placeholder="test@example.com">
                    </div>

                    <div class="flex items-end">
                        <button type="button" id="sendTestEmail" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Test E-postası Gönder
                        </button>
                    </div>
                </div>

                <div id="testResult" class="mt-4 hidden">
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
            </div>

            <!-- DKIM/SPF Information -->
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                <h4 class="text-sm font-medium text-amber-800 dark:text-amber-200 mb-2">DKIM & SPF Bilgilendirmesi</h4>
                <ul class="text-sm text-amber-700 dark:text-amber-300 space-y-1">
                    <li>• SPF/DKIM DNS kayıtlarını kendi alan adınızda ayarlamanız gerekir</li>
                    <li>• DKIM private key güvenli bir şekilde saklanır (şifrelenir)</li>
                    <li>• SPF record örneği: <code>v=spf1 include:_spf.yourdomain.com ~all</code></li>
                    <li>• DKIM selector genellikle "default" veya "mail" kullanılır</li>
                </ul>
            </div>

            <!-- Information -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Bilgilendirme</h4>
                <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                    <li>• E-posta ayarları veritabanında saklanır, .env dosyası kullanılmaz</li>
                    <li>• Her e-posta gönderiminde ayarlar otomatik olarak yüklenir</li>
                    <li>• Test e-postası göndermeden önce ayarları kaydediniz</li>
                    <li>• SMTP, Mailgun, SES, Postmark ve Sendmail desteklenir</li>
                    <li>• E-posta kuyruğu ile yüksek performanslı gönderim</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('emailSettings', () => ({
                testEmail: '',

                sendTestEmail() {
                    if (!this.testEmail) {
                        alert('Lütfen test e-posta adresi giriniz.');
                        return;
                    }

                    const button = document.getElementById('sendTestEmail');
                    const originalText = button.innerHTML;
                    button.disabled = true;
                    button.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Gönderiliyor...';

                    fetch('{{ route("system.email.test") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            test_email: this.testEmail
                        })
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
                        button.disabled = false;
                        button.innerHTML = originalText;
                    });
                }
            }))
        });

        // Simple vanilla JS for test email
        document.getElementById('sendTestEmail').addEventListener('click', function() {
            const testEmail = document.getElementById('test_email').value;

            if (!testEmail) {
                alert('Lütfen test e-posta adresi giriniz.');
                return;
            }

            const button = this;
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Gönderiliyor...';

            fetch('{{ route("system.email.test") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    test_email: testEmail
                })
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
                button.disabled = false;
                button.innerHTML = originalText;
            });
        });
    </script>
</x-app-layout>