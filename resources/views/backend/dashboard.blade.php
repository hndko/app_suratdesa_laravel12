@extends('layouts.app-backend')

@section('content')
<div class="dashboard-page">
    <div class="dashboard-hero">
        <div>
            <span class="eyebrow">Ringkasan Operasional</span>
            <h1>Dashboard SIMADES</h1>
            <p>Pantau data penduduk, layanan surat, pengaduan warga, dan informasi desa dari satu halaman kerja.</p>
        </div>
        <div class="hero-actions">
            @can('surat-create')
            <a href="{{ route('surat.create') }}" class="btn btn-light">
                <i class="fas fa-pen mr-1"></i> Buat Surat
            </a>
            @endcan
            @can('pengaduan-index')
            <a href="{{ route('pengaduan.index') }}" class="btn btn-outline-light">
                <i class="fas fa-comments mr-1"></i> Pengaduan
            </a>
            @endcan
        </div>
    </div>

    <div class="metric-grid">
        <div class="metric-card metric-blue">
            <div class="metric-icon"><i class="fas fa-users"></i></div>
            <span>Total Penduduk</span>
            <strong>{{ number_format($totalPenduduk, 0, ',', '.') }}</strong>
            <p>L/P {{ number_format($totalLakiLaki, 0, ',', '.') }} / {{ number_format($totalPerempuan, 0, ',', '.') }}
            </p>
            @can('penduduk-index')
            <a href="{{ route('penduduk.index') }}">Lihat data <i class="fas fa-arrow-right ml-1"></i></a>
            @endcan
        </div>

        <div class="metric-card metric-green">
            <div class="metric-icon"><i class="fas fa-address-card"></i></div>
            <span>Kartu Keluarga</span>
            <strong>{{ number_format($totalKartuKeluarga, 0, ',', '.') }}</strong>
            <p>Rasio L/P {{ $rasioJenisKelamin }}</p>
            @can('kartu-keluarga-index')
            <a href="{{ route('kartu-keluarga.index') }}">Kelola KK <i class="fas fa-arrow-right ml-1"></i></a>
            @endcan
        </div>

        <div class="metric-card metric-orange">
            <div class="metric-icon"><i class="fas fa-file-signature"></i></div>
            <span>Layanan Surat</span>
            <strong>{{ number_format($totalSurat, 0, ',', '.') }}</strong>
            <p>{{ number_format($suratBulanIni, 0, ',', '.') }} surat bulan ini</p>
            <div class="progress progress-thin">
                <div class="progress-bar" style="width: {{ $suratCompletionRate }}%"></div>
            </div>
            <small>{{ $suratCompletionRate }}% selesai</small>
        </div>

        <div class="metric-card metric-red">
            <div class="metric-icon"><i class="fas fa-headset"></i></div>
            <span>Pengaduan Warga</span>
            <strong>{{ number_format($totalPengaduan, 0, ',', '.') }}</strong>
            <p>{{ number_format($pengaduanBulanIni, 0, ',', '.') }} laporan bulan ini</p>
            <div class="progress progress-thin">
                <div class="progress-bar" style="width: {{ $pengaduanCompletionRate }}%"></div>
            </div>
            <small>{{ $pengaduanCompletionRate }}% selesai</small>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-xl-8">
            <div class="dashboard-card">
                <div class="card-heading">
                    <div>
                        <span>Tren {{ $year }}</span>
                        <h2>Statistik Surat Keluar</h2>
                    </div>
                    @can('report-index')
                    <a href="{{ route('report.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-file-export mr-1"></i> Laporan
                    </a>
                    @endcan
                </div>
                <div class="chart-shell">
                    <div class="chart-loader" data-chart-loader="chartSurat">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Memuat grafik
                    </div>
                    <canvas id="chartSurat" data-lazy-chart="surat" height="320"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="dashboard-card h-100">
                <div class="card-heading">
                    <div>
                        <span>Prioritas</span>
                        <h2>Status Surat</h2>
                    </div>
                </div>
                <div class="status-list">
                    @foreach($suratStatusLabels as $status => $label)
                    <div class="status-item">
                        <div>
                            <strong>{{ $label }}</strong>
                            <small>{{ $status === 'done' ? 'Dokumen selesai' : 'Perlu dipantau' }}</small>
                        </div>
                        <span>{{ number_format($suratStatusStats[$status] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="attention-box">
                    <i class="fas fa-tasks"></i>
                    <div>
                        <strong>{{ number_format($suratMenungguApproval, 0, ',', '.') }} surat menunggu tindak
                            lanjut</strong>
                        <p>Fokuskan proses verifikasi dan persetujuan agar pemohon tidak menunggu terlalu lama.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('ai-playground-send')
    <div class="dashboard-card ai-card">
        <div class="card-heading">
            <div>
                <span>AI Assistant</span>
                <h2>Ringkasan AI Dashboard</h2>
            </div>
            <form action="{{ route('dashboard.ai-summary') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-magic mr-1"></i> Buat Ringkasan
                </button>
            </form>
        </div>
        @if(session('dashboard_ai_summary'))
        <div class="ai-result">{{ session('dashboard_ai_summary') }}</div>
        @else
        <p class="muted-text mb-0">Gunakan ringkasan AI untuk melihat insight cepat dari data surat dan pengaduan. Hasil
            AI hanya rekomendasi awal.</p>
        @endif
    </div>
    @endcan

    <div class="row">
        <div class="col-xl-4">
            <div class="dashboard-card">
                <div class="card-heading">
                    <div>
                        <span>Komposisi</span>
                        <h2>Jenis Surat</h2>
                    </div>
                </div>
                <div class="chart-shell chart-compact">
                    <div class="chart-loader" data-chart-loader="chartJenis">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Memuat grafik
                    </div>
                    <canvas id="chartJenis" data-lazy-chart="jenis" height="260"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="dashboard-card">
                <div class="card-heading">
                    <div>
                        <span>Layanan Warga</span>
                        <h2>Status Pengaduan</h2>
                    </div>
                </div>
                <div class="chart-shell chart-compact">
                    <div class="chart-loader" data-chart-loader="chartPengaduan">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Memuat grafik
                    </div>
                    <canvas id="chartPengaduan" data-lazy-chart="pengaduan" height="260"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="dashboard-card">
                <div class="card-heading">
                    <div>
                        <span>Kategori</span>
                        <h2>Top Pengaduan</h2>
                    </div>
                </div>
                <div class="category-list">
                    @forelse($pengaduanPerKategori as $kategori => $total)
                    <div class="category-item">
                        <span>{{ ucfirst($kategori ?: 'Tidak berkategori') }}</span>
                        <strong>{{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Belum ada data pengaduan.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="dashboard-card">
                <div class="card-heading">
                    <div>
                        <span>Aktivitas Terbaru</span>
                        <h2>Surat Masuk</h2>
                    </div>
                    @can('surat-index')
                    <a href="{{ route('surat.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-archive mr-1"></i> Arsip
                    </a>
                    @endcan
                </div>
                <div class="activity-list">
                    @forelse($latestSurats as $surat)
                    <div class="activity-item">
                        <div class="activity-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="activity-content">
                            <strong>{{ $surat->jenisSurat?->nama_surat ?? 'Surat Desa' }}</strong>
                            <span>{{ $surat->penduduk?->nama ?? 'Pemohon tidak tersedia' }}</span>
                            <small>{{ $surat->created_at?->diffForHumans() }}</small>
                        </div>
                        <span class="status-badge status-{{ $surat->status }}">{{ $suratStatusLabels[$surat->status] ??
                            ucfirst($surat->status) }}</span>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <p>Belum ada surat terbaru.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="dashboard-card">
                <div class="card-heading">
                    <div>
                        <span>Aktivitas Terbaru</span>
                        <h2>Pengaduan Warga</h2>
                    </div>
                    @can('pengaduan-index')
                    <a href="{{ route('pengaduan.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-headset mr-1"></i> Kelola
                    </a>
                    @endcan
                </div>
                <div class="activity-list">
                    @forelse($latestPengaduans as $pengaduan)
                    <div class="activity-item">
                        <div class="activity-icon"><i class="fas fa-comment-dots"></i></div>
                        <div class="activity-content">
                            <strong>{{ $pengaduan->name }}</strong>
                            <span>{{ ucfirst($pengaduan->category ?: 'Tidak berkategori') }}</span>
                            <small>{{ $pengaduan->created_at?->diffForHumans() }}</small>
                        </div>
                        <span class="status-badge status-{{ $pengaduan->status }}">{{
                            $pengaduanStatusLabels[$pengaduan->status] ?? ucfirst($pengaduan->status) }}</span>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Belum ada pengaduan terbaru.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dashboard-page {
        color: #1f2937;
    }

    .dashboard-hero {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding: 1.35rem;
        border-radius: 16px;
        background:
            linear-gradient(135deg, rgba(17, 24, 39, 0.94), rgba(15, 118, 110, 0.9)),
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.28), transparent 34%);
        color: #ffffff;
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.16);
    }

    .dashboard-hero h1,
    .card-heading h2 {
        letter-spacing: 0;
    }

    .dashboard-hero h1 {
        font-size: 2rem;
        font-weight: 800;
        margin: 0.22rem 0;
    }

    .dashboard-hero p {
        max-width: 680px;
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }

    .eyebrow,
    .card-heading span {
        display: block;
        color: #0f766e;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .dashboard-hero .eyebrow {
        color: rgba(255, 255, 255, 0.72);
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        justify-content: flex-end;
    }

    .metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .metric-card,
    .dashboard-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }

    .metric-card {
        --metric-color: #2563eb;
        position: relative;
        overflow: hidden;
        min-height: 180px;
        padding: 1rem;
        color: var(--metric-color);
    }

    .metric-card::after {
        content: "";
        position: absolute;
        inset: auto -28px -34px auto;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.08;
    }

    .metric-card span,
    .metric-card p,
    .metric-card small {
        color: #6b7280;
    }

    .metric-card span {
        display: block;
        margin-top: 0.8rem;
        font-weight: 700;
    }

    .metric-card strong {
        display: block;
        margin: 0.2rem 0;
        font-size: 2.05rem;
        font-weight: 800;
        color: #111827;
    }

    .metric-card p {
        min-height: 22px;
        margin-bottom: 0.7rem;
    }

    .metric-card a {
        position: relative;
        z-index: 1;
        color: inherit;
        font-weight: 800;
    }

    .metric-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        color: #ffffff;
        background: var(--metric-color);
    }

    .metric-icon i {
        color: #ffffff;
    }

    .metric-blue {
        --metric-color: #2563eb;
    }

    .metric-green {
        --metric-color: #059669;
    }

    .metric-orange {
        --metric-color: #ea580c;
    }

    .metric-red {
        --metric-color: #dc2626;
    }

    .progress-thin {
        height: 7px;
        border-radius: 999px;
        background: #eef2f7;
    }

    .progress-thin .progress-bar {
        border-radius: inherit;
        background: currentColor;
    }

    .dashboard-card {
        margin-bottom: 1rem;
        padding: 1rem;
    }

    .card-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 1rem;
    }

    .card-heading h2 {
        margin: 0.12rem 0 0;
        font-size: 1.08rem;
        font-weight: 800;
        color: #111827;
    }

    .chart-shell {
        position: relative;
        min-height: 320px;
    }

    .chart-compact {
        min-height: 260px;
    }

    .chart-loader {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        background: linear-gradient(180deg, #f8fafc, #ffffff);
        border: 1px dashed #dbe3ef;
        border-radius: 12px;
        z-index: 1;
    }

    .chart-loader.is-hidden {
        display: none;
    }

    .status-list,
    .category-list,
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .status-item,
    .category-item,
    .activity-item,
    .attention-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        padding: 0.75rem;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #eef2f7;
    }

    .status-item small,
    .activity-content span,
    .activity-content small,
    .muted-text,
    .attention-box p {
        color: #6b7280;
    }

    .status-item span,
    .category-item strong {
        min-width: 42px;
        padding: 0.28rem 0.58rem;
        border-radius: 999px;
        background: #ffffff;
        color: #111827;
        font-weight: 800;
        text-align: center;
        box-shadow: inset 0 0 0 1px #e5e7eb;
    }

    .attention-box {
        align-items: flex-start;
        margin-top: 0.85rem;
        background: #ecfeff;
        border-color: #bae6fd;
    }

    .attention-box i {
        color: #0284c7;
        font-size: 1.45rem;
        margin-top: 0.15rem;
    }

    .attention-box p {
        margin: 0.2rem 0 0;
    }

    .ai-card {
        border-color: #bfdbfe;
        background: linear-gradient(180deg, #ffffff, #eff6ff);
    }

    .ai-result {
        white-space: pre-wrap;
        padding: 0.9rem;
        border-radius: 12px;
        color: #1e3a8a;
        background: rgba(255, 255, 255, 0.72);
        border: 1px solid #dbeafe;
    }

    .activity-item {
        justify-content: flex-start;
    }

    .activity-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        color: #0f766e;
        background: #ccfbf1;
    }

    .activity-content {
        min-width: 0;
        flex: 1;
    }

    .activity-content strong,
    .activity-content span,
    .activity-content small {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .status-badge {
        flex: 0 0 auto;
        border-radius: 999px;
        padding: 0.34rem 0.62rem;
        font-size: 0.75rem;
        font-weight: 800;
        color: #334155;
        background: #e2e8f0;
    }

    .status-pending {
        color: #92400e;
        background: #fef3c7;
    }

    .status-process,
    .status-verified {
        color: #075985;
        background: #e0f2fe;
    }

    .status-approved {
        color: #065f46;
        background: #d1fae5;
    }

    .status-done,
    .status-resolved {
        color: #166534;
        background: #dcfce7;
    }

    .status-rejected {
        color: #991b1b;
        background: #fee2e2;
    }

    .empty-state {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        min-height: 86px;
        padding: 0.85rem;
        border-radius: 12px;
        color: #64748b;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
    }

    .empty-state i {
        font-size: 1.35rem;
    }

    .empty-state p {
        margin: 0;
    }

    @media (max-width: 1199.98px) {
        .metric-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .dashboard-hero {
            align-items: stretch;
            flex-direction: column;
            padding: 1rem;
        }

        .dashboard-hero h1 {
            font-size: 1.55rem;
        }

        .hero-actions {
            justify-content: stretch;
        }

        .hero-actions .btn {
            flex: 1 1 150px;
        }

        .metric-grid {
            grid-template-columns: 1fr;
        }

        .card-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .card-heading .btn,
        .card-heading form,
        .card-heading form .btn {
            width: 100%;
        }

        .activity-item {
            align-items: flex-start;
        }

        .status-badge {
            max-width: 104px;
            white-space: normal;
            text-align: center;
        }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script>
    var dashboardCharts = {};
    var dashboardPalette = ['#2563eb', '#0f766e', '#ea580c', '#db2777', '#7c3aed', '#0891b2', '#65a30d', '#dc2626'];

    var chartConfigs = {
        surat: function () {
            return {
                type: 'bar',
                data: {
                    labels: @json($chartSuratBulanLbl),
                    datasets: [{
                        label: 'Jumlah Surat',
                        data: @json($chartSuratBulanVal),
                        backgroundColor: 'rgba(37, 99, 235, 0.82)',
                        borderColor: '#1d4ed8',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    tooltips: { mode: 'index', intersect: false },
                    scales: {
                        yAxes: [{
                            ticks: { beginAtZero: true, precision: 0 },
                            gridLines: { color: 'rgba(148, 163, 184, 0.18)' }
                        }],
                        xAxes: [{
                            gridLines: { display: false }
                        }]
                    }
                }
            };
        },
        jenis: function () {
            return {
                type: 'doughnut',
                data: {
                    labels: @json($chartJenisLbl),
                    datasets: [{
                        data: @json($chartJenisVal),
                        backgroundColor: dashboardPalette,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14 } }
                }
            };
        },
        pengaduan: function () {
            return {
                type: 'doughnut',
                data: {
                    labels: @json($chartPengaduanLbl),
                    datasets: [{
                        data: @json($chartPengaduanVal),
                        backgroundColor: ['#f59e0b', '#0ea5e9', '#22c55e'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 62,
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14 } }
                }
            };
        }
    };

    function renderDashboardChart(canvas) {
        var key = canvas.getAttribute('data-lazy-chart');
        if (!key || dashboardCharts[key] || typeof chartConfigs[key] !== 'function') {
            return;
        }

        dashboardCharts[key] = new Chart(canvas.getContext('2d'), chartConfigs[key]());

        var loader = document.querySelector('[data-chart-loader="' + canvas.id + '"]');
        if (loader) {
            loader.classList.add('is-hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var canvases = document.querySelectorAll('[data-lazy-chart]');

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        renderDashboardChart(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '120px 0px' });

            canvases.forEach(function (canvas) {
                observer.observe(canvas);
            });
            return;
        }

        canvases.forEach(renderDashboardChart);
    });
</script>
@endpush