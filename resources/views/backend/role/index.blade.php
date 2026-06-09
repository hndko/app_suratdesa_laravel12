@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="role-page">
    <div class="role-hero">
        <div>
            <span class="eyebrow">RBAC SIMADES</span>
            <h1>{{ $title }}</h1>
            <p>Kelola role dan permission granular agar setiap tombol, form, validasi, request, dan aksi backend tetap terkendali sesuai tanggung jawab pengguna.</p>
        </div>
        <div class="hero-actions">
            @can('user-index')
            <a href="{{ route('user.index') }}" class="btn btn-outline-light">
                <i class="fas fa-users mr-1"></i> Manajemen User
            </a>
            @endcan
            @can('role-create')
            <a href="{{ route('role.create') }}" class="btn btn-light">
                <i class="fas fa-plus mr-1"></i> Tambah Role
            </a>
            @endcan
        </div>
    </div>

    <div class="role-metric-grid">
        <div class="role-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-user-shield"></i></div>
            <div><span>Total Role</span><strong>{{ number_format($totalRoles, 0, ',', '.') }}</strong></div>
        </div>
        <div class="role-metric metric-green">
            <div class="metric-icon"><i class="fas fa-key"></i></div>
            <div><span>Total Permission</span><strong>{{ number_format($totalPermissions, 0, ',', '.') }}</strong></div>
        </div>
        <div class="role-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-lock"></i></div>
            <div><span>Role Inti</span><strong>{{ number_format($coreRoles, 0, ',', '.') }}</strong></div>
        </div>
        <div class="role-metric metric-purple">
            <div class="metric-icon"><i class="fas fa-layer-group"></i></div>
            <div><span>Role Custom</span><strong>{{ number_format($customRoles, 0, ',', '.') }}</strong></div>
        </div>
    </div>

    <div class="role-panel">
        <div class="panel-heading">
            <div>
                <span>Daftar Data</span>
                <h2>Role Sistem</h2>
            </div>
            <small><i class="fas fa-bolt mr-1"></i> Search, pagination, dan sorting memakai DataTables server-side.</small>
        </div>

        <div class="table-responsive">
            <table id="datatableRoles" class="table role-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Role</th>
                        <th>Permission</th>
                        <th>User</th>
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
    .role-page { color: #1f2937; }
    .role-hero {
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
    .role-hero h1 { margin: 0.2rem 0; font-size: 1.9rem; font-weight: 800; letter-spacing: 0; }
    .role-hero p { max-width: 790px; margin: 0; color: rgba(255, 255, 255, 0.78); }
    .eyebrow,
    .panel-heading span {
        display: block;
        color: #0f766e;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .role-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .hero-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 0.55rem; }
    .role-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .role-metric,
    .role-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .role-metric {
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
    .role-metric span { color: #6b7280; font-weight: 700; }
    .role-metric strong { display: block; color: #111827; font-size: 1.55rem; font-weight: 800; }
    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-cyan { --metric-color: #0891b2; }
    .metric-purple { --metric-color: #7c3aed; }
    .role-panel { padding: 1rem; }
    .panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .panel-heading h2 { margin: 0.12rem 0 0; font-size: 1.08rem; font-weight: 800; letter-spacing: 0; }
    .panel-heading small { color: #64748b; font-weight: 700; }
    .role-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .role-table tbody td { vertical-align: middle; border-top: 1px solid #eef2f7; }
    .role-table small { display: block; color: #64748b; }
    .role-cell { display: flex; align-items: center; gap: 0.75rem; }
    .role-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        color: #ffffff;
        background: #0f766e;
    }
    .permission-pill,
    .user-pill,
    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        border-radius: 999px;
        font-weight: 800;
    }
    .permission-pill { padding: 0.34rem 0.58rem; color: #075985; background: #e0f2fe; }
    .user-pill { padding: 0.34rem 0.58rem; color: #7c2d12; background: #ffedd5; }
    .status-pill { min-width: 96px; padding: 0.3rem 0.55rem; }
    .status-success { color: #047857; background: #d1fae5; }
    .status-info { color: #075985; background: #e0f2fe; }
    .action-group { display: inline-flex; align-items: center; gap: 0.35rem; }
    .action-group .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        padding: 0;
    }
    .role-panel .dataTables_filter input,
    .role-panel .dataTables_length select { border-radius: 8px; border-color: #dbe3ef; }
    .role-panel .dataTables_length label,
    .role-panel .dataTables_filter label,
    .role-panel .dataTables_info { color: #475569; font-weight: 700; }
    .role-panel .pagination .page-link { border-radius: 8px; margin-left: 0.18rem; }
    @media (max-width: 1199.98px) {
        .role-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767.98px) {
        .role-hero,
        .panel-heading { align-items: stretch; flex-direction: column; }
        .role-hero h1 { font-size: 1.5rem; }
        .hero-actions .btn { width: 100%; }
        .role-metric-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
        $('#datatableRoles').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('role.index') }}',
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
                { data: 'permissions', name: 'permissions_count' },
                { data: 'users', name: 'users_count' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'aksi', name: 'aksi' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: [4, 6] },
                { className: 'text-right', targets: 6 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat role...',
                search: 'Cari cepat:',
                searchPlaceholder: 'Nama role atau permission...',
                lengthMenu: 'Tampilkan _MENU_ role',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ role',
                infoEmpty: 'Belum ada role',
                infoFiltered: '(difilter dari _MAX_ total role)',
                zeroRecords: 'Role tidak ditemukan',
                emptyTable: 'Belum ada data role',
                paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
            }
        });

        $(document).on('submit', 'form.js-role-confirm', function (event) {
            event.preventDefault();
            var form = this;

            Swal.fire({
                title: 'Konfirmasi',
                text: form.dataset.confirmText || 'Yakin ingin menghapus role ini?',
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
