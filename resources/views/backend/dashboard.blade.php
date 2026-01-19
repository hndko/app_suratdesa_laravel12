@extends('layouts.app-backend')

@section('title', 'Dashboard')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header ps-0 pe-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-6 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalPenduduk }}</h3>
                <p>Total Penduduk</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('penduduk.index') }}" class="small-box-footer">Lihat Detail <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-6 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalSurat }}</h3>
                <p>Total Surat Keluar</p>
            </div>
            <div class="icon">
                <i class="fas fa-envelope"></i>
            </div>
            <a href="{{ route('surat.index') }}" class="small-box-footer">Lihat Detail <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
<!-- /.row -->

<div class="row">
    <!-- Bar Chart -->
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Statistik Surat Keluar ({{ $year }})
                </h3>
            </div>
            <div class="card-body">
                <canvas id="chartSurat"
                    style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart -->
    <div class="col-lg-4">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Komposisi Jenis Surat
                </h3>
            </div>
            <div class="card-body">
                <canvas id="chartJenis"
                    style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script>
    // Manual Color Palette (Vibrant)
    var colors = [
        '#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545',
        '#fd7e14', '#ffc107', '#198754', '#20c997', '#0dcaf0', '#adb5bd'
    ];

    var ctxSurat = document.getElementById('chartSurat').getContext('2d');
    var chartSurat = new Chart(ctxSurat, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartSuratBulanLbl) !!},
            datasets: [{
                label: 'Jumlah Surat',
                data: {!! json_encode($chartSuratBulanVal) !!},
                backgroundColor: 'rgba(60, 141, 188, 0.9)',
                borderColor: 'rgba(60, 141, 188, 0.8)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });

    var ctxJenis = document.getElementById('chartJenis').getContext('2d');
    var chartJenis = new Chart(ctxJenis, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartJenisLbl) !!},
            datasets: [{
                data: {!! json_encode($chartJenisVal) !!},
                backgroundColor: colors,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });
</script>
@endpush