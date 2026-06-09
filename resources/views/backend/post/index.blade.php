@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="post-page">
    <div class="post-hero">
        <div>
            <span class="eyebrow">Informasi Desa</span>
            <h1>{{ $title }}</h1>
            <p>Kelola informasi publik, pengumuman kegiatan, dan berita singkat yang tampil pada halaman frontend SIMADES.</p>
        </div>
        <div class="hero-actions">
            @can('post-create')
            <a href="{{ route('post.create') }}" class="btn btn-light">
                <i class="fas fa-plus mr-1"></i> Buat Pengumuman
            </a>
            @endcan
        </div>
    </div>

    <div class="post-metric-grid">
        <div class="post-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-bullhorn"></i></div>
            <div>
                <span>Total Pengumuman</span>
                <strong>{{ number_format($totalPost, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="post-metric metric-green">
            <div class="metric-icon"><i class="fas fa-globe"></i></div>
            <div>
                <span>Published</span>
                <strong>{{ number_format($totalPublished, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="post-metric metric-gray">
            <div class="metric-icon"><i class="fas fa-pencil-alt"></i></div>
            <div>
                <span>Draft</span>
                <strong>{{ number_format($totalDraft, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="post-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-image"></i></div>
            <div>
                <span>Dengan Gambar</span>
                <strong>{{ number_format($totalWithImage, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <div class="post-panel">
        <div class="panel-heading">
            <div>
                <span>Daftar Data</span>
                <h2>Pengumuman Terdaftar</h2>
            </div>
            <small><i class="fas fa-server mr-1"></i> Search, pagination, dan sorting memakai DataTables server-side.</small>
        </div>

        <div class="table-responsive">
            <table id="datatablePost" class="table post-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .post-page { color: #1f2937; }
    .post-hero {
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
    .post-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .post-hero p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }
    .eyebrow,
    .panel-heading span {
        display: block;
        color: #0f766e;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .post-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .post-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .post-metric,
    .post-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .post-metric {
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
    .post-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .post-metric strong {
        display: block;
        color: #111827;
        font-size: 1.6rem;
        font-weight: 800;
    }
    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-gray { --metric-color: #64748b; }
    .metric-cyan { --metric-color: #0891b2; }
    .post-panel { padding: 1rem; }
    .panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .panel-heading h2 {
        margin: 0.12rem 0 0;
        font-size: 1.08rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .panel-heading small {
        color: #64748b;
        font-weight: 700;
    }
    .post-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .post-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
    }
    .post-table small {
        display: block;
        color: #64748b;
    }
    .post-thumb,
    .post-thumb-placeholder {
        width: 86px;
        height: 58px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
    }
    .post-thumb-placeholder {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }
    .author-pill,
    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        border-radius: 999px;
        font-weight: 800;
    }
    .author-pill {
        padding: 0.34rem 0.58rem;
        color: #075985;
        background: #e0f2fe;
    }
    .status-pill {
        min-width: 92px;
        padding: 0.33rem 0.62rem;
    }
    .status-success { color: #047857; background: #d1fae5; }
    .status-secondary { color: #475569; background: #e2e8f0; }
    .action-group {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .action-group .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        padding: 0;
    }
    .post-panel .dataTables_filter input,
    .post-panel .dataTables_length select {
        border-radius: 8px;
        border-color: #dbe3ef;
    }
    .post-panel .dataTables_length label,
    .post-panel .dataTables_filter label,
    .post-panel .dataTables_info {
        color: #475569;
        font-weight: 700;
    }
    .post-panel .pagination .page-link {
        border-radius: 8px;
        margin-left: 0.18rem;
    }
    @media (max-width: 1199.98px) {
        .post-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767.98px) {
        .post-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .post-hero h1 { font-size: 1.5rem; }
        .hero-actions .btn { width: 100%; }
        .post-metric-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
        $('#datatablePost').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('post.index') }}',
            scrollX: true,
            lengthChange: true,
            searching: true,
            paging: true,
            info: true,
            autoWidth: false,
            deferRender: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[5, 'desc']],
            columns: [
                { data: 'no', name: 'id' },
                { data: 'image', name: 'image' },
                { data: 'title', name: 'title' },
                { data: 'author', name: 'author' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'aksi', name: 'aksi' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: [1, 3, 6] },
                { className: 'text-right', targets: 6 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat pengumuman...',
                search: 'Cari:',
                searchPlaceholder: 'Judul, isi, penulis, status...',
                lengthMenu: 'Tampilkan _MENU_ pengumuman',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ pengumuman',
                infoEmpty: 'Belum ada pengumuman',
                infoFiltered: '(difilter dari _MAX_ total pengumuman)',
                zeroRecords: 'Pengumuman tidak ditemukan',
                emptyTable: 'Belum ada pengumuman',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });
    });
</script>
@endpush
