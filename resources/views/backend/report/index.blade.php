@extends('layouts.app-backend')

@section('content')
<div class="report-page">
    <div class="report-hero">
        <div>
            <span class="eyebrow">Laporan Operasional</span>
            <h1>{{ $title }}</h1>
            <p>Unduh rekap data penduduk, arsip surat, dan pengaduan warga untuk kebutuhan administrasi, monitoring, dan pelaporan desa.</p>
        </div>
        <div class="hero-badge">
            <i class="fas fa-calendar-check"></i>
            <span>{{ now()->format('d M Y') }}</span>
        </div>
    </div>

    <div class="report-metric-grid">
        <div class="report-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-users"></i></div>
            <div>
                <span>Penduduk</span>
                <strong>{{ number_format($totalPenduduk, 0, ',', '.') }}</strong>
                <small>{{ number_format($totalKartuKeluarga, 0, ',', '.') }} kartu keluarga</small>
            </div>
        </div>
        <div class="report-metric metric-green">
            <div class="metric-icon"><i class="fas fa-file-signature"></i></div>
            <div>
                <span>Arsip Surat</span>
                <strong>{{ number_format($totalSurat, 0, ',', '.') }}</strong>
                <small>{{ number_format($totalSuratBulanIni, 0, ',', '.') }} surat bulan ini</small>
            </div>
        </div>
        <div class="report-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-comments"></i></div>
            <div>
                <span>Pengaduan</span>
                <strong>{{ number_format($totalPengaduan, 0, ',', '.') }}</strong>
                <small>{{ number_format($totalPengaduanPending, 0, ',', '.') }} perlu tindak lanjut</small>
            </div>
        </div>
    </div>

    <div class="report-grid">
        <div class="report-card">
            <div class="card-icon icon-blue"><i class="fas fa-address-card"></i></div>
            <div class="card-body-custom">
                <span>Master Data</span>
                <h2>Data Penduduk</h2>
                <p>Export seluruh data penduduk, relasi Kartu Keluarga, alamat, pekerjaan, dan informasi dasar administrasi.</p>
                <div class="report-meta">
                    <div><i class="fas fa-table"></i> Excel</div>
                    <div><i class="fas fa-database"></i> {{ number_format($totalPenduduk, 0, ',', '.') }} baris</div>
                </div>
                @can('report-penduduk-excel')
                <a href="{{ route('report.penduduk.excel') }}" class="btn btn-info btn-block">
                    <i class="fas fa-file-excel mr-1"></i> Export Data Penduduk
                </a>
                @endcan
            </div>
        </div>

        <div class="report-card">
            <div class="card-icon icon-green"><i class="fas fa-comments"></i></div>
            <div class="card-body-custom">
                <span>Layanan Warga</span>
                <h2>Rekap Pengaduan</h2>
                <p>Export riwayat pengaduan warga beserta kategori, status, tanggapan operator, dan waktu pelaporan.</p>
                <div class="report-meta">
                    <div><i class="fas fa-table"></i> Excel</div>
                    <div><i class="fas fa-hourglass-half"></i> {{ number_format($totalPengaduanPending, 0, ',', '.') }} aktif</div>
                </div>
                @can('report-pengaduan-excel')
                <a href="{{ route('report.pengaduan.excel') }}" class="btn btn-success btn-block">
                    <i class="fas fa-file-excel mr-1"></i> Export Rekap Pengaduan
                </a>
                @endcan
            </div>
        </div>

        <form action="{{ route('report.surat.excel') }}" method="GET" class="report-card report-card-wide">
            <div class="card-icon icon-primary"><i class="fas fa-folder-open"></i></div>
            <div class="card-body-custom">
                <span>Transaksi Surat</span>
                <h2>Laporan Arsip Surat</h2>
                <p>Export arsip surat berdasarkan rentang tanggal. Kosongkan tanggal jika ingin mengunduh seluruh data surat.</p>

                <div class="date-grid">
                    <div class="form-group">
                        <label for="start_date">Dari Tanggal</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-calendar-alt"></i></span></div>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date', $defaultStartDate) }}" placeholder="Pilih tanggal awal">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="end_date">Sampai Tanggal</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-calendar-check"></i></span></div>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date', $defaultEndDate) }}" placeholder="Pilih tanggal akhir">
                        </div>
                    </div>
                </div>

                <div class="report-meta">
                    <div><i class="fas fa-file-excel"></i> Excel</div>
                    <div><i class="fas fa-file-pdf"></i> PDF maksimal 1.000 data</div>
                </div>

                <div class="button-grid">
                    @can('report-surat-excel')
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </button>
                    @endcan
                    @can('report-surat-pdf')
                    <button type="submit" formaction="{{ route('report.surat.pdf') }}" formtarget="_blank" class="btn btn-danger">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </button>
                    @endcan
                </div>
            </div>
        </form>
    </div>

    <div class="report-note">
        <i class="fas fa-shield-alt"></i>
        <div>
            <strong>Catatan export</strong>
            <span>Pastikan data sudah benar sebelum dibagikan. File laporan dapat memuat data pribadi warga, sehingga distribusinya tetap mengikuti kebutuhan administrasi resmi.</span>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .report-page { color: #1f2937; }
    .report-hero {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 1.3rem;
        border-radius: 16px;
        background: linear-gradient(135deg, #111827, #0f766e);
        color: #ffffff;
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.16);
    }
    .report-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .report-hero p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }
    .eyebrow,
    .report-card span {
        display: block;
        color: #0f766e;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .report-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 0.8rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-weight: 800;
        white-space: nowrap;
    }
    .report-metric-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .report-metric,
    .report-card,
    .report-note {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .report-metric {
        --metric-color: #2563eb;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 1rem;
    }
    .metric-icon,
    .card-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        background: var(--metric-color);
    }
    .metric-icon {
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        border-radius: 13px;
    }
    .report-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .report-metric strong {
        display: block;
        color: #111827;
        font-size: 1.6rem;
        font-weight: 800;
    }
    .report-metric small {
        color: #64748b;
        font-weight: 700;
    }
    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-cyan { --metric-color: #0891b2; }
    .report-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .report-card {
        display: flex;
        flex-direction: column;
        padding: 1rem;
    }
    .report-card-wide {
        grid-column: span 1;
    }
    .card-icon {
        width: 52px;
        height: 52px;
        margin-bottom: 0.9rem;
        border-radius: 14px;
        font-size: 1.35rem;
    }
    .icon-blue { --metric-color: #2563eb; }
    .icon-green { --metric-color: #059669; }
    .icon-primary { --metric-color: #0f766e; }
    .card-body-custom {
        display: flex;
        flex: 1;
        flex-direction: column;
    }
    .report-card h2 {
        margin: 0.12rem 0 0.45rem;
        color: #0f172a;
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .report-card p {
        color: #64748b;
        font-weight: 700;
    }
    .report-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin: auto 0 1rem;
    }
    .report-meta div {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.34rem 0.55rem;
        border-radius: 999px;
        color: #475569;
        background: #f1f5f9;
        font-size: 0.82rem;
        font-weight: 800;
    }
    .date-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
    }
    .report-card label {
        color: #334155;
        font-weight: 800;
    }
    .report-card .input-group-text {
        min-width: 42px;
        justify-content: center;
        border-color: #dbe3ef;
        color: #0f766e;
        background: #f8fafc;
    }
    .report-card .form-control {
        border-color: #dbe3ef;
        border-radius: 0 8px 8px 0;
    }
    .button-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.55rem;
    }
    .button-grid .btn,
    .report-card .btn-block {
        font-weight: 800;
    }
    .report-note {
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
        padding: 1rem;
    }
    .report-note i {
        color: #0f766e;
        font-size: 1.2rem;
        margin-top: 0.15rem;
    }
    .report-note strong {
        display: block;
        color: #0f172a;
    }
    .report-note span {
        color: #64748b;
        font-weight: 700;
    }
    @media (max-width: 1199.98px) {
        .report-metric-grid,
        .report-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .report-card-wide {
            grid-column: span 2;
        }
    }
    @media (max-width: 767.98px) {
        .report-hero {
            align-items: stretch;
            flex-direction: column;
        }
        .report-hero h1 { font-size: 1.5rem; }
        .hero-badge { justify-content: center; }
        .report-metric-grid,
        .report-grid,
        .date-grid,
        .button-grid {
            grid-template-columns: 1fr;
        }
        .report-card-wide {
            grid-column: span 1;
        }
    }
</style>
@endpush
