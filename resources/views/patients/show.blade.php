<x-app-layout>
    <div class="py-10" x-data x-init="$store.prescriptions.init({ patientId: {{ $patient->id }} })">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <x-card>
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </h1>
                        <dl class="mt-4 grid gap-4 text-sm text-gray-600 dark:text-gray-300 md:grid-cols-2">
                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('patient.age') }}</dt>
                                <dd>{{ $age ? $age . ' ' . __('patient.age_unit') : __('patient.not_available') }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('patient.primary_phone') }}</dt>
                                <dd>{{ $patient->phone_primary ?? __('patient.not_available') }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('patient.secondary_phone') }}</dt>
                                <dd>{{ $patient->phone_secondary ?? __('patient.not_available') }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('patient.email') }}</dt>
                                <dd>{{ $patient->email ?? __('patient.not_available') }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('patient.address') }}</dt>
                                <dd>{{ $patient->address_text ?? __('patient.not_available') }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="flex flex-col items-start gap-4 md:items-end">
                        @can('update', $patient)
                            <x-primary-button-link href="{{ route('patients.edit', $patient) }}">
                                {{ __('patient.edit_button') }}
                            </x-primary-button-link>
                        @endcan
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <p>{{ __('patient.private_insurance') }}:
                                <span class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $patient->has_private_insurance ? __('patient.yes') : __('patient.no') }}
                                </span>
                            </p>
                            @if($patient->consent_kvkk_at)
                                <p>{{ __('patient.consent_date') }}: {{ $patient->consent_kvkk_at->format('d.m.Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('patient.upcoming_appointments') }}</h2>
                </div>
                <div class="space-y-4">
                    @forelse($upcomingAppointments as $appointment)
                        @php
                            $statusValue = $appointment->status->value;
                            $badgeClass = $appointmentStatusStyles[$statusValue] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200';
                        @endphp
                        <div class="rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-700">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $appointment->start_at->format('d.m.Y H:i') }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $appointment->dentist?->name }}
                                    </p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $badgeClass }}">
                                    {{ $appointmentStatusLabels[$statusValue] ?? ucfirst($statusValue) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('patient.no_upcoming') }}</p>
                    @endforelse
                </div>
            </x-card>

            <x-card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">{{ __('patient.clinical_history') }}</h2>
                <div class="space-y-4">
                    @forelse($encounters as $encounter)
                        @php
                            $encounterDate = $encounter->arrived_at ?? $encounter->created_at;
                            $typeLabel = $encounterTypeLabels[$encounter->type->value] ?? ucfirst($encounter->type->value);
                            $statusLabel = $encounterStatusLabels[$encounter->status->value] ?? ucfirst($encounter->status->value);
                        @endphp
                        <details class="rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
                            <summary class="cursor-pointer list-none px-4 py-3">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $encounterDate?->format('d.m.Y H:i') ?? 'â€”' }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-300">
                                            {{ $typeLabel }} Â· {{ $encounter->dentist?->name ?? __('patient.unknown_dentist') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                                            {{ $statusLabel }}
                                        </span>
                                        @can('update', $encounter)
                                            <x-secondary-button-link href="{{ route('waiting-room.action', $encounter) }}">{{ __('patient.edit') }}</x-secondary-button-link>
                                        @endcan
                                    </div>
                                </div>
                            </summary>
                            <div class="border-t border-gray-200 px-4 py-4 dark:border-gray-700">
                                <div class="space-y-6">
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('patient.treatments') }}</h3>
                                        <ul class="mt-3 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                                            @forelse($encounter->treatments as $treatment)
                                                <li class="rounded border border-gray-200 px-3 py-2 dark:border-gray-700">
                                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                                        <span>{{ $treatment->performed_at?->format('d.m.Y H:i') ?? 'â€”' }}</span>
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
                                            @empty
                                                <li class="text-xs text-gray-500 dark:text-gray-400">{{ __('patient.no_treatments') }}</li>
                                            @endforelse
                                        </ul>
                                    </div>

                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('patient.prescriptions') }}</h3>
                                            @can('create', \App\Models\Prescription::class)
                                                <x-primary-button type="button" @click="$store.prescriptions.openCreate({ encounterId: {{ $encounter->id }} }); $dispatch('open-modal', { name: 'prescription-modal' })">
                                                    {{ __('patient.add_prescription') }}
                                                </x-primary-button>
                                            @endcan
                                        </div>
                                        <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                                            @forelse($encounter->prescriptions as $prescription)
                                                <li class="rounded border border-gray-200 px-3 py-2 dark:border-gray-700">
                                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                                        <div>
                                                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $prescription->dentist?->name }}</p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $prescription->created_at?->format('d.m.Y H:i') }}</p>
                                                        </div>
                                                        <div class="flex gap-2">
                                                            @can('update', $prescription)
                                                                <x-secondary-button type="button" @click="$store.prescriptions.openEdit({ id: {{ $prescription->id }} }); $dispatch('open-modal', { name: 'prescription-modal' })">
                                                                    {{ __('patient.edit') }}
                                                                </x-secondary-button>
                                                            @endcan
                                                            @can('delete', $prescription)
                                                                <x-danger-button type="button" @click="$store.prescriptions.remove({ id: {{ $prescription->id }} })">
                                                                    {{ __('patient.delete') }}
                                                                </x-danger-button>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                    <p class="mt-3 whitespace-pre-line text-gray-700 dark:text-gray-200">{{ $prescription->text }}</p>
                                                </li>
                                            @empty
                                                <li class="text-xs text-gray-500 dark:text-gray-400">{{ __('patient.no_prescriptions') }}</li>
                                            @endforelse
                                        </ul>
                                    </div>

                                    @if($encounter->notes)
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('patient.encounter_notes') }}</h3>
                                            <p class="mt-2 whitespace-pre-line text-sm text-gray-600 dark:text-gray-300">{{ $encounter->notes }}</p>
                                        </div>
                                    @endif

                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('patient.encounter_files') }}</h3>
                                        <ul class="mt-3 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                                            @forelse($encounter->files as $file)
                                                <li class="rounded border border-gray-200 px-3 py-2 dark:border-gray-700">
                                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                                        <a href="{{ $file->download_url }}" target="_blank" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">
                                                            {{ $file->type->value ? __('file.type.' . $file->type->value) : __('file.type.other') }}
                                                        </a>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $file->created_at?->format('d.m.Y H:i') }} Â· {{ $file->uploader?->name }}
                                                        </span>
                                                    </div>
                                                    @if($file->notes)
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $file->notes }}</p>
                                                    @endif
                                                </li>
                                            @empty
                                                <li class="text-xs text-gray-500 dark:text-gray-400">{{ __('patient.no_files_for_encounter') }}</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </details>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('patient.no_encounters') }}</p>
                    @endforelse
                </div>
            </x-card>

            <x-card>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('patient.general_notes') }}</h2>
                <form method="POST" action="{{ route('patients.updateNotes', $patient) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="notes" value="{{ __('patient.notes_label') }}" />
                        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $patient->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="medications_used" value="{{ __('patient.medications_label') }}" />
                        <textarea id="medications_used" name="medications_used" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('medications_used', $patient->medications_used) }}</textarea>
                        <x-input-error :messages="$errors->get('medications_used')" class="mt-2" />
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button>{{ __('patient.save_changes') }}</x-primary-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>

    <x-modal name="prescription-modal" maxWidth="3xl">
        <div class="p-6 space-y-4" x-data>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="$store.prescriptions.heading"></h2>
            <form class="space-y-4" @submit.prevent="$store.prescriptions.submit()">
                <input type="hidden" x-model="$store.prescriptions.form.encounter_id">
                <div>
                    <x-input-label for="prescription-text" value="{{ __('patient.prescription_text_label') }}" />
                    <textarea id="prescription-text" rows="6" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" x-model="$store.prescriptions.form.text" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <x-secondary-button type="button" @click="$dispatch('close')">{{ __('patient.cancel') }}</x-secondary-button>
                    <x-primary-button type="submit" x-bind:disabled="$store.prescriptions.isSubmitting">
                        <span x-show="!$store.prescriptions.isSubmitting">{{ __('patient.save_prescription') }}</span>
                        <span x-show="$store.prescriptions.isSubmitting">{{ __('patient.saving') }}</span>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('prescriptions', {
                    patientId: null,
                    form: { id: null, encounter_id: null, text: '' },
                    heading: '',
                    isSubmitting: false,
                    init(config) {
                        this.patientId = config.patientId;
                    },
                    resetForm() {
                        this.form = { id: null, encounter_id: null, text: '' };
                    },
                    openCreate({ encounterId }) {
                        this.resetForm();
                        this.form.encounter_id = encounterId || null;
                        this.heading = '{{ __('patient.add_prescription_heading') }}';
                    },
                    openEdit({ id }) {
                        this.isSubmitting = true;
                        fetch(`/api/v1/prescriptions/${id}`, {
                            headers: { 'Accept': 'application/json' },
                        })
                            .then(response => response.json())
                            .then(data => {
                                this.form = { id: data.id, encounter_id: data.encounter_id, text: data.text };
                                this.heading = '{{ __('patient.edit_prescription_heading') }}';
                            })
                            .catch(() => alert('{{ __('patient.prescription_load_error') }}'))
                            .finally(() => { this.isSubmitting = false; });
                    },
                    submit() {
                        if (!this.form.text) {
                            alert('{{ __('patient.prescription_text_required') }}');
                            return;
                        }

                        const isEdit = !!this.form.id;
                        const url = isEdit
                            ? `/api/v1/prescriptions/${this.form.id}`
                            : '/api/v1/prescriptions';
                        const method = isEdit ? 'PUT' : 'POST';
                        const payload = {
                            text: this.form.text,
                        };

                        if (!isEdit) {
                            payload.patient_id = this.patientId;
                            payload.encounter_id = this.form.encounter_id;
                        } else if (this.form.encounter_id !== undefined) {
                            payload.encounter_id = this.form.encounter_id;
                        }

                        this.isSubmitting = true;

                        fetch(url, {
                            method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify(payload),
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
                                const message = error?.message || '{{ __('patient.prescription_save_error') }}';
                                alert(message);
                            })
                            .finally(() => {
                                this.isSubmitting = false;
                            });
                    },
                    remove({ id }) {
                        if (!confirm('{{ __('patient.confirm_delete_prescription') }}')) {
                            return;
                        }

                        fetch(`/api/v1/prescriptions/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        })
                            .then(response => {
                                if (!response.ok) {
                                    return Promise.reject();
                                }

                                window.location.reload();
                            })
                            .catch(() => alert('{{ __('patient.prescription_delete_error') }}'));
                    },
                });
            });
        </script>
    @endpush
</x-app-layout>
