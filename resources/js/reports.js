import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const chartData = window.chartData;

    const dailyAppointmentsCtx = document.getElementById('dailyAppointmentsChart');
    if (dailyAppointmentsCtx) {
        new Chart(dailyAppointmentsCtx, {
            type: 'line',
            data: {
                labels: chartData.dailyAppointments.labels,
                datasets: [{
                    label: 'Randevu Sayısı',
                    data: chartData.dailyAppointments.data,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    const appointmentStatusCtx = document.getElementById('appointmentStatusChart');
    if (appointmentStatusCtx) {
        new Chart(appointmentStatusCtx, {
            type: 'pie',
            data: {
                labels: chartData.appointmentStatus.labels,
                datasets: [{
                    label: 'Randevu Durumları',
                    data: chartData.appointmentStatus.data,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true
            }
        });
    }
});