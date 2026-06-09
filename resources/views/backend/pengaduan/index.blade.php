@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="complaint-page">
    <div class="complaint-hero">
        <div>
            <span class="eyebrow">Layanan Warga</span>
            <h1>{{ $title }}</h1>
            <p>Pantau laporan warga, status penanganan, kategori masalah, lampiran, dan rekomendasi AI untuk membantu operator merespons lebih cepat.</p>
        </div>
        @can('report-pengaduan-excel')
        <a href="{{ route('report.pengaduan.excel') }}" class="btn btn-outline-light">
            <i class="fas fa-file-excel mr-1"></i> Export Excel
        </a>
        @endcan
    </div>

    <div class="complaint-metric-grid">
        <div class="complaint-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-comments"></i></div>
            <div>
                <span>Total Pengaduan</span>
                <strong>{{ number_format($totalPengaduan, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="complaint-metric metric-red">
            <div class="metric-icon"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <span>Pending</span>
                <strong>{{ number_format($totalPending, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="complaint-metric metric-yellow">
            <div class="metric-icon"><i class="fas fa-tools"></i></div>
            <div>
                <span>Diproses</span>
                <strong>{{ number_format($totalProcess, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="complaint-metric metric-green">
            <div class="metric-icon"><i class="fas fa-check-double"></i></div>
            <div>
                <span>Selesai</span>
                <strong>{{ number_format($totalResolved, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <div class="complaint-panel">
        <div class="panel-heading">
            <div>
                <span>Daftar Data</span>
                <h2>Pengaduan Masuk</h2>
            </div>
            <small><i class="fas fa-server mr-1"></i> Search, pagination, dan sorting memakai DataTables server-side.</small>
        </div>

        <div class="table-responsive">
            <table id="datatablePengaduan" class="table complaint-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Tiket</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Isi Laporan</th>
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
    .complaint-page { color: #1f2937; }
    .complaint-hero {
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
    .complaint-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .complaint-hero p {
        max-width: 790px;
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
    .complaint-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .complaint-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .complaint-metric,
    .complaint-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .complaint-metric {
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
    .complaint-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .complaint-metric strong {
        display: block;
        color: #111827;
        font-size: 1.6rem;
        font-weight: 800;
    }
    .metric-blue { --metric-color: #2563eb; }
    .metric-red { --metric-color: #dc2626; }
    .metric-yellow { --metric-color: #d97706; }
    .metric-green { --metric-color: #059669; }
    .complaint-panel { padding: 1rem; }
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
    .complaint-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .complaint-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
    }
    .complaint-table small {
        display: block;
        color: #64748b;
    }
    .ticket-pill,
    .category-pill,
    .status-pill,
    .ai-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        border-radius: 999px;
        font-weight: 800;
    }
    .ticket-pill {
        padding: 0.34rem 0.58rem;
        color: #075985;
        background: #e0f2fe;
    }
    .category-pill {
        padding: 0.34rem 0.58rem;
        color: #0f766e;
        background: #ccfbf1;
    }
    .status-pill {
        min-width: 92px;
        padding: 0.33rem 0.62rem;
    }
    .ai-pill {
        margin-left: 0.35rem;
        padding: 0.28rem 0.48rem;
        color: #6d28d9;
        background: #ede9fe;
        font-size: 0.78rem;
    }
    .status-danger { color: #991b1b; background: #fee2e2; }
    .status-warning { color: #92400e; background: #fef3c7; }
    .status-success { color: #047857; background: #d1fae5; }
    .status-secondary { color: #475569; background: #e2e8f0; }
    .complaint-text {
        display: inline-block;
        max-width: 340px;
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
    .complaint-panel .dataTables_filter input,
    .complaint-panel .dataTables_length select {
        border-radius: 8px;
        border-color: #dbe3ef;
    }
    .complaint-panel .dataTables_length label,
    .complaint-panel .dataTables_filter label,
    .complaint-panel .dataTables_info {
        color: #475569;
        font-weight: 700;
    }
    .complaint-panel .pagination .page-link {
        border-radius: 8px;
        margin-left: 0.18rem;
    }
    @media (max-width: 1199.98px) {
        .complaint-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767.98px) {
        .complaint-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .complaint-hero h1 { font-size: 1.5rem; }
        .complaint-hero .btn { width: 100%; }
        .complaint-metric-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
        $('#datatablePengaduan').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('pengaduan.index') }}',
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
                { data: 'ticket_code', name: 'ticket_code' },
                { data: 'pelapor', name: 'name' },
                { data: 'category', name: 'category' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'content', name: 'content' },
                { data: 'aksi', name: 'aksi' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: [7] },
                { className: 'text-right', targets: 7 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat pengaduan...',
                search: 'Cari:',
                searchPlaceholder: 'Tiket, pelapor, NIK, kategori, status...',
                lengthMenu: 'Tampilkan _MENU_ pengaduan',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ pengaduan',
                infoEmpty: 'Belum ada pengaduan',
                infoFiltered: '(difilter dari _MAX_ total pengaduan)',
                zeroRecords: 'Pengaduan tidak ditemukan',
                emptyTable: 'Belum ada pengaduan',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });

        $(document).on('submit', 'form.js-pengaduan-confirm', function (event) {
            event.preventDefault();
            var form = this;

            Swal.fire({
                title: 'Konfirmasi',
                text: form.dataset.confirmText || 'Apakah Anda yakin ingin melanjutkan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger mx-1',
                    cancelButton: 'btn btn-secondary mx-1'
                }
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
