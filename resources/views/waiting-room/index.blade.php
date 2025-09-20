<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bekleme Odası') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Randevu Bekleyenler ({{ $checkedInAppointments->count() }})</h3>
                    <div class="space-y-4">
                        @forelse($checkedInAppointments as $appointment)
                            <div id="appointment-{{ $appointment->id }}" class="p-4 border rounded-lg dark:border-gray-700">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-lg dark:text-white">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Hekim: {{ $appointment->dentist->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Check-in: {{ $appointment->checked_in_at?->format('H:i') ?? 'Belirtilmemiş' }}</p>
                                    </div>
                                    <span class="status-badge text-sm font-medium text-blue-600 dark:text-blue-400">BEKLİYOR</span>
                                </div>
                                <div class="mt-4 flex space-x-2">
                                    <x-primary-button class="action-btn" type="button" data-type="appointment" data-action="call" data-id="{{ $appointment->id }}">Çağır</x-primary-button>
                                    <x-secondary-button class="action-btn" type="button" data-type="appointment" data-action="in_service" data-id="{{ $appointment->id }}">İşlemde</x-secondary-button>
                                    <x-danger-button class="action-btn" type="button" data-type="appointment" data-action="completed" data-id="{{ $appointment->id }}">Tamamlandı</x-danger-button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">Bekleyen randevulu hasta bulunmamaktadır.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Acil / Randevusuz Bekleyenler ({{ $waitingEncounters->count() }})</h3>
                    <div class="space-y-4">
                        @forelse($waitingEncounters as $encounter)
                             <div id="encounter-{{ $encounter->id }}" class="p-4 border rounded-lg dark:border-gray-700">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-lg dark:text-white patient-name">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Geliş: {{ $encounter->arrived_at?->format('H:i') ?? 'Belirtilmemiş' }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 assigned-dentist">Hekim: {{ $encounter->dentist->name ?? 'Atanmadı' }}</p>
                                    </div>
                                    <div>
                                        <x-triage-badge :level="$encounter->triage_level" />
                                        <span class="status-badge block text-right mt-1 text-sm font-medium text-blue-600 dark:text-blue-400">BEKLİYOR</span>
                                    </div>
                                </div>
                                 <div class="mt-4 flex space-x-2">
                                     <x-primary-button class="assign-btn" 
                                         type="button"
                                         data-encounter-id="{{ $encounter->id }}" 
                                         data-patient-name="{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}">
                                         Hekim Ata ve İşleme Al
                                     </x-primary-button>
                                 </div>
                            </div>
                        @empty
                             <p class="text-gray-500 dark:text-gray-400">Bekleyen acil/randevusuz hasta bulunmamaktadır.</p>
                        @endforelse
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
                <x-input-label for="dentist_id" value="Hekimler" />
                <x-select-input id="dentist_id" name="dentist_id" class="mt-1 block w-full" required>
                    <option value="">-- Hekim Seçin --</option>
                    @if(isset($allDentists))
                        @foreach($allDentists as $dentist)
                            <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                        @endforeach
                    @endif
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
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Genel Olay Dinleyici (Sayfadaki tüm tıklamalar için)
            document.body.addEventListener('click', function(event) {
                const button = event.target.closest('.action-btn, .assign-btn');
                if (!button) return; // Tıklanan şey bir buton değilse, devam etme

                // "Hekim Ata" butonuna mı tıklandı?
                if (button.classList.contains('assign-btn')) {
                    const encounterId = button.dataset.encounterId;
                    const patientName = button.dataset.patientName;
                    
                    document.getElementById('modal_encounter_id').value = encounterId;
                    document.getElementById('modal_patient_name').textContent = patientName;
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: { name: 'assign-doctor-modal' }}));
                } 
                // Diğer aksiyon butonlarına mı tıklandı?
                else if (button.classList.contains('action-btn')) {
                    const id = button.dataset.id;
                    const type = button.dataset.type;
                    const action = button.dataset.action;

                    if (type === 'appointment') {
                        if (action === 'call') {
                            callPatient(id, button);
                        } else {
                            updateAppointmentStatus(id, action, button);
                        }
                    }
                }
            });

            // Modal'daki formu dinle
            const assignForm = document.getElementById('assign-doctor-form');
            assignForm.addEventListener('submit', function (event) {
                event.preventDefault();
                const submitButton = assignForm.querySelector('button[type="submit"]');
                submitButton.disabled = true;

                const encounterId = document.getElementById('modal_encounter_id').value;
                const dentistId = document.getElementById('dentist_id').value;
                
                if (!dentistId) {
                    alert('Lütfen bir hekim seçin.');
                    submitButton.disabled = false;
                    return;
                }

                fetch(`/api/v1/encounters/${encounterId}/assign-and-process`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ dentist_id: dentistId })
                })
                .then(handleResponse)
                .then(data => {
                    const card = document.getElementById(`encounter-${encounterId}`);
                    if (card) {
                        card.style.transition = 'opacity 0.5s';
                        card.style.opacity = '0';
                        setTimeout(() => card.remove(), 500);
                    }
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: { name: 'assign-doctor-modal' }}));
                })
                .catch(handleError)
                .finally(() => {
                    submitButton.disabled = false;
                });
            });

            // Fonksiyonlar
            function callPatient(id, buttonEl) {
                buttonEl.disabled = true;
                fetch(`/api/v1/appointments/${id}/call`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({})
                })
                .then(handleResponse)
                .then(data => {
                    const card = document.getElementById(`appointment-${id}`);
                    const statusBadge = card.querySelector('.status-badge');
                    statusBadge.textContent = 'İŞLEMDE';
                    statusBadge.className = 'status-badge text-sm font-medium text-green-600 dark:text-green-400';
                })
                .catch(err => {
                    handleError(err);
                    buttonEl.disabled = false;
                });
            }

            function updateAppointmentStatus(id, status, buttonEl) {
                buttonEl.disabled = true;
                fetch(`/api/v1/appointments/${id}/status`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ status: status })
                })
                .then(handleResponse)
                .then(data => {
                    const card = document.getElementById(`appointment-${id}`);
                    const statusBadge = card.querySelector('.status-badge');
                    statusBadge.textContent = data.status.toUpperCase().replace('_', ' ');
                    if (status === 'completed') {
                        card.style.opacity = '0.5';
                        card.querySelectorAll('.action-btn').forEach(btn => btn.disabled = true);
                    }
                })
                .catch(err => {
                    handleError(err);
                    buttonEl.disabled = false;
                });
            }

            // Yardımcı Fonksiyonlar
            function handleResponse(response) {
                if (!response.ok) {
                    return response.json().then(err => {
                        return Promise.reject(err);
                    });
                }
                return response.json();
            }

            function handleError(error) {
                console.error('Hata:', error);
                const errorMsg = error.errors ? Object.values(error.errors).flat().join('\n') : (error.message || 'İşlem sırasında bir hata oluştu.');
                alert(errorMsg);
            }
        });
    </script>
    @endpush
</x-app-layout>