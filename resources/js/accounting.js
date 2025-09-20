import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const currencyFormatter = new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY',
        minimumFractionDigits: 2,
    });

    const data = window.accountingData ?? {};

    const monthlyRevenue = data.monthlyRevenue ?? { labels: [], data: [] };
    const monthlyCtx = document.getElementById('monthlyRevenueChart');

    if (monthlyCtx && monthlyRevenue.labels.length) {
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyRevenue.labels,
                datasets: [
                    {
                        label: 'Tahsilat',
                        data: monthlyRevenue.data,
                        borderColor: 'rgb(37, 99, 235)',
                        backgroundColor: 'rgba(37, 99, 235, 0.15)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        ticks: {
                            callback: (value) => currencyFormatter.format(value),
                        },
                        grid: {
                            drawBorder: false,
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => currencyFormatter.format(context.parsed.y ?? 0),
                        },
                    },
                },
            },
        });
    }

    const paymentMethods = data.paymentMethods ?? { labels: [], data: [] };
    const paymentCtx = document.getElementById('paymentMethodChart');

    if (paymentCtx && paymentMethods.labels.length) {
        const colors = [
            'rgba(34, 197, 94, 0.75)',
            'rgba(59, 130, 246, 0.75)',
            'rgba(249, 115, 22, 0.75)',
            'rgba(236, 72, 153, 0.75)',
            'rgba(139, 92, 246, 0.75)',
            'rgba(234, 179, 8, 0.75)',
        ];

        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: paymentMethods.labels,
                datasets: [
                    {
                        data: paymentMethods.data,
                        backgroundColor: paymentMethods.labels.map((_, index) => colors[index % colors.length]),
                        borderWidth: 1,
                        borderColor: 'rgba(255, 255, 255, 0.9)',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const value = context.parsed ?? 0;
                                const total = paymentMethods.data.reduce((sum, current) => sum + current, 0) || 1;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${currencyFormatter.format(value)} (${percentage}%)`;
                            },
                        },
                    },
                },
                cutout: '65%',
            },
        });
    }
});