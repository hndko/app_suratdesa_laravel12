@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="ai-provider-page">
    <div class="ai-provider-hero">
        <div>
            <span class="eyebrow">AI Gateway</span>
            <h1>{{ $title }}</h1>
            <p>Kelola provider AI internal SIMADES, pilih provider utama, siapkan fallback, dan uji koneksi tanpa menampilkan API key.</p>
        </div>
        <div class="hero-actions">
            @can('ai-log-index')
            <a href="{{ route('ai-logs.index') }}" class="btn btn-outline-light">
                <i class="fas fa-clipboard-list mr-1"></i> AI Logs
            </a>
            @endcan
            @can('ai-setting-create')
            <a href="{{ route('ai-settings.create') }}" class="btn btn-light">
                <i class="fas fa-plus mr-1"></i> Tambah Provider
            </a>
            @endcan
        </div>
    </div>

    <div class="ai-provider-metric-grid">
        <div class="ai-provider-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-network-wired"></i></div>
            <div>
                <span>Total Provider</span>
                <strong>{{ number_format($totalProviders, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="ai-provider-metric metric-green">
            <div class="metric-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <span>Provider Aktif</span>
                <strong>{{ number_format($totalActive, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="ai-provider-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-life-ring"></i></div>
            <div>
                <span>Fallback</span>
                <strong>{{ number_format($totalFallback, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="ai-provider-metric metric-purple">
            <div class="metric-icon"><i class="fas fa-layer-group"></i></div>
            <div>
                <span>Tipe Provider</span>
                <strong>{{ number_format($totalTypes, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <div class="ai-provider-panel">
        <div class="panel-heading">
            <div>
                <span>Daftar Data</span>
                <h2>Provider AI Terdaftar</h2>
            </div>
            <small><i class="fas fa-server mr-1"></i> Search, pagination, dan sorting memakai DataTables server-side.</small>
        </div>

        <div class="table-responsive">
            <table id="datatableAiProvider" class="table ai-provider-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Provider</th>
                        <th>Model</th>
                        <th>Status</th>
                        <th>Runtime</th>
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
    .ai-provider-page { color: #1f2937; }
    .ai-provider-hero {
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
    .ai-provider-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .ai-provider-hero p {
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
    .ai-provider-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.55rem;
    }
    .ai-provider-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .ai-provider-metric,
    .ai-provider-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .ai-provider-metric {
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
    .ai-provider-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .ai-provider-metric strong {
        display: block;
        color: #111827;
        font-size: 1.6rem;
        font-weight: 800;
    }
    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-cyan { --metric-color: #0891b2; }
    .metric-purple { --metric-color: #7c3aed; }
    .ai-provider-panel { padding: 1rem; }
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
    .ai-provider-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .ai-provider-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
    }
    .ai-provider-table small {
        display: block;
        color: #64748b;
    }
    .provider-pill,
    .status-pill,
    .runtime-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        border-radius: 999px;
        font-weight: 800;
    }
    .provider-pill {
        padding: 0.34rem 0.58rem;
        color: #075985;
        background: #e0f2fe;
    }
    .status-stack {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }
    .status-pill {
        min-width: 82px;
        padding: 0.3rem 0.55rem;
    }
    .status-success { color: #047857; background: #d1fae5; }
    .status-info { color: #075985; background: #e0f2fe; }
    .status-secondary { color: #475569; background: #e2e8f0; }
    .runtime-pill {
        padding: 0.34rem 0.58rem;
        color: #0f766e;
        background: #ccfbf1;
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
    .ai-provider-panel .dataTables_filter input,
    .ai-provider-panel .dataTables_length select {
        border-radius: 8px;
        border-color: #dbe3ef;
    }
    .ai-provider-panel .dataTables_length label,
    .ai-provider-panel .dataTables_filter label,
    .ai-provider-panel .dataTables_info {
        color: #475569;
        font-weight: 700;
    }
    .ai-provider-panel .pagination .page-link {
        border-radius: 8px;
        margin-left: 0.18rem;
    }
    @media (max-width: 1199.98px) {
        .ai-provider-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767.98px) {
        .ai-provider-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .ai-provider-hero h1 { font-size: 1.5rem; }
        .hero-actions .btn { width: 100%; }
        .ai-provider-metric-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
        $('#datatableAiProvider').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('ai-settings.index') }}',
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
                { data: 'name', name: 'name' },
                { data: 'provider_type', name: 'provider_type' },
                { data: 'model', name: 'model' },
                { data: 'status', name: 'status' },
                { data: 'runtime', name: 'runtime' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'aksi', name: 'aksi' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: [4, 7] },
                { className: 'text-right', targets: 7 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat provider AI...',
                search: 'Cari:',
                searchPlaceholder: 'Nama, provider, model, base URL...',
                lengthMenu: 'Tampilkan _MENU_ provider',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ provider',
                infoEmpty: 'Belum ada provider',
                infoFiltered: '(difilter dari _MAX_ total provider)',
                zeroRecords: 'Provider AI tidak ditemukan',
                emptyTable: 'Belum ada provider AI',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });

        $(document).on('submit', 'form.js-ai-provider-confirm', function (event) {
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
