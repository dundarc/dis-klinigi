<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ziyaret İşlemi: {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
        </h2>
    </x-slot>

    <div class="py-12" 
         x-data="visitActionManager({
            treatments: {{ json_encode($treatments) }},
            patientId: {{ $encounter->patient_id }},
            encounterId: {{ $encounter->id }},
            fileTypes: {{ json_encode(array_map(fn($case) => ['value' => $case->value, 'label' => ucfirst($case->value)], $fileTypes)) }}
         })">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Uyarı kutusu --}}
            <div class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-600 dark:bg-amber-900/30 dark:text-amber-200">
                Muayene tamamlandığında ziyaret durumunu <strong>“Tamamlandı”</strong> olarak güncellemeyi unutmayın. 
                Ziyaret tamamlanmadıkça hasta bekleme listesinde görünmeye devam eder.
            </div>

            {{-- Ziyaret Formu --}}
            <x-card>
                <form method="POST" action="{{ route('waiting-room.action.update', $encounter) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        {{-- Ziyaret durumu --}}
                        <div>
                            <x-input-label for="status" value="Ziyaret Durumu" />
                            <x-select-input id="status" name="status" class="mt-1 block w-full" required>
                                @foreach($statuses as $status)
                                    @if(!in_array($status, [\App\Enums\EncounterStatus::WAITING]))
                                        <option value="{{ $status->value }}" @selected($encounter->status === $status)>
                                            {{ __("patient.encounter_status." . $status->value) }}
                                        </option>
                                    @endif
                                @endforeach
                            </x-select-input>
                        </div>

                        {{-- Notlar --}}
                        <div>
                            <x-input-label for="notes" value="Ziyaret Notu" />
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 
                                       dark:bg-gray-900 dark:text-gray-300 shadow-sm">{{ old('notes', $encounter->notes) }}</textarea>
                        </div>
                    </div>

                    {{-- Tedaviler --}}
                    <div>
                        <x-input-label value="Uygulanan Tedaviler" />
                        @if($encounter->treatments->isNotEmpty())
                            <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                @foreach($encounter->treatments as $treatment)
                                    <li class="rounded border border-gray-200 px-3 py-2 dark:border-gray-700">
                                        <div class="flex justify-between gap-2">
                                            <span>{{ $treatment->performed_at?->format('d.m.Y H:i') ?? '' }}</span>
                                            <span>{{ $treatment->dentist?->name }}</span>
                                        </div>
                                        <p class="mt-1 font-medium text-gray-900 dark:text-gray-100">
                                            {{ $treatment->display_treatment_name }}
                                            @if($treatment->tooth_number)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">#{{ $treatment->tooth_number }}</span>
                                            @endif
                                        </p>
                                        @if($treatment->notes)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $treatment->notes }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- Yeni tedavi ekleme --}}
                        <div class="mt-4 space-y-3">
                            <template x-for="(treatment, index) in appliedTreatments" :key="index">
                                <div class="flex flex-col gap-3 rounded-lg border border-gray-200 p-3 dark:border-gray-700 md:flex-row md:items-end">
                                    <div class="md:flex-1">
                                        <x-input-label value="Tedavi" />
                                        <x-select-input 
                                            x-bind:name="`treatments[${index}][treatment_id]`" 
                                            @change="updatePrice(index, $event.target)" 
                                            class="mt-1 w-full">
                                            <option value="">Seçiniz...</option>
                                            <template x-for="t in treatments" :key="t.id">
                                                <option :value="t.id" x-text="t.name"></option>
                                            </template>
                                        </x-select-input>
                                    </div>
                                    <div class="md:w-28">
                                        <x-input-label value="Diş No" />
                                        <input type="number" 
                                               x-bind:name="`treatments[${index}][tooth_number]`" 
                                               class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 
                                                      dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                    </div>
                                    <div class="md:w-36">
                                        <x-input-label value="Ücret" />
                                        <input type="number" step="0.01" 
                                               x-bind:name="`treatments[${index}][unit_price]`" 
                                               x-model="treatment.unit_price"
                                               class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 
                                                      dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                    </div>
                                    <x-danger-button type="button" @click="removeTreatment(index)">Sil</x-danger-button>
                                </div>
                            </template>
                        </div>
                        <x-secondary-button type="button" @click="addTreatment" class="mt-3">Yeni Tedavi Ekle</x-secondary-button>
                    </div>

                    {{-- Reçete --}}
                    <div>
                        <x-input-label for="prescription_text" value="Reçete Notu" />
                        <textarea id="prescription_text" name="prescription_text" rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 
                                   dark:bg-gray-900 dark:text-gray-300 shadow-sm"
                            placeholder="İlaç ve kullanım talimatlarını yazın."></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <x-secondary-button-link :href="route('waiting-room.appointments')">İptal</x-secondary-button-link>
                        <x-primary-button>Ziyareti Kaydet</x-primary-button>
                    </div>
                </form>
            </x-card>

            {{-- Dosya / Röntgen --}}
            <x-card>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Dosyalar / Röntgenler</h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">PDF, JPG, PNG veya DICOM dosyaları ekleyebilirsiniz.</span>
                </div>
                <div class="space-y-4">
                    @if($encounter->files->isNotEmpty())
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            @foreach($encounter->files as $file)
                                <li class="flex flex-col rounded border border-gray-200 px-3 py-2 dark:border-gray-700">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ $file->download_url }}" target="_blank" 
                                               class="text-indigo-600 hover:underline dark:text-indigo-300">
                                                {{ strtoupper($file->type->value) }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $file->created_at?->format('d.m.Y H:i') }} · {{ $file->uploader?->name }}
                                        </div>
                                    </div>
                                    @if($file->notes)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $file->notes }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Bu ziyarete ait yüklenmiş dosya bulunmuyor.</p>
                    @endif

                    {{-- Dosya yükleme --}}
                    <form class="space-y-3" @submit.prevent="uploadFile">
                        <div class="grid gap-3 md:grid-cols-2">
                            <div>
                                <x-input-label for="file_type" value="Dosya Türü" />
                                <x-select-input id="file_type" name="type" class="mt-1 block w-full" x-model="fileForm.type">
                                    <template x-for="option in fileTypes" :key="option.value">
                                        <option :value="option.value" x-text="option.label"></option>
                                    </template>
                                </x-select-input>
                            </div>
                            <div>
                                <x-input-label for="file_notes" value="Notlar" />
                                <input id="file_notes" name="notes" type="text" 
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 
                                              dark:bg-gray-900 dark:text-gray-300 shadow-sm" 
                                       x-model="fileForm.notes" placeholder="Opsiyonel açıklama">
                            </div>
                        </div>
                        <div>
                            <x-input-label for="visit_file" value="Dosya Seç" />
                            <input id="visit_file" name="file" type="file" 
                                   class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300" 
                                   @change="setFile($event)">
                        </div>
                        <div class="flex justify-end">
                            <x-primary-button type="submit" x-bind:disabled="fileUploading">
                                <span x-show="!fileUploading">Dosya Yükle</span>
                                <span x-show="fileUploading">Yükleniyor...</span>
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </x-card>
        </div>
    </div>

    @push('scripts')
        <script>
            function visitActionManager(config) {
                return {
                    treatments: config.treatments || [],
                    fileTypes: config.fileTypes || [],
                    patientId: config.patientId,
                    encounterId: config.encounterId,
                    appliedTreatments: [],
                    fileForm: {
                        type: (config.fileTypes && config.fileTypes.length ? config.fileTypes[0].value : 'other'),
                        notes: '',
                        file: null,
                    },
                    fileUploading: false,
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
                    },
                    setFile(event) {
                        this.fileForm.file = event.target.files[0] || null;
                    },
                    uploadFile() {
                        if (!this.fileForm.file) {
                            alert('Lütfen yüklemek için bir dosya seçin.');
                            return;
                        }

                        const formData = new FormData();
                        formData.append('file', this.fileForm.file);
                        formData.append('type', this.fileForm.type || 'other');
                        if (this.fileForm.notes) {
                            formData.append('notes', this.fileForm.notes);
                        }
                        formData.append('encounter_id', this.encounterId);

                        this.fileUploading = true;

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
                                window.location.reload();
                            })
                            .catch(error => {
                                const message = error?.message || 'Dosya yüklenirken bir hata oluştu.';
                                alert(message);
                            })
                            .finally(() => {
                                this.fileUploading = false;
                            });
                    },
                }
            }
        </script>
    @endpush
</x-app-layout>
