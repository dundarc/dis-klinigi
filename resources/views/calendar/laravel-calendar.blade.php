<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Randevu Takvimi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <!-- Filtreler -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div>
                        <x-input-label for="dentist_filter" value="Hekimler" />
                        <x-select-input id="dentist_filter" class="w-full" multiple>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                            @endforeach
                        </x-select-input>
                    </div>
                    <div>
                        <x-input-label for="status_filter" value="Randevu Durumları" />
                        <x-select-input id="status_filter" class="w-full" multiple>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}">{{ ucfirst(__($status->value)) }}</option>
                            @endforeach
                        </x-select-input>
                    </div>
                </div>

                <!-- FullCalendar'ın Render Edileceği Alan -->
                <div id="calendar" class="w-full h-[85vh]"></div>
            </x-card>
        </div>
    </div>
    
    <!-- Randevu Ekleme/Düzenleme Modalı -->
    <x-modal name="appointment-modal" x-data="appointmentModal()" @open-appointment-modal.window="openModal($event.detail)">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="isEditMode ? 'Randevuyu Düzenle' : 'Yeni Randevu Oluştur'"></h2>
        
        <form @submit.prevent="saveAppointment" class="mt-6 space-y-4">
            <div>
                <x-input-label for="patient_id" value="Hasta" />
                <x-select-input x-model="patient_id" id="patient_id" class="w-full" required>
                    <option value="">Hasta Seçiniz...</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                    @endforeach
                </x-select-input>
            </div>
            <div>
                <x-input-label for="dentist_id" value="Hekim" />
                <x-select-input x-model="dentist_id" id="dentist_id" class="w-full" required>
                    @foreach($dentists as $dentist)
                        <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                    @endforeach
                </x-select-input>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="start_at" value="Başlangıç" />
                    <x-text-input x-model="start_at" id="start_at" type="datetime-local" class="w-full" required />
                </div>
                <div>
                    <x-input-label for="end_at" value="Bitiş" />
                    <x-text-input x-model="end_at" id="end_at" type="datetime-local" class="w-full" required />
                </div>
            </div>
            
            {{-- YENİ: Durum (Onaylama) Alanı --}}
            <div>
                <x-input-label for="status" value="Randevu Durumu" />
                <x-select-input x-model="status" id="status" class="w-full" required>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}">{{ ucfirst(__($status->value)) }}</option>
                    @endforeach
                </x-select-input>
            </div>
            
            <div class="mt-6 flex justify-between items-center">
                <div>
                    {{-- Yetki kontrolünü App\Models\Appointment::class yerine doğrudan $appointment nesnesiyle yapmak daha doğrudur,
                         ancak Alpine.js içinde bu mümkün olmadığı için bu şekilde bırakıyoruz.
                         Backend'deki Policy bunu zaten doğru şekilde kontrol edecektir. --}}
                    @can('delete', App\Models\Appointment::class)
                        <x-danger-button type="button" x-show="isEditMode" @click="deleteAppointment">Randevuyu Sil</x-danger-button>
                    @endcan
                </div>
                <div class="flex justify-end">
                    <x-secondary-button type="button" @click="$dispatch('close')">İptal</x-secondary-button>
                    <x-primary-button class="ms-3" x-text="isEditMode ? 'Güncelle' : 'Kaydet'"></x-primary-button>
                </div>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    {{-- Tüm JS mantığını doğrudan buraya ekliyoruz --}}
    <script>
        // Alpine.js Modal Veri Yönetimi Fonksiyonu
        function appointmentModal() {
            return {
                isEditMode: false,
                appointmentId: null,
                patient_id: '',
                dentist_id: '',
                start_at: '',
                end_at: '',
                status: 'scheduled', // Varsayılan durum
                
                openModal(detail) {
                    this.resetForm();
                    this.isEditMode = detail.isEditMode || false;
                    
                    if (this.isEditMode) { // Düzenleme Modu
                        const event = detail.event;
                        this.appointmentId = event.id;
                        this.patient_id = event.extendedProps.patient.id;
                        this.dentist_id = event.extendedProps.dentist.id;
                        this.start_at = this.formatDateToLocal(event.start);
                        this.end_at = event.end ? this.formatDateToLocal(event.end) : this.start_at;
                        this.status = event.extendedProps.status; // Durumu yükle
                    } else { // Oluşturma Modu
                        this.start_at = this.formatDateToLocal(detail.start);
                        this.end_at = this.formatDateToLocal(detail.end);
                        this.status = 'scheduled'; // Yeni randevu için varsayılan
                    }
                },
                
                saveAppointment() {
                    const url = this.isEditMode ? `/api/v1/appointments/${this.appointmentId}` : '/api/v1/appointments';
                    const method = this.isEditMode ? 'PUT' : 'POST';
                    fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify({
                            patient_id: this.patient_id,
                            dentist_id: this.dentist_id,
                            start_at: this.start_at.replace('T', ' '),
                            end_at: this.end_at.replace('T', ' '),
                            status: this.status, // Durumu API'ye gönder
                        })
                    })
                    .then(res => res.json()).then(data => {
                        if (data.errors) throw new Error(Object.values(data.errors).flat().join('\n'));
                        window.calendar.refetchEvents();
                        this.$dispatch('close');
                    }).catch(err => alert(err.message));
                },

                deleteAppointment() {
                    if (!confirm('Bu randevuyu silmek istediğinizden emin misiniz?')) return;
                    fetch(`/api/v1/appointments/${this.appointmentId}`, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Randevu silinemedi.');
                        window.calendar.refetchEvents();
                        this.$dispatch('close');
                    }).catch(err => alert(err.message));
                },

                resetForm() { 
                    this.appointmentId = null; 
                    this.patient_id = ''; 
                    this.dentist_id = ''; 
                    this.start_at = ''; 
                    this.end_at = '';
                    this.status = 'scheduled';
                },
                formatDateToLocal(date) {
                    if (!date) return '';
                    const d = new Date(date);
                    d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                    return d.toISOString().slice(0, 16);
                }
            }
        }
        
        // FullCalendar'ı Alpine.js hazır olduğunda başlat
        document.addEventListener('alpine:init', () => {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return; 

            const dentistFilter = document.getElementById('dentist_filter');
            const statusFilter = document.getElementById('status_filter');
            const doctorColors = {};
            const colors = ['#4A90E2', '#50E3C2', '#F5A623', '#F8E71C', '#BD10E0', '#9013FE', '#B8E986', '#7ED321'];
            let colorIndex = 0;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [FullCalendar.dayGrid, FullCalendar.timeGrid, FullCalendar.list, FullCalendar.interaction],
                locale: 'tr',
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
                initialView: 'timeGridWeek',
                editable: true,
                selectable: true,
                allDaySlot: false,
                businessHours: { daysOfWeek: [1, 2, 3, 4, 5], startTime: '09:00', endTime: '18:00' },
                events: {
                    url: '/api/v1/appointments',
                    extraParams: () => ({
                        dentist_id: Array.from(dentistFilter.selectedOptions).map(opt => opt.value),
                        status: Array.from(statusFilter.selectedOptions).map(opt => opt.value),
                    }),
                    failure: () => alert('Randevular yüklenirken bir hata oluştu!'),
                },
                eventContent: (arg) => {
                    const dentistId = arg.event.extendedProps.dentist.id;
                    if (!doctorColors[dentistId]) doctorColors[dentistId] = colors[colorIndex++ % colors.length];
                    arg.backgroundColor = doctorColors[dentistId];
                    const status = arg.event.extendedProps.status;
                    const statusColors = { scheduled: 'border-blue-500', confirmed: 'border-green-500', cancelled: 'border-gray-500', no_show: 'border-orange-500' };
                    return { html: `<div class="p-1 overflow-hidden h-full border-l-4 ${statusColors[status] || 'border-gray-400'}"><b>${arg.timeText}</b><p class="text-xs truncate">${arg.event.title}</p><p class="text-xs truncate italic">${arg.event.extendedProps.dentist.name}</p></div>` };
                },
                selectConstraint: "businessHours",
                eventDrop: handleEventUpdate,
                eventResize: handleEventUpdate,
                select: (info) => {
                    if (isLunchBreak(info.start, info.end)) {
                        alert('Öğle arası saatlerine randevu oluşturulamaz.');
                        calendar.unselect();
                        return;
                    }
                    window.dispatchEvent(new CustomEvent('open-appointment-modal', { detail: { start: info.start, end: info.end } }));
                },
                eventClick: (info) => window.dispatchEvent(new CustomEvent('open-appointment-modal', { detail: { isEditMode: true, event: info.event } }))
            });
            calendar.render();
            window.calendar = calendar;

            dentistFilter.addEventListener('change', () => calendar.refetchEvents());
            statusFilter.addEventListener('change', () => calendar.refetchEvents());
            
            function handleEventUpdate(info) {
                if (!confirm("Randevu değişikliğini onaylıyor musunuz?")) { info.revert(); return; }
                const event = info.event;
                const data = { 
                    start_at: event.start.toISOString().slice(0, 19).replace('T', ' '), 
                    end_at: event.end ? event.end.toISOString().slice(0, 19).replace('T', ' ') : event.start.toISOString().slice(0, 19).replace('T', ' '),
                    // Sürükle-bırakta status değişmez, mevcut durumu korunur
                    status: event.extendedProps.status 
                };
                fetch(`/api/v1/appointments/${event.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify(data)
                })
                .then(res => res.json()).then(data => { if(data.errors) { alert(Object.values(data.errors).flat().join('\n')); info.revert(); }})
                .catch(err => { alert('Güncelleme sırasında bir hata oluştu.'); info.revert(); });
            }

            function isLunchBreak(start, end) {
                const lunchStart = 12 * 60 + 30, lunchEnd = 13 * 60 + 30;
                const s = new Date(start), e = new Date(end);
                const startMinutes = s.getHours() * 60 + s.getMinutes();
                const endMinutes = e.getHours() * 60 + e.getMinutes();
                return Math.max(startMinutes, lunchStart) < Math.min(endMinutes, lunchEnd);
            }
        });
    </script>
    @endpush
</x-app-layout>

