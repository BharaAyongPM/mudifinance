import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const areaCharts = document.querySelectorAll('[data-chart="finance-area"]');
    const donutCharts = document.querySelectorAll('[data-chart="finance-donut"]');

    areaCharts.forEach((element) => {
        if (!window.ApexCharts) {
            return;
        }

        const labels = JSON.parse(element.dataset.labels || '[]');
        const income = JSON.parse(element.dataset.income || '[]');
        const expense = JSON.parse(element.dataset.expense || '[]');

        const options = {
            chart: {
                type: 'area',
                height: 340,
                toolbar: {
                    show: false,
                },
                fontFamily: 'Outfit, sans-serif',
            },
            colors: ['#12B76A', '#F04438'],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: 'smooth',
                width: 3,
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.03,
                    stops: [0, 100],
                },
            },
            grid: {
                borderColor: '#E4E7EC',
                strokeDashArray: 4,
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
            },
            series: [
                {
                    name: 'Pemasukan',
                    data: income,
                },
                {
                    name: 'Pengeluaran',
                    data: expense,
                },
            ],
            xaxis: {
                categories: labels,
            },
            yaxis: {
                labels: {
                    formatter(value) {
                        return new Intl.NumberFormat('id-ID', {
                            notation: 'compact',
                            compactDisplay: 'short',
                            maximumFractionDigits: 1,
                        }).format(value);
                    },
                },
            },
            tooltip: {
                y: {
                    formatter(value) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            maximumFractionDigits: 0,
                        }).format(value);
                    },
                },
            },
        };

        new window.ApexCharts(element, options).render();
    });

    donutCharts.forEach((element) => {
        if (!window.ApexCharts) {
            return;
        }

        const labels = JSON.parse(element.dataset.labels || '[]');
        const series = JSON.parse(element.dataset.series || '[]');
        const colors = JSON.parse(element.dataset.colors || '[]');

        const options = {
            chart: {
                type: 'donut',
                height: 320,
                fontFamily: 'Outfit, sans-serif',
            },
            labels,
            series,
            colors,
            legend: {
                position: 'bottom',
            },
            stroke: {
                width: 0,
            },
            dataLabels: {
                enabled: false,
            },
            tooltip: {
                y: {
                    formatter(value) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            maximumFractionDigits: 0,
                        }).format(value);
                    },
                },
            },
        };

        new window.ApexCharts(element, options).render();
    });
});
