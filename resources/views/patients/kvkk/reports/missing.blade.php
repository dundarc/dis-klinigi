<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">KVKK Onamı Olmayan Hastalar</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">KVKK onamı alınmamış veya süresi dolmuş hastalar</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('kvkk.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    KVKK Ana Sayfa
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- İstatistikler -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Toplam Hasta</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['total_patients']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Onam Yok</p>
                            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['missing_consents']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Geri Çekildi</p>
                            <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['withdrawn_consents']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Süresi Dolmuş</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['expired_consents']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hasta Arama -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Hasta Ara</h3>
                <form method="GET" action="{{ route('patients.kvkk.reports.missing') }}" class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Hasta adı, soyadı, TC kimlik veya telefon ile ara..."
                               class="w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Ara
                    </button>
                </form>
            </div>

            <!-- Hasta Listesi -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">KVKK Onam Sorunu Olan Hastalar</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Toplam {{ $patients->total() }} hasta ({{ $stats['total_issues'] }} sorunlu kayıt)
                    </p>
                </div>

                @if($patients->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-800">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Ad Soyad</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">TC Kimlik</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Telefon</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Kayıt Tarihi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">İşlem</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($patients as $patient)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                                {{ $patient->first_name }} {{ $patient->last_name }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100 font-mono">
                                            {{ $patient->national_id ? '*** *** ** ' . substr($patient->national_id, -2) : 'Belirtilmemiş' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100 font-mono">
                                            {{ $patient->phone_primary ? '*** *** ' . substr($patient->phone_primary, -4) : 'Belirtilmemiş' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">
                                            {{ $patient->created_at ? $patient->created_at->format('d.m.Y') : 'Belirtilmemiş' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('patients.kvkk.consent', $patient) }}"
                                               class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Onam Al
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Sayfalama -->
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $patients->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-green-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-2">
                            Tüm hastaların KVKK onamı mevcut
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400">
                            Tebrikler! Sistemdeki tüm hastaların geçerli KVKK onamı bulunmaktadır.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>