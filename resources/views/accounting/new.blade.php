<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Yeni Fatura Oluştur: Tedavi Seçimi') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ selectedPatientId: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('accounting.prepare') }}">
                @csrf
                <x-card>
                    @if($patientsWithTreatments->isEmpty())
                        <div class="text-center py-12">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Harika İş!</h3>
                            <p class="mt-2 text-sm text-gray-500">Faturalandırılacak yeni bir tedavi bulunmuyor.</p>
                            <div class="mt-6">
                                <x-secondary-button-link href="{{ route('accounting.index') }}">
                                    Muhasebe Ana Sayfasına Dön
                                </x-secondary-button-link>
                            </div>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($patientsWithTreatments as $patient)
                                <div class="p-4 border rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50" 
                                     :class="{ 'opacity-50': selectedPatientId && selectedPatientId != {{ $patient->id }} }">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                        Hasta: {{ $patient->first_name }} {{ $patient->last_name }}
                                    </h3>
                                    <div class="space-y-2">
                                        @foreach($patient->treatments as $treatment)
                                            <label class="flex items-center p-3 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700/50 cursor-pointer">
                                                <input type="checkbox" name="treatment_ids[]" value="{{ $treatment->id }}" 
                                                       x-on:change="
                                                            if ($event.target.checked) {
                                                                selectedPatientId = {{ $patient->id }};
                                                            } else {
                                                                // Eğer bu hastaya ait seçili başka checkbox kalmadıysa, seçimi sıfırla
                                                                const patientCheckboxes = $el.closest('.space-y-6').querySelectorAll('input[type=checkbox]');
                                                                const isAnyChecked = Array.from(patientCheckboxes).some(cb => cb.checked);
                                                                if (!isAnyChecked) {
                                                                    selectedPatientId = null;
                                                                }
                                                            }
                                                       "
                                                       :disabled="selectedPatientId && selectedPatientId != {{ $patient->id }}"
                                                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                <div class="ms-3 text-sm text-gray-600 dark:text-gray-400 flex-grow">
                                                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ $treatment->treatment->name }}</span>
                                                    <span class="text-xs block">({{ $treatment->performed_at->format('d.m.Y') }} - Dr. {{ $treatment->dentist->name }})</span>
                                                </div>
                                                <span class="font-semibold text-gray-800 dark:text-gray-200">
                                                    {{ number_format($treatment->unit_price, 2, ',', '.') }} TL
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <input type="radio" name="patient_id" value="{{ $patient->id }}" class="hidden" :checked="selectedPatientId == {{ $patient->id }}">
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end mt-6 border-t pt-4 dark:border-gray-700">
                            <a href="{{ route('accounting.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">İptal</a>
                            <x-primary-button class="ms-4">
                                Önizlemeye Geç
                            </x-primary-button>
                        </div>
                    @endif
                </x-card>
            </form>
        </div>
    </div>
</x-app-layout>

