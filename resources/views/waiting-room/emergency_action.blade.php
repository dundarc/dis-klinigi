<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Acil İşlem</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
            </div>
            <a href="{{ route('waiting-room.emergency') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Listeye Dön
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Visit Information Card -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Ziyaret Bilgileri</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $encounter->patient->phone ?? 'Telefon bilgisi yok' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Atanan Hekim</p>
                                <p class="text-sm text-slate-900 dark:text-slate-100">{{ $encounter->dentist->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Geliş Saati</p>
                                <p class="text-sm text-slate-900 dark:text-slate-100">{{ $encounter->arrived_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Mevcut Durum</p>
                                <p class="text-sm text-slate-900 dark:text-slate-100">{{ ucfirst($encounter->status->value) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Triyaj Seviyesi</p>
                                <div class="flex items-center gap-2">
                                    @if($encounter->triage_level)
                                        @if($encounter->triage_level->value === 'red')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                Kritik
                                            </span>
                                        @elseif($encounter->triage_level->value === 'yellow')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">
                                                Acil
                                            </span>
                                        @elseif($encounter->triage_level->value === 'green')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                Normal
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-sm text-slate-500 dark:text-slate-400">Belirtilmemiş</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Form Card -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">İşlem Yap</h3>
                    </div>

                    <form method="POST" action="{{ route('waiting-room.emergency.update', $encounter) }}" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Status Selection -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Yeni Durum Ayarla <span class="text-red-500">*</span></label>
                            <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @foreach($statuses as $status)
                                    {{-- Sadece belirli durumları seçebilsin --}}
                                    @if(in_array($status, [\App\Enums\EncounterStatus::IN_SERVICE, \App\Enums\EncounterStatus::DONE, \App\Enums\EncounterStatus::CANCELLED]))
                                        <option value="{{ $status->value }}" @selected($encounter->status == $status)>
                                            {{ ucfirst($status->value) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">İşlem Notu</label>
                            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="İşlem ile ilgili notlar...">{{ old('notes', $encounter->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                            <a href="{{ route('waiting-room.emergency') }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                                İptal
                            </a>
                            <button type="submit" class="px-8 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                Durumu Güncelle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>