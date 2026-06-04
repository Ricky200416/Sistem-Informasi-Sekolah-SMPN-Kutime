{{-- resources/views/guru/dashboard/scripts-chart.blade.php --}}
@php
    $chartLabelsJson = json_encode($chartLabels ?? ['Sen','Sel','Rab','Kam','Jum','Sab','Min']);
    $chartHadirJson  = json_encode($chartHadir  ?? [0,0,0,0,0,0,0]);
    $chartTidakJson  = json_encode($chartTidak  ?? [0,0,0,0,0,0,0]);
@endphp
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
<script>
(function () {
    var el = document.getElementById('kehadiranChart');
    if (!el) return;

    var labels    = {!! $chartLabelsJson !!};
    var hadirData = {!! $chartHadirJson !!};
    var tidakData = {!! $chartTidakJson !!};

    var options = {
        chart: {
            type: 'area',
            height: 200,
            toolbar: { show: false },
            fontFamily: "'Plus Jakarta Sans', 'Inter', sans-serif",
            animations: { enabled: true, easing: 'easeinout', speed: 600 },
            background: 'transparent',
        },
        series: [
            { name: 'Hadir',       data: hadirData },
            { name: 'Tidak Hadir', data: tidakData },
        ],
        colors: ['#4f46e5', '#fca5a5'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.02,
                stops: [0, 100],
            },
        },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: {
            categories: labels,
            labels: {
                style: {
                    fontSize: '10px',
                    fontFamily: "'Plus Jakarta Sans', sans-serif",
                    colors: '#94a3b8',
                },
            },
            axisBorder: { show: false },
            axisTicks: { show: false },
        },
        yaxis: {
            min: 0,
            labels: {
                style: {
                    fontSize: '10px',
                    fontFamily: "'Plus Jakarta Sans', sans-serif",
                    colors: '#94a3b8',
                },
                formatter: function (v) { return Math.round(v); },
            },
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4,
            padding: { left: 0, right: 0, top: 0, bottom: 0 },
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '11px',
            fontFamily: "'Plus Jakarta Sans', sans-serif",
            fontWeight: 600,
            markers: { width: 8, height: 8, radius: 3 },
        },
        tooltip: {
            y: { formatter: function (v) { return v + ' siswa'; } },
            style: { fontSize: '11px', fontFamily: "'Plus Jakarta Sans', sans-serif" },
        },
        dataLabels: { enabled: false },
        markers: { size: 3, strokeWidth: 0, hover: { size: 5 } },
    };

    new ApexCharts(el, options).render();
})();
</script>