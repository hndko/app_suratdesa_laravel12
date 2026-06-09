@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

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
            <small class="table-hint"><i class="fas fa-server mr-1"></i> Search, pagination, dan sorting memakai DataTables server-side.</small>
        </div>

        <div class="kk-table-wrap">
            <table id="datatableKartuKeluarga" class="table kk-table table-hover nowrap" style="width: 100%;">
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
                <tbody></tbody>
            </table>
        </div>
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

    .table-hint {
        color: #64748b;
        font-weight: 700;
    }

    .kk-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .kk-table {
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .kk-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
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
        background: #ffffff;
    }

    .kk-table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control::before,
    .kk-table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control::before {
        top: 50%;
        background-color: #0f766e;
        border: 0;
        box-shadow: none;
        transform: translateY(-50%);
    }

    .kk-card .dataTables_wrapper .row:first-child {
        align-items: center;
        margin-bottom: 0.8rem;
    }

    .kk-card .dataTables_length label,
    .kk-card .dataTables_filter label {
        color: #475569;
        font-weight: 700;
    }

    .kk-card .dataTables_filter input,
    .kk-card .dataTables_length select {
        border-radius: 8px;
        border-color: #dbe3ef;
    }

    .kk-card .dataTables_info {
        color: #64748b;
        font-weight: 700;
    }

    .kk-card .pagination .page-link {
        border-radius: 8px;
        margin-left: 0.18rem;
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

        .kk-card .dataTables_wrapper .row:first-child > div,
        .kk-card .dataTables_wrapper .row:last-child > div {
            margin-bottom: 0.6rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
        var table = $('#datatableKartuKeluarga').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('kartu-keluarga.index') }}',
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            info: true,
            autoWidth: false,
            deferRender: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[0, 'asc']],
            columns: [
                { data: 'no', name: 'id' },
                { data: 'no_kk', name: 'no_kk' },
                { data: 'kepala_keluarga', name: 'kepala_keluarga' },
                { data: 'domisili', name: 'alamat' },
                { data: 'anggota', name: 'penduduks_count' },
                { data: 'aksi', name: 'aksi' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: 5 },
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: 2 },
                { responsivePriority: 3, targets: 5 },
                { className: 'text-right', targets: 5 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat data...',
                search: 'Cari:',
                searchPlaceholder: 'Nomor KK, kepala keluarga, alamat...',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Belum ada data',
                infoFiltered: '(difilter dari _MAX_ total data)',
                zeroRecords: 'Data Kartu Keluarga tidak ditemukan',
                emptyTable: 'Belum ada data Kartu Keluarga',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            },
            drawCallback: function () {
                $('#datatableKartuKeluarga tbody tr').addClass('is-visible');
            }
        });

        table.rows({ page: 'current' }).nodes().to$().addClass('is-visible');
    });
</script>
@endpush
