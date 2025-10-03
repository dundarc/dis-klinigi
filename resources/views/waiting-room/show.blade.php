<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Ziyaret Görüntüleme</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('encounters.pdf', $encounter) }}"
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF İndir
                </a>
                <a href="{{ route('waiting-room.action', $encounter) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
                <a href="{{ route('waiting-room.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Visit Information -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Ziyaret Bilgileri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Ziyaret Durumu</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $encounter->status->label() }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Doktor</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $encounter->dentist?->name ?? 'Atanmamış' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Giriş Saati</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $encounter->arrived_at?->format('d.m.Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Çıkış Saati</p>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $encounter->ended_at?->format('d.m.Y H:i') ?? 'Devam ediyor' }}</p>
                    </div>
                </div>
                @if($encounter->notes)
                <div class="mt-4">
                    <p class="text-slate-500 dark:text-slate-400">Notlar</p>
                    <p class="text-slate-900 dark:text-slate-100 mt-1">{{ $encounter->notes }}</p>
                </div>
                @endif
            </div>

            @php
                // Separate treatments from treatment plan vs manually added
                $treatmentPlanTreatments = $encounter->treatments->where('treatment_plan_item_id', '!=', null);
                $manualTreatments = $encounter->treatments->where('treatment_plan_item_id', null);
            @endphp

            <!-- Treatments from Treatment Plan -->
            @if($treatmentPlanTreatments->isNotEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Tedavi Planından Yapılan İşlemler</h3>
                        <span class="text-xs px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-full">{{ $treatmentPlanTreatments->count() }} işlem</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($treatmentPlanTreatments as $treatment)
                        <div class="flex items-center justify-between p-4 rounded-lg border border-green-200 dark:border-green-700 bg-green-50 dark:bg-green-900/20">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900 dark:text-slate-100">{{ $treatment->display_treatment_name }}</div>
                                        <div class="text-sm text-slate-600 dark:text-slate-400">
                                            <span class="inline-flex items-center px-2 py-1 bg-green-200 dark:bg-green-800 rounded text-xs font-medium text-green-800 dark:text-green-200 mr-2">
                                                📋 Tedavi Planından
                                            </span>
                                            {{ $treatment->performed_at?->format('d.m.Y H:i') ?? '' }}
                                            @if($treatment->tooth_number)
                                            <span class="ml-2 px-2 py-1 bg-slate-200 dark:bg-slate-700 rounded text-xs">#{{ $treatment->tooth_number }}</span>
                                            @endif
                                        </div>
                                        @if($treatment->notes)
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $treatment->notes }}</div>
                                        @endif
                                        @if($treatment->treatmentPlanItem)
                                        <div class="text-xs text-green-600 dark:text-green-400 mt-1">

                                             <a href="{{ route('treatment-plans.show', $treatment->treatmentPlanItem->treatmentPlan->id) }}">Tedavi Planı: {{ $treatment->treatmentPlanItem->treatmentPlan->id ?? 'Plan Bulunamadı' }}</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ number_format($treatment->unit_price, 2, ',', '.') }} TL</div>
                                <div class="text-xs text-green-600 dark:text-green-400">Planlı tedavi</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @php
                        $totalPlanPrice = $treatmentPlanTreatments->sum('unit_price');
                    @endphp
                    @if($totalPlanPrice > 0)
                    <div class="mt-4 pt-4 border-t border-green-200 dark:border-green-700">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-900 dark:text-slate-100">Tedavi Planı Toplamı:</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ number_format($totalPlanPrice, 2, ',', '.') }} TL</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Manual Treatments (Outside Treatment Plan) -->
            @if($manualTreatments->isNotEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Tedavi Planı Dışında Yapılan İşlemler</h3>
                        <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-full">{{ $manualTreatments->count() }} işlem</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($manualTreatments as $treatment)
                        <div class="flex items-center justify-between p-4 rounded-lg border border-blue-200 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/20">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900 dark:text-slate-100">{{ $treatment->display_treatment_name }}</div>
                                        <div class="text-sm text-slate-600 dark:text-slate-400">
                                            <span class="inline-flex items-center px-2 py-1 bg-blue-200 dark:bg-blue-800 rounded text-xs font-medium text-blue-800 dark:text-blue-200 mr-2">
                                                ➕ Manuel Ekleme
                                            </span>
                                            {{ $treatment->performed_at?->format('d.m.Y H:i') ?? '' }}
                                            @if($treatment->tooth_number)
                                            <span class="ml-2 px-2 py-1 bg-slate-200 dark:bg-slate-700 rounded text-xs">#{{ $treatment->tooth_number }}</span>
                                            @endif
                                        </div>
                                        @if($treatment->notes)
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $treatment->notes }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ number_format($treatment->unit_price, 2, ',', '.') }} TL</div>
                                <div class="text-xs text-blue-600 dark:text-blue-400">Manuel tedavi</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @php
                        $totalManualPrice = $manualTreatments->sum('unit_price');
                    @endphp
                    @if($totalManualPrice > 0)
                    <div class="mt-4 pt-4 border-t border-blue-200 dark:border-blue-700">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-900 dark:text-slate-100">Manuel Tedavi Toplamı:</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalManualPrice, 2, ',', '.') }} TL</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Total Summary -->
            @if($encounter->treatments->isNotEmpty())
            @php
                $grandTotal = $encounter->treatments->sum('unit_price');
            @endphp
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Toplam Tutar</h3>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($grandTotal, 2, ',', '.') }} TL</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">{{ $encounter->treatments->count() }} tedavi işlemi</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Prescriptions -->
            @if($encounter->prescriptions->isNotEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Reçeteler</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($encounter->prescriptions as $prescription)
                        <div class="p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $prescription->dentist->name }}</div>
                                <div class="flex items-center gap-2">
                                    <div class="text-sm text-slate-600 dark:text-slate-400">{{ $prescription->created_at->format('d.m.Y H:i') }}</div>
                                    <a href="{{ route('prescriptions.pdf', $prescription) }}"
                                       target="_blank"
                                       class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        PDF
                                    </a>
                                </div>
                            </div>
                            <div class="text-slate-900 dark:text-slate-100 whitespace-pre-line">{{ $prescription->text }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Files -->
            @if($encounter->files->isNotEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Dosyalar / Röntgenler</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($encounter->files as $file)
                        <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-slate-900 dark:text-slate-100">
                                        <a href="{{ $file->download_url }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            {{ strtoupper($file->type->value) }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-slate-600 dark:text-slate-400">
                                        {{ $file->created_at?->format('d.m.Y H:i') }} • {{ $file->uploader?->name }}
                                    </div>
                                    @if($file->notes)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $file->notes }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>