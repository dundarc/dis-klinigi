<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">KVKK Hasta Detayları</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $patient->first_name }} {{ $patient->last_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('kvkk.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
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

            <!-- Son Onam Kutucuğu -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Son KVKK Onamı</h3>

                @if($consentStatus['status'] || $consentStatus['pending'])
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Durum</div>
                            @if($consentStatus['status'])
                                <div class="text-lg font-semibold text-green-600">
                                    Onaylı
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    {{ $consentStatus['method'] === 'email_verification' ? 'E-posta ile doğrulandı' : 'Islak imza ile onaylandı' }}
                                </div>
                            @else
                                <div class="text-lg font-semibold text-yellow-600">
                                    Beklemede
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    E-posta doğrulaması bekleniyor
                                </div>
                            @endif
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Tarih</div>
                            <div class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                                {{ $consentStatus['latest_at'] ? \Carbon\Carbon::parse($consentStatus['latest_at'])->format('d.m.Y H:i') : '-' }}
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Versiyon</div>
                            <div class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                                {{ $patient->consents->first()?->version ?? '-' }}
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 text-center">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Hash</div>
                            <div class="text-sm font-mono text-slate-900 dark:text-slate-100">
                                {{ $patient->consents->first()?->hash ? substr($patient->consents->first()->hash, 0, 16) . '...' : '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">IP Adresi</div>
                            <div class="text-sm font-mono text-slate-900 dark:text-slate-100">
                                {{ $patient->consents->first()?->ip_address ?? '-' }}
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                            <div class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">User Agent</div>
                            <div class="text-sm text-slate-900 dark:text-slate-100 truncate" title="{{ $patient->consents->first()?->user_agent }}">
                                {{ $patient->consents->first()?->user_agent ? substr($patient->consents->first()->user_agent, 0, 50) . '...' : '-' }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-orange-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <h4 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-2">KVKK Onamı Bulunmuyor</h4>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Bu hasta için henüz KVKK onamı alınmamış.</p>
                        <a href="{{ route('kvkk.create-consent', $patient) }}"
                           class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            KVKK Onamı Oluştur
                        </a>
                    </div>
                @endif
            </div>

            <!-- Aksiyon Butonları -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">KVKK İşlemleri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    <!-- Onam PDF Yazdır -->
                    @if($consentStatus['status'])
                    <a href="{{ route('kvkk.consent-pdf', $patient) }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Onam PDF
                    </a>
                    @else
                    <div class="inline-flex items-center justify-center px-4 py-3 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Onam PDF
                    </div>
                    @endif

                    <!-- Export ZIP -->
                    <a href="{{ route('kvkk.export', $patient) }}"
                       class="inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                        Export ZIP
                    </a>

                    <!-- Onam İptal -->
                    @if($patient->hasKvkkConsent())
                    <a href="{{ route('kvkk.cancel-consent', $patient) }}"
                       class="inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Onam İptal
                    </a>
                    @endif

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
                                                {{ $log->action->label() }}
                                            </span>
                                        @elseif($log->action->value === 'hard_delete')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                {{ $log->action->label() }}
                                            </span>
                                        @elseif($log->action->value === 'restore')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ $log->action->label() }}
                                            </span>
                                        @elseif($log->action->value === 'export')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $log->action->label() }}
                                            </span>
                                        @elseif($log->action->value === 'create_consent')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ $log->action->label() }}
                                            </span>
                                        @elseif($log->action->value === 'cancel_consent')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                {{ $log->action->label() }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                {{ $log->action->label() }}
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

</x-app-layout>