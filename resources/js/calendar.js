import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import trLocale from '@fullcalendar/core/locales/tr';

// Alpine.js Modal Veri Yönetimi Fonksiyonu (Global Kapsamda)
function appointmentModal() {
    return {
        isEditMode: false,
        appointmentId: null,
        patient_id: '',
        dentist_id: '',
        start_at: '',
        end_at: '',
        
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
            } else { // Oluşturma Modu
                this.start_at = this.formatDateToLocal(detail.start);
                this.end_at = this.formatDateToLocal(detail.end);
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
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.errors) throw new Error(Object.values(data.errors).flat().join('\n'));
                window.calendar.refetchEvents();
                this.$dispatch('close');
            })
            .catch(err => alert(err.message));
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
            })
            .catch(err => alert(err.message));
        },

        resetForm() {
            this.appointmentId = null; this.patient_id = ''; this.dentist_id = ''; this.start_at = ''; this.end_at = '';
        },

        formatDateToLocal(date) {
            if (!date) return '';
            const d = new Date(date);
            d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
            return d.toISOString().slice(0, 16);
        }
    }
}
window.appointmentModal = appointmentModal; // Fonksiyonu global olarak erişilebilir yapıyoruz

// FullCalendar Başlatma
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const dentistFilter = document.getElementById('dentist_filter');
    const statusFilter = document.getElementById('status_filter');
    const doctorColors = {};
    const colors = ['#4A90E2', '#50E3C2', '#F5A623', '#F8E71C', '#BD10E0', '#9013FE', '#B8E986', '#7ED321'];
    let colorIndex = 0;

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
        locale: trLocale,
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
        initialView: 'timeGridWeek',
        editable: true,
        selectable: true,
        allDaySlot: false,
        businessHours: { daysOfWeek: [ 1, 2, 3, 4, 5 ], startTime: '09:00', endTime: '18:00', },
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
            if (!doctorColors[dentistId]) {
                doctorColors[dentistId] = colors[colorIndex++ % colors.length];
            }
            arg.backgroundColor = doctorColors[dentistId];
            const status = arg.event.extendedProps.status;
            const statusColors = { scheduled: 'border-blue-500', confirmed: 'border-green-500', cancelled: 'border-gray-500', no_show: 'border-orange-500' };
            arg.borderColor = ''; // Sınırı sıfırla, class ile yöneteceğiz
            return {
                html: `<div class="p-1 overflow-hidden h-full border-l-4 ${statusColors[status] || 'border-gray-400'}">
                           <b>${arg.timeText}</b>
                           <p class="text-xs truncate">${arg.event.title}</p>
                           <p class="text-xs truncate italic">${arg.event.extendedProps.dentist.name}</p>
                       </div>`
            };
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
        eventClick: (info) => {
            window.dispatchEvent(new CustomEvent('open-appointment-modal', { detail: { isEditMode: true, event: info.event } }));
        }
    });
    calendar.render();
    window.calendar = calendar;

    dentistFilter.addEventListener('change', () => calendar.refetchEvents());
    statusFilter.addEventListener('change', () => calendar.refetchEvents());
    
    function handleEventUpdate(info) {
        if (!confirm("Randevu değişikliğini onaylıyor musunuz?")) {
            info.revert(); return;
        }
        const event = info.event;
        const data = {
            start_at: event.start.toISOString().slice(0, 19).replace('T', ' '),
            end_at: event.end.toISOString().slice(0, 19).replace('T', ' '),
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
        const lunchStartHour = 12, lunchStartMinute = 30;
        const lunchEndHour = 13, lunchEndMinute = 30;
        const s = new Date(start), e = new Date(end);
        const startMinutes = s.getHours() * 60 + s.getMinutes();
        const endMinutes = e.getHours() * 60 + e.getMinutes();
        const lunchStart = lunchStartHour * 60 + lunchStartMinute;
        const lunchEnd = lunchEndHour * 60 + lunchEndMinute;
        return Math.max(startMinutes, lunchStart) < Math.min(endMinutes, lunchEnd);
    }
});
