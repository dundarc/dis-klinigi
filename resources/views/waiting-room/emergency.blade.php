<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Acil Hastalar Listesi</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Triyaj seviyesine göre acil hastaları yönetin</p>
            </div>
            @can('createEmergency', App\Models\Encounter::class)
                <a href="{{ route('waiting-room.emergency.create') }}" class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Acil Kaydı Ekle
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Acil Bekleme Listesi</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Kritik seviyeden normale doğru triyaj öncelik sırasına göre listelenmektedir</p>
                </div>

                <div class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($emergencyEncounters as $encounter)
                        <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4 flex-1">
                                    <!-- Patient Avatar with Triage Indicator -->
                                    <div class="relative">
                                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <!-- Triage Level Indicator -->
                                        @if($encounter->triage_level)
                                            <div class="absolute -top-1 -right-1 w-5 h-5 rounded-full border-2 border-white dark:border-slate-800
                                                {{ $encounter->triage_level->value === 'red' ? 'bg-red-500' :
                                                   ($encounter->triage_level->value === 'yellow' ? 'bg-yellow-500' : 'bg-green-500') }}">
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Patient Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                                                @if($encounter->patient)
                                                    {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
                                                @else
                                                    Hasta Bilgisi Yok
                                                @endif
                                            </h4>

                                            <!-- Triage Badge -->
                                            @if($encounter->triage_level)
                                                @if($encounter->triage_level->value === 'red')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Kritik
                                                    </span>
                                                @elseif($encounter->triage_level->value === 'yellow')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Acil
                                                    </span>
                                                @elseif($encounter->triage_level->value === 'green')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Normal
                                                    </span>
                                                @endif
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-600 dark:text-slate-400">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Geliş: {{ $encounter->arrived_at->format('d.m.Y H:i') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span>Hekim: {{ $encounter->dentist->name ?? 'Atanmadı' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="flex-shrink-0">
                                    @can('update', $encounter)
                                        <a href="{{ route('waiting-room.action', $encounter) }}" class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            İşlem Yap
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Bekleyen acil/randevusuz hasta bulunmamaktadır</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Tüm acil hastalar işlem görmüş veya bekleme listesi boş.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($emergencyEncounters->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                Siz hastaların {{ $emergencyEncounters->firstItem() }}-{{ $emergencyEncounters->lastItem() }} arasını görüyorsunuz
                                (Toplam: {{ $emergencyEncounters->total() }} hasta)
                            </div>
                            <div class="flex justify-center">
                                {{ $emergencyEncounters->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
