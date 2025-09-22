<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ziyaret İşlemi: {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="visitActionManager({ treatments: {{ json_encode($treatments) }} })">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('waiting-room.action.update', $encounter) }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Ziyaret Durumu ve Notlar -->
                    <x-card>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="status" value="Ziyaret Durumu" />
                                <x-select-input id="status" name="status" class="mt-1 block w-full" required>
                                    @foreach($statuses as $status)
                                        @if(!in_array($status, [\App\Enums\EncounterStatus::WAITING]))
                                            <option value="{{ $status->value }}" @selected($encounter->status == $status)>{{ ucfirst($status->value) }}</option>
                                        @endif
                                    @endforeach
                                </x-select-input>
                            </div>
                            <div>
                                <x-input-label for="notes" value="Ziyaret Notu" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ $encounter->notes }}</textarea>
                            </div>
                        </div>
                    </x-card>

                    <!-- Tedaviler -->
                    <x-card>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Uygulanan Tedaviler</h3>
                        @if($encounter->treatments->isNotEmpty())
                           <ul class="mb-4 text-sm list-disc list-inside">
                               @foreach($encounter->treatments as $treatment)
                                   <li>{{ $treatment->treatment->name }}</li>
                               @endforeach
                           </ul>
                        @endif
                        <div class="space-y-2">
                            <template x-for="(treatment, index) in appliedTreatments" :key="index">
                                <div class="flex items-end gap-2 p-2 border rounded-md dark:border-gray-700">
                                    <div class="flex-grow">
                                        <x-input-label value="Tedavi" />
                                        {{-- DÜZELTME BURADA: :name yerine x-bind:name ve JS string birleştirme --}}
                                        <x-select-input x-bind:name="'treatments[' + index + '][treatment_id]'" @change="updatePrice(index, $event.target)" class="w-full mt-1">
                                            <option value="">Seçiniz...</option>
                                            <template x-for="t in treatments" :key="t.id">
                                                <option :value="t.id" x-text="t.name"></option>
                                            </template>
                                        </x-select-input>
                                    </div>
                                    <div class="w-24"><x-input-label value="Diş No"/><input type="number" x-bind:name="'treatments[' + index + '][tooth_number]'" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"></div>
                                    <div class="w-32"><x-input-label value="Ücret"/><input type="number" x-bind:name="'treatments[' + index + '][unit_price]'" x-model="treatment.unit_price" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"></div>
                                    <x-danger-button type="button" @click="removeTreatment(index)">X</x-danger-button>
                                </div>
                            </template>
                        </div>
                        <x-secondary-button type="button" @click="addTreatment" class="mt-2">Yeni Tedavi Ekle</x-secondary-button>
                    </x-card>

                    <!-- Reçete -->
                    <x-card>
                         <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Reçete</h3>
                         @if($encounter->prescriptions->isNotEmpty())
                            <div class="mb-4 p-2 border rounded-md text-sm whitespace-pre-wrap">{{ $encounter->prescriptions->pluck('text')->join("\n---\n") }}</div>
                         @endif
                         <div>
                            <x-input-label for="prescription_text" value="Yeni Reçete Ekle (İlaçlar)" />
                            <textarea id="prescription_text" name="prescription_text" rows="5" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                    </x-card>
                    
                    <!-- Kaydet Butonu -->
                    <div class="flex justify-end">
                        <a href="{{ route('waiting-room.appointments') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                            İptal
                        </a>
                        <x-primary-button class="ms-4">
                            Ziyareti Kaydet
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
        function visitActionManager(config) {
            return {
                treatments: config.treatments || [],
                appliedTreatments: [],
                addTreatment() {
                    this.appliedTreatments.push({ treatment_id: '', tooth_number: '', unit_price: 0 });
                },
                removeTreatment(index) {
                    this.appliedTreatments.splice(index, 1);
                },
                updatePrice(index, selectElement) {
                    const selectedId = selectElement.value;
                    const treatment = this.treatments.find(t => t.id == selectedId);
                    if (treatment) {
                        this.appliedTreatments[index].unit_price = treatment.default_price;
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
