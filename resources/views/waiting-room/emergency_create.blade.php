<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Yeni Acil Hasta Kaydı') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form method="POST" action="{{ route('waiting-room.emergency.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="patient_id" value="Hasta Seçin" />
                            <x-select-input id="patient_id" name="patient_id" class="mt-1 block w-full" required>
                                <option value="">-- Lütfen bir hasta seçin --</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} (TC: {{ $patient->national_id ?? 'N/A' }})</option>
                                @endforeach
                            </x-select-input>
                             <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                Hasta listede yoksa, önce <a href="{{ route('patients.create') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Hastalar ekranından</a> yeni bir kayıt oluşturun.
                            </p>
                        </div>
                        
                        <div>
                            <x-input-label for="triage_level" value="Aciliyet Durumu" />
                            <x-select-input id="triage_level" name="triage_level" class="mt-1 block w-full" required>
                                <option value="green">Normal (Yeşil)</option>
                                <option value="yellow">Acil (Sarı)</option>
                                <option value="red">Kritik (Kırmızı)</option>
                            </x-select-input>
                             <x-input-error :messages="$errors->get('triage_level')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="dentist_id" value="Hekim Atayın" />
                            <x-select-input id="dentist_id" name="dentist_id" class="mt-1 block w-full" required>
                                 <option value="">-- Lütfen bir hekim seçin --</option>
                                @foreach($dentists as $dentist)
                                    <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                                @endforeach
                            </x-select-input>
                             <x-input-error :messages="$errors->get('dentist_id')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="notes" value="Notlar" />
                            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('waiting-room.emergency') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">İptal</a>
                            <x-primary-button class="ms-4">
                                Kaydı Oluştur
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>