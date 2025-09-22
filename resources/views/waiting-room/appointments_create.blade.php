<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Yeni Randevu Ekle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form method="POST" action="{{ route('waiting-room.appointments.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="patient_id" value="Hasta" />
                            <x-select-input id="patient_id" name="patient_id" class="mt-1 block w-full" required>
                                <option value="">-- Hasta Seçin --</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="dentist_id" value="Hekim" />
                            <x-select-input id="dentist_id" name="dentist_id" class="mt-1 block w-full" required>
                                <option value="">-- Hekim Seçin --</option>
                                @foreach($dentists as $dentist)
                                    <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('dentist_id')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                <x-input-label for="start_at" value="Randevu Başlangıç" />
                                <x-text-input id="start_at" name="start_at" type="datetime-local" class="mt-1 block w-full" :value="old('start_at')" required />
                                <x-input-error :messages="$errors->get('start_at')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="end_at" value="Randevu Bitiş" />
                                <x-text-input id="end_at" name="end_at" type="datetime-local" class="mt-1 block w-full" :value="old('end_at')" required />
                                <x-input-error :messages="$errors->get('end_at')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div>
                            <x-input-label for="notes" value="Notlar (Opsiyonel)" />
                            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('waiting-room.appointments') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">İptal</a>
                            <x-primary-button class="ms-4">
                                Randevu Oluştur
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>

