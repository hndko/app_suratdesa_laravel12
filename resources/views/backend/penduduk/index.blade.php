@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="resident-page">
    <div class="resident-hero">
        <div>
            <span class="eyebrow">Master Data</span>
            <h1>{{ $title }}</h1>
            <p>Kelola identitas penduduk, relasi Kartu Keluarga, kontak, alamat, dan data administrasi untuk layanan surat desa.</p>
        </div>
        <div class="hero-actions">
            @can('penduduk-create')
            <a href="{{ route('penduduk.create') }}" class="btn btn-light">
                <i class="fas fa-user-plus mr-1"></i> Tambah Penduduk
            </a>
            @endcan
            @can('import-penduduk-index')
            <a href="{{ route('import-penduduk.index') }}" class="btn btn-outline-light">
                <i class="fas fa-file-import mr-1"></i> Import Excel
            </a>
            @endcan
        </div>
    </div>

    <div class="resident-metric-grid">
        <div class="resident-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-users"></i></div>
            <div>
                <span>Total Penduduk</span>
                <strong>{{ number_format($totalPenduduk, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="resident-metric metric-green">
            <div class="metric-icon"><i class="fas fa-address-card"></i></div>
            <div>
                <span>Terhubung KK</span>
                <strong>{{ number_format($totalKkTerhubung, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="resident-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-mars"></i></div>
            <div>
                <span>Laki-laki</span>
                <strong>{{ number_format($totalLakiLaki, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="resident-metric metric-pink">
            <div class="metric-icon"><i class="fas fa-venus"></i></div>
            <div>
                <span>Perempuan</span>
                <strong>{{ number_format($totalPerempuan, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <div class="resident-card">
        <div class="resident-card-heading">
            <div>
                <span>Daftar Data</span>
                <h2>Penduduk Terdaftar</h2>
            </div>
            <small class="table-hint"><i class="fas fa-server mr-1"></i> Search, pagination, dan sorting memakai DataTables server-side.</small>
        </div>

        <div class="resident-table-wrap">
            <table id="datatablePenduduk" class="table resident-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>No. KK</th>
                        <th>L/P</th>
                        <th>TTL</th>
                        <th>Alamat</th>
                        <th>Pekerjaan</th>
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
    .resident-page {
        color: #1f2937;
    }

    .resident-hero {
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

    .resident-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }

    .resident-hero p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }

    .eyebrow,
    .resident-card-heading span {
        display: block;
        color: #0f766e;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .resident-hero .eyebrow {
        color: rgba(255, 255, 255, 0.72);
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        justify-content: flex-end;
    }

    .resident-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .resident-metric,
    .resident-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }

    .resident-metric {
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

    .resident-metric span {
        color: #6b7280;
        font-weight: 700;
    }

    .resident-metric strong {
        display: block;
        font-size: 1.6rem;
        font-weight: 800;
        color: #111827;
    }

    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-cyan { --metric-color: #0891b2; }
    .metric-pink { --metric-color: #db2777; }

    .resident-card {
        padding: 1rem;
    }

    .resident-card-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .resident-card-heading h2 {
        margin: 0.12rem 0 0;
        font-size: 1.08rem;
        font-weight: 800;
        letter-spacing: 0;
    }

    .table-hint {
        color: #64748b;
        font-weight: 700;
    }

    .resident-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .resident-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .resident-table tbody tr {
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.24s ease, transform 0.24s ease;
    }

    .resident-table tbody tr.is-visible {
        opacity: 1;
        transform: none;
    }

    .resident-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
    }

    .resident-table small {
        display: block;
        color: #64748b;
    }

    .resident-id {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        font-weight: 800;
        color: #0f172a;
    }

    .resident-id i {
        color: #0f766e;
    }

    .gender-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        padding: 0.34rem 0.62rem;
        border-radius: 999px;
        font-weight: 800;
    }

    .gender-L {
        color: #075985;
        background: #e0f2fe;
    }

    .gender-P {
        color: #9d174d;
        background: #fce7f3;
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

    .resident-card .dataTables_wrapper .row:first-child {
        align-items: center;
        margin-bottom: 0.8rem;
    }

    .resident-card .dataTables_length label,
    .resident-card .dataTables_filter label {
        color: #475569;
        font-weight: 700;
    }

    .resident-card .dataTables_filter input,
    .resident-card .dataTables_length select {
        border-radius: 8px;
        border-color: #dbe3ef;
    }

    .resident-card .dataTables_info {
        color: #64748b;
        font-weight: 700;
    }

    .resident-card .pagination .page-link {
        border-radius: 8px;
        margin-left: 0.18rem;
    }

    @media (max-width: 1199.98px) {
        .resident-metric-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .resident-hero,
        .resident-card-heading {
            align-items: stretch;
            flex-direction: column;
        }

        .resident-hero h1 {
            font-size: 1.5rem;
        }

        .hero-actions .btn {
            flex: 1 1 150px;
        }

        .resident-metric-grid {
            grid-template-columns: 1fr;
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
        var table = $('#datatablePenduduk').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('penduduk.index') }}',
            responsive: false,
            scrollX: true,
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
                { data: 'nik', name: 'nik' },
                { data: 'nama', name: 'nama' },
                { data: 'no_kk', name: 'kartu_keluarga_id' },
                { data: 'jenis_kelamin', name: 'jenis_kelamin' },
                { data: 'ttl', name: 'tgl_lahir' },
                { data: 'alamat', name: 'alamat' },
                { data: 'pekerjaan', name: 'pekerjaan' },
                { data: 'aksi', name: 'aksi' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: 8 },
                { className: 'text-right', targets: 8 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat data...',
                search: 'Cari:',
                searchPlaceholder: 'NIK, nama, KK, alamat...',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Belum ada data',
                infoFiltered: '(difilter dari _MAX_ total data)',
                zeroRecords: 'Data Penduduk tidak ditemukan',
                emptyTable: 'Belum ada data Penduduk',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            },
            drawCallback: function () {
                $('#datatablePenduduk tbody tr').addClass('is-visible');
            }
        });

        table.rows({ page: 'current' }).nodes().to$().addClass('is-visible');
    });
</script>
@endpush
