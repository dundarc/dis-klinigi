<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Hard Delete Onayı</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $patient->first_name }} {{ $patient->last_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('kvkk.show', $patient) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Hasta Detayına Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Uyarı Kartı -->
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-2">⚠️ Tehlikeli İşlem</h3>
                        <div class="text-sm text-red-700 dark:text-red-300 space-y-1">
                            <p><strong>Bu işlem geri alınamaz!</strong> Hasta verilerinin kalıcı olarak silinmesi isteniyor.</p>
                            <p>Bu işlem tüm hasta bilgilerini, randevuları, faturaları, tedavi planlarını ve dosyaları kalıcı olarak silecektir.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hasta Bilgileri -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Silinecek Hasta Bilgileri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Ad Soyad:</span>
                            <span class="text-sm text-slate-900 dark:text-slate-100">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">TC Kimlik:</span>
                            <span class="text-sm text-slate-900 dark:text-slate-100">{{ $patient->national_id ? '*** *** ** ' . substr($patient->national_id, -2) : 'Belirtilmemiş' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Doğum Tarihi:</span>
                            <span class="text-sm text-slate-900 dark:text-slate-100">{{ $patient->birth_date?->format('d.m.Y') ?? 'Belirtilmemiş' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Cinsiyet:</span>
                            <span class="text-sm text-slate-900 dark:text-slate-100">{{ $patient->gender?->label() ?? 'Belirtilmemiş' }}</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Telefon:</span>
                            <span class="text-sm text-slate-900 dark:text-slate-100">{{ $patient->phone_primary ? '*** *** ' . substr($patient->phone_primary, -4) : 'Belirtilmemiş' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">E-posta:</span>
                            <span class="text-sm text-slate-900 dark:text-slate-100">{{ $patient->email ? substr($patient->email, 0, 2) . '***@' . explode('@', $patient->email)[1] : 'Belirtilmemiş' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Kayıt Tarihi:</span>
                            <span class="text-sm text-slate-900 dark:text-slate-100">{{ $patient->created_at?->format('d.m.Y H:i') ?? 'Belirtilmemiş' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- İlişkili Veriler -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Silinecek İlişkili Veriler</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $patient->appointments->count() }}</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Randevu</div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $patient->invoices->count() }}</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Fatura</div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $patient->treatmentPlans->count() }}</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Tedavi Planı</div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $patient->files->count() }}</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Dosya</div>
                    </div>
                </div>
            </div>

            <!-- Onay Formu -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">İşlemi Onaylayın</h3>

                <div class="space-y-4 mb-6">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="understand-risk" class="mt-1 h-4 w-4 text-red-600 focus:ring-red-500 border-slate-300 rounded">
                        <label for="understand-risk" class="text-sm text-slate-700 dark:text-slate-300">
                            Bu işlemin geri alınamayacağını ve tüm hasta verilerinin kalıcı olarak silineceğini anladım.
                        </label>
                    </div>
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="confirm-deletion" class="mt-1 h-4 w-4 text-red-600 focus:ring-red-500 border-slate-300 rounded">
                        <label for="confirm-deletion" class="text-sm text-slate-700 dark:text-slate-300">
                            Hasta <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong> ve tüm ilişkili verilerinin kalıcı olarak silinmesini onaylıyorum.
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('kvkk.show', $patient) }}"
                       class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                        İptal Et
                    </a>
                    <form action="{{ route('kvkk.hard-delete', $patient) }}" method="POST" id="hard-delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                id="confirm-hard-delete"
                                disabled
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Kalıcı Olarak Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('hardDeleteConfirm', () => ({
                init() {
                    this.checkConfirmation();
                },
                checkConfirmation() {
                    const understandRisk = document.getElementById('understand-risk').checked;
                    const confirmDeletion = document.getElementById('confirm-deletion').checked;
                    const submitButton = document.getElementById('confirm-hard-delete');

                    submitButton.disabled = !(understandRisk && confirmDeletion);
                }
            }))
        });

        // Checkbox event listeners
        document.getElementById('understand-risk').addEventListener('change', function() {
            checkConfirmation();
        });
        document.getElementById('confirm-deletion').addEventListener('change', function() {
            checkConfirmation();
        });

        function checkConfirmation() {
            const understandRisk = document.getElementById('understand-risk').checked;
            const confirmDeletion = document.getElementById('confirm-deletion').checked;
            const submitButton = document.getElementById('confirm-hard-delete');

            submitButton.disabled = !(understandRisk && confirmDeletion);
        }
    </script>
</x-app-layout>