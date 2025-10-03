<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Yeni Fatura Oluştur</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Faturalandırılacak tedavileri seçin</p>
            </div>
            <a href="{{ route('accounting.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                İptal
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ selectedPatientId: null, selectedTreatments: [] }">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('accounting.prepare') }}" x-on:submit="document.getElementById('patient_id').value = selectedPatientId">
                @csrf
                <input type="hidden" id="patient_id" name="patient_id" x-model="selectedPatientId" />

                @if($patientsWithTreatments->isEmpty())
                    <!-- Empty State -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-12 text-center">
                        <svg class="mx-auto h-24 w-24 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-6 text-2xl font-bold text-slate-900 dark:text-slate-100">Harika İş!</h3>
                        <p class="mt-2 text-lg text-slate-600 dark:text-slate-400">Faturalandırılacak yeni bir tedavi bulunmuyor.</p>
                        <div class="mt-8">
                            <a href="{{ route('accounting.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Muhasebe Ana Sayfasına Dön
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Treatment Selection -->
                    <div class="space-y-6">
                        @foreach($patientsWithTreatments as $patient)
                            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
                                 :class="{ 'ring-2 ring-blue-500 dark:ring-blue-400': selectedPatientId == {{ $patient->id }} }">
                                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $patient->first_name }} {{ $patient->last_name }}</h3>
                                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $patient->treatments->count() }} tedavi hazır</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm text-slate-500 dark:text-slate-400">Toplam Tutar</div>
                                            <div class="text-xl font-bold text-green-600 dark:text-green-400">{{ number_format($patient->treatments->sum('unit_price'), 2, ',', '.') }} TL</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="space-y-3">
                                        @foreach($patient->treatments as $treatment)
                                            <label class="flex items-center p-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors cursor-pointer"
                                                   :class="{ 'opacity-50': selectedPatientId && selectedPatientId != {{ $patient->id }} }">
                                                <input type="checkbox"
                                                       name="treatment_ids[]"
                                                       value="{{ $treatment->id }}"
                                                       x-model="selectedTreatments"
                                                       x-on:change="
                                                           if ($event.target.checked) {
                                                               selectedPatientId = {{ $patient->id }};
                                                           } else {
                                                               const patientTreatments = {{ $patient->treatments->pluck('id')->toJson() }};
                                                               const hasOtherSelected = selectedTreatments.some(id => patientTreatments.includes(parseInt(id)));
                                                               if (!hasOtherSelected) {
                                                                   selectedPatientId = null;
                                                               }
                                                           }
                                                       "
                                                       :disabled="selectedPatientId && selectedPatientId != {{ $patient->id }}"
                                                       class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" />

                                                <div class="ml-4 flex-grow">
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <div class="flex items-center gap-2 mb-1">
                                                                <div class="font-medium text-slate-900 dark:text-slate-100">{{ $treatment->display_treatment_name }}</div>
                                                                @if($treatment->treatmentPlanItem && $treatment->treatmentPlanItem->treatmentPlan)
                                                                    <span class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/30 px-2 py-1 text-xs font-medium text-purple-800 dark:text-purple-200">
                                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                        </svg>
                                                                        Tedavi Planı #{{ $treatment->treatmentPlanItem->treatmentPlan->id }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                                                {{ $treatment->performed_at->format('d.m.Y H:i') }} • Dr. {{ $treatment->dentist->name }}
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ number_format($treatment->unit_price, 2, ',', '.') }} TL</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary and Actions -->
                    <div class="mt-8 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Seçilen Tedaviler</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400" x-text="selectedTreatments.length + ' tedavi seçildi'"></p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-slate-500 dark:text-slate-400">Toplam Tutar</div>
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400" x-text="calculateTotal() + ' TL'"></div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('accounting.index') }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                                İptal
                            </a>
                            <button type="submit" class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Önizlemeye Geç
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        function calculateTotal() {
            const selectedIds = this.selectedTreatments.map(id => parseInt(id));
            let total = 0;

            @foreach($patientsWithTreatments as $patient)
                @foreach($patient->treatments as $treatment)
                    if (selectedIds.includes({{ $treatment->id }})) {
                        total += {{ $treatment->unit_price }};
                    }
                @endforeach
            @endforeach

            return new Intl.NumberFormat('tr-TR', {
                style: 'currency',
                currency: 'TRY'
            }).format(total);
        }
    </script>
</x-app-layout>
