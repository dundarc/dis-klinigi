<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">KVKK Onam Formu</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $patient->first_name }} {{ $patient->last_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('patients.kvkk.reports.missing') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Hasta Bilgileri -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Bilgileri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Ad Soyad</p>
                        <p class="font-medium text-slate-900 dark:text-slate-100">{{ $patient->first_name }} {{ $patient->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">TC Kimlik Numarası</p>
                        <p class="font-medium text-slate-900 dark:text-slate-100 font-mono">{{ $patient->national_id ?: 'Belirtilmemiş' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Telefon</p>
                        <p class="font-medium text-slate-900 dark:text-slate-100 font-mono">{{ $patient->phone_primary ?: 'Belirtilmemiş' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Kayıt Tarihi</p>
                        <p class="font-medium text-slate-900 dark:text-slate-100">{{ $patient->created_at ? $patient->created_at->format('d.m.Y') : 'Belirtilmemiş' }}</p>
                    </div>
                </div>
            </div>

            <!-- KVKK Aydınlatma Metni -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">KVKK Aydınlatma Metni</h3>
                <div class="border border-slate-200 dark:border-slate-600 rounded-lg p-4 max-h-96 overflow-y-auto bg-slate-50 dark:bg-slate-700/50">
                    <div class="prose prose-sm dark:prose-invert max-w-none">
                        <h4>6698 Sayılı Kişisel Verilerin Korunması Kanunu Kapsamında Aydınlatma Metni</h4>

                        <p><strong>Veri Sorumlusu:</strong> [Klinik Adı] Diş Kliniği</p>

                        <p>6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca, kişisel verilerinizin işlenmesine ilişkin olarak sizleri bilgilendirmek amacıyla bu aydınlatma metnini hazırladık.</p>

                        <h5>1. Kişisel Verilerin Toplanması ve İşlenme Amaçları</h5>
                        <p>Kişisel verileriniz, aşağıdaki amaçlarla işlenmektedir:</p>
                        <ul>
                            <li>Tıbbi teşhis, tedavi ve bakım hizmetlerinin yürütülmesi</li>
                            <li>Randevu ve tedavi planlaması</li>
                            <li>Fatura ve ödeme işlemlerinin gerçekleştirilmesi</li>
                            <li>Yasal yükümlülüklerin yerine getirilmesi</li>
                            <li>Kalite ve hasta memnuniyeti çalışmalarının yürütülmesi</li>
                        </ul>

                        <h5>2. İşlenen Kişisel Veri Kategorileri</h5>
                        <p>Aşağıdaki kategorilerde kişisel verileriniz işlenmektedir:</p>
                        <ul>
                            <li>Kimlik bilgileri (ad, soyad, TC kimlik numarası)</li>
                            <li>İletişim bilgileri (telefon, e-posta, adres)</li>
                            <li>Sağlık bilgileri (tıbbi geçmiş, teşhis, tedavi bilgileri)</li>
                            <li>Finansal bilgiler (ödeme bilgileri, fatura bilgileri)</li>
                        </ul>

                        <h5>3. Kişisel Verilerin Aktarılması</h5>
                        <p>Kişisel verileriniz, yasal zorunluluklar çerçevesinde aşağıdaki taraflara aktarılabilir:</p>
                        <ul>
                            <li>Sosyal Güvenlik Kurumu (SGK)</li>
                            <li>Vergi dairesi ve diğer kamu kurumları</li>
                            <li>İş ortakları ve tedarikçiler</li>
                            <li>Hukuki zorunluluklar halinde yetkili merciler</li>
                        </ul>

                        <h5>4. Kişisel Verilerin Korunma Yöntemleri</h5>
                        <p>Kişisel verileriniz, KVKK ve ilgili mevzuata uygun olarak aşağıdaki güvenlik tedbirleri alınarak korunmaktadır:</p>
                        <ul>
                            <li>Fiziksel güvenlik tedbirleri (kilitli dolaplar, güvenli odalar)</li>
                            <li>Teknik güvenlik tedbirleri (şifreleme, firewall, antivirus)</li>
                            <li>Yönetimsel güvenlik tedbirleri (eğitim, yetkilendirme, denetim)</li>
                        </ul>

                        <h5>5. Haklarınız</h5>
                        <p>KVKK'nın 11. maddesi uyarınca aşağıdaki haklara sahipsiniz:</p>
                        <ul>
                            <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
                            <li>Kişisel veriler işlenmişse buna ilişkin bilgi talep etme</li>
                            <li>Kişisel verilerin işlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme</li>
                            <li>Yurt içinde veya yurt dışında kişisel verilerin aktarıldığı üçüncü kişileri bilme</li>
                            <li>Kişisel verilerin eksik veya yanlış işlenmiş olması hâlinde bunların düzeltilmesini isteme</li>
                            <li>KVKK'nın 7. maddesinde öngörülen şartlar çerçevesinde kişisel verilerin silinmesini veya yok edilmesini isteme</li>
                            <li>Düzeltilme veya silme işlemlerinin kişisel verilerin aktarıldığı üçüncü kişilere bildirilmesini isteme</li>
                            <li>İşlenen verilerin münhasıran otomatik sistemler vasıtasıyla analiz edilmesi suretiyle kişinin kendisi aleyhine bir sonucun ortaya çıkmasına itiraz etme</li>
                            <li>Kişisel verilerin kanuna aykırı olarak işlenmesi sebebiyle zarara uğraması hâlinde zararın giderilmesini talep etme</li>
                        </ul>

                        <h5>6. İletişim</h5>
                        <p>Yukarıda belirtilen haklarınızı kullanmak için [klinik iletişim bilgileri] adresinden bizimle iletişime geçebilirsiniz.</p>

                        <p>Bu aydınlatma metni [tarih] tarihinde güncellenmiştir.</p>
                    </div>
                </div>
            </div>

            <!-- Onam Formu -->
            <form action="{{ route('patients.kvkk.store-consent', $patient) }}" method="POST" id="consentForm">
                @csrf

                <!-- Onam Checkbox -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="consent_accepted" name="consent_accepted" type="checkbox" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="consent_accepted" class="text-slate-900 dark:text-slate-100 font-medium">
                                KVKK Aydınlatma Metnini okudum, anladım ve kabul ediyorum.
                            </label>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">
                                Bu onayı vererek, kişisel verilerimin yukarıda belirtilen amaçlar doğrultusunda işlenmesine ve aktarılmasına izin veriyorum.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- E-İmza Alanı -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">E-İmza (İsteğe Bağlı)</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                        Aşağıdaki alana imzanızı çizerek onayı dijital olarak imzalayabilirsiniz. Bu adım isteğe bağlıdır.
                    </p>

                    <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-4">
                        <canvas id="signatureCanvas"
                                class="w-full h-40 border border-slate-200 dark:border-slate-600 rounded bg-white dark:bg-slate-700"
                                style="cursor: crosshair;"></canvas>

                        <div class="flex justify-between items-center mt-4">
                            <button type="button" id="clearSignature"
                                    class="inline-flex items-center px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Temizle
                            </button>
                            <span class="text-sm text-slate-500 dark:text-slate-400">İmzanızı çizmek için fareyi kullanın</span>
                        </div>
                    </div>

                    <input type="hidden" name="signature" id="signatureInput">
                </div>

                <!-- Onay Butonu -->
                <div class="flex justify-end">
                    <button type="submit" id="submitConsent" disabled
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-400 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Onamı Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('consentForm', () => ({
                consentAccepted: false,
                signatureData: null,

                init() {
                    this.initSignaturePad();
                    this.checkFormValidity();
                },

                initSignaturePad() {
                    const canvas = document.getElementById('signatureCanvas');
                    const ctx = canvas.getContext('2d');
                    let isDrawing = false;

                    // Set canvas size
                    canvas.width = canvas.offsetWidth;
                    canvas.height = canvas.offsetHeight;

                    // Clear canvas initially
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    ctx.strokeStyle = '#000';
                    ctx.lineWidth = 2;
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';

                    // Mouse events
                    canvas.addEventListener('mousedown', (e) => {
                        isDrawing = true;
                        ctx.beginPath();
                        ctx.moveTo(e.offsetX, e.offsetY);
                    });

                    canvas.addEventListener('mousemove', (e) => {
                        if (isDrawing) {
                            ctx.lineTo(e.offsetX, e.offsetY);
                            ctx.stroke();
                        }
                    });

                    canvas.addEventListener('mouseup', () => {
                        isDrawing = false;
                        this.updateSignatureData();
                    });

                    canvas.addEventListener('mouseout', () => {
                        isDrawing = false;
                    });

                    // Touch events for mobile
                    canvas.addEventListener('touchstart', (e) => {
                        e.preventDefault();
                        isDrawing = true;
                        const rect = canvas.getBoundingClientRect();
                        const x = e.touches[0].clientX - rect.left;
                        const y = e.touches[0].clientY - rect.top;
                        ctx.beginPath();
                        ctx.moveTo(x, y);
                    });

                    canvas.addEventListener('touchmove', (e) => {
                        e.preventDefault();
                        if (isDrawing) {
                            const rect = canvas.getBoundingClientRect();
                            const x = e.touches[0].clientX - rect.left;
                            const y = e.touches[0].clientY - rect.top;
                            ctx.lineTo(x, y);
                            ctx.stroke();
                        }
                    });

                    canvas.addEventListener('touchend', (e) => {
                        e.preventDefault();
                        isDrawing = false;
                        this.updateSignatureData();
                    });
                },

                updateSignatureData() {
                    const canvas = document.getElementById('signatureCanvas');
                    this.signatureData = canvas.toDataURL('image/png');
                    document.getElementById('signatureInput').value = this.signatureData;
                },

                clearSignature() {
                    const canvas = document.getElementById('signatureCanvas');
                    const ctx = canvas.getContext('2d');
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    this.signatureData = null;
                    document.getElementById('signatureInput').value = '';
                },

                checkFormValidity() {
                    const submitButton = document.getElementById('submitConsent');
                    const consentCheckbox = document.getElementById('consent_accepted');

                    if (consentCheckbox.checked) {
                        submitButton.disabled = false;
                        submitButton.classList.remove('disabled:bg-slate-400', 'disabled:cursor-not-allowed');
                        submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    } else {
                        submitButton.disabled = true;
                        submitButton.classList.add('disabled:bg-slate-400', 'disabled:cursor-not-allowed');
                        submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    }
                }
            }))
        });

        // Event listeners
        document.getElementById('consent_accepted').addEventListener('change', function() {
            const submitButton = document.getElementById('submitConsent');
            if (this.checked) {
                submitButton.disabled = false;
                submitButton.classList.remove('disabled:bg-slate-400', 'disabled:cursor-not-allowed');
                submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
            } else {
                submitButton.disabled = true;
                submitButton.classList.add('disabled:bg-slate-400', 'disabled:cursor-not-allowed');
                submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            }
        });

        document.getElementById('clearSignature').addEventListener('click', function() {
            const canvas = document.getElementById('signatureCanvas');
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            document.getElementById('signatureInput').value = '';
        });
    </script>
</x-app-layout>