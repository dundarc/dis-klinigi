<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-900 to-indigo-600 dark:from-blue-100 dark:to-indigo-400 bg-clip-text text-transparent">
                        Tedavi Planı Düzenleme
                    </h2>
                    <p class="text-blue-600 dark:text-blue-400 mt-1 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span>Plan #{{ $treatmentPlan->id }}</span>
                    </p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('treatment-plans.show', $treatmentPlan) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Planı Görüntüle
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('treatment-plans.update', $treatmentPlan) }}" class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-600/50 overflow-hidden backdrop-blur-sm">
                @csrf
                @method('PATCH')

                <!-- Form Header -->
                <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-600/50 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-blue-900/20 dark:to-indigo-900/20">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-blue-900 dark:text-blue-100">Tedavi Planı Düzenleme</h3>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Tedavi planı bilgilerini güncelleyin</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="px-8 py-6">
                    <!-- Plan Settings Form -->
                    <div class="bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200/50 dark:border-blue-700/50 rounded-xl p-6 mb-8">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-blue-900 dark:text-blue-100">Tedavi Planı Ayarları</h3>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Temel plan bilgilerini güncelleyin</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('treatment-plans.update', $treatmentPlan) }}" class="space-y-6">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="dentist_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Sorumlu Diş Hekimi <span class="text-red-500">*</span>
                                    </label>
                                    <select id="dentist_id" name="dentist_id" required
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <option value="">Hekim Seçiniz</option>
                                        @foreach($dentists as $dentist)
                                            <option value="{{ $dentist['id'] }}" {{ $treatmentPlan->dentist_id == $dentist['id'] ? 'selected' : '' }}>
                                                {{ $dentist['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Tedavi Planı Durumu <span class="text-red-500">*</span>
                                    </label>
                                    <select id="status" name="status" required
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <option value="draft" {{ $treatmentPlan->status == 'draft' ? 'selected' : '' }}>📝 Taslak</option>
                                        <option value="active" {{ $treatmentPlan->status == 'active' ? 'selected' : '' }}>📅 Aktif</option>
                                    </select>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Sadece taslak ve aktif durumları değiştirebilirsiniz. İptal ve tamamlanma işlemleri ayrı olarak yapılır.
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Plan Notları
                                </label>
                                <textarea id="notes" name="notes" rows="4"
                                    class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                                    placeholder="Tedavi planı ile ilgili genel notlar...">{{ $treatmentPlan->notes }}</textarea>
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                    Ayarları Kaydet
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Treatment Plan Items Section -->
                    <div class="border-t border-gray-200/50 dark:border-gray-600/50 pt-8 mt-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tedavi Kalemleri</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Tedavi planındaki öğeler ve durumları</p>
                                </div>
                            </div>
                        </div>

                        <!-- Items List -->
                        <div class="space-y-4">
                            @php
                                $hasCancellableItems = false;
                            @endphp

                            @forelse($treatmentPlan->items as $item)
                                <div class="bg-gradient-to-r from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-700/50 rounded-xl border border-gray-200/50 dark:border-gray-600/50 p-6 shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">{{ $loop->iteration }}</span>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $item->treatment->name ?? 'Tedavi Silinmiş' }}</span>
                                                @if($item->tooth_number)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">(Diş {{ $item->tooth_number }})</span>
                                                @endif
                                            </div>
                                            @if($item->status->value === 'done')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    ✅ Tamamlandı
                                                </span>
                                            @elseif($item->status->value === 'cancelled')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    ❌ İptal Edildi
                                                </span>
                                            @elseif($item->status->value === 'in_progress')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    ⚡ Devam Ediyor
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                    📅 Planlandı
                                                </span>
                                            @endif
                                        </div>
                                        @if($item->status->value !== 'done' && $item->status->value !== 'cancelled')
                                            @php
                                                $hasCancellableItems = true;
                                            @endphp
                                            <button type="button"
                                                onclick="confirmCancelItem({{ $item->id }}, '{{ addslashes($item->treatment->name ?? 'Tedavi') }}', {{ $item->tooth_number ?? 'null' }}, {{ $item->appointment ? 'true' : 'false' }})"
                                                class="inline-flex items-center px-4 py-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Öğeyi İptal Et
                                            </button>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Randevu:</span>
                                            @if($item->appointment)
                                                <span class="text-gray-900 dark:text-white">{{ $item->appointment->start_at->format('d.m.Y H:i') }}</span>
                                                @if($item->appointment->rescheduled_from)
                                                    <span class="text-xs text-orange-600 dark:text-orange-400 block">
                                                        (Önceden: {{ $item->appointment->rescheduled_from->format('d.m.Y H:i') }})
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">Randevu Yok</span>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Gerçekleşen Tarih:</span>
                                            @if($item->actual_date)
                                                <span class="text-gray-900 dark:text-white">{{ $item->actual_date->format('d.m.Y H:i') }}</span>
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">-</span>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Ücret:</span>
                                            <span class="text-gray-900 dark:text-white">{{ number_format($item->estimated_price, 2, ',', '.') }} TL</span>
                                        </div>
                                    </div>

                                    @if($item->status->value === 'done')
                                        <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-green-800 dark:text-green-200">Bu öğe tamamlandığı için düzenlenemez veya iptal edilemez.</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <div class="w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tedavi öğesi bulunmuyor</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Bu tedavi planında henüz öğe bulunmuyor.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Cancellation Section -->
                        @if(!$hasCancellableItems && $treatmentPlan->items->count() > 0)
                            <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">İptal edilecek bir işlem bulunamadı</h4>
                                        <p class="text-sm text-yellow-700 dark:text-yellow-300">Tüm tedavi öğeleri tamamlanmış veya zaten iptal edilmiş durumda.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($hasCancellableItems)
                            <div class="mt-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-red-800 dark:text-red-200">Tamamlanmamış tedavi plan öğeleri buradan iptal edilir.</h4>
                                        <p class="text-sm text-red-700 dark:text-red-300">İptal butonu şu şekilde işlem yapar: Bu işlem geri alınamaz. Emin misiniz? (Randevu varsa, randevuyu iptal et, plan öğesini iptal et)</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Entire Plan Cancellation Section -->
                    @php
                        $completedCount = $treatmentPlan->items()->where('status', 'done')->count();
                        $incompleteCount = $treatmentPlan->items()->whereNotIn('status', ['done', 'cancelled'])->count();
                    @endphp

                    @if($treatmentPlan->status->value !== 'cancelled' && $treatmentPlan->status->value !== 'cancelled_partial' && $incompleteCount > 0)
                        <div class="border-t border-gray-200/50 dark:border-gray-600/50 pt-8 mt-8">
                            <div class="bg-gradient-to-r from-red-50 via-red-100 to-red-50 dark:from-red-900/20 dark:via-red-900/30 dark:to-red-900/20 border border-red-200 dark:border-red-700 rounded-xl p-6">
                                <div class="flex items-center space-x-4 mb-6">
                                    <div class="p-3 bg-gradient-to-r from-red-500 to-red-600 rounded-xl">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-red-900 dark:text-red-100">Tedavi Planını İptal Et</h3>
                                        <p class="text-sm text-red-700 dark:text-red-300 mt-1">Bu işlem geri alınamaz. Tüm tedavi planını iptal eder.</p>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-red-200 dark:border-red-700 mb-6">
                                    <div class="flex items-center space-x-4 mb-4">
                                        <div class="p-3 bg-gradient-to-r from-red-500 to-red-600 rounded-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-red-900 dark:text-red-100">Tüm Tedavi Planını İptal Et</h4>
                                            <p class="text-sm text-red-700 dark:text-red-300">Tamamlanmamış işlemlerin randevuları iptal edilir. Tamamlanmış işlemler korunur.</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-700 dark:text-gray-300">Tamamlanmış İşlemler:</span>
                                                <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $completedCount }}</span>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-700 dark:text-gray-300">İptal Edilecek İşlemler:</span>
                                                <span class="text-lg font-bold text-red-600 dark:text-red-400">{{ $incompleteCount }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-6">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                                    @if($completedCount > 0)
                                                        Tamamlanmış işlemler korunacak. Tedavi Plan Durumu: <strong>Kısmen İptal</strong>
                                                    @else
                                                        Tüm işlemler iptal edilecek. Tedavi Plan Durumu: <strong>İptal</strong>
                                                    @endif
                                                </p>
                                                <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">
                                                    Randevulu işlemlerin randevuları otomatik olarak iptal edilecektir.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button"
                                        onclick="cancelEntirePlan()"
                                        class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="cancel-entire-plan-btn">
                                        Tüm Tedavi Planını İptal Et
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif($treatmentPlan->status->value !== 'cancelled' && $treatmentPlan->status->value !== 'cancelled_partial' && $incompleteCount === 0 && $treatmentPlan->items->count() > 0)
                        <div class="border-t border-gray-200/50 dark:border-gray-600/50 pt-8 mt-8">
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl p-6">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="p-3 bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tedavi Planı Tamamlandı</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Tüm işlemler tamamlandığı için tedavi planı iptal edilemez.</p>
                                    </div>
                                </div>

                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                                Tüm tedavi öğeleri tamamlanmış durumda. Tedavi planı başarıyla gerçekleştirildi.
                                            </p>
                                            <p class="text-xs text-green-700 dark:text-green-300 mt-1">
                                                İptal işlemi için tamamlanmamış öğeler olması gerekir.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-4 pt-8 border-t border-gray-200/50 dark:border-gray-600/50 mt-8">
                        <a href="{{ route('treatment-plans.show', $treatmentPlan) }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-xl transition-all duration-300 shadow-sm hover:shadow-md">
                            İptal
                        </a>
                    </div>
                </div>
        </div>
    </div>

    <!-- Item Cancellation Modal -->
    <div id="item-cancel-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Tedavi Öğesini İptal Et
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Bu işlem geri alınamaz. Tedavi öğesini iptal etmek istediğinizden emin misiniz?
                            </p>
                            <div id="item-cancel-details" class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <!-- Item details will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="cancelItemConfirmed()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Evet, İptal Et
                </button>
                <button type="button" onclick="closeItemCancelModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    İptal
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentItemId = null;

        function confirmCancelItem(itemId, treatmentName, toothNumber, hasAppointment) {
            currentItemId = itemId;

            let detailsHtml = `<div class="space-y-2">`;
            detailsHtml += `<p class="text-sm font-medium text-gray-900 dark:text-white">${treatmentName}`;
            if (toothNumber) {
                detailsHtml += ` (Diş ${toothNumber})`;
            }
            detailsHtml += `</p>`;

            if (hasAppointment) {
                detailsHtml += `<p class="text-sm text-orange-600 dark:text-orange-400">⚠️ Bu öğeye bağlı randevu varsa, randevu da iptal edilecektir.</p>`;
            }

            detailsHtml += `<p class="text-sm text-red-600 dark:text-red-400 font-medium">Bu işlem geri alınamaz!</p>`;
            detailsHtml += `</div>`;

            document.getElementById('item-cancel-details').innerHTML = detailsHtml;
            document.getElementById('item-cancel-modal').classList.remove('hidden');
        }

        function closeItemCancelModal() {
            document.getElementById('item-cancel-modal').classList.add('hidden');
            currentItemId = null;
        }

        function cancelItemConfirmed() {
            if (!currentItemId) return;

            // Show loading state
            const confirmBtn = document.querySelector('#item-cancel-modal button[onclick="cancelItemConfirmed()"]');
            const originalText = confirmBtn.textContent;
            confirmBtn.textContent = 'İptal Ediliyor...';
            confirmBtn.disabled = true;

            // Send AJAX request
            fetch('{{ route("treatment-plans.cancel-items", $treatmentPlan) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    item_ids: [currentItemId]
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert(data.message);
                    // Reload page to show updated state
                    window.location.reload();
                } else {
                    alert('Hata: ' + data.message);
                    confirmBtn.textContent = originalText;
                    confirmBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                confirmBtn.textContent = originalText;
                confirmBtn.disabled = false;
            });
        }

        function cancelEntirePlan() {
            if (!confirm('Bu işlem geri alınamaz. Tüm tedavi planını iptal etmek istediğinizden emin misiniz?')) {
                return;
            }

            // Show loading state
            const cancelBtn = document.getElementById('cancel-entire-plan-btn');
            const originalText = cancelBtn.textContent;
            cancelBtn.textContent = 'İptal Ediliyor...';
            cancelBtn.disabled = true;

            // Send AJAX request
            fetch('{{ route("treatment-plans.cancel-plan", $treatmentPlan) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert(data.message);
                    // Reload page to show updated state
                    window.location.reload();
                } else {
                    alert('Hata: ' + data.message);
                    cancelBtn.textContent = originalText;
                    cancelBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                cancelBtn.textContent = originalText;
                cancelBtn.disabled = false;
            });
        }
    </script>

</x-app-layout>
