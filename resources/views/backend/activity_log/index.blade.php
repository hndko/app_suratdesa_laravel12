@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="activity-page">
    <div class="activity-hero">
        <div>
            <span class="eyebrow">System Observability</span>
            <h1>{{ $title }}</h1>
            <p>Pantau jejak perubahan data, pelaku aktivitas, modul terdampak, dan payload audit tanpa memenuhi tampilan tabel utama.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Dashboard
        </a>
    </div>

    <div class="activity-metric-grid">
        <div class="activity-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-history"></i></div>
            <div><span>Total Aktivitas</span><strong>{{ number_format($totalActivities, 0, ',', '.') }}</strong></div>
        </div>
        <div class="activity-metric metric-green">
            <div class="metric-icon"><i class="fas fa-calendar-day"></i></div>
            <div><span>Hari Ini</span><strong>{{ number_format($todayActivities, 0, ',', '.') }}</strong></div>
        </div>
        <div class="activity-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-bolt"></i></div>
            <div><span>Jenis Event</span><strong>{{ number_format($totalEvents, 0, ',', '.') }}</strong></div>
        </div>
        <div class="activity-metric metric-purple">
            <div class="metric-icon"><i class="fas fa-users"></i></div>
            <div><span>Pelaku</span><strong>{{ number_format($totalActors, 0, ',', '.') }}</strong></div>
        </div>
    </div>

    <div class="activity-filter-panel">
        <div class="panel-heading">
            <div>
                <span>Filter</span>
                <h2>Pencarian Aktivitas</h2>
            </div>
            <small><i class="fas fa-bolt mr-1"></i> Tabel dimuat server-side agar tetap ringan untuk data audit besar.</small>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="form-group">
                    <label for="filterEvent">Event</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-bolt"></i></span></div>
                        <select id="filterEvent" class="form-control">
                            <option value="">Semua Event</option>
                            @foreach($events as $event)
                                <option value="{{ $event }}">{{ $event }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="form-group">
                    <label for="filterModule">Modul</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-cube"></i></span></div>
                        <select id="filterModule" class="form-control">
                            <option value="">Semua Modul</option>
                            @foreach($modules as $module)
                                <option value="{{ $module }}">{{ $module }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="form-group">
                    <label for="filterDateFrom">Dari Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
                        <input type="date" id="filterDateFrom" class="form-control" placeholder="Tanggal awal">
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="form-group">
                    <label for="filterDateTo">Sampai Tanggal</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-check"></i></span></div>
                        <input type="date" id="filterDateTo" class="form-control" placeholder="Tanggal akhir">
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-12">
                <div class="filter-actions">
                    <button type="button" id="btnFilterActivity" class="btn btn-primary">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <button type="button" id="btnResetActivity" class="btn btn-outline-secondary">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="activity-panel">
        <div class="panel-heading">
            <div>
                <span>Daftar Data</span>
                <h2>Riwayat Aktivitas Sistem</h2>
            </div>
            <small><i class="fas fa-shield-alt mr-1"></i> Payload detail hanya ditampilkan saat tombol detail dibuka.</small>
        </div>

        <div class="table-responsive">
            <table id="datatableActivityLog" class="table activity-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>Event</th>
                        <th>Modul</th>
                        <th>Pelaku</th>
                        <th>Perubahan</th>
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
    .activity-page { color: #1f2937; }
    .activity-hero {
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
    .activity-hero h1 { margin: 0.2rem 0; font-size: 1.9rem; font-weight: 800; letter-spacing: 0; }
    .activity-hero p { max-width: 790px; margin: 0; color: rgba(255, 255, 255, 0.78); }
    .eyebrow,
    .panel-heading span {
        display: block;
        color: #0f766e;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .activity-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .activity-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .activity-metric,
    .activity-filter-panel,
    .activity-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .activity-metric {
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
    .activity-metric span { color: #6b7280; font-weight: 700; }
    .activity-metric strong { display: block; color: #111827; font-size: 1.55rem; font-weight: 800; }
    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-cyan { --metric-color: #0891b2; }
    .metric-purple { --metric-color: #7c3aed; }
    .activity-filter-panel,
    .activity-panel {
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
    .panel-heading h2 { margin: 0.12rem 0 0; font-size: 1.08rem; font-weight: 800; letter-spacing: 0; }
    .panel-heading small { color: #64748b; font-weight: 700; }
    .activity-filter-panel label { color: #374151; font-weight: 800; }
    .activity-filter-panel .form-control,
    .activity-filter-panel .input-group-text { border-color: #dbe3ef; }
    .activity-filter-panel .input-group-text { color: #0f766e; background: #f8fafc; }
    .filter-actions {
        display: flex;
        gap: 0.55rem;
        align-items: flex-end;
        height: 100%;
        padding-bottom: 1rem;
    }
    .activity-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .activity-table tbody td { vertical-align: middle; border-top: 1px solid #eef2f7; }
    .activity-table small { display: block; color: #64748b; }
    .event-pill,
    .change-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        border-radius: 999px;
        font-weight: 800;
    }
    .event-pill { padding: 0.34rem 0.58rem; }
    .event-success { color: #047857; background: #d1fae5; }
    .event-info { color: #075985; background: #e0f2fe; }
    .event-danger { color: #b91c1c; background: #fee2e2; }
    .event-secondary { color: #475569; background: #e2e8f0; }
    .change-pill { padding: 0.34rem 0.58rem; color: #7c2d12; background: #ffedd5; }
    .actor-cell { display: flex; align-items: center; gap: 0.75rem; }
    .actor-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        color: #ffffff;
        background: #0f766e;
        font-weight: 800;
    }
    .activity-panel .dataTables_filter input,
    .activity-panel .dataTables_length select { border-radius: 8px; border-color: #dbe3ef; }
    .activity-panel .dataTables_length label,
    .activity-panel .dataTables_filter label,
    .activity-panel .dataTables_info { color: #475569; font-weight: 700; }
    .activity-panel .pagination .page-link { border-radius: 8px; margin-left: 0.18rem; }
    @media (max-width: 1199.98px) {
        .activity-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767.98px) {
        .activity-hero,
        .panel-heading { align-items: stretch; flex-direction: column; }
        .activity-hero h1 { font-size: 1.5rem; }
        .activity-hero .btn,
        .filter-actions .btn { width: 100%; }
        .activity-metric-grid { grid-template-columns: 1fr; }
        .filter-actions { flex-direction: column; padding-bottom: 0; }
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

        var table = $('#datatableActivityLog').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('activity-log.index') }}',
                data: function (payload) {
                    payload.event = $('#filterEvent').val();
                    payload.module = $('#filterModule').val();
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
            order: [[1, 'desc']],
            columns: [
                { data: 'no', name: 'id' },
                { data: 'created_at', name: 'created_at' },
                { data: 'event', name: 'event' },
                { data: 'module', name: 'subject_type' },
                { data: 'actor', name: 'causer_id' },
                { data: 'changes', name: 'changes' },
                { data: 'detail', name: 'detail' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: [5, 6] },
                { className: 'text-right', targets: 6 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat activity log...',
                search: 'Cari cepat:',
                searchPlaceholder: 'Event, modul, deskripsi...',
                lengthMenu: 'Tampilkan _MENU_ aktivitas',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ aktivitas',
                infoEmpty: 'Belum ada aktivitas',
                infoFiltered: '(difilter dari _MAX_ total aktivitas)',
                zeroRecords: 'Activity log tidak ditemukan',
                emptyTable: 'Belum ada activity log',
                paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
            }
        });

        $('#btnFilterActivity').on('click', function () {
            table.ajax.reload();
        });

        $('#btnResetActivity').on('click', function () {
            $('#filterEvent, #filterModule, #filterDateFrom, #filterDateTo').val('').trigger('change');
            table.search('');
            table.ajax.reload();
        });

        $(document).on('click', '.js-activity-detail', function () {
            var $button = $(this);
            var event = escapeHtml($button.attr('data-event'));
            var module = escapeHtml($button.attr('data-module'));
            var actor = escapeHtml($button.attr('data-actor'));
            var description = escapeHtml($button.attr('data-description'));
            var properties = escapeHtml($button.attr('data-properties'));

            Swal.fire({
                title: 'Detail Activity Log',
                html: '<div class="text-left">'
                    + '<p class="mb-2"><strong>Event:</strong> ' + event + '</p>'
                    + '<p class="mb-2"><strong>Modul:</strong> ' + module + '</p>'
                    + '<p class="mb-2"><strong>Pelaku:</strong> ' + actor + '</p>'
                    + '<p class="mb-2"><strong>Deskripsi:</strong><br>' + description + '</p>'
                    + '<pre class="text-left bg-light border rounded p-2 mb-0" style="max-height: 260px; overflow:auto; white-space: pre-wrap;">' + properties + '</pre>'
                    + '</div>',
                icon: 'info',
                confirmButtonText: 'Tutup',
                buttonsStyling: false,
                customClass: { confirmButton: 'btn btn-primary' },
                width: 760
            });
        });
    });
</script>
@endpush
