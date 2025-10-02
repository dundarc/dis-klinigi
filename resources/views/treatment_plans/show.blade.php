<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Tedavi Planı #{{ $treatmentPlan->id }}</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $patient->first_name }} {{ $patient->last_name }} - {{ $treatmentPlan->created_at->format('d.m.Y') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('treatment-plans.edit', $treatmentPlan) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
                <a href="{{ route('treatment-plans.cost-report', $treatmentPlan) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Maliyet Raporu
                </a>
                <a href="{{ route('treatment-plans.pdf', $treatmentPlan) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF İndir
                </a>
                <form action="{{ route('treatment-plans.destroy', $treatmentPlan) }}" method="POST" onsubmit="return confirm('Bu tedavi planını silmek istediğinizden emin misiniz?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Sil
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Plan Overview Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Hasta</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $patient->first_name }} {{ $patient->last_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Diş Hekimi</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $treatmentPlan->dentist?->name ?? 'Hekim atanmamış' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($treatmentPlan->status->value === 'active')
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @elseif($treatmentPlan->status->value === 'completed')
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Plan Durumu</p>
                            @if($treatmentPlan->status->value === 'active')
                                <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-sm font-medium text-green-800 dark:text-green-200">Aktif</span>
                            @elseif($treatmentPlan->status->value === 'completed')
                                <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-sm font-medium text-blue-800 dark:text-blue-200">Tamamlandı</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-1 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $treatmentPlan->status->label() }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Tahmini Maliyet</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ number_format($treatmentPlan->total_estimated_cost, 2, ',', '.') }} TL</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cost Summary --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Maliyet Takibi</h3>
                @php
                    $costSummary = app(\App\Services\TreatmentPlanService::class)->getCostSummary($treatmentPlan);
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($costSummary['total_estimated'], 2, ',', '.') }} TL</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Tahmini Toplam</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($costSummary['total_actual'], 2, ',', '.') }} TL</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Gerçekleşen</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($costSummary['remaining'], 2, ',', '.') }} TL</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Kalan</div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600 dark:text-slate-400">Tamamlanma Oranı</span>
                        <div class="flex items-center gap-2">
                            <div class="w-32 bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $costSummary['completion_percentage'] }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $costSummary['completion_percentage'] }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Appointment-Treatment Linking --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Randevu - Tedavi Bağlantısı</h3>
                <div class="space-y-4">
                    @foreach($treatmentPlan->appointments as $appointment)
                        <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="font-medium text-slate-900 dark:text-slate-100">{{ $appointment->start_at->format('d.m.Y H:i') }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Dr. {{ $appointment->dentist?->name ?? 'â€”' }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">{{ $appointment->status->label() }}</span>
                            </div>
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Bağlı Tedavi Kalemleri:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($appointment->treatmentPlanItems as $item)
                                        <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">
                                            {{ $item->treatment->name }}
                                            @if($item->tooth_number)
                                                ({{ $item->tooth_number }})
                                            @endif
                                        </span>
                                    @endforeach
                                    @if($appointment->treatmentPlanItems->isEmpty())
                                        <span class="text-sm text-slate-500 dark:text-slate-400">Henüz bağlantılı kalem yok</span>
                                    @endif
                                </div>
                            </div>
                            @if($appointment->status->value === 'scheduled' || $appointment->status->value === 'confirmed')
                            <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                                <form method="POST" action="{{ route('appointments.link-items', $appointment) }}" class="flex gap-2">
                                    @csrf
                                    <select name="item_ids[]" multiple class="flex-1 rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        @foreach($treatmentPlan->items->where('status', '!=', \App\Enums\TreatmentPlanItemStatus::DONE)->where('appointment_id', '!=', $appointment->id) as $item)
                                            <option value="{{ $item->id }}">{{ $item->treatment->name }} @if($item->tooth_number)({{ $item->tooth_number }})@endif</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded">Bağla</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    @endforeach
                    @if($treatmentPlan->appointments->isEmpty())
                        <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">Bu tedavi planına ait randevu bulunmuyor.</p>
                    @endif
                </div>
            </div>

            {{-- Treatment Items --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Tedavi Kalemleri</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                            <thead class="bg-slate-50 dark:bg-slate-800">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tedavi</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Diş No</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Durum</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Randevu Bilgileri</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Sorumlu Hekim</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ücret</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse($treatmentPlan->items->sortBy('created_at') as $item)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900 dark:text-slate-100">{{ $item->treatment->name ?? 'Tedavi Silinmiş' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item->tooth_number)
                                                <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-1 text-xs font-medium text-slate-800 dark:text-slate-200">{{ $item->tooth_number }}</span>
                                            @else
                                                <span class="text-slate-500 dark:text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item->status)
                                                @if($item->status->value === 'planned')
                                                    <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-900/30 px-2.5 py-1 text-xs font-medium text-gray-800 dark:text-gray-200">Planlandı</span>
                                                @elseif($item->status->value === 'in_progress')
                                                    <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">Devam Ediyor</span>
                                                @elseif($item->status->value === 'done')
                                                    <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-200">Tamamlandı</span>
                                                @elseif($item->status->value === 'cancelled')
                                                    <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-200">İptal Edildi</span>
                                                @elseif($item->status->value === 'no_show')
                                                    <span class="inline-flex items-center rounded-full bg-orange-100 dark:bg-orange-900/30 px-2.5 py-1 text-xs font-medium text-orange-800 dark:text-orange-200">Gelmedi</span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/30 px-2.5 py-1 text-xs font-medium text-purple-800 dark:text-purple-200">Faturalandırıldı</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item->appointment)
                                                <div class="text-sm">
                                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $item->appointment->start_at->format('d.m.Y H:i') }}</div>
                                                    @if($item->appointment->status->value === 'cancelled')
                                                        <div class="text-red-600 dark:text-red-400 text-xs">İptal edildi ({{ $item->appointment->updated_at->format('d.m.Y') }})</div>
                                                    @elseif($item->appointment->status->value === 'completed')
                                                        <div class="text-green-600 dark:text-green-400 text-xs">Tamamlandı</div>
                                                    @else
                                                        <div class="text-slate-600 dark:text-slate-400 text-xs">{{ $item->appointment->status->label() }}</div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-slate-500 dark:text-slate-400 text-sm">Randevu atanmamış</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                            {{ $item->appointment?->dentist?->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-900 dark:text-slate-100 font-medium">
                                            {{ number_format($item->estimated_price, 2, ',', '.') }} TL
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Tedavi kalemi bulunmuyor</h3>
                                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Bu tedavi planında hemüz kalem eklenmemiş.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($treatmentPlan->items->where('status', \App\Enums\TreatmentPlanItemStatus::DONE)->count() > 0)
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                        Faturalanacak kalemleri seçin (seçili değilse tüm tamamlanan kalemler faturalanır)
                        </div>
                        <form method="POST" action="{{ route('treatment-plans.generateInvoice', $treatmentPlan) }}" class="flex gap-2">
                            @csrf
                            @foreach($treatmentPlan->items->where('status', \App\Enums\TreatmentPlanItemStatus::DONE) as $item)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="item_ids[]" value="{{ $item->id }}" class="rounded border-slate-300 dark:border-slate-600 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <span class="ml-1 text-xs text-slate-600 dark:text-slate-400">{{ $item->treatment->name }}</span>
                                </label>
                            @endforeach
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Fatura Oluştur
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Treatment History Timeline --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Tedavi Geçmişi</h3>
                </div>
                <div class="p-6">
                    @php
                        $timelineEvents = collect();
                        foreach($treatmentPlan->items as $item) {
                            // Item creation
                            $timelineEvents->push([
                                'date' => $item->created_at,
                                'type' => 'created',
                                'title' => 'Tedavi Kalemi Oluşturuldu',
                                'description' => ($item->treatment ? $item->treatment->name : 'Tedavi Silinmiş') . ' tedavisi planlandı',
                                'item' => $item,
                                'icon' => 'plus',
                                'color' => 'blue'
                            ]);

                            // Status history from histories table
                            foreach($item->histories as $history) {
                                $timelineEvents->push([
                                    'date' => $history->created_at,
                                    'type' => 'status_change',
                                    'title' => 'Durum Değişikliği',
                                    'description' => ($item->treatment ? $item->treatment->name : 'Tedavi Silinmiş') . ' - ' . $history->old_status?->label() . '  ' . $history->new_status->label(),
                                    'item' => $item,
                                    'history' => $history,
                                    'icon' => 'arrow-right',
                                    'color' => 'purple'
                                ]);
                            }

                            // Appointment history
                            foreach($item->appointmentHistory as $history) {
                                $actionValue = $history->action->value;
                                $actionLabel = $history->action->label();
                                $timelineEvents->push([
                                    'date' => $history->created_at,
                                    'type' => 'appointment_' . $actionValue,
                                    'title' => $actionLabel . ' Randevu',
                                    'description' => ($item->treatment ? $item->treatment->name : 'Tedavi Silinmiş') . ' için randevu ' . $actionLabel,
                                    'item' => $item,
                                    'appointment' => $history->appointment,
                                    'user' => $history->user,
                                    'icon' => $actionValue == 'completed' ? 'check' : ($actionValue == 'cancelled' ? 'x' : ($actionValue == 'no_show' ? 'user-x' : 'calendar')),
                                    'color' => $actionValue == 'completed' ? 'green' : ($actionValue == 'cancelled' ? 'red' : ($actionValue == 'no_show' ? 'orange' : 'blue'))
                                ]);
                            }
                        }

                        $timelineEvents = $timelineEvents->sortByDesc('date');
                    @endphp

                    @if($timelineEvents->isNotEmpty())
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($timelineEvents as $index => $event)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200 dark:bg-slate-700" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-slate-900
                                                        @if($event['color'] == 'blue') bg-blue-500
                                                        @elseif($event['color'] == 'green') bg-green-500
                                                        @elseif($event['color'] == 'red') bg-red-500
                                                        @else bg-slate-500
                                                        @endif">
                                                        @if($event['icon'] == 'plus')
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0-6h-6"></path>
                                                            </svg>
                                                        @elseif($event['icon'] == 'check')
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        @elseif($event['icon'] == 'x')
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        @elseif($event['icon'] == 'calendar')
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        @elseif($event['icon'] == 'check-circle')
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        @elseif($event['icon'] == 'arrow-right')
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                            </svg>
                                                        @elseif($event['icon'] == 'user-x')
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7zM16 11l4 4m0 0l-4 4m4-4H12"></path>
                                                            </svg>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <div>
                                                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $event['title'] }}</p>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $event['description'] }}</p>
                                                        @if(isset($event['appointment']))
                                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                                                Randevu: {{ $event['appointment']->start_at->format('d.m.Y H:i') }}
                                                                @if($event['appointment']->dentist)
                                                                    - {{ $event['appointment']->dentist?->name ?? 'â€”' }}
                                                                @endif
                                                            </p>
                                                        @endif
                                                        @if(isset($event['user']) && $event['user'])
                                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                                                Tarafından: {{ $event['user']->name }}
                                                            </p>
                                                        @elseif(isset($event['history']) && $event['history']->user)
                                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                                                Tarafından: {{ $event['history']->user->name }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                                        {{ $event['date']->format('d.m.Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-slate-100">Geçmiş bulunmuyor</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Bu tedavi planında henüz bir aktivite gerçekleşmemiş.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
