import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import trLocale from '@fullcalendar/core/locales/tr';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const dentistFilter = document.getElementById('dentist_filter');

    // CSRF token'ı meta tag'den alalım (POST/PUT/DELETE işlemleri için gerekli)
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
        initialView: 'timeGridWeek',
        locale: trLocale,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        // API'dan event'leri bu adresten çek
        events: {
            url: '/calendar/events', // Rota adımız
            failure: function() {
                alert('Randevular yüklenirken bir hata oluştu!');
            },
        },
        editable: true, // Sürükle-bırak özelliğini aktif et

        // Bir randevu sürüklenip bırakıldığında tetiklenir
        eventDrop: function(info) {
            const event = info.event;
            const newStartDate = event.start.toISOString().slice(0, 19).replace('T', ' ');
            const newEndDate = event.end.toISOString().slice(0, 19).replace('T', ' ');

            // API'a PUT isteği gönder
            fetch(`/api/v1/appointments/${event.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    // Blade ile oturum açtığımız için API token yerine X-CSRF-TOKEN yeterli
                    'X-CSRF-TOKEN': csrfToken 
                },
                body: JSON.stringify({
                    start_at: newStartDate,
                    end_at: newEndDate,
                })
            })
            .then(response => {
                if (!response.ok) {
                    // Hata durumunda değişikliği geri al
                    info.revert();
                    alert("Randevu güncellenemedi.");
                }
                return response.json();
            })
            .then(data => {
                console.log("Randevu başarıyla güncellendi:", data);
                // Başarı bildirimi gösterebilirsiniz (örn: Toast)
            })
            .catch(error => {
                info.revert();
                console.error('Hata:', error);
                alert("Bir hata oluştu.");
            });
        },
    });

    calendar.render();

    // Hekim filtresi değiştiğinde takvimi yenile
    if (dentistFilter) {
        dentistFilter.addEventListener('change', function() {
            const dentistId = this.value;
            calendar.getEventSources().forEach(source => source.remove());
            calendar.addEventSource({
                url: `/calendar/events?dentist_id=${dentistId}`,
                failure: function() {
                    alert('Randevular yüklenirken bir hata oluştu!');
                },
            });
            calendar.refetchEvents();
        });
    }
});