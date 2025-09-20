import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    // Blade'den aktarılan veriyi al
    const data = window.chartData;

    // 1. Günlük Randevu Sayısı Grafiği (Çizgi Grafik)
    const dailyAppointmentsCtx = document.getElementById('dailyAppointmentsChart');
    if (dailyAppointmentsCtx && data.dailyAppointments) {
        new Chart(dailyAppointmentsCtx, {
            type: 'line',
            data: {
                labels: data.dailyAppointments.labels,
                datasets: [{
                    label: 'Randevu Sayısı',
                    data: data.dailyAppointments.data,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // 2. Randevu Durum Dağılımı Grafiği (Pasta Grafik)
    const appointmentStatusCtx = document.getElementById('appointmentStatusChart');
    if (appointmentStatusCtx && data.appointmentStatus) {
        new Chart(appointmentStatusCtx, {
            type: 'pie', // veya 'doughnut'
            data: {
                labels: data.appointmentStatus.labels,
                datasets: [{
                    label: 'Randevu Durumu',
                    data: data.appointmentStatus.data,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',  // Mavi (completed)
                        'rgba(255, 99, 132, 0.7)',   // Kırmızı (cancelled)
                        'rgba(255, 206, 86, 0.7)',  // Sarı (scheduled)
                        'rgba(75, 192, 192, 0.7)',   // Yeşil (checked_in)
                        'rgba(153, 102, 255, 0.7)', // Mor (in_service)
                        'rgba(255, 159, 64, 0.7)'   // Turuncu (no_show)
                    ],
                    hoverOffset: 4
                }]
            }
        });
    }
});