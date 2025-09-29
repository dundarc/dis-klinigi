<x-app-layout>
    <!-- Sayfa başlığı ve geri dön butonu -->
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Ziyaret İşlemi</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
            </div>
            <a href="{{ route('waiting-room.appointments') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Geri Dön
            </a>
        </div>
    </x-slot>

    <!-- Ana sayfa içeriği - Alpine.js ile yönetiliyor - Optimized for Desktop -->
    <div class="py-6"
          x-data="visitActionManager({
                  treatments: @js($treatments),
                  patientId: {{ $encounter->patient_id }},
                  encounterId: {{ $encounter->id }},
                  fileTypes: @js(array_map(fn($case) => ['value' => $case->value, 'label' => $case->label()], $fileTypes)),
                  unscheduledTreatmentPlanItems: @js($unscheduledTreatmentPlanItems->toArray()),
                  scheduledTreatmentPlanItems: @js($scheduledTreatmentPlanItems->toArray()),
                  appointmentTreatmentPlanItems: @js($appointmentTreatmentPlanItems->toArray())
               })">

        <div class="max-w-6xl mx-auto px-3 sm:px-3 lg:px-4 space-y-3">

            <!-- Hasta ve Randevu Bilgileri Kartları - Compact -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Hasta Bilgileri Kartı -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">
                                {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                TC: {{ $encounter->patient->national_id }} • Tel: {{ $encounter->patient->phone_primary ?: $encounter->patient->phone_secondary }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Randevu Bilgileri Kartı -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">
                                @if($encounter->appointment)
                                    {{ $encounter->appointment->start_at->format('d.m.Y H:i') }}
                                @else
                                    Acil Ziyaret
                                @endif
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Doktor: {{ $encounter->dentist?->name ?? 'Atanmamış' }} •
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($encounter->status->value === 'waiting') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200
                                    @elseif($encounter->status->value === 'in_service') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200
                                    @elseif($encounter->status->value === 'done') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                    @endif">
                                    {{ __("patient.encounter_status.{$encounter->status->value}") }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Önemli Uyarı - Compact -->
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="text-sm text-amber-700 dark:text-amber-300">
                        <strong>Önemli:</strong> Muayene tamamlandığında ziyaret durumunu "Tamamlandı" olarak güncelleyin.
                        Ziyaret tamamlanmadıkça hasta bekleme listesinde görünür.
                    </p>
                </div>
            </div>

            <!-- Bu Randevuya Ait Tedavi Planı Öğeleri - Compact -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">Bu Randevuya Ait Tedavi Planı Öğeleri</h3>
                        <span class="text-xs px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200 rounded-full">
                            {{ $appointmentTreatmentPlanItems->count() }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    @if($appointmentTreatmentPlanItems->isNotEmpty())
                        <!-- Tedavi öğeleri tablosu - Compact -->
                        <div class="overflow-x-auto max-h-64 overflow-y-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tedavi</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Diş</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ücret</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Durum</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($appointmentTreatmentPlanItems as $item)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                                    {{ $item->treatment->name ?? 'Tedavi Bulunamadı' }}
                                                </div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                                    Plan #{{ $item->treatmentPlan->id ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                @if($item->tooth_number)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                                        #{{ $item->tooth_number }}
                                                    </span>
                                                @else
                                                    <span class="text-slate-400 dark:text-slate-600">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                                                @if($item->estimated_price)
                                                    {{ number_format($item->estimated_price, 0, ',', '.') }}₺
                                                @else
                                                    <span class="text-slate-400 dark:text-slate-600">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                @php
                                                    $statusColor = match($item->status->value) {
                                                        'planned' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
                                                        'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                                                        'done' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                                                        'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                                    {{ $item->status->label() }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium">
                                                @if($item->status->value !== 'done' && $item->status->value !== 'cancelled')
                                                    <div class="flex flex-col space-y-1">
                                                        <!-- Başlat butonu - Mavi -->
                                                        <button type="button"
                                                                @click="startTreatmentPlanItem({{ $item->id }}, '{{ addslashes($item->treatment->name ?? '') }}')"
                                                                class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l.707.707A1 1 0 0012.414 11H15m-3 7.5A9.5 9.5 0 1121.5 12 9.5 9.5 0 0112 2.5z"></path>
                                                            </svg>
                                                            Başlat
                                                        </button>
                                                        <!-- Tamamla butonu - Yeşil -->
                                                        <button type="button"
                                                                @click="completeTreatmentPlanItem({{ $item->id }}, '{{ addslashes($item->treatment->name ?? '') }}', '{{ $item->tooth_number ?? '' }}', {{ $item->estimated_price ?? 0 }})"
                                                                class="inline-flex items-center px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Tamamla
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-slate-400 dark:text-slate-600 text-xs">
                                                        @if($item->status->value === 'done')
                                                            ✅ Tamamlandı
                                                        @else
                                                            ❌ İptal Edildi
                                                        @endif
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Boş durum mesajı -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Kayıt bulunamadı</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Bu randevuya atanmış tedavi planı öğesi bulunmuyor.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Diğer Tedavi Planı Öğeleri - Compact -->
            @if(count($scheduledTreatmentPlanItems) > 0 || count($unscheduledTreatmentPlanItems) > 0)
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">Diğer Tedavi Planı Öğeleri</h3>
                            <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-full">
                                {{ count($scheduledTreatmentPlanItems) + count($unscheduledTreatmentPlanItems) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="space-y-6">
                            <!-- Randevulu Öğeler -->
                            @if(count($scheduledTreatmentPlanItems) > 0)
                                <div>
                                    <h4 class="text-md font-medium text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Randevulu Öğeler
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($scheduledTreatmentPlanItems as $planItem)
                                            <div class="p-4 rounded-lg border border-blue-200 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <div class="font-medium text-slate-900 dark:text-slate-100">{{ $planItem->treatment->name ?? 'Tedavi Bulunamadı' }}</div>
                                                                <div class="text-sm text-slate-600 dark:text-slate-400">
                                                                    Plan: {{ $planItem->treatmentPlan->title ?? 'Plan Bulunamadı' }}
                                                                    @if($planItem->tooth_number)
                                                                        <span class="ml-2 px-2 py-1 bg-slate-200 dark:bg-slate-700 rounded text-xs">#{{ $planItem->tooth_number }}</span>
                                                                    @endif
                                                                    <span class="ml-2 px-2 py-1 bg-blue-200 dark:bg-blue-700 rounded text-xs text-blue-800 dark:text-blue-200">
                                                                        📅 {{ $planItem->appointment->start_at->format('d.m.Y H:i') ?? 'Tarih Yok' }}
                                                                    </span>
                                                                    @if($planItem->estimated_price)
                                                                        <span class="ml-2">{{ number_format($planItem->estimated_price, 2, ',', '.') }} ₺</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <!-- Bu randevuya ekle butonu - Gri -->
                                                        <button type="button"
                                                                @click="addScheduledTreatmentToVisit({{ $planItem->id }}, '{{ addslashes($planItem->treatment->name ?? '') }}', '{{ $planItem->tooth_number ?? '' }}', {{ $planItem->estimated_price ?? 0 }})"
                                                                :disabled="appliedTreatments.some(t => t.treatment_plan_item_id === {{ $planItem->id }})"
                                                                :class="appliedTreatments.some(t => t.treatment_plan_item_id === {{ $planItem->id }}) ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-600 hover:bg-gray-700'"
                                                                class="inline-flex items-center px-3 py-1 text-white text-sm font-medium rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                            </svg>
                                                            <span x-text="appliedTreatments.some(t => t.treatment_plan_item_id === {{ $planItem->id }}) ? 'Eklendi' : 'Bu Randevuya Ekle'"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Randevusuz Öğeler -->
                            @if(count($unscheduledTreatmentPlanItems) > 0)
                                <div>
                                    <h4 class="text-md font-medium text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Randevusuz Öğeler
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($unscheduledTreatmentPlanItems as $planItem)
                                            <div class="p-4 rounded-lg border border-amber-200 dark:border-amber-700 bg-amber-50 dark:bg-amber-900/20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <div class="font-medium text-slate-900 dark:text-slate-100">{{ $planItem->treatment->name ?? 'Tedavi Bulunamadı' }}</div>
                                                                <div class="text-sm text-slate-600 dark:text-slate-400">
                                                                    Plan: Tedavi Planı #{{ $planItem->treatmentPlan->id ?? 'N/A' }}
                                                                    @if($planItem->tooth_number)
                                                                        <span class="ml-2 px-2 py-1 bg-slate-200 dark:bg-slate-700 rounded text-xs">#{{ $planItem->tooth_number }}</span>
                                                                    @endif
                                                                    @if($planItem->estimated_price)
                                                                        <span class="ml-2">{{ number_format($planItem->estimated_price, 2, ',', '.') }} ₺</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <!-- Bu randevuya ekle butonu - Gri -->
                                                        <button type="button"
                                                                @click="addUnscheduledTreatmentToVisit({{ $planItem->id }}, '{{ addslashes($planItem->treatment->name ?? '') }}', '{{ $planItem->tooth_number ?? '' }}', {{ $planItem->estimated_price ?? 0 }})"
                                                                :disabled="appliedTreatments.some(t => t.treatment_plan_item_id === {{ $planItem->id }})"
                                                                :class="appliedTreatments.some(t => t.treatment_plan_item_id === {{ $planItem->id }}) ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-600 hover:bg-gray-700'"
                                                                class="inline-flex items-center px-3 py-1 text-white text-sm font-medium rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                            </svg>
                                                            <span x-text="appliedTreatments.some(t => t.treatment_plan_item_id === {{ $planItem->id }}) ? 'Eklendi' : 'Bu Randevuya Ekle'"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Visit Form - Compact -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">Ziyaret Bilgileri</h3>
                </div>

                <form method="POST" action="{{ route('waiting-room.action.update', $encounter) }}" class="p-4 space-y-4" id="visit-action-form" @submit="prepareFormData">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="applied_treatments" id="applied-treatments-input">

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Visit Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ziyaret Durumu</label>
                            <select id="status" name="status" @change="hasChanges = true" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" @selected($encounter->status === $status)>
                                        {{ __("patient.encounter_status." . $status->value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Visit Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ziyaret Notu</label>
                            <textarea id="notes" name="notes" rows="2" @input="hasChanges = true" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Muayene notlarını girin...">{{ old('notes', $encounter->notes) }}</textarea>
                        </div>
                    </div>

                    <!-- Applied Treatments Section -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Uygulanan Tedaviler</label>
                            <button type="button" @click="addManualTreatment" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Manuel Tedavi Ekle
                            </button>
                        </div>

                        <!-- Existing Treatments -->
                        @if($encounter->treatments->isNotEmpty())
                            <div class="space-y-3 mb-4">
                                @foreach($encounter->treatments as $treatment)
                                    <div class="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $treatment->display_treatment_name }}</div>
                                                    <div class="text-sm text-slate-600 dark:text-slate-400">
                                                        @if($treatment->treatment_plan_item_id)
                                                            <span class="inline-flex items-center px-2 py-1 bg-green-200 dark:bg-green-800 rounded text-xs font-medium text-green-800 dark:text-green-200 mr-2">
                                                                📋 Tedavi Planından
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-1 bg-blue-200 dark:bg-blue-800 rounded text-xs font-medium text-blue-800 dark:text-blue-200 mr-2">
                                                                ➕ Manuel Ekleme
                                                            </span>
                                                        @endif
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
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Dynamic Treatment Forms -->
                        <div class="space-y-3">
                            <template x-for="(treatment, index) in appliedTreatments" :key="index">
                                <div class="flex flex-col gap-3 p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                            <span x-show="treatment.treatment_plan_item_id">Tedavi Planından: </span>
                                            <span x-show="!treatment.treatment_plan_item_id">Manuel Tedavi</span>
                                            <span x-text="treatment.treatment_name || treatment.treatment?.name || 'Tedavi adı bulunamadı'"></span>
                                        </h4>
                                        <button type="button" @click="removeTreatment(index)" class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Manual treatment selection for non-plan treatments -->
                                    <div x-show="!treatment.treatment_plan_item_id" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Tedavi Seçin</label>
                                            <select x-bind:name="`treatments[${index}][treatment_id]`" 
                                                    x-model="treatment.treatment_id"
                                                    @change="updatePrice(index, $event.target)"
                                                    class="w-full text-sm rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                                                <option value="">Tedavi seçin...</option>
                                                <template x-for="t in treatments" :key="t.id">
                                                    <option x-bind:value="t.id" x-text="t.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Diş Numarası</label>
                                            <input type="text" 
                                                   x-bind:name="`treatments[${index}][tooth_number]`" 
                                                   x-model="treatment.tooth_number"
                                                   placeholder="Diş no (opsiyonel)"
                                                   class="w-full text-sm rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Fiyat (TL)</label>
                                            <input type="number" 
                                                   x-bind:name="`treatments[${index}][unit_price]`" 
                                                   x-model="treatment.unit_price"
                                                   step="0.01" 
                                                   min="0"
                                                   placeholder="0.00"
                                                   class="w-full text-sm rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                                        </div>
                                    </div>
                                    
                                    <!-- Treatment plan item details display -->
                                    <div x-show="treatment.treatment_plan_item_id" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-slate-600 dark:text-slate-400">
                                        <div x-show="treatment.tooth_number">
                                            <span class="font-medium">Diş: </span><span x-text="'#' + treatment.tooth_number"></span>
                                        </div>
                                        <div x-show="treatment.unit_price">
                                            <span class="font-medium">Fiyat: </span><span x-text="Number(treatment.unit_price).toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' TL'"></span>
                                        </div>
                                        <div x-show="treatment.is_scheduled !== undefined">
                                            <span class="px-2 py-1 rounded text-xs" x-bind:class="treatment.is_scheduled ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'" x-text="treatment.is_scheduled ? 'Randevulu' : 'Randevusuz'"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Action Buttons - Compact -->
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('waiting-room.appointments') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors text-sm">
                            İptal
                        </a>
                        <button type="submit" name="action" value="save" :disabled="!hasChanges" class="save-visit-btn px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors text-sm">
                            Kaydet
                        </button>
                        <button type="submit" name="action" value="complete" class="complete-treatment-btn px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-sm">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Tamamla
                        </button>
                    </div>
                </form>
            </div>

            <!-- Prescription Section - Compact -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">Reçete</h3>
                    </div>
                </div>

                <div class="p-4">
                    <div class="space-y-4">
                        <!-- Prescription Text Area -->
                        <div>
                            <label for="prescription_text" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Reçete Metni
                            </label>
                            <textarea
                                id="prescription_text"
                                name="prescription_text"
                                x-model="prescriptionText"
                                @input="hasChanges = true"
                                rows="4"
                                class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 resize-vertical"
                                placeholder="Reçete içeriğini buraya yazın...">{{ old('prescription_text', $encounter->prescriptions->first()?->content ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                Reçete metnini yazarken otomatik kaydetme aktif olacaktır.
                            </p>
                        </div>

                        <!-- Existing Prescriptions -->
                        @if($encounter->prescriptions->isNotEmpty())
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                                <h4 class="text-sm font-medium text-slate-900 dark:text-slate-100 mb-3">Önceki Reçeteler</h4>
                                <div class="space-y-3">
                                    @foreach($encounter->prescriptions as $prescription)
                                        <div class="p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                                    Reçete #{{ $prescription->id }}
                                                </div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                                    {{ $prescription->created_at->format('d.m.Y H:i') }}
                                                </div>
                                            </div>
                                            <div class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line">
                                                {{ $prescription->content }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Files Section - Compact -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">Dosyalar</h3>
                        <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-full">
                            PDF, JPG, PNG, DICOM
                        </span>
                    </div>
                </div>

                <div class="p-4">
                    <!-- File Upload Form -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="file_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Dosya Türü</label>
                                <select id="file_type" x-model="fileForm.type" @change="hasChanges = true" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Dosya türü seçin...</option>
                                    <template x-for="fileType in fileTypes" :key="fileType.value">
                                        <option x-bind:value="fileType.value" x-text="fileType.label"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label for="file_notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notlar</label>
                                <input id="file_notes" type="text" x-model="fileForm.notes" @input="hasChanges = true" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Opsiyonel açıklama">
                            </div>
                        </div>
                        <div>
                            <label for="visit_file" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Dosya Seç</label>
                            <input id="visit_file" type="file" @change="handleFileUpload($event)" class="mt-1 block w-full text-sm text-slate-700 dark:text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <div x-show="fileUploading" class="mt-2 flex items-center text-sm text-blue-600 dark:text-blue-400">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Dosya yükleniyor...
                            </div>
                            <div x-show="fileUploaded" class="mt-2 flex items-center text-sm text-green-600 dark:text-green-400">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Dosya başarıyla yüklendi!
                            </div>
                        </div>
                    </div>

                    <!-- Existing Files -->
                    @if($encounter->patient->files->isNotEmpty())
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-6 mt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-medium text-slate-900 dark:text-slate-100">Hasta Dosyaları</h4>
                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $encounter->patient->files->count() }} dosya
                                </div>
                            </div>
                            <div class="space-y-3">
                                @foreach($encounter->patient->files as $file)
                                    <div class="p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50"
                                         x-data="{ editing: false, editForm: { type: '{{ $file->type->value }}', notes: '{{ addslashes($file->notes ?? '') }}' } }">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3 flex-1">
                                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <!-- View Mode -->
                                                    <div x-show="!editing">
                                                        <div class="font-medium text-slate-900 dark:text-slate-100">
                                                            <a href="{{ $file->download_url }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                                {{ $file->display_name }}
                                                            </a>
                                                        </div>
                                                        <div class="text-sm text-slate-600 dark:text-slate-400">
                                                            {{ $file->created_at?->format('d.m.Y H:i') }} • {{ $file->uploader?->name }} • {{ $file->type->label() }}
                                                        </div>
                                                        @if($file->notes)
                                                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $file->notes }}</div>
                                                        @endif
                                                    </div>

                                                    <!-- Edit Mode -->
                                                    <div x-show="editing" x-transition>
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                            <div>
                                                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Dosya Türü</label>
                                                                <select x-model="editForm.type" class="w-full text-sm rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100">
                                                                    <template x-for="fileType in fileTypes" :key="fileType.value">
                                                                        <option x-bind:value="fileType.value" x-text="fileType.label"></option>
                                                                    </template>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Notlar</label>
                                                                <input type="text" x-model="editForm.notes" class="w-full text-sm rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100" placeholder="Opsiyonel açıklama">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex items-center gap-2">
                                                <!-- Edit/Save Buttons -->
                                                <button type="button"
                                                        x-show="!editing"
                                                        @click="editing = true"
                                                        class="inline-flex items-center px-2 py-1 text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 text-xs font-medium rounded transition-colors">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Düzenle
                                                </button>

                                                <button type="button"
                                                        x-show="editing"
                                                        @click="updateFile({{ $file->id }}, editForm)"
                                                        class="inline-flex items-center px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Kaydet
                                                </button>

                                                <button type="button"
                                                        x-show="editing"
                                                        @click="editing = false; editForm = { type: '{{ $file->type->value }}', notes: '{{ addslashes($file->notes ?? '') }}' }"
                                                        class="inline-flex items-center px-2 py-1 bg-slate-600 hover:bg-slate-700 text-white text-xs font-medium rounded transition-colors">
                                                    İptal
                                                </button>

                                                <!-- Delete Button -->
                                                <button type="button"
                                                        x-show="!editing"
                                                        @click="deleteFile({{ $file->id }}, '{{ addslashes($file->display_name) }}')"
                                                        class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Sil
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Ziyaret işlemi yönetimi için Alpine.js bileşeni
            function visitActionManager(config) {
                return {
                    // Yapılandırma verileri
                    treatments: config.treatments || [],
                    patientId: config.patientId,
                    encounterId: config.encounterId,
                    unscheduledTreatmentPlanItems: config.unscheduledTreatmentPlanItems || [],
                    scheduledTreatmentPlanItems: config.scheduledTreatmentPlanItems || [],
                    appointmentTreatmentPlanItems: config.appointmentTreatmentPlanItems || [],

                    // Durum değişkenleri
                    appliedTreatments: [], // Uygulanacak tedaviler listesi
                    prescriptionText: '', // Reçete metni
                    fileForm: {
                        type: '',
                        notes: '',
                        file: null,
                    },
                    fileUploading: false,
                    fileUploaded: false,
                    hasChanges: false, // Değişiklik takibi

                    // Form submit öncesi veri hazırlama
                    prepareFormData() {
                        // Applied treatments verisini gizli input'a koy
                        const input = document.getElementById('applied-treatments-input');
                        if (input) {
                            input.value = JSON.stringify(this.appliedTreatments);
                        }

                        // Tedaviyi tamamla butonu için özel işlem
                        const submitter = event.submitter;
                        if (submitter && submitter.value === 'complete') {
                            if (!confirm('Tedaviyi tamamlamak istediğinizden emin misiniz?')) {
                                event.preventDefault();
                                return false;
                            }
                            // Durumu 'done' olarak ayarla
                            const statusSelect = document.getElementById('status');
                            if (statusSelect) {
                                statusSelect.value = 'done';
                            }
                        }

                        return true;
                    },

                    // Bileşen başlatma
                    init() {
                        // Değişiklikleri takip et
                        this.$watch('appliedTreatments', () => {
                            this.hasChanges = true;
                        }, { deep: true });

                        this.$watch('prescriptionText', () => {
                            this.hasChanges = true;
                        });

                        this.$watch('fileForm', () => {
                            this.hasChanges = true;
                        }, { deep: true });

                        // Otomatik kaydetme - her 10 saniyede bir
                        setInterval(() => {
                            this.autoSave();
                        }, 10000);

                        console.log('Ziyaret işlemi yöneticisi başlatıldı');
                    },

                    // Tedavi planı öğesi durumunu başlat
                    startTreatmentPlanItem(planItemId, treatmentName) {
                        if (!confirm(`${treatmentName} işlemini başlatmak istediğinizden emin misiniz?`)) return;

                        fetch(`/api/v1/treatment-plan-items/${planItemId}/start`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Sayfayı yenile
                                window.location.reload();
                            } else {
                                alert('Bir hata oluştu: ' + (data.message || 'Bilinmeyen hata'));
                            }
                        })
                        .catch(error => {
                            console.error('Hata:', error);
                            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                        });
                    },

                    // Tedavi planı öğesini tamamla
                    completeTreatmentPlanItem(planItemId, treatmentName, toothNumber, estimatedPrice) {
                        if (!confirm(`${treatmentName} işlemini tamamlandı olarak işaretlemek istediğinizden emin misiniz?`)) return;

                        fetch(`/api/v1/treatment-plan-items/${planItemId}/complete`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Sayfayı yenile
                                window.location.reload();
                            } else {
                                alert('Bir hata oluştu: ' + (data.message || 'Bilinmeyen hata'));
                            }
                        })
                        .catch(error => {
                            console.error('Hata:', error);
                            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                        });
                    },

                    // Tedavi planı öğesini iptal et
                    cancelTreatmentPlanItem(planItemId, treatmentName) {
                        if (!confirm(`${treatmentName} işlemini iptal etmek istediğinizden emin misiniz?`)) return;

                        fetch(`/api/v1/treatment-plan-items/${planItemId}/cancel`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Sayfayı yenile
                                window.location.reload();
                            } else {
                                alert('Bir hata oluştu: ' + (data.message || 'Bilinmeyen hata'));
                            }
                        })
                        .catch(error => {
                            console.error('Hata:', error);
                            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                        });
                    },

                    // Randevulu tedavi öğesini ziyarete ekle
                    addScheduledTreatmentToVisit(planItemId, treatmentName, toothNumber, estimatedPrice) {
                        if (this.appliedTreatments.some(treatment => treatment.treatment_plan_item_id === planItemId)) {
                            alert('Bu tedavi öğesi zaten uygulanacaklar listesine eklenmiş!');
                            return;
                        }

                        if (!confirm(`${treatmentName} randevulu işlemini bu ziyarete eklemek istediğinizden emin misiniz?`)) return;

                        let planItem = this.scheduledTreatmentPlanItems.find(item => item.id === planItemId);
                        if (!planItem) {
                            alert('Tedavi planı öğesi bulunamadı.');
                            return;
                        }

                        this.appliedTreatments.push({
                            treatment_id: planItem.treatment_id,
                            treatment_name: treatmentName,
                            tooth_number: toothNumber,
                            unit_price: estimatedPrice,
                            treatment_plan_item_id: planItemId,
                            is_scheduled: true
                        });

                        alert(`${treatmentName} tedavisi uygulanacaklar listesine eklendi.`);
                    },

                    // Randevusuz tedavi öğesini ziyarete ekle
                    addUnscheduledTreatmentToVisit(planItemId, treatmentName, toothNumber, estimatedPrice) {
                        if (this.appliedTreatments.some(treatment => treatment.treatment_plan_item_id === planItemId)) {
                            alert('Bu tedavi öğesi zaten uygulanacaklar listesine eklenmiş!');
                            return;
                        }

                        let planItem = this.unscheduledTreatmentPlanItems.find(item => item.id === planItemId);
                        if (!planItem) {
                            alert('Tedavi planı öğesi bulunamadı.');
                            return;
                        }

                        this.appliedTreatments.push({
                            treatment_id: planItem.treatment_id,
                            treatment_name: treatmentName,
                            tooth_number: toothNumber,
                            unit_price: estimatedPrice,
                            treatment_plan_item_id: planItemId,
                            is_scheduled: false
                        });

                        alert(`${treatmentName} tedavisi uygulanacaklar listesine eklendi.`);
                    },

                    // Manuel tedavi ekle
                    addManualTreatment() {
                        this.appliedTreatments.push({
                            treatment_id: '',
                            treatment_name: '',
                            tooth_number: '',
                            unit_price: 0,
                            treatment_plan_item_id: null,
                            is_scheduled: false
                        });
                    },

                    // Tedavi fiyatını güncelle
                    updatePrice(index, selectElement) {
                        const selectedId = selectElement.value;
                        const treatment = this.treatments.find(t => t.id == selectedId);
                        if (treatment) {
                            this.appliedTreatments[index].unit_price = parseFloat(treatment.default_price) || 0;
                            this.appliedTreatments[index].treatment_name = treatment.name;
                        } else {
                            this.appliedTreatments[index].unit_price = 0;
                            this.appliedTreatments[index].treatment_name = '';
                        }
                    },

                    // Tedavi öğesini kaldır
                    removeTreatment(index) {
                        this.appliedTreatments.splice(index, 1);
                    },

                    // Dosya yükleme işlemini yönet
                    handleFileUpload(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        this.fileForm.file = file;
                        this.fileUploading = true;
                        this.fileUploaded = false;

                        const formData = new FormData();
                        formData.append('file', file);
                        formData.append('type', this.fileForm.type || 'other');
                        if (this.fileForm.notes) {
                            formData.append('notes', this.fileForm.notes);
                        }
                        formData.append('patient_id', this.patientId);

                        fetch(`/api/v1/patients/${this.patientId}/files`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: formData,
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => Promise.reject(data));
                            }
                            return response.json();
                        })
                        .then(() => {
                            this.fileUploading = false;
                            this.fileUploaded = true;
                            event.target.value = '';
                            this.fileForm.file = null;
                            this.hasChanges = false; // Dosya yüklendi, değişiklik kaydedildi
                            setTimeout(() => {
                                this.fileUploaded = false;
                            }, 3000);
                            // Sayfayı yenilemek yerine, dosyalar listesini güncelle
                            window.location.reload();
                        })
                        .catch(error => {
                            const message = error?.message || 'Dosya yüklenirken bir hata oluştu.';
                            alert(message);
                            this.fileUploading = false;
                            this.fileUploaded = false;
                        });
                    },

                    // Dosya güncelleme
                    updateFile(fileId, formData) {
                        fetch(`/api/v1/files/${fileId}`, {
                            method: 'PATCH',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Dosya bilgileri güncellendi.');
                                window.location.reload();
                            } else {
                                alert('Güncelleme başarısız: ' + (data.message || 'Bilinmeyen hata'));
                            }
                        })
                        .catch(error => {
                            console.error('Dosya güncelleme hatası:', error);
                            alert('Dosya güncellenirken bir hata oluştu.');
                        });
                    },

                    // Dosya silme (soft delete)
                    deleteFile(fileId, fileName) {
                        if (!confirm(`"${fileName}" dosyasını silmek istediğinizden emin misiniz? Bu işlem geri alınabilir.`)) return;

                        fetch(`/api/v1/files/${fileId}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Dosya başarıyla silindi.');
                                window.location.reload();
                            } else {
                                alert('Silme başarısız: ' + (data.message || 'Bilinmeyen hata'));
                            }
                        })
                        .catch(error => {
                            console.error('Dosya silme hatası:', error);
                            alert('Dosya silinirken bir hata oluştu.');
                        });
                    },

                    // Otomatik kaydetme
                    autoSave() {
                        if (!this.hasChanges) return;

                        // Tedavileri kaydet
                        if (this.appliedTreatments.length > 0) {
                            const formData = new FormData();
                            this.appliedTreatments.forEach((treatment, index) => {
                                Object.keys(treatment).forEach(key => {
                                    if (treatment[key] !== null && treatment[key] !== undefined) {
                                        formData.append(`treatments[${index}][${key}]`, treatment[key]);
                                    }
                                });
                            });

                            fetch(`/api/v1/encounters/${this.encounterId}/auto-save-treatments`, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log('Tedaviler otomatik kaydedildi');
                                }
                            })
                            .catch(error => {
                                console.error('Tedavi otomatik kaydetme hatası:', error);
                            });
                        }

                        // Reçeteyi kaydet
                        if (this.prescriptionText.trim()) {
                            fetch(`/api/v1/encounters/${this.encounterId}/auto-save-prescription`, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({
                                    content: this.prescriptionText
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log('Reçete otomatik kaydedildi');
                                }
                            })
                            .catch(error => {
                                console.error('Reçete otomatik kaydetme hatası:', error);
                            });
                        }

                        this.hasChanges = false;
                    },


                }
            }
        </script>
    @endpush
</x-app-layout>
