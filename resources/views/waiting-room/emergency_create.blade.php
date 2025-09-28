<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Acil Hasta Kaydı</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Yeni acil hasta kaydı oluşturun</p>
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
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Hasta Bilgileri</h3>
                </div>

                <form method="POST" action="{{ route('waiting-room.emergency.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Patient Selection -->
                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hasta Seçin <span class="text-red-500">*</span></label>
                        <select id="patient_id" name="patient_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Lütfen bir hasta seçin --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} (TC: {{ $patient->national_id ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                            Hasta listede yoksa, önce <a href="{{ route('patients.create') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">Hastalar ekranından</a> yeni bir kayıt oluşturun.
                        </p>
                    </div>

                    <!-- Triage Level -->
                    <div>
                        <label for="triage_level" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Aciliyet Durumu <span class="text-red-500">*</span></label>
                        <div class="mt-3 grid grid-cols-1 gap-3">
                            <label class="relative flex cursor-pointer rounded-lg border bg-white dark:bg-slate-700 p-4 shadow-sm focus:outline-none border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20">
                                <input type="radio" name="triage_level" value="green" class="sr-only" @checked(old('triage_level') === 'green' || !old('triage_level')) />
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="flex items-center text-sm font-medium text-green-900 dark:text-green-100">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Normal (Yeşil)
                                        </span>
                                        <span class="mt-1 text-sm text-green-700 dark:text-green-300">Acil olmayan durumlar</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </label>

                            <label class="relative flex cursor-pointer rounded-lg border bg-white dark:bg-slate-700 p-4 shadow-sm focus:outline-none border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/20">
                                <input type="radio" name="triage_level" value="yellow" class="sr-only" @checked(old('triage_level') === 'yellow') />
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="flex items-center text-sm font-medium text-yellow-900 dark:text-yellow-100">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Acil (Sarı)
                                        </span>
                                        <span class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">Önemli ancak hayati tehlike yok</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </label>

                            <label class="relative flex cursor-pointer rounded-lg border bg-white dark:bg-slate-700 p-4 shadow-sm focus:outline-none border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20">
                                <input type="radio" name="triage_level" value="red" class="sr-only" @checked(old('triage_level') === 'red') />
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="flex items-center text-sm font-medium text-red-900 dark:text-red-100">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Kritik (Kırmızı)
                                        </span>
                                        <span class="mt-1 text-sm text-red-700 dark:text-red-300">Hayati tehlike var, acil müdahale gerekli</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('triage_level')" class="mt-2" />
                    </div>

                    <!-- Dentist Assignment -->
                    <div>
                        <label for="dentist_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hekim Atayın <span class="text-red-500">*</span></label>
                        <select id="dentist_id" name="dentist_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Lütfen bir hekim seçin --</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('dentist_id')" class="mt-2" />
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notlar</label>
                        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hasta durumu ile ilgili notlar...">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('waiting-room.emergency') }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                            İptal
                        </a>
                        <button type="submit" class="px-8 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                            Kaydı Oluştur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>