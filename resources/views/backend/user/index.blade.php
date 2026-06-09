@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="user-page">
    <div class="user-hero">
        <div>
            <span class="eyebrow">Access Control</span>
            <h1>{{ $title }}</h1>
            <p>Kelola akun staff desa, role akses, email login, dan nomor WhatsApp untuk notifikasi operasional SIMADES.</p>
        </div>
        <div class="hero-actions">
            @can('role-index')
            <a href="{{ route('role.index') }}" class="btn btn-outline-light">
                <i class="fas fa-user-lock mr-1"></i> Manajemen Role
            </a>
            @endcan
            @can('user-create')
            <a href="{{ route('user.create') }}" class="btn btn-light">
                <i class="fas fa-user-plus mr-1"></i> Tambah User
            </a>
            @endcan
        </div>
    </div>

    <div class="user-metric-grid">
        <div class="user-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-users"></i></div>
            <div>
                <span>Total User</span>
                <strong>{{ number_format($totalUsers, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="user-metric metric-green">
            <div class="metric-icon"><i class="fas fa-user-lock"></i></div>
            <div>
                <span>Total Role</span>
                <strong>{{ number_format($totalRoles, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="user-metric metric-cyan">
            <div class="metric-icon"><i class="fab fa-whatsapp"></i></div>
            <div>
                <span>Nomor WA</span>
                <strong>{{ number_format($totalWithPhone, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="user-metric metric-purple">
            <div class="metric-icon"><i class="fas fa-calendar-plus"></i></div>
            <div>
                <span>30 Hari Ini</span>
                <strong>{{ number_format($latestUsers, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <div class="user-filter-panel">
        <div class="panel-heading">
            <div>
                <span>Filter</span>
                <h2>Pencarian User</h2>
            </div>
            <small><i class="fas fa-bolt mr-1"></i> Search, pagination, dan sorting memakai DataTables server-side.</small>
        </div>

        <div class="row align-items-end">
            <div class="col-lg-4 col-md-6">
                <div class="form-group">
                    <label for="filterRole">Role</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                        </div>
                        <select id="filterRole" class="form-control">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-6">
                <div class="filter-actions">
                    <button type="button" id="btnFilterUser" class="btn btn-primary">
                        <i class="fas fa-filter mr-1"></i> Terapkan Filter
                    </button>
                    <button type="button" id="btnResetUser" class="btn btn-outline-secondary">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="user-panel">
        <div class="panel-heading">
            <div>
                <span>Daftar Data</span>
                <h2>Pengguna Sistem</h2>
            </div>
            <small><i class="fas fa-lock mr-1"></i> Aksi mengikuti permission role login.</small>
        </div>

        <div class="table-responsive">
            <table id="datatableUsers" class="table user-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email & WhatsApp</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat</th>
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
    .user-page { color: #1f2937; }
    .user-hero {
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
    .user-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .user-hero p {
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
    .user-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .hero-actions,
    .filter-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.55rem;
    }
    .user-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .user-metric,
    .user-filter-panel,
    .user-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .user-metric {
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
    .user-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .user-metric strong {
        display: block;
        color: #111827;
        font-size: 1.55rem;
        font-weight: 800;
    }
    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-cyan { --metric-color: #0891b2; }
    .metric-purple { --metric-color: #7c3aed; }
    .user-filter-panel,
    .user-panel {
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
    .user-filter-panel label {
        color: #374151;
        font-weight: 800;
    }
    .user-filter-panel .form-control,
    .user-filter-panel .input-group-text {
        border-color: #dbe3ef;
    }
    .user-filter-panel .input-group-text {
        color: #0f766e;
        background: #f8fafc;
    }
    .user-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .user-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
    }
    .user-table small {
        display: block;
        color: #64748b;
    }
    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .user-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        color: #ffffff;
        background: #0f766e;
        font-weight: 800;
    }
    .role-pill,
    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        margin: 0.1rem 0.12rem 0.1rem 0;
        border-radius: 999px;
        font-weight: 800;
    }
    .role-pill {
        padding: 0.34rem 0.58rem;
        color: #075985;
        background: #e0f2fe;
    }
    .role-empty {
        color: #475569;
        background: #e2e8f0;
    }
    .status-pill {
        min-width: 92px;
        padding: 0.3rem 0.55rem;
    }
    .status-success { color: #047857; background: #d1fae5; }
    .status-info { color: #075985; background: #e0f2fe; }
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
    .user-panel .dataTables_filter input,
    .user-panel .dataTables_length select {
        border-radius: 8px;
        border-color: #dbe3ef;
    }
    .user-panel .dataTables_length label,
    .user-panel .dataTables_filter label,
    .user-panel .dataTables_info {
        color: #475569;
        font-weight: 700;
    }
    .user-panel .pagination .page-link {
        border-radius: 8px;
        margin-left: 0.18rem;
    }
    @media (max-width: 1199.98px) {
        .user-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767.98px) {
        .user-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .user-hero h1 { font-size: 1.5rem; }
        .hero-actions .btn,
        .filter-actions .btn { width: 100%; }
        .user-metric-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
        var table = $('#datatableUsers').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('user.index') }}',
                data: function (payload) {
                    payload.role = $('#filterRole').val();
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
            order: [[5, 'desc']],
            columns: [
                { data: 'no', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'roles', name: 'roles' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'aksi', name: 'aksi' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: [3, 4, 6] },
                { className: 'text-right', targets: 6 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat data user...',
                search: 'Cari cepat:',
                searchPlaceholder: 'Nama, email, WhatsApp, role...',
                lengthMenu: 'Tampilkan _MENU_ user',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ user',
                infoEmpty: 'Belum ada user',
                infoFiltered: '(difilter dari _MAX_ total user)',
                zeroRecords: 'User tidak ditemukan',
                emptyTable: 'Belum ada data user',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });

        $('#btnFilterUser').on('click', function () {
            table.ajax.reload();
        });

        $('#btnResetUser').on('click', function () {
            $('#filterRole').val('').trigger('change');
            table.search('');
            table.ajax.reload();
        });

        $(document).on('submit', 'form.js-user-confirm', function (event) {
            event.preventDefault();
            var form = this;

            Swal.fire({
                title: 'Konfirmasi',
                text: form.dataset.confirmText || 'Yakin ingin menghapus user ini?',
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
