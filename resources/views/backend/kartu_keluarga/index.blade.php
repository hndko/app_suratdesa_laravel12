@extends('layouts.app-backend')

@section('content')
<div class="kk-page">
    <div class="kk-hero">
        <div>
            <span class="eyebrow">Master Data</span>
            <h1>{{ $title }}</h1>
            <p>Kelola nomor kartu keluarga, kepala keluarga, wilayah domisili, dan jumlah anggota dalam satu tampilan yang mudah dipindai.</p>
        </div>
        <div class="hero-actions">
            @can('kartu-keluarga-create')
            <a href="{{ route('kartu-keluarga.create') }}" class="btn btn-light">
                <i class="fas fa-plus-circle mr-1"></i> Tambah KK
            </a>
            @endcan
            @can('penduduk-index')
            <a href="{{ route('penduduk.index') }}" class="btn btn-outline-light">
                <i class="fas fa-users mr-1"></i> Data Penduduk
            </a>
            @endcan
        </div>
    </div>

    <div class="kk-metric-grid">
        <div class="kk-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-address-card"></i></div>
            <div>
                <span>Total KK</span>
                <strong>{{ number_format($totalKartuKeluarga, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="kk-metric metric-green">
            <div class="metric-icon"><i class="fas fa-users"></i></div>
            <div>
                <span>Anggota Terhubung</span>
                <strong>{{ number_format($totalAnggota, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="kk-metric metric-orange">
            <div class="metric-icon"><i class="fas fa-chart-line"></i></div>
            <div>
                <span>Rata-rata Anggota</span>
                <strong>{{ number_format($rataRataAnggota, 1, ',', '.') }}</strong>
            </div>
        </div>
        <div class="kk-metric metric-red">
            <div class="metric-icon"><i class="fas fa-user-slash"></i></div>
            <div>
                <span>KK Tanpa Anggota</span>
                <strong>{{ number_format($kkKosong, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <div class="kk-card">
        <div class="kk-card-heading">
            <div>
                <span>Daftar Data</span>
                <h2>Kartu Keluarga</h2>
            </div>
            <form action="{{ route('kartu-keluarga.index') }}" method="GET" class="kk-search">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" name="q" class="form-control" value="{{ $q ?? '' }}" placeholder="Cari nomor KK atau kepala keluarga">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search mr-1"></i> Cari
                        </button>
                        @if(!empty($q))
                        <a href="{{ route('kartu-keluarga.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times mr-1"></i> Reset
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="kk-table-wrap">
            <table class="table kk-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor KK</th>
                        <th>Kepala Keluarga</th>
                        <th>Domisili</th>
                        <th>Anggota</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kartuKeluargas as $row)
                    <tr class="js-lazy-row">
                        <td data-label="No">{{ $kartuKeluargas->firstItem() + $loop->index }}</td>
                        <td data-label="Nomor KK">
                            <div class="kk-number">
                                <i class="fas fa-id-card"></i>
                                <span>{{ $row->no_kk }}</span>
                            </div>
                        </td>
                        <td data-label="Kepala Keluarga">
                            <strong>{{ $row->kepala_keluarga }}</strong>
                            <small>{{ $row->desa ?: 'Desa belum diisi' }}</small>
                        </td>
                        <td data-label="Domisili">
                            <span>{{ $row->alamat }}</span>
                            <small>RT {{ $row->rt }} / RW {{ $row->rw }}{{ $row->kecamatan ? ' - ' . $row->kecamatan : '' }}</small>
                        </td>
                        <td data-label="Anggota">
                            <span class="member-badge">{{ number_format($row->penduduks_count, 0, ',', '.') }} orang</span>
                        </td>
                        <td data-label="Aksi" class="text-right">
                            <div class="action-group">
                                @can('kartu-keluarga-show')
                                <a href="{{ route('kartu-keluarga.show', $row->id) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                                @can('kartu-keluarga-edit')
                                <a href="{{ route('kartu-keluarga.edit', $row->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('kartu-keluarga-destroy')
                                <form action="{{ route('kartu-keluarga.destroy', $row->id) }}" method="POST" class="d-inline js-confirm-submit"
                                    data-confirm-text="Yakin ingin menghapus Kartu Keluarga {{ $row->no_kk }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <strong>Belum ada data Kartu Keluarga.</strong>
                                <span>Tambahkan data KK untuk mulai menghubungkan data penduduk.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="kk-pagination">{{ $kartuKeluargas->links() }}</div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .kk-page {
        color: #1f2937;
    }

    .kk-hero {
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

    .kk-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }

    .kk-hero p {
        max-width: 720px;
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }

    .eyebrow,
    .kk-card-heading span {
        display: block;
        color: #0f766e;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .kk-hero .eyebrow {
        color: rgba(255, 255, 255, 0.72);
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        justify-content: flex-end;
    }

    .kk-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .kk-metric,
    .kk-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }

    .kk-metric {
        --metric-color: #2563eb;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 1rem;
    }

    .metric-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        border-radius: 13px;
        color: #ffffff;
        background: var(--metric-color);
    }

    .kk-metric span {
        color: #6b7280;
        font-weight: 700;
    }

    .kk-metric strong {
        display: block;
        font-size: 1.6rem;
        font-weight: 800;
        color: #111827;
    }

    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-orange { --metric-color: #ea580c; }
    .metric-red { --metric-color: #dc2626; }

    .kk-card {
        padding: 1rem;
    }

    .kk-card-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .kk-card-heading h2 {
        margin: 0.12rem 0 0;
        font-size: 1.08rem;
        font-weight: 800;
        letter-spacing: 0;
    }

    .kk-search {
        width: min(100%, 620px);
    }

    .kk-search .input-group-text,
    .kk-search .form-control,
    .kk-search .btn {
        min-height: 42px;
    }

    .kk-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .kk-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0 0.55rem;
    }

    .kk-table thead th {
        border: 0;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .kk-table tbody tr {
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.24s ease, transform 0.24s ease;
    }

    .kk-table tbody tr.is-visible,
    .kk-table tbody tr:not(.js-lazy-row) {
        opacity: 1;
        transform: none;
    }

    .kk-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
        border-bottom: 1px solid #eef2f7;
        background: #ffffff;
    }

    .kk-table tbody td:first-child {
        border-left: 1px solid #eef2f7;
        border-radius: 12px 0 0 12px;
    }

    .kk-table tbody td:last-child {
        border-right: 1px solid #eef2f7;
        border-radius: 0 12px 12px 0;
    }

    .kk-number {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        font-weight: 800;
        color: #0f172a;
    }

    .kk-number i {
        color: #0f766e;
    }

    .kk-table small {
        display: block;
        color: #64748b;
    }

    .member-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 72px;
        padding: 0.34rem 0.62rem;
        border-radius: 999px;
        color: #075985;
        background: #e0f2fe;
        font-weight: 800;
    }

    .action-group {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .action-group .btn {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.35rem;
        padding: 2rem 1rem;
        color: #64748b;
        text-align: center;
    }

    .empty-state i {
        font-size: 2rem;
        color: #0f766e;
    }

    .empty-state strong {
        color: #111827;
    }

    .kk-pagination {
        margin-top: 1rem;
    }

    @media (max-width: 1199.98px) {
        .kk-metric-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .kk-hero,
        .kk-card-heading {
            flex-direction: column;
            align-items: stretch;
        }

        .kk-hero h1 {
            font-size: 1.5rem;
        }

        .hero-actions .btn {
            flex: 1 1 150px;
        }

        .kk-metric-grid {
            grid-template-columns: 1fr;
        }

        .kk-search .input-group {
            display: block;
        }

        .kk-search .input-group-prepend,
        .kk-search .input-group-append,
        .kk-search .form-control,
        .kk-search .btn {
            display: flex;
            width: 100%;
            border-radius: 8px !important;
            margin-bottom: 0.45rem;
        }

        .kk-table thead {
            display: none;
        }

        .kk-table,
        .kk-table tbody,
        .kk-table tr,
        .kk-table td {
            display: block;
            width: 100%;
        }

        .kk-table {
            border-spacing: 0;
        }

        .kk-table tbody tr {
            margin-bottom: 0.85rem;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            background: #ffffff;
        }

        .kk-table tbody td {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            border: 0;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 0 !important;
            text-align: right !important;
        }

        .kk-table tbody td::before {
            content: attr(data-label);
            color: #64748b;
            font-weight: 800;
            text-align: left;
        }

        .kk-table tbody td:last-child {
            border-bottom: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rows = document.querySelectorAll('.js-lazy-row');

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '80px 0px' });

            rows.forEach(function (row) {
                observer.observe(row);
            });
            return;
        }

        rows.forEach(function (row) {
            row.classList.add('is-visible');
        });
    });
</script>
@endpush
