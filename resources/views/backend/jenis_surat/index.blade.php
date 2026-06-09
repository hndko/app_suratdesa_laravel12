@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="letter-type-page">
    <div class="letter-type-hero">
        <div>
            <span class="eyebrow">Master Data</span>
            <h1>Data Jenis Surat</h1>
            <p>Kelola kode, nama layanan, kop judul, dan template surat yang dipakai dalam proses pengajuan dan penerbitan dokumen desa.</p>
        </div>
        <div class="hero-actions">
            @can('jenis-surat-create')
            <a href="{{ route('jenis-surat.create') }}" class="btn btn-light">
                <i class="fas fa-plus mr-1"></i> Tambah Jenis Surat
            </a>
            @endcan
        </div>
    </div>

    <div class="letter-type-metric-grid">
        <div class="letter-type-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-envelope-open-text"></i></div>
            <div>
                <span>Total Jenis</span>
                <strong>{{ number_format($totalJenisSurat, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="letter-type-metric metric-green">
            <div class="metric-icon"><i class="fas fa-file-code"></i></div>
            <div>
                <span>Template Siap</span>
                <strong>{{ number_format($totalDenganTemplate, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="letter-type-metric metric-red">
            <div class="metric-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div>
                <span>Belum Template</span>
                <strong>{{ number_format($totalTanpaTemplate, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="letter-type-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-paper-plane"></i></div>
            <div>
                <span>Sudah Digunakan</span>
                <strong>{{ number_format($totalDigunakan, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <div class="letter-type-panel">
        <div class="panel-heading">
            <div>
                <span>Daftar Data</span>
                <h2>Jenis Surat Terdaftar</h2>
            </div>
            <small><i class="fas fa-server mr-1"></i> Search, pagination, dan sorting memakai DataTables server-side.</small>
        </div>

        <div class="table-responsive">
            <table id="datatableJenisSurat" class="table letter-type-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Surat</th>
                        <th>Kop Judul</th>
                        <th>Template</th>
                        <th>Dipakai</th>
                        <th>Update</th>
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
    .letter-type-page { color: #1f2937; }
    .letter-type-hero {
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
    .letter-type-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .letter-type-hero p {
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
    .letter-type-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        justify-content: flex-end;
    }
    .letter-type-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .letter-type-metric,
    .letter-type-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .letter-type-metric {
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
    .letter-type-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .letter-type-metric strong {
        display: block;
        color: #111827;
        font-size: 1.6rem;
        font-weight: 800;
    }
    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-red { --metric-color: #dc2626; }
    .metric-cyan { --metric-color: #0891b2; }
    .letter-type-panel { padding: 1rem; }
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
    .letter-type-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .letter-type-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
    }
    .letter-type-table small {
        display: block;
        color: #64748b;
    }
    .code-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.34rem 0.58rem;
        border-radius: 999px;
        color: #0f766e;
        background: #ccfbf1;
        font-weight: 800;
    }
    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        padding: 0.33rem 0.62rem;
        border-radius: 999px;
        font-weight: 800;
    }
    .status-success { color: #047857; background: #d1fae5; }
    .status-warning { color: #92400e; background: #fef3c7; }
    .usage-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 32px;
        border-radius: 10px;
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
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        padding: 0;
    }
    .letter-type-panel .dataTables_filter input,
    .letter-type-panel .dataTables_length select {
        border-radius: 8px;
        border-color: #dbe3ef;
    }
    .letter-type-panel .dataTables_length label,
    .letter-type-panel .dataTables_filter label,
    .letter-type-panel .dataTables_info {
        color: #475569;
        font-weight: 700;
    }
    .letter-type-panel .pagination .page-link {
        border-radius: 8px;
        margin-left: 0.18rem;
    }
    @media (max-width: 1199.98px) {
        .letter-type-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767.98px) {
        .letter-type-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .letter-type-hero h1 { font-size: 1.5rem; }
        .hero-actions .btn { width: 100%; }
        .letter-type-metric-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
        $('#datatableJenisSurat').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('jenis-surat.index') }}',
            scrollX: true,
            lengthChange: true,
            searching: true,
            paging: true,
            info: true,
            autoWidth: false,
            deferRender: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[6, 'desc']],
            columns: [
                { data: 'no', name: 'id' },
                { data: 'kode_surat', name: 'kode_surat' },
                { data: 'nama_surat', name: 'nama_surat' },
                { data: 'kop_judul', name: 'kop_judul' },
                { data: 'template', name: 'template' },
                { data: 'digunakan', name: 'digunakan' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'aksi', name: 'aksi' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: [4, 5, 7] },
                { className: 'text-right', targets: 7 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat data jenis surat...',
                search: 'Cari:',
                searchPlaceholder: 'Kode, nama, atau kop judul...',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Belum ada data',
                infoFiltered: '(difilter dari _MAX_ total data)',
                zeroRecords: 'Jenis surat tidak ditemukan',
                emptyTable: 'Belum ada jenis surat',
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
