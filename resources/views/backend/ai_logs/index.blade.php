@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="ai-log-page">
    <div class="ai-log-hero">
        <div>
            <span class="eyebrow">AI Observability</span>
            <h1>{{ $title }}</h1>
            <p>Pantau pemakaian provider AI, status request, token, latency, dan error tanpa membuka data sensitif atau prompt lengkap.</p>
        </div>
        <div class="hero-actions">
            @can('ai-setting-index')
            <a href="{{ route('ai-settings.index') }}" class="btn btn-outline-light">
                <i class="fas fa-network-wired mr-1"></i> AI Provider
            </a>
            @endcan
            @can('ai-playground-send')
            <a href="{{ route('ai-assistant.index') }}" class="btn btn-light">
                <i class="fas fa-comments mr-1"></i> AI Assistant
            </a>
            @endcan
        </div>
    </div>

    <div class="ai-log-metric-grid">
        <div class="ai-log-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <span>Total Log</span>
                <strong>{{ number_format($totalLogs, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="ai-log-metric metric-green">
            <div class="metric-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <span>Berhasil</span>
                <strong>{{ number_format($totalSuccess, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="ai-log-metric metric-red">
            <div class="metric-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div>
                <span>Error</span>
                <strong>{{ number_format($totalError, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="ai-log-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-stopwatch"></i></div>
            <div>
                <span>Avg Latency</span>
                <strong>{{ number_format($averageLatency, 0, ',', '.') }} ms</strong>
            </div>
        </div>
    </div>

    <div class="ai-log-filter-panel">
        <div class="panel-heading">
            <div>
                <span>Filter</span>
                <h2>Pencarian Log AI</h2>
            </div>
            <small><i class="fas fa-bolt mr-1"></i> Tabel dimuat server-side agar tetap ringan.</small>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="form-group">
                    <label for="filterFeature">Fitur</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" id="filterFeature" class="form-control" placeholder="Contoh: ai-pengaduan">
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="form-group">
                    <label for="filterStatus">Status</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                        </div>
                        <select id="filterStatus" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="success">Success</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="form-group">
                    <label for="filterProvider">Provider</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-server"></i></span>
                        </div>
                        <select id="filterProvider" class="form-control">
                            <option value="">Semua Provider</option>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->name }} ({{ strtoupper($provider->provider_type) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="form-group">
                    <label for="filterDateFrom">Dari Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" id="filterDateFrom" class="form-control" placeholder="Tanggal awal">
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="form-group">
                    <label for="filterDateTo">Sampai Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                        </div>
                        <input type="date" id="filterDateTo" class="form-control" placeholder="Tanggal akhir">
                    </div>
                </div>
            </div>
        </div>

        <div class="filter-actions">
            <button type="button" id="btnFilterLog" class="btn btn-primary">
                <i class="fas fa-filter mr-1"></i> Terapkan Filter
            </button>
            <button type="button" id="btnResetLog" class="btn btn-outline-secondary">
                <i class="fas fa-undo mr-1"></i> Reset
            </button>
        </div>
    </div>

    <div class="ai-log-panel">
        <div class="panel-heading">
            <div>
                <span>Daftar Data</span>
                <h2>Riwayat Pemakaian AI</h2>
            </div>
            <small><i class="fas fa-coins mr-1"></i> Total token tercatat: {{ number_format($totalTokens, 0, ',', '.') }}</small>
        </div>

        <div class="table-responsive">
            <table id="datatableAiLogs" class="table ai-log-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Fitur</th>
                        <th>Provider</th>
                        <th>Model</th>
                        <th>Status</th>
                        <th>Token</th>
                        <th>Latency</th>
                        <th class="text-right">Detail</th>
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
    .ai-log-page { color: #1f2937; }
    .ai-log-hero {
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
    .ai-log-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .ai-log-hero p {
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
    .ai-log-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .hero-actions,
    .filter-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.55rem;
    }
    .ai-log-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .ai-log-metric,
    .ai-log-filter-panel,
    .ai-log-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .ai-log-metric {
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
    .ai-log-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .ai-log-metric strong {
        display: block;
        color: #111827;
        font-size: 1.35rem;
        font-weight: 800;
    }
    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-red { --metric-color: #dc2626; }
    .metric-cyan { --metric-color: #0891b2; }
    .ai-log-filter-panel,
    .ai-log-panel {
        margin-bottom: 1rem;
        padding: 1rem;
    }
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
    .ai-log-filter-panel label {
        color: #374151;
        font-weight: 800;
    }
    .ai-log-filter-panel .form-control,
    .ai-log-filter-panel .input-group-text {
        border-color: #dbe3ef;
    }
    .ai-log-filter-panel .input-group-text {
        color: #0f766e;
        background: #f8fafc;
    }
    .ai-log-filter-panel .form-control {
        min-height: 42px;
        border-radius: 8px;
    }
    .ai-log-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .ai-log-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
    }
    .ai-log-table small {
        display: block;
        color: #64748b;
    }
    .provider-pill,
    .status-pill,
    .token-pill,
    .latency-pill {
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
    .status-pill {
        min-width: 92px;
        padding: 0.3rem 0.55rem;
    }
    .status-success { color: #047857; background: #d1fae5; }
    .status-danger { color: #b91c1c; background: #fee2e2; }
    .token-pill {
        padding: 0.34rem 0.58rem;
        color: #7c2d12;
        background: #ffedd5;
    }
    .latency-pill {
        padding: 0.34rem 0.58rem;
        color: #0f766e;
        background: #ccfbf1;
    }
    .ai-log-panel .dataTables_filter input,
    .ai-log-panel .dataTables_length select {
        border-radius: 8px;
        border-color: #dbe3ef;
    }
    .ai-log-panel .dataTables_length label,
    .ai-log-panel .dataTables_filter label,
    .ai-log-panel .dataTables_info {
        color: #475569;
        font-weight: 700;
    }
    .ai-log-panel .pagination .page-link {
        border-radius: 8px;
        margin-left: 0.18rem;
    }
    @media (max-width: 1199.98px) {
        .ai-log-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767.98px) {
        .ai-log-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .ai-log-hero h1 { font-size: 1.5rem; }
        .hero-actions .btn,
        .filter-actions .btn { width: 100%; }
        .ai-log-metric-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
        function escapeHtml(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        var table = $('#datatableAiLogs').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('ai-logs.index') }}',
                data: function (payload) {
                    payload.feature = $('#filterFeature').val();
                    payload.status = $('#filterStatus').val();
                    payload.provider_id = $('#filterProvider').val();
                    payload.date_from = $('#filterDateFrom').val();
                    payload.date_to = $('#filterDateTo').val();
                }
            },
            scrollX: true,
            lengthChange: true,
            searching: true,
            paging: true,
            info: true,
            autoWidth: false,
            deferRender: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[0, 'desc']],
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'feature', name: 'feature' },
                { data: 'provider', name: 'provider' },
                { data: 'model', name: 'model' },
                { data: 'status', name: 'status' },
                { data: 'tokens', name: 'total_tokens' },
                { data: 'latency', name: 'latency_ms' },
                { data: 'error', name: 'error' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: [2, 7] },
                { className: 'text-right', targets: 7 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat usage log AI...',
                search: 'Cari cepat:',
                searchPlaceholder: 'Fitur, model, provider, error...',
                lengthMenu: 'Tampilkan _MENU_ log',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ log',
                infoEmpty: 'Belum ada log',
                infoFiltered: '(difilter dari _MAX_ total log)',
                zeroRecords: 'Usage log tidak ditemukan',
                emptyTable: 'Belum ada usage log AI',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });

        $('#btnFilterLog').on('click', function () {
            table.ajax.reload();
        });

        $('#btnResetLog').on('click', function () {
            $('#filterFeature, #filterStatus, #filterProvider, #filterDateFrom, #filterDateTo').val('').trigger('change');
            table.search('');
            table.ajax.reload();
        });

        $('#filterFeature').on('keyup', function (event) {
            if (event.key === 'Enter') {
                table.ajax.reload();
            }
        });

        $(document).on('click', '.js-log-detail', function () {
            var $button = $(this);
            var feature = escapeHtml($button.attr('data-feature'));
            var status = escapeHtml($button.attr('data-status'));
            var error = escapeHtml($button.attr('data-error'));
            var metadata = escapeHtml($button.attr('data-metadata'));

            Swal.fire({
                title: 'Detail Usage Log',
                html: '<div class="text-left">'
                    + '<p class="mb-2"><strong>Fitur:</strong> ' + feature + '</p>'
                    + '<p class="mb-2"><strong>Status:</strong> ' + status + '</p>'
                    + '<p class="mb-2"><strong>Error:</strong><br>' + error + '</p>'
                    + '<pre class="text-left bg-light border rounded p-2 mb-0" style="max-height: 220px; overflow:auto; white-space: pre-wrap;">' + metadata + '</pre>'
                    + '</div>',
                icon: $button.attr('data-status') === 'success' ? 'info' : 'warning',
                confirmButtonText: 'Tutup',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                width: 720
            });
        });
    });
</script>
@endpush
