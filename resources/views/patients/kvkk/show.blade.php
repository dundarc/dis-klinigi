<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">KVKK Hasta Detayları</h2>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Hasta Başlığı ve İletişim Özeti -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Bilgileri</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Ad Soyad:</span>
                                <span class="text-sm text-slate-900 dark:text-slate-100">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">TC Kimlik:</span>
                                <span class="text-sm text-slate-900 dark:text-slate-100 font-mono">{{ $patient->national_id ? '*** *** ** ' . substr($patient->national_id, -2) : 'Belirtilmemiş' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Doğum Tarihi:</span>
                                <span class="text-sm text-slate-900 dark:text-slate-100">{{ $patient->birth_date?->format('d.m.Y') ?? 'Belirtilmemiş' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Cinsiyet:</span>
                                <span class="text-sm text-slate-900 dark:text-slate-100">{{ $patient->gender?->label() ?? 'Belirtilmemiş' }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">İletişim Bilgileri</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Telefon (Birincil):</span>
                                <span class="text-sm text-slate-900 dark:text-slate-100 font-mono">{{ $patient->phone_primary ? '*** *** ' . substr($patient->phone_primary, -4) : 'Belirtilmemiş' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Telefon (İkincil):</span>
                                <span class="text-sm text-slate-900 dark:text-slate-100 font-mono">{{ $patient->phone_secondary ? '*** *** ' . substr($patient->phone_secondary, -4) : 'Belirtilmemiş' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">E-posta:</span>
                                <span class="text-sm text-slate-900 dark:text-slate-100 font-mono">{{ $patient->email ? substr($patient->email, 0, 2) . '***@' . explode('@', $patient->email)[1] : 'Belirtilmemiş' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KVKK Onam Durumu -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">KVKK Onam Durumu</h3>

                @if($patient->hasKvkkConsent())
                    <!-- Onam Bilgileri -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Durum</div>
                            <div class="text-lg font-semibold text-green-600">
                                Onaylı
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Tarih</div>
                            <div class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                                {{ $patient->latestConsent()->accepted_at?->format('d.m.Y H:i') ?? '-' }}
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Versiyon</div>
                            <div class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                                {{ $patient->latestConsent()->version ?? '-' }}
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Hash</div>
                            <div class="text-sm font-mono text-slate-900 dark:text-slate-100">
                                {{ $patient->latestConsent()->hash ? substr($patient->latestConsent()->hash, 0, 16) . '...' : '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">IP Adresi</div>
                            <div class="text-sm font-mono text-slate-900 dark:text-slate-100">
                                {{ $patient->latestConsent()->ip_address ?? '-' }}
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">User Agent</div>
                            <div class="text-sm text-slate-900 dark:text-slate-100 truncate" title="{{ $patient->latestConsent()->user_agent }}">
                                {{ $patient->latestConsent()->user_agent ? substr($patient->latestConsent()->user_agent, 0, 50) . '...' : '-' }}
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Onam Formu -->
                    <form action="{{ route('patients.kvkk.store-consent', $patient) }}" method="POST" id="consentForm">
                        @csrf

                        <!-- KVKK Aydınlatma Metni Kısa Özeti -->
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 mb-6">
                            <h4 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-2">KVKK Aydınlatma Metni</h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Kişisel verilerinizin 6698 sayılı KVKK kapsamında işlenmesi, korunması ve aktarılması hakkında bilgilendirildiniz.
                                Aşağıdaki onayı vererek kişisel verilerinizin belirtilen amaçlar doğrultusunda işlenmesine izin vermiş olursunuz.
                            </p>
                        </div>

                        <!-- Onam Checkbox -->
                        <div class="flex items-start mb-6">
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
                @endif
            </div>

            <!-- Aksiyon Butonları -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">KVKK İşlemleri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    <!-- Onam PDF Yazdır -->
                    <a href="{{ route('kvkk.export', ['patient' => $patient, 'format' => 'pdf']) }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Onam PDF
                    </a>

                    <!-- Export ZIP -->
                    <button @click="showExportModal = true"
                            class="inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                        Export ZIP
                    </button>

                    <!-- Soft Delete -->
                    <form action="{{ route('kvkk.soft-delete', $patient) }}" method="POST" class="inline"
                          onsubmit="return confirm('{{ $patient->first_name }} {{ $patient->last_name }} hastasının tüm verilerini KVKK uyumluluğu için soft delete işlemine tabi tutmak istediğinizden emin misiniz?\n\nBu işlem geri alınabilir ancak hasta verileri normal erişimden kaldırılacaktır.')">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Soft Delete
                        </button>
                    </form>

                    <!-- Hard Delete (Admin) -->
                    @can('hardDelete', $patient)
                        <a href="{{ route('kvkk.hard-delete.confirm', $patient) }}"
                           class="inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hard Delete
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Audit Log Geçmişi -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">KVKK İşlem Geçmişi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Tarih</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">İşlem</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Kullanıcı</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">IP</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Detay</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($patient->kvkkAuditLogs as $log)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">
                                        {{ $log->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($log->action->value === 'soft_delete')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                Soft Delete
                                            </span>
                                        @elseif($log->action->value === 'hard_delete')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Hard Delete
                                            </span>
                                        @elseif($log->action->value === 'restore')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Geri Yükleme
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                {{ ucfirst($log->action->value) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">
                                        {{ $log->user?->name ?? 'Sistem' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-mono text-slate-900 dark:text-slate-100">
                                        {{ $log->ip_address }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                                        {{ $log->meta['reason'] ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                        KVKK işlem geçmişi bulunmuyor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div x-show="showExportModal" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="showExportModal = false">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" @click="showExportModal = false">
                <div class="absolute inset-0 bg-slate-500 dark:bg-slate-900 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('kvkk.export', $patient) }}" method="GET" target="_blank" rel="noopener">
                    <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-slate-100">
                                    Veri Export Seçenekleri
                                </h3>
                                <div class="mt-4">
                                    <div class="flex items-center">
                                        <input id="masking" name="masking" type="checkbox" value="1"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                        <label for="masking" class="ml-2 block text-sm text-slate-900 dark:text-slate-100">
                                            Hassas verileri maskele (TC, telefon, e-posta, adres)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Export Et
                        </button>
                        <button type="button" @click="showExportModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            İptal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kvkkShow', () => ({
                showExportModal: false,
            }))
        })

        // Consent form validation
        document.addEventListener('DOMContentLoaded', function() {
            const consentCheckbox = document.getElementById('consent_accepted');
            const submitButton = document.getElementById('submitConsent');

            if (consentCheckbox && submitButton) {
                consentCheckbox.addEventListener('change', function() {
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
            }
        });
    </script>
</x-app-layout>