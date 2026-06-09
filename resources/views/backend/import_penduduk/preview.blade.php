@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="import-preview-page">
    <div class="preview-hero">
        <div>
            <span class="eyebrow">Preview Import</span>
            <h1>{{ $title }}</h1>
            <p>{{ $batch->file_name }}</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('import-penduduk.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            @if($batch->status === 'preview' && $batch->invalid_rows === 0)
                @can('import-penduduk-process')
                <form action="{{ route('import-penduduk.process', $batch) }}" method="POST" class="d-inline js-confirm-submit" data-confirm-text="Proses import valid ini sekarang?">
                    @csrf
                    <button class="btn btn-light">
                        <i class="fas fa-check mr-1"></i> Proses Import
                    </button>
                </form>
                @endcan
            @endif
        </div>
    </div>

    <div class="preview-metric-grid">
        <div class="preview-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-table"></i></div>
            <div>
                <span>Total Baris</span>
                <strong>{{ number_format($batch->total_rows, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="preview-metric metric-green">
            <div class="metric-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <span>Valid</span>
                <strong>{{ number_format($batch->valid_rows, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="preview-metric metric-red">
            <div class="metric-icon"><i class="fas fa-times-circle"></i></div>
            <div>
                <span>Invalid</span>
                <strong>{{ number_format($batch->invalid_rows, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="preview-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-database"></i></div>
            <div>
                <span>Diproses</span>
                <strong>{{ number_format($batch->processed_rows, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    @if($batch->invalid_rows > 0)
    <div class="notice-box notice-danger">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Import belum bisa diproses.</strong>
            <span>Masih ada baris invalid. Perbaiki file sumber, lalu upload ulang agar data tetap bersih.</span>
        </div>
    </div>
    @elseif($batch->status === 'preview')
    <div class="notice-box notice-success">
        <i class="fas fa-check-circle"></i>
        <div>
            <strong>Semua baris valid.</strong>
            <span>Data siap diproses ke master Penduduk dan Kartu Keluarga.</span>
        </div>
    </div>
    @endif

    <div class="preview-panel">
        <div class="panel-heading">
            <div>
                <span>Detail Baris</span>
                <h2>Hasil Validasi File</h2>
            </div>
            <small><i class="fas fa-server mr-1"></i> Search, pagination, dan sorting memakai DataTables server-side.</small>
        </div>

        <div class="table-responsive">
            <table id="datatableImportRows" class="table preview-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Baris</th>
                        <th>NIK / Nama</th>
                        <th>No. KK</th>
                        <th>Status</th>
                        <th>Error</th>
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
    .import-preview-page { color: #1f2937; }
    .preview-hero {
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
    .preview-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .preview-hero p {
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
        font-weight: 700;
    }
    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.55rem;
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
    .preview-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .preview-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .preview-metric,
    .preview-panel,
    .notice-box {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .preview-metric {
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
    .preview-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .preview-metric strong {
        display: block;
        color: #111827;
        font-size: 1.6rem;
        font-weight: 800;
    }
    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-red { --metric-color: #dc2626; }
    .metric-cyan { --metric-color: #0891b2; }
    .notice-box {
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
        margin-bottom: 1rem;
        padding: 1rem;
    }
    .notice-box i {
        margin-top: 0.15rem;
        font-size: 1.2rem;
    }
    .notice-box strong {
        display: block;
        color: #0f172a;
    }
    .notice-box span {
        color: #64748b;
        font-weight: 700;
    }
    .notice-danger i { color: #dc2626; }
    .notice-success i { color: #059669; }
    .preview-panel { padding: 1rem; }
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
    .preview-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .preview-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
    }
    .preview-table small {
        display: block;
        color: #64748b;
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
    .status-info { color: #075985; background: #e0f2fe; }
    .status-danger { color: #991b1b; background: #fee2e2; }
    .status-secondary { color: #475569; background: #e2e8f0; }
    .error-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }
    .error-list span {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.45rem;
        border-radius: 999px;
        color: #991b1b;
        background: #fee2e2;
        font-size: 0.82rem;
        font-weight: 700;
    }
    .preview-panel .dataTables_filter input,
    .preview-panel .dataTables_length select {
        border-radius: 8px;
        border-color: #dbe3ef;
    }
    .preview-panel .dataTables_length label,
    .preview-panel .dataTables_filter label,
    .preview-panel .dataTables_info {
        color: #475569;
        font-weight: 700;
    }
    .preview-panel .pagination .page-link {
        border-radius: 8px;
        margin-left: 0.18rem;
    }
    @media (max-width: 1199.98px) {
        .preview-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767.98px) {
        .preview-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .preview-hero h1 { font-size: 1.5rem; }
        .hero-actions .btn { width: 100%; }
        .preview-metric-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
        $('#datatableImportRows').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('import-penduduk.preview', $batch) }}',
            scrollX: true,
            lengthChange: true,
            searching: true,
            paging: true,
            info: true,
            autoWidth: false,
            deferRender: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[1, 'asc']],
            columns: [
                { data: 'no', name: 'no' },
                { data: 'row_number', name: 'row_number' },
                { data: 'nik', name: 'nik' },
                { data: 'no_kk', name: 'no_kk' },
                { data: 'status', name: 'status' },
                { data: 'error', name: 'error' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: [0, 2, 3, 5] }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat hasil validasi...',
                search: 'Cari:',
                searchPlaceholder: 'NIK, nama, KK, status...',
                lengthMenu: 'Tampilkan _MENU_ baris',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ baris',
                infoEmpty: 'Belum ada baris',
                infoFiltered: '(difilter dari _MAX_ total baris)',
                zeroRecords: 'Baris import tidak ditemukan',
                emptyTable: 'Belum ada baris import',
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
