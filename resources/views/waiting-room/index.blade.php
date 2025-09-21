<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bekleme Odası') }}
        </h2>
    </x-slot>

    @php
        $routeConfig = [
            'appointmentBase' => url('/waiting-room/appointments'),
            'appointmentStore' => route('waiting-room.appointments.store'),
            'encounterStore' => route('waiting-room.encounters.store'),
            'assignEncounterBase' => url('/waiting-room/encounters'),
            'patientSearch' => route('waiting-room.patients.search'),
            'patientStore' => route('waiting-room.patients.store'),
            'dentistScheduleBase' => url('/waiting-room/dentists'),
        ];

        $triageLabels = [
            'red' => 'Kritik',
            'yellow' => 'Acil',
            'green' => 'Normal',
        ];

        $triageColors = [
            'red' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'green' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        ];

        $encounterTypeLabels = [
            'scheduled' => 'Planlı',
            'walk_in' => 'Randevusuz',
            'emergency' => 'Acil',
        ];
    @endphp

    <div
        id="waiting-room-app"
        data-routes='@json($routeConfig)'
        data-today="{{ $today }}"
        data-triage='@json($triageLabels)'
        data-triage-colors='@json($triageColors)'
    >
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Hasta Seçimi</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            TC Kimlik, ad soyad veya telefon ile arama yapın. Hasta kaydı yoksa hızlıca yeni hasta oluşturabilirsiniz.
                        </p>

                        <div class="mt-4">
                            <x-input-label for="patient-search-input" value="Hasta Ara" />
                            <x-text-input
                                id="patient-search-input"
                                type="text"
                                autocomplete="off"
                                class="mt-1 block w-full"
                                placeholder="TC, Ad Soyad veya Telefon"
                            />
                        </div>
                        <div id="patient-search-results" class="mt-3 space-y-2"></div>

                        <div class="mt-4 text-sm text-gray-700 dark:text-gray-300">
                            Seçilen Hasta:
                            <span id="selected-patient-name" class="font-semibold">Henüz seçilmedi</span>
                        </div>
                        <div id="selected-patient-meta" class="mt-1 text-xs text-gray-500 dark:text-gray-400 hidden"></div>
                        <div class="mt-2">
                            <x-secondary-button type="button" id="clear-selected-patient" class="hidden">Seçimi Temizle</x-secondary-button>
                        </div>

                        <div class="mt-4">
                            <x-secondary-button type="button" id="toggle-new-patient">Yeni Hasta Kaydet</x-secondary-button>
                        </div>
                        <div id="new-patient-form" class="mt-4 hidden">
                            <form id="quick-patient-form">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="new_patient_first_name" value="Ad" />
                                        <x-text-input id="new_patient_first_name" name="first_name" type="text" class="mt-1 block w-full" required />
                                    </div>
                                    <div>
                                        <x-input-label for="new_patient_last_name" value="Soyad" />
                                        <x-text-input id="new_patient_last_name" name="last_name" type="text" class="mt-1 block w-full" required />
                                    </div>
                                    <div>
                                        <x-input-label for="new_patient_national_id" value="TC Kimlik (11 hane)" />
                                        <x-text-input id="new_patient_national_id" name="national_id" type="text" class="mt-1 block w-full" maxlength="11" />
                                    </div>
                                    <div>
                                        <x-input-label for="new_patient_phone" value="Telefon" />
                                        <x-text-input id="new_patient_phone" name="phone_primary" type="text" class="mt-1 block w-full" required />
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-input-label for="new_patient_notes" value="Notlar" />
                                        <textarea id="new_patient_notes" name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <x-primary-button type="submit">Hızlı Kaydet</x-primary-button>
                                </div>
                            </form>
                        </div>
                        <p id="patient-feedback" class="mt-4 text-sm"></p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Randevu Oluştur</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Randevu oluştururken seçilen hekimin takvimindeki uygunlukları kontrol edin. İsterseniz hastayı hemen check-in yapabilirsiniz.
                        </p>
                        <form id="create-appointment-form" class="mt-4 space-y-4">
                            <input type="hidden" name="patient_id" id="appointment_patient_id">
                            <div>
                                <x-input-label for="appointment_start_at" value="Randevu Başlangıç" />
                                <input id="appointment_start_at" type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" required />
                            </div>
                            <div>
                                <x-input-label for="appointment_end_at" value="Randevu Bitiş" />
                                <input id="appointment_end_at" type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" required />
                            </div>
                            <div>
                                <x-input-label for="appointment_dentist_id" value="Hekim" />
                                <x-select-input id="appointment_dentist_id" class="mt-1 block w-full" required>
                                    <option value="">-- Hekim Seçin --</option>
                                    @foreach($allDentists as $dentist)
                                        <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                                    @endforeach
                                </x-select-input>
                            </div>
                            <div>
                                <x-input-label for="appointment_notes" value="Notlar" />
                                <textarea id="appointment_notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                            <div class="flex items-center">
                                <input id="appointment_check_in" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                <label for="appointment_check_in" class="ms-2 text-sm text-gray-600 dark:text-gray-400">Randevu oluşturulduktan sonra hastayı hemen check-in yap</label>
                            </div>
                            <div class="flex justify-end">
                                <x-primary-button type="submit">Randevuyu Kaydet</x-primary-button>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Seçilen Gün İçin Hekim Takvimi</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Hekim ve tarih seçtikten sonra güncel randevu listesi burada görünür.</p>
                                <ul id="dentist-schedule" class="mt-2 space-y-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 border border-dashed border-gray-200 dark:border-gray-700 rounded-md p-3"></ul>
                            </div>
                            <p id="appointment-feedback" class="text-sm"></p>
                        </form>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Acil / Randevusuz Kayıt</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Acil durumlarda hekim takvimi çakışması kontrol edilmez. Dilerseniz hekimi şimdi seçebilir veya daha sonra bekleme listesinden atayabilirsiniz.
                        </p>
                        <form id="create-encounter-form" class="mt-4 space-y-4">
                            <input type="hidden" name="patient_id" id="encounter_patient_id">
                            <div>
                                <x-input-label for="encounter_type" value="Geliş Tipi" />
                                <x-select-input id="encounter_type" class="mt-1 block w-full" required>
                                    @foreach($encounterTypes as $type)
                                        <option value="{{ $type->value }}">{{ $encounterTypeLabels[$type->value] ?? ucfirst(str_replace('_', ' ', $type->value)) }}</option>
                                    @endforeach
                                </x-select-input>
                            </div>
                            <div>
                                <x-input-label for="encounter_triage" value="Triyaj" />
                                <x-select-input id="encounter_triage" class="mt-1 block w-full" required>
                                    @foreach($triageLabels as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </x-select-input>
                            </div>
                            <div>
                                <x-input-label for="encounter_dentist_id" value="Hekim (Opsiyonel)" />
                                <x-select-input id="encounter_dentist_id" class="mt-1 block w-full">
                                    <option value="">-- Hekim Seçin --</option>
                                    @foreach($allDentists as $dentist)
                                        <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                                    @endforeach
                                </x-select-input>
                            </div>
                            <div>
                                <x-input-label for="encounter_notes" value="Notlar" />
                                <textarea id="encounter_notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <x-primary-button type="submit">Acil Kaydı Oluştur</x-primary-button>
                            </div>
                            <p id="encounter-feedback" class="text-sm"></p>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Randevu Bekleyenler (<span id="appointments-count">{{ $pendingAppointments->count() }}</span>)
                            </h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Gün içindeki sıraya göre listelenir.</span>
                        </div>
                        <div id="appointments-container" class="mt-4 space-y-4">
                            @forelse($pendingAppointments as $appointment)
                                <div id="appointment-{{ $appointment->id }}" class="p-4 border rounded-lg dark:border-gray-700" data-status="{{ $appointment->status?->value ?? '' }}" data-start="{{ optional($appointment->start_at)->format('Y-m-d H:i:s') }}">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="font-bold text-lg dark:text-white">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Hekim: {{ $appointment->dentist->name ?? 'Atanmadı' }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Randevu: {{ optional($appointment->start_at)->format('H:i') ?? 'Belirtilmemiş' }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Check-in: {{ $appointment->checked_in_at?->format('H:i') ?? 'Belirtilmemiş' }}</p>
                                        </div>
                                        <span class="status-badge text-sm font-medium text-blue-600 dark:text-blue-400">BEKLİYOR</span>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <x-primary-button class="action-btn" type="button" data-type="appointment" data-action="call" data-id="{{ $appointment->id }}">Çağır</x-primary-button>
                                        <x-secondary-button class="action-btn" type="button" data-type="appointment" data-action="in_service" data-id="{{ $appointment->id }}">İşlemde</x-secondary-button>
                                        <x-danger-button class="action-btn" type="button" data-type="appointment" data-action="completed" data-id="{{ $appointment->id }}">Tamamlandı</x-danger-button>
                                        <button type="button" class="action-btn inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150" data-type="appointment" data-action="cancelled" data-id="{{ $appointment->id }}">İptal</button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">Bekleyen randevulu hasta bulunmamaktadır.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Acil / Randevusuz Bekleyenler (<span id="encounters-count">{{ $waitingEncounters->count() }}</span>)
                        </h3>
                        <div id="encounters-container" class="mt-4 space-y-4">
                            @forelse($waitingEncounters as $encounter)
                                <div id="encounter-{{ $encounter->id }}" class="p-4 border rounded-lg dark:border-gray-700" data-triage="{{ $encounter->triage_level?->value ?? '' }}">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="font-bold text-lg dark:text-white patient-name">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Geliş: {{ $encounter->arrived_at?->format('H:i') ?? 'Belirtilmemiş' }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 assigned-dentist">Hekim: {{ $encounter->dentist->name ?? 'Atanmadı' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <x-triage-badge :level="$encounter->triage_level" />
                                            <span class="status-badge block mt-1 text-sm font-medium text-blue-600 dark:text-blue-400">BEKLİYOR</span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <x-primary-button class="assign-btn" type="button" data-encounter-id="{{ $encounter->id }}" data-patient-name="{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}">
                                            Hekim Ata ve İşleme Al
                                        </x-primary-button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">Bekleyen acil/randevusuz hasta bulunmamaktadır.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            İçeride Muayene Olanlar (
                            <span id="in-service-count">{{ $inServiceAppointments->count() + $inServiceEncounters->count() }}</span>
                            )
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Şu anda hekim yanında olan hastalar.</p>
                        @php
                            $hasInService = $inServiceAppointments->isNotEmpty() || $inServiceEncounters->isNotEmpty();
                        @endphp
                        <div id="in-service-container" class="mt-4 space-y-4">
                            @foreach($inServiceAppointments as $appointment)
                                <div
                                    id="in-service-appointment-{{ $appointment->id }}"
                                    class="p-4 border rounded-lg dark:border-gray-700 bg-emerald-50 dark:bg-emerald-900/20"
                                    data-scope="in-service"
                                    data-started="{{ optional($appointment->called_at)->format('Y-m-d H:i:s') ?? optional($appointment->checked_in_at)->format('Y-m-d H:i:s') ?? optional($appointment->start_at)->format('Y-m-d H:i:s') }}"
                                >
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="font-bold text-lg dark:text-white">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Hekim: {{ $appointment->dentist->name ?? 'Atanmadı' }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Randevu: {{ optional($appointment->start_at)->format('H:i') ?? 'Belirtilmemiş' }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Çağırılma: {{ $appointment->called_at?->format('H:i') ?? '-' }}</p>
                                        </div>
                                        <span class="status-badge text-sm font-medium text-green-700 dark:text-green-300">İŞLEMDE</span>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <x-primary-button
                                            type="button"
                                            class="action-btn"
                                            data-type="appointment"
                                            data-action="completed"
                                            data-id="{{ $appointment->id }}"
                                            data-scope="in-service"
                                        >
                                            Tamamlandı
                                        </x-primary-button>
                                        <x-danger-button
                                            type="button"
                                            class="action-btn"
                                            data-type="appointment"
                                            data-action="cancelled"
                                            data-id="{{ $appointment->id }}"
                                            data-scope="in-service"
                                        >
                                            İptal
                                        </x-danger-button>
                                    </div>
                                </div>
                            @endforeach

                            @foreach($inServiceEncounters as $encounter)
                                <div
                                    id="in-service-encounter-{{ $encounter->id }}"
                                    class="p-4 border rounded-lg dark:border-gray-700 bg-emerald-50 dark:bg-emerald-900/20"
                                    data-scope="in-service"
                                    data-started="{{ optional($encounter->started_at)->format('Y-m-d H:i:s') ?? optional($encounter->arrived_at)->format('Y-m-d H:i:s') }}"
                                >
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="font-bold text-lg dark:text-white patient-name">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Hekim: {{ $encounter->dentist->name ?? 'Atanmadı' }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Başlangıç: {{ $encounter->started_at?->format('H:i') ?? $encounter->arrived_at?->format('H:i') ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <x-triage-badge :level="$encounter->triage_level" />
                                            <span class="status-badge block mt-1 text-sm font-medium text-green-700 dark:text-green-300">İŞLEMDE</span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <x-primary-button
                                            type="button"
                                            class="action-btn"
                                            data-type="encounter"
                                            data-action="done"
                                            data-id="{{ $encounter->id }}"
                                            data-scope="in-service"
                                        >
                                            Tamamlandı
                                        </x-primary-button>
                                        <x-danger-button
                                            type="button"
                                            class="action-btn"
                                            data-type="encounter"
                                            data-action="cancelled"
                                            data-id="{{ $encounter->id }}"
                                            data-scope="in-service"
                                        >
                                            İptal
                                        </x-danger-button>
                                    </div>
                                </div>
                            @endforeach

                            <p id="in-service-empty" class="text-gray-500 dark:text-gray-400 {{ $hasInService ? 'hidden' : '' }}">
                                Şu anda muayene edilen hasta bulunmuyor.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="assign-doctor-modal" title="Hekim Ata ve İşleme Al">
        <form id="assign-doctor-form" onsubmit="return false;">
            <input type="hidden" id="modal_encounter_id" name="encounter_id">
            <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                <strong id="modal_patient_name" class="dark:text-white"></strong> isimli hastayı yönlendirmek istediğiniz hekimi seçin.
            </p>
            <div>
                <x-input-label for="modal_dentist_id" value="Hekimler" />
                <x-select-input id="modal_dentist_id" name="dentist_id" class="mt-1 block w-full" required>
                    <option value="">-- Hekim Seçin --</option>
                    @foreach($allDentists as $dentist)
                        <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                    @endforeach
                </x-select-input>
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    İptal
                </x-secondary-button>
                <x-primary-button type="submit" class="ms-3">
                    Ata ve İşleme Al
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const app = document.getElementById('waiting-room-app');
                const routes = JSON.parse(app.dataset.routes);
                const today = app.dataset.today;
                const triageLabels = JSON.parse(app.dataset.triage);
                const triageColors = JSON.parse(app.dataset.triageColors);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const state = {
                    selectedPatient: null,
                    dentistSchedule: [],
                };

                const elements = {
                    patientSearchInput: document.getElementById('patient-search-input'),
                    patientSearchResults: document.getElementById('patient-search-results'),
                    selectedPatientName: document.getElementById('selected-patient-name'),
                    selectedPatientMeta: document.getElementById('selected-patient-meta'),
                    clearSelectedPatient: document.getElementById('clear-selected-patient'),
                    toggleNewPatientButton: document.getElementById('toggle-new-patient'),
                    newPatientFormWrapper: document.getElementById('new-patient-form'),
                    quickPatientForm: document.getElementById('quick-patient-form'),
                    patientFeedback: document.getElementById('patient-feedback'),
                    appointmentForm: document.getElementById('create-appointment-form'),
                    appointmentPatientInput: document.getElementById('appointment_patient_id'),
                    appointmentStart: document.getElementById('appointment_start_at'),
                    appointmentEnd: document.getElementById('appointment_end_at'),
                    appointmentDentist: document.getElementById('appointment_dentist_id'),
                    appointmentNotes: document.getElementById('appointment_notes'),
                    appointmentCheckIn: document.getElementById('appointment_check_in'),
                    appointmentFeedback: document.getElementById('appointment-feedback'),
                    dentistSchedule: document.getElementById('dentist-schedule'),
                    encounterForm: document.getElementById('create-encounter-form'),
                    encounterPatientInput: document.getElementById('encounter_patient_id'),
                    encounterType: document.getElementById('encounter_type'),
                    encounterTriage: document.getElementById('encounter_triage'),
                    encounterDentist: document.getElementById('encounter_dentist_id'),
                    encounterNotes: document.getElementById('encounter_notes'),
                    encounterFeedback: document.getElementById('encounter-feedback'),
                    appointmentsContainer: document.getElementById('appointments-container'),
                    encountersContainer: document.getElementById('encounters-container'),
                    inServiceContainer: document.getElementById('in-service-container'),
                    appointmentsCount: document.getElementById('appointments-count'),
                    encountersCount: document.getElementById('encounters-count'),
                    inServiceCount: document.getElementById('in-service-count'),
                    assignModalForm: document.getElementById('assign-doctor-form'),
                    assignModalDentist: document.getElementById('modal_dentist_id'),
                };

                function showFeedback(element, message, type = 'success') {
                    if (!element) return;
                    element.textContent = message;
                    element.className = `mt-2 text-sm ${type === 'success' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}`;
                }

                function clearFeedback(element) {
                    if (!element) return;
                    element.textContent = '';
                    element.className = 'text-sm';
                }

                function handleResponse(response) {
                    if (!response.ok) {
                        return response.json().then((err) => Promise.reject(err));
                    }
                    return response.json();
                }

                function handleError(error, element) {
                    console.error(error);
                    const message = error?.errors ? Object.values(error.errors).flat().join('\n') : (error?.message || 'İşlem sırasında bir hata oluştu.');
                    if (element) {
                        showFeedback(element, message, 'error');
                    } else {
                        alert(message);
                    }
                }

                function unwrapResource(data) {
                    return data?.data ?? data;
                }

                function setSelectedPatient(patient) {
                    state.selectedPatient = patient;
                    elements.appointmentPatientInput.value = patient?.id ?? '';
                    elements.encounterPatientInput.value = patient?.id ?? '';

                    if (patient) {
                        elements.selectedPatientName.textContent = patient.full_name;
                        const metaParts = [];
                        if (patient.national_id) metaParts.push(`TC: ${patient.national_id}`);
                        if (patient.phone) metaParts.push(`Tel: ${patient.phone}`);
                        elements.selectedPatientMeta.textContent = metaParts.join(' • ');
                        elements.selectedPatientMeta.classList.toggle('hidden', metaParts.length === 0);
                    } else {
                        elements.selectedPatientName.textContent = 'Henüz seçilmedi';
                        elements.selectedPatientMeta.textContent = '';
                        elements.selectedPatientMeta.classList.add('hidden');
                    }

                    if (elements.clearSelectedPatient) {
                        elements.clearSelectedPatient.classList.toggle('hidden', !patient);
                        elements.clearSelectedPatient.disabled = !patient;
                    }
                }

                function buildActionButton(label, variant, dataset = {}) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = label;
                    button.classList.add('action-btn', 'inline-flex', 'items-center', 'px-4', 'py-2', 'rounded-md', 'font-semibold', 'text-xs', 'uppercase', 'tracking-widest', 'transition', 'ease-in-out', 'duration-150', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2');

                    if (variant === 'primary') {
                        button.classList.add('bg-indigo-600', 'text-white', 'border', 'border-transparent', 'hover:bg-indigo-500', 'focus:ring-indigo-500');
                    } else if (variant === 'secondary') {
                        button.classList.add('bg-white', 'text-gray-700', 'dark:text-gray-300', 'border', 'border-gray-300', 'dark:border-gray-700', 'hover:bg-gray-50', 'dark:hover:bg-gray-800', 'focus:ring-indigo-500');
                    } else if (variant === 'danger') {
                        button.classList.add('bg-red-600', 'text-white', 'border', 'border-transparent', 'hover:bg-red-500', 'focus:ring-red-500');
                    } else if (variant === 'warning') {
                        button.classList.add('bg-amber-500', 'text-white', 'border', 'border-transparent', 'hover:bg-amber-400', 'focus:ring-amber-500');
                    }

                    Object.entries(dataset).forEach(([key, value]) => {
                        button.dataset[key] = value;
                    });

                    return button;
                }

                function formatTime(value) {
                    if (!value) return 'Belirtilmemiş';
                    const date = new Date(value.replace(' ', 'T'));
                    if (Number.isNaN(date.getTime())) return value;
                    return date.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
                }

                function convertDatetimeLocal(value) {
                    if (!value) return null;
                    return value.replace('T', ' ') + ':00';
                }

                function updateCount(element, delta) {
                    if (!element) return;
                    const current = parseInt(element.textContent ?? '0', 10) || 0;
                    element.textContent = Math.max(0, current + delta);
                }

                function fadeOutAndRemove(element, counterElement, afterRemove = null) {
                    if (!element) {
                        return;
                    }

                    element.style.transition = 'opacity 0.4s';
                    element.style.opacity = '0';

                    setTimeout(() => {
                        element.remove();
                        if (counterElement) {
                            updateCount(counterElement, -1);
                        }
                        if (typeof afterRemove === 'function') {
                            afterRemove();
                        }
                    }, 400);
                }

                function toggleInServiceEmptyState() {
                    const emptyElement = document.getElementById('in-service-empty');
                    if (!emptyElement || !elements.inServiceContainer) {
                        return;
                    }

                    const hasActiveCard = Boolean(elements.inServiceContainer.querySelector('[data-scope="in-service"]'));
                    emptyElement.classList.toggle('hidden', hasActiveCard);
                }

                function getAppointmentStatusMeta(status) {
                    switch (status) {
                        case 'in_service':
                            return { label: 'İŞLEMDE', className: 'status-badge text-sm font-medium text-green-600 dark:text-green-400' };
                        case 'completed':
                            return { label: 'TAMAMLANDI', className: 'status-badge text-sm font-medium text-gray-500 dark:text-gray-400' };
                        case 'cancelled':
                            return { label: 'İPTAL EDİLDİ', className: 'status-badge text-sm font-medium text-red-600 dark:text-red-400' };
                        default:
                            return { label: 'BEKLİYOR', className: 'status-badge text-sm font-medium text-blue-600 dark:text-blue-400' };
                    }
                }

                function insertAppointmentCard(card) {
                    if (!elements.appointmentsContainer) {
                        return;
                    }

                    const startValue = card.dataset.start;
                    if (!startValue) {
                        elements.appointmentsContainer.appendChild(card);
                        return;
                    }

                    const startDate = new Date(startValue.replace(' ', 'T'));
                    const siblings = Array.from(elements.appointmentsContainer.children);
                    const insertBefore = siblings.find((child) => {
                        if (!child.dataset.start) {
                            return false;
                        }

                        const childDate = new Date(child.dataset.start.replace(' ', 'T'));
                        return childDate > startDate;
                    });

                    if (insertBefore) {
                        elements.appointmentsContainer.insertBefore(card, insertBefore);
                    } else {
                        elements.appointmentsContainer.appendChild(card);
                    }
                }

                function insertInServiceCard(card) {
                    if (!elements.inServiceContainer) {
                        return;
                    }

                    const startedValue = card.dataset.started;
                    if (!startedValue) {
                        elements.inServiceContainer.appendChild(card);
                        return;
                    }

                    const startedDate = new Date(startedValue.replace(' ', 'T'));
                    const siblings = Array.from(elements.inServiceContainer.children);
                    const insertBefore = siblings.find((child) => {
                        if (!child.dataset.started) {
                            return false;
                        }

                        const childDate = new Date(child.dataset.started.replace(' ', 'T'));
                        return childDate > startedDate;
                    });

                    if (insertBefore) {
                        elements.inServiceContainer.insertBefore(card, insertBefore);
                    } else {
                        elements.inServiceContainer.appendChild(card);
                    }
                }

                function buildAppointmentCard(appointment) {
                    const card = document.createElement('div');
                    card.id = `appointment-${appointment.id}`;
                    card.className = 'p-4 border rounded-lg dark:border-gray-700 transition-opacity';
                    card.dataset.status = appointment.status;
                    card.dataset.start = appointment.start ?? '';

                    const header = document.createElement('div');
                    header.className = 'flex justify-between items-start gap-4';

                    const info = document.createElement('div');
                    info.innerHTML = `
                        <p class="font-bold text-lg dark:text-white">${appointment.patient?.fullName ?? ''}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Hekim: ${appointment.dentist?.name ?? 'Atanmadı'}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Randevu: ${formatTime(appointment.start)}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Check-in: ${formatTime(appointment.checked_in_at)}</p>
                    `;

                    const statusMeta = getAppointmentStatusMeta(appointment.status);
                    const badge = document.createElement('span');
                    badge.className = statusMeta.className;
                    badge.textContent = statusMeta.label;

                    header.append(info, badge);
                    card.appendChild(header);

                    const actions = document.createElement('div');
                    actions.className = 'mt-4 flex flex-wrap gap-2';

                    const callButton = buildActionButton('Çağır', 'primary', { type: 'appointment', action: 'call', id: appointment.id });
                    const inServiceButton = buildActionButton('İşlemde', 'secondary', { type: 'appointment', action: 'in_service', id: appointment.id });
                    const completedButton = buildActionButton('Tamamlandı', 'danger', { type: 'appointment', action: 'completed', id: appointment.id });
                    const cancelButton = buildActionButton('İptal', 'warning', { type: 'appointment', action: 'cancelled', id: appointment.id });

                    actions.append(callButton, inServiceButton, completedButton, cancelButton);
                    card.appendChild(actions);

                    if (['completed', 'cancelled'].includes(appointment.status)) {
                        card.classList.add('opacity-60');
                        actions.querySelectorAll('button').forEach((btn) => {
                            btn.disabled = true;
                            btn.classList.add('opacity-60', 'cursor-not-allowed');
                        });
                    }

                    return card;
                }

                function buildInServiceAppointmentCard(appointment) {
                    const card = document.createElement('div');
                    card.id = `in-service-appointment-${appointment.id}`;
                    card.className = 'p-4 border rounded-lg dark:border-gray-700 bg-emerald-50 dark:bg-emerald-900/20 transition-opacity';
                    card.dataset.scope = 'in-service';
                    card.dataset.started = appointment.called_at || appointment.checked_in_at || appointment.start || '';

                    const header = document.createElement('div');
                    header.className = 'flex justify-between items-start gap-4';

                    const info = document.createElement('div');
                    info.innerHTML = `
                        <p class="font-bold text-lg dark:text-white">${appointment.patient?.fullName ?? ''}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Hekim: ${appointment.dentist?.name ?? 'Atanmadı'}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Randevu: ${formatTime(appointment.start)}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Çağırılma: ${formatTime(appointment.called_at)}</p>
                    `;

                    const badge = document.createElement('span');
                    badge.className = 'status-badge text-sm font-medium text-green-700 dark:text-green-300';
                    badge.textContent = 'İŞLEMDE';

                    header.append(info, badge);
                    card.appendChild(header);

                    const actions = document.createElement('div');
                    actions.className = 'mt-4 flex flex-wrap gap-2';
                    const doneButton = buildActionButton('Tamamlandı', 'primary', { type: 'appointment', action: 'completed', id: appointment.id, scope: 'in-service' });
                    const cancelButton = buildActionButton('İptal', 'danger', { type: 'appointment', action: 'cancelled', id: appointment.id, scope: 'in-service' });
                    actions.append(doneButton, cancelButton);
                    card.appendChild(actions);

                    return card;
                }

                function addInServiceAppointmentCard(appointment) {
                    const card = buildInServiceAppointmentCard(appointment);
                    const existing = document.getElementById(card.id);

                    if (existing) {
                        elements.inServiceContainer.replaceChild(card, existing);
                    } else {
                        insertInServiceCard(card);
                        updateCount(elements.inServiceCount, 1);
                    }

                    toggleInServiceEmptyState();
                }

                function removeInServiceAppointmentCard(id) {
                    const card = document.getElementById(`in-service-appointment-${id}`);
                    if (card) {
                        fadeOutAndRemove(card, elements.inServiceCount, toggleInServiceEmptyState);
                    }
                }

                function syncAppointmentCard(appointment) {
                    const resource = unwrapResource(appointment);
                    if (!resource) {
                        return;
                    }

                    const status = resource.status;
                    const waitingCard = document.getElementById(`appointment-${resource.id}`);

                    if (status === 'in_service') {
                        if (waitingCard) {
                            fadeOutAndRemove(waitingCard, elements.appointmentsCount);
                        }
                        addInServiceAppointmentCard(resource);
                        return;
                    }

                    if (['completed', 'cancelled'].includes(status)) {
                        if (waitingCard) {
                            fadeOutAndRemove(waitingCard, elements.appointmentsCount);
                        }
                        removeInServiceAppointmentCard(resource.id);
                        return;
                    }

                    const card = buildAppointmentCard(resource);
                    if (waitingCard) {
                        elements.appointmentsContainer.replaceChild(card, waitingCard);
                    } else {
                        insertAppointmentCard(card);
                        updateCount(elements.appointmentsCount, 1);
                    }
                }

                function buildTriageBadge(level) {
                    const span = document.createElement('span');
                    span.className = `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${triageColors[level] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'}`;
                    span.textContent = triageLabels[level] ?? 'Bilinmiyor';
                    return span;
                }

                function buildEncounterCard(encounter) {
                    const card = document.createElement('div');
                    card.id = `encounter-${encounter.id}`;
                    card.className = 'p-4 border rounded-lg dark:border-gray-700 transition-opacity';
                    card.dataset.triage = encounter.triage_level;

                    const header = document.createElement('div');
                    header.className = 'flex justify-between items-start gap-4';

                    const info = document.createElement('div');
                    info.innerHTML = `
                        <p class="font-bold text-lg dark:text-white patient-name">${encounter.patient?.first_name ?? ''} ${encounter.patient?.last_name ?? ''}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Geliş: ${formatTime(encounter.arrived_at)}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 assigned-dentist">Hekim: ${encounter.dentist?.name ?? 'Atanmadı'}</p>
                    `;

                    const meta = document.createElement('div');
                    meta.className = 'text-right';
                    meta.appendChild(buildTriageBadge(encounter.triage_level));
                    const status = document.createElement('span');
                    status.className = 'status-badge block mt-1 text-sm font-medium text-blue-600 dark:text-blue-400';
                    status.textContent = 'BEKLİYOR';
                    meta.appendChild(status);

                    header.append(info, meta);
                    card.appendChild(header);

                    const actions = document.createElement('div');
                    actions.className = 'mt-4 flex flex-wrap gap-2';
                    const assignButton = buildActionButton('Hekim Ata ve İşleme Al', 'primary', {
                        encounterId: encounter.id,
                        patientName: `${encounter.patient?.first_name ?? ''} ${encounter.patient?.last_name ?? ''}`,
                    });
                    assignButton.classList.add('assign-btn');
                    actions.appendChild(assignButton);
                    card.appendChild(actions);

                    return card;
                }

                function buildInServiceEncounterCard(encounter) {
                    const card = document.createElement('div');
                    card.id = `in-service-encounter-${encounter.id}`;
                    card.className = 'p-4 border rounded-lg dark:border-gray-700 bg-emerald-50 dark:bg-emerald-900/20 transition-opacity';
                    card.dataset.scope = 'in-service';
                    card.dataset.started = encounter.started_at || encounter.arrived_at || '';

                    const header = document.createElement('div');
                    header.className = 'flex justify-between items-start gap-4';

                    const info = document.createElement('div');
                    info.innerHTML = `
                        <p class="font-bold text-lg dark:text-white patient-name">${encounter.patient?.first_name ?? ''} ${encounter.patient?.last_name ?? ''}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Hekim: ${encounter.dentist?.name ?? 'Atanmadı'}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Başlangıç: ${formatTime(encounter.started_at ?? encounter.arrived_at)}</p>
                    `;

                    const meta = document.createElement('div');
                    meta.className = 'text-right';
                    meta.appendChild(buildTriageBadge(encounter.triage_level));
                    const status = document.createElement('span');
                    status.className = 'status-badge block mt-1 text-sm font-medium text-green-700 dark:text-green-300';
                    status.textContent = 'İŞLEMDE';
                    meta.appendChild(status);

                    header.append(info, meta);
                    card.appendChild(header);

                    const actions = document.createElement('div');
                    actions.className = 'mt-4 flex flex-wrap gap-2';
                    const doneButton = buildActionButton('Tamamlandı', 'primary', { type: 'encounter', action: 'done', id: encounter.id, scope: 'in-service' });
                    const cancelButton = buildActionButton('İptal', 'danger', { type: 'encounter', action: 'cancelled', id: encounter.id, scope: 'in-service' });
                    actions.append(doneButton, cancelButton);
                    card.appendChild(actions);

                    return card;
                }

                function addEncounterCard(encounter) {
                    const resource = unwrapResource(encounter);
                    const card = buildEncounterCard(resource);
                    elements.encountersContainer.prepend(card);
                    updateCount(elements.encountersCount, 1);
                }

                function addInServiceEncounterCard(encounter) {
                    const resource = unwrapResource(encounter);
                    const card = buildInServiceEncounterCard(resource);
                    const existing = document.getElementById(card.id);

                    if (existing) {
                        elements.inServiceContainer.replaceChild(card, existing);
                    } else {
                        insertInServiceCard(card);
                        updateCount(elements.inServiceCount, 1);
                    }

                    toggleInServiceEmptyState();
                }

                function removeInServiceEncounterCard(id) {
                    const card = document.getElementById(`in-service-encounter-${id}`);
                    if (card) {
                        fadeOutAndRemove(card, elements.inServiceCount, toggleInServiceEmptyState);
                    }
                }

                function loadDentistSchedule() {
                    const dentistId = elements.appointmentDentist.value;
                    if (!dentistId) {
                        elements.dentistSchedule.innerHTML = '';
                        state.dentistSchedule = [];
                        return;
                    }

                    const startValue = elements.appointmentStart.value;
                    const date = startValue ? startValue.split('T')[0] : today;

                    fetch(`${routes.dentistScheduleBase}/${dentistId}/schedule?date=${encodeURIComponent(date)}`, {
                        headers: { 'Accept': 'application/json' },
                    })
                        .then(handleResponse)
                        .then((data) => {
                            const schedule = unwrapResource(data) ?? [];
                            state.dentistSchedule = schedule;
                            renderSchedule(schedule);
                        })
                        .catch((error) => handleError(error, elements.appointmentFeedback));
                }

                function renderSchedule(schedule) {
                    elements.dentistSchedule.innerHTML = '';
                    if (!schedule.length) {
                        elements.dentistSchedule.innerHTML = '<li class="text-xs text-gray-500 dark:text-gray-400">Seçilen gün için kayıtlı randevu bulunmuyor.</li>';
                        return;
                    }

                    schedule.forEach((item) => {
                        const li = document.createElement('li');
                        li.className = 'flex items-center justify-between bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md px-3 py-2';
                        li.innerHTML = `
                            <span class="font-medium">${formatTime(item.start)} - ${formatTime(item.end)}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">${item.status.replace('_', ' ')}</span>
                        `;
                        elements.dentistSchedule.appendChild(li);
                    });
                }

                function overlapsExistingAppointment(start, end) {
                    const startDate = new Date(start.replace(' ', 'T'));
                    const endDate = new Date(end.replace(' ', 'T'));
                    if (Number.isNaN(startDate.getTime()) || Number.isNaN(endDate.getTime())) {
                        return false;
                    }

                    return state.dentistSchedule.some((item) => {
                        const itemStart = new Date(item.start);
                        const itemEnd = new Date(item.end);
                        return startDate < itemEnd && endDate > itemStart;
                    });
                }

                let searchTimeout;
                elements.patientSearchInput.addEventListener('input', (event) => {
                    const query = event.target.value.trim();
                    clearTimeout(searchTimeout);
                    if (query.length < 2) {
                        elements.patientSearchResults.innerHTML = '';
                        return;
                    }

                    searchTimeout = setTimeout(() => {
                        fetch(`${routes.patientSearch}?query=${encodeURIComponent(query)}`, {
                            headers: { 'Accept': 'application/json' },
                        })
                            .then(handleResponse)
                            .then((data) => {
                                const patients = unwrapResource(data) ?? [];
                                elements.patientSearchResults.innerHTML = '';
                                if (!patients.length) {
                                    const empty = document.createElement('p');
                                    empty.className = 'text-xs text-gray-500 dark:text-gray-400';
                                    empty.textContent = 'Eşleşen hasta bulunamadı.';
                                    elements.patientSearchResults.appendChild(empty);
                                    return;
                                }

                                patients.forEach((patient) => {
                                    const button = document.createElement('button');
                                    button.type = 'button';
                                    button.className = 'w-full text-left px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-md hover:bg-gray-50 dark:hover:bg-gray-800 transition';
                                    const phoneInfo = patient.phone ? `<span class="text-xs text-gray-500 dark:text-gray-400">${patient.phone}</span>` : '';
                                    const nationalInfo = patient.national_id ? `<span class="text-xs text-gray-500 dark:text-gray-400">TC: ${patient.national_id}</span>` : '';
                                    button.innerHTML = `
                                        <div class="flex flex-col">
                                            <span class="font-medium text-sm text-gray-800 dark:text-gray-100">${patient.full_name}</span>
                                            <span class="flex flex-wrap gap-2">${nationalInfo} ${phoneInfo}</span>
                                        </div>
                                    `;
                                    button.addEventListener('click', () => {
                                        setSelectedPatient(patient);
                                        elements.patientSearchResults.innerHTML = '';
                                        elements.patientSearchInput.value = '';
                                    });
                                    elements.patientSearchResults.appendChild(button);
                                });
                            })
                            .catch((error) => handleError(error, elements.patientFeedback));
                    }, 300);
                });

                elements.toggleNewPatientButton.addEventListener('click', () => {
                    elements.newPatientFormWrapper.classList.toggle('hidden');
                });

                if (elements.clearSelectedPatient) {
                    elements.clearSelectedPatient.addEventListener('click', () => {
                        setSelectedPatient(null);
                    });
                }

                elements.quickPatientForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    clearFeedback(elements.patientFeedback);

                    const formData = new FormData(elements.quickPatientForm);
                    const payload = {
                        first_name: formData.get('first_name'),
                        last_name: formData.get('last_name'),
                        national_id: formData.get('national_id') || null,
                        phone_primary: formData.get('phone_primary'),
                        notes: formData.get('notes') || null,
                    };

                    fetch(routes.patientStore, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(payload),
                    })
                        .then(handleResponse)
                        .then((data) => {
                            const patient = unwrapResource(data)?.patient ?? data.patient;
                            setSelectedPatient(patient);
                            elements.quickPatientForm.reset();
                            elements.newPatientFormWrapper.classList.add('hidden');
                            showFeedback(elements.patientFeedback, data.message || 'Hasta kaydı başarıyla oluşturuldu.');
                        })
                        .catch((error) => handleError(error, elements.patientFeedback));
                });

                ['change', 'blur'].forEach((eventName) => {
                    elements.appointmentDentist.addEventListener(eventName, loadDentistSchedule);
                    elements.appointmentStart.addEventListener(eventName, loadDentistSchedule);
                });

                elements.appointmentForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    clearFeedback(elements.appointmentFeedback);

                    if (!state.selectedPatient) {
                        showFeedback(elements.appointmentFeedback, 'Lütfen önce bir hasta seçin.', 'error');
                        return;
                    }

                    const start = convertDatetimeLocal(elements.appointmentStart.value);
                    const end = convertDatetimeLocal(elements.appointmentEnd.value);
                    const dentistId = elements.appointmentDentist.value;

                    if (!start || !end || !dentistId) {
                        showFeedback(elements.appointmentFeedback, 'Başlangıç, bitiş ve hekim alanları zorunludur.', 'error');
                        return;
                    }

                    if (start >= end) {
                        showFeedback(elements.appointmentFeedback, 'Bitiş saati başlangıç saatinden sonra olmalıdır.', 'error');
                        return;
                    }

                    if (overlapsExistingAppointment(start, end)) {
                        showFeedback(elements.appointmentFeedback, 'Seçilen zaman aralığı seçilen hekimin takvimi ile çakışıyor.', 'error');
                        return;
                    }

                    const payload = {
                        patient_id: state.selectedPatient.id,
                        dentist_id: dentistId,
                        start_at: start,
                        end_at: end,
                        notes: elements.appointmentNotes.value || null,
                    };

                    fetch(routes.appointmentStore, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(payload),
                    })
                        .then(handleResponse)
                        .then((data) => {
                            const appointment = unwrapResource(data);
                            showFeedback(elements.appointmentFeedback, 'Randevu başarıyla oluşturuldu.');

                            if (elements.appointmentCheckIn.checked) {
                                fetch(`${routes.appointmentBase}/${appointment.id}/check-in`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                    },
                                })
                                    .then(handleResponse)
                                    .then((checkInResponse) => {
                                        syncAppointmentCard(unwrapResource(checkInResponse));
                                        elements.appointmentForm.reset();
                                        setSelectedPatient(state.selectedPatient);
                                        loadDentistSchedule();
                                    })
                                    .catch((error) => handleError(error, elements.appointmentFeedback));
                            } else {
                                elements.appointmentForm.reset();
                                setSelectedPatient(state.selectedPatient);
                                loadDentistSchedule();
                            }
                        })
                        .catch((error) => handleError(error, elements.appointmentFeedback));
                });

                elements.encounterForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    clearFeedback(elements.encounterFeedback);

                    if (!state.selectedPatient) {
                        showFeedback(elements.encounterFeedback, 'Lütfen önce bir hasta seçin.', 'error');
                        return;
                    }

                    const payload = {
                        patient_id: state.selectedPatient.id,
                        type: elements.encounterType.value,
                        triage_level: elements.encounterTriage.value,
                        dentist_id: elements.encounterDentist.value || null,
                        notes: elements.encounterNotes.value || null,
                    };

                    fetch(routes.encounterStore, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(payload),
                    })
                        .then(handleResponse)
                        .then((data) => {
                            showFeedback(elements.encounterFeedback, data.message || 'Acil kaydı oluşturuldu.');
                            addEncounterCard(data.encounter ?? unwrapResource(data));
                            elements.encounterForm.reset();
                            setSelectedPatient(state.selectedPatient);
                        })
                        .catch((error) => handleError(error, elements.encounterFeedback));
                });

                document.body.addEventListener('click', (event) => {
                    const assignButton = event.target.closest('.assign-btn');
                    if (assignButton) {
                        const encounterId = assignButton.dataset.encounterId;
                        const patientName = assignButton.dataset.patientName;
                        document.getElementById('modal_encounter_id').value = encounterId;
                        document.getElementById('modal_patient_name').textContent = patientName;
                        elements.assignModalDentist.value = '';
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: { name: 'assign-doctor-modal' } }));
                        return;
                    }

                    const actionButton = event.target.closest('.action-btn');
                    if (!actionButton) {
                        return;
                    }

                    const { type, action, id } = actionButton.dataset;
                    if (type === 'appointment') {
                        if (action === 'call') {
                            actionButton.disabled = true;
                            fetch(`${routes.appointmentBase}/${id}/call`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                            })
                                .then(handleResponse)
                                .then((data) => syncAppointmentCard(unwrapResource(data)))
                                .catch((error) => {
                                    actionButton.disabled = false;
                                    handleError(error);
                                });
                        } else {
                            if (action === 'cancelled' && !confirm('Randevuyu iptal etmek istediğinize emin misiniz?')) {
                                return;
                            }

                            actionButton.disabled = true;
                            fetch(`${routes.appointmentBase}/${id}/status`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                body: JSON.stringify({ status: action }),
                            })
                                .then(handleResponse)
                                .then((data) => syncAppointmentCard(unwrapResource(data)))
                                .catch((error) => {
                                    actionButton.disabled = false;
                                    handleError(error);
                                });
                        }
                    } else if (type === 'encounter') {
                        if (action === 'cancelled' && !confirm('Vaka kaydını iptal etmek istediğinize emin misiniz?')) {
                            return;
                        }

                        actionButton.disabled = true;
                        fetch(`${routes.assignEncounterBase}/${id}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({ status: action }),
                        })
                            .then(handleResponse)
                            .then(() => {
                                removeInServiceEncounterCard(id);
                            })
                            .catch((error) => {
                                actionButton.disabled = false;
                                handleError(error);
                            })
                            .finally(() => {
                                actionButton.disabled = false;
                            });
                    }
                });

                elements.assignModalForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const encounterId = document.getElementById('modal_encounter_id').value;
                    const dentistId = elements.assignModalDentist.value;

                    if (!dentistId) {
                        alert('Lütfen bir hekim seçin.');
                        return;
                    }

                    const submitButton = elements.assignModalForm.querySelector('button[type="submit"]');
                    submitButton.disabled = true;

                    fetch(`${routes.assignEncounterBase}/${encounterId}/assign-and-process`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ dentist_id: dentistId }),
                    })
                        .then(handleResponse)
                        .then((data) => {
                            const card = document.getElementById(`encounter-${encounterId}`);
                            if (card) {
                                fadeOutAndRemove(card, elements.encountersCount);
                            }
                            const encounter = data?.encounter ?? unwrapResource(data);
                            if (encounter) {
                                addInServiceEncounterCard(encounter);
                            }
                            window.dispatchEvent(new CustomEvent('close-modal', { detail: { name: 'assign-doctor-modal' } }));
                        })
                        .catch((error) => handleError(error))
                        .finally(() => {
                            submitButton.disabled = false;
                        });
                });

                setSelectedPatient(null);
                toggleInServiceEmptyState();
            });
        </script>
    @endpush
</x-app-layout>
