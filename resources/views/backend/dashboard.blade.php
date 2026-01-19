@extends('layouts.app-backend')

@section('title', 'Dashboard')

@section('content')
<ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
</ul>

<h1 class="page-header">
    Dashboard <small>Overview aplikasi admin Surat Desa</small>
</h1>

<div class="row">
    <!-- Stat Card: Total Penduduk -->
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex fw-bold small mb-3">
                    <span class="flex-grow-1">TOTAL PENDUDUK</span>
                    <a href="#" data-toggle="card-expand" class="text-body text-opacity-50 text-decoration-none"><i
                            class="fa fa-fw fa-expand"></i></a>
                </div>
                <div class="row align-items-center mb-2">
                    <div class="col-12">
                        <h3 class="mb-0">{{ $totalPenduduk }}</h3>
                    </div>
                </div>
                <div class="small text-body text-opacity-50 text-truncate">
                    <i class="fa fa-user fa-fw me-1"></i> Data warga terdaftar
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card: Total Surat -->
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex fw-bold small mb-3">
                    <span class="flex-grow-1">SURAT KELUAR</span>
                    <a href="#" data-toggle="card-expand" class="text-body text-opacity-50 text-decoration-none"><i
                            class="fa fa-fw fa-expand"></i></a>
                </div>
                <div class="row align-items-center mb-2">
                    <div class="col-12">
                        <h3 class="mb-0">{{ $totalSurat }}</h3>
                    </div>
                </div>
                <div class="small text-body text-opacity-50 text-truncate">
                    <i class="fa fa-file-alt fa-fw me-1"></i> Total surat dicetak
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-xl-8">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-3">Statistik Surat Keluar ({{ $year }})</h6>
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-3">Komposisi Jenis Surat</h6>
                <div class="h-300px w-300px mx-auto">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="{{ asset('assets/plugins/chart.js/dist/chart.umd.js') }}"></script>
<script>
    // Global defaults from template
    Chart.defaults.font.family = app.font.bodyFontFamily;
    Chart.defaults.font.size = 12;
    Chart.defaults.color = app.color.bodyColor;
    Chart.defaults.borderColor = app.color.borderColor;
    Chart.defaults.plugins.legend.display = false;
    Chart.defaults.plugins.tooltip.padding = { left: 8, right: 12, top: 8, bottom: 8 };
    Chart.defaults.plugins.tooltip.cornerRadius = 8;
    Chart.defaults.plugins.tooltip.titleMarginBottom = 6;
    Chart.defaults.plugins.tooltip.color = app.color.componentBg;
    Chart.defaults.plugins.tooltip.multiKeyBackground = app.color.componentColor;
    Chart.defaults.plugins.tooltip.backgroundColor = app.color.componentColor;
    Chart.defaults.plugins.tooltip.titleFont.family = app.font.bodyFontFamily;
    Chart.defaults.plugins.tooltip.titleFont.weight = app.font.bodyFontWeight;
    Chart.defaults.plugins.tooltip.footerFont.family = app.font.bodyFontFamily;
    Chart.defaults.plugins.tooltip.displayColors = true;
    Chart.defaults.plugins.tooltip.boxPadding = 6;
    Chart.defaults.scale.grid.color = app.color.borderColor;
    Chart.defaults.scale.beginAtZero = true;

    // Manual Color Palette
    var themeColor = '#0d6efd';
    var themeColorRgb = '13, 110, 253';
    var colors = [
        'rgba(13, 110, 253, 0.75)',  // Blue
        'rgba(25, 135, 84, 0.75)',   // Green
        'rgba(255, 193, 7, 0.75)',   // Yellow
        'rgba(220, 53, 69, 0.75)',   // Red
        'rgba(13, 202, 240, 0.75)',  // Cyan
        'rgba(108, 117, 125, 0.75)'  // Gray
    ];
    var hoverColors = [
        'rgba(13, 110, 253, 0.5)',
        'rgba(25, 135, 84, 0.5)',
        'rgba(255, 193, 7, 0.5)',
        'rgba(220, 53, 69, 0.5)',
        'rgba(13, 202, 240, 0.5)',
        'rgba(108, 117, 125, 0.5)'
    ];

    // Bar Chart
    var ctxBar = document.getElementById('barChart');
    var barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartSuratBulanLbl) !!},
            datasets: [{
                label: 'Total Surat',
                data: {!! json_encode($chartSuratBulanVal) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.5)',
                borderColor: '#0d6efd',
                borderWidth: 1.5
            }]
        }
    });

    // Doughnut Chart
    var ctxDoughnut = document.getElementById('doughnutChart');
    var doughnutChart = new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartJenisLbl) !!},
            datasets: [{
                data: {!! json_encode($chartJenisVal) !!},
                backgroundColor: colors,
                borderColor: app.color.componentBg,
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection