@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="archive-page">
    <div class="archive-hero">
        <div>
            <span class="eyebrow">Transaksi Surat</span>
            <h1>{{ $title }}</h1>
            <p>Pantau seluruh dokumen surat, status approval, kode tracking, QR verifikasi, dan kebutuhan layanan warga dari satu daftar arsip.</p>
        </div>
        <div class="hero-actions">
            @can('surat-create')
            <a href="{{ route('surat.create') }}" class="btn btn-light">
                <i class="fas fa-plus mr-1"></i> Buat Surat Baru
            </a>
            @endcan
        </div>
    </div>

    <div class="archive-metric-grid">
        <div class="archive-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-folder-open"></i></div>
            <div>
                <span>Total Arsip</span>
                <strong>{{ number_format($totalSurat, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="archive-metric metric-red">
            <div class="metric-icon"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <span>Menunggu/Proses</span>
                <strong>{{ number_format($totalMenunggu, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="archive-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-user-check"></i></div>
            <div>
                <span>Diverifikasi/Disetujui</span>
                <strong>{{ number_format($totalDisetujui, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="archive-metric metric-green">
            <div class="metric-icon"><i class="fas fa-check-double"></i></div>
            <div>
                <span>Selesai</span>
                <strong>{{ number_format($totalSelesai, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <div class="archive-panel">
        <div class="panel-heading">
            <div>
                <span>Daftar Data</span>
                <h2>Arsip Surat Terdaftar</h2>
            </div>
            <small><i class="fas fa-server mr-1"></i> Search, pagination, dan sorting memakai DataTables server-side.</small>
        </div>

        <div class="table-responsive">
            <table id="datatableSurat" class="table archive-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Surat</th>
                        <th>Tracking</th>
                        <th>Jenis Surat</th>
                        <th>Penduduk</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Keperluan</th>
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
    .archive-page { color: #1f2937; }
    .archive-hero {
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
    .archive-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .archive-hero p {
        max-width: 780px;
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
    .archive-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        justify-content: flex-end;
    }
    .archive-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .archive-metric,
    .archive-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .archive-metric {
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
    .archive-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .archive-metric strong {
        display: block;
        color: #111827;
        font-size: 1.6rem;
        font-weight: 800;
    }
    .metric-blue { --metric-color: #2563eb; }
    .metric-red { --metric-color: #dc2626; }
    .metric-cyan { --metric-color: #0891b2; }
    .metric-green { --metric-color: #059669; }
    .archive-panel { padding: 1rem; }
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
    .archive-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .archive-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
    }
    .archive-table small {
        display: block;
        color: #64748b;
    }
    .letter-number {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
    }
    .letter-number i {
        color: #0f766e;
        font-size: 1.15rem;
    }
    .letter-number strong,
    .archive-table td > strong {
        color: #0f172a;
    }
    .tracking-pill,
    .qr-pill,
    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        border-radius: 999px;
        font-weight: 800;
    }
    .tracking-pill {
        padding: 0.34rem 0.58rem;
        color: #075985;
        background: #e0f2fe;
    }
    .status-pill {
        min-width: 92px;
        padding: 0.33rem 0.62rem;
    }
    .qr-pill {
        margin-left: 0.35rem;
        padding: 0.28rem 0.48rem;
        color: #0f766e;
        background: #ccfbf1;
        font-size: 0.78rem;
    }
    .status-danger { color: #991b1b; background: #fee2e2; }
    .status-warning { color: #92400e; background: #fef3c7; }
    .status-info { color: #075985; background: #e0f2fe; }
    .status-primary { color: #1d4ed8; background: #dbeafe; }
    .status-success { color: #047857; background: #d1fae5; }
    .status-dark { color: #334155; background: #e2e8f0; }
    .status-secondary { color: #475569; background: #e2e8f0; }
    .purpose-text {
        display: inline-block;
        max-width: 320px;
        color: #475569;
        font-weight: 700;
        white-space: normal;
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
    .archive-panel .dataTables_filter input,
    .archive-panel .dataTables_length select {
        border-radius: 8px;
        border-color: #dbe3ef;
    }
    .archive-panel .dataTables_length label,
    .archive-panel .dataTables_filter label,
    .archive-panel .dataTables_info {
        color: #475569;
        font-weight: 700;
    }
    .archive-panel .pagination .page-link {
        border-radius: 8px;
        margin-left: 0.18rem;
    }
    @media (max-width: 1199.98px) {
        .archive-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767.98px) {
        .archive-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .archive-hero h1 { font-size: 1.5rem; }
        .hero-actions .btn { width: 100%; }
        .archive-metric-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
        $('#datatableSurat').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('surat.index') }}',
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
                { data: 'no_surat', name: 'no_surat' },
                { data: 'tracking', name: 'tracking_code' },
                { data: 'jenis_surat', name: 'jenis_surat' },
                { data: 'penduduk', name: 'penduduk' },
                { data: 'tanggal', name: 'tanggal_surat' },
                { data: 'status', name: 'status' },
                { data: 'keperluan', name: 'keperluan' },
                { data: 'aksi', name: 'aksi' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: [3, 4, 8] },
                { className: 'text-right', targets: 8 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat arsip surat...',
                search: 'Cari:',
                searchPlaceholder: 'Nomor, tracking, pemohon, jenis, status...',
                lengthMenu: 'Tampilkan _MENU_ arsip',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ arsip',
                infoEmpty: 'Belum ada arsip',
                infoFiltered: '(difilter dari _MAX_ total arsip)',
                zeroRecords: 'Arsip surat tidak ditemukan',
                emptyTable: 'Belum ada arsip surat',
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
