@extends('layouts.app-backend')

@section('content')
<div class="role-detail-page">
    <div class="role-detail-hero">
        <div>
            <span class="eyebrow">Detail RBAC</span>
            <h1>{{ $title }}</h1>
            <p>Lihat ringkasan permission yang melekat pada role ini sebelum melakukan perubahan akses.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('role.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            @can('role-edit')
                @if($role->name !== 'super-admin')
                    <a href="{{ route('role.edit', $role->id) }}" class="btn btn-light">
                        <i class="fas fa-edit mr-1"></i> Edit Role
                    </a>
                @endif
            @endcan
        </div>
    </div>

    @if($role->name === 'super-admin')
        <div class="role-alert">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Akses penuh otomatis</strong>
                <span>Role ini mendapat bypass permission melalui Gate dan tidak perlu permission manual untuk mengakses seluruh fitur.</span>
            </div>
        </div>
    @endif

    <div class="role-detail-grid">
        <div class="role-summary-card">
            <div class="summary-icon"><i class="fas fa-user-shield"></i></div>
            <span>Nama Role</span>
            <strong>{{ $role->name }}</strong>
        </div>
        <div class="role-summary-card">
            <div class="summary-icon"><i class="fas fa-key"></i></div>
            <span>Total Permission</span>
            <strong>{{ number_format($role->permissions->count(), 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="role-detail-panel">
        <div class="panel-heading">
            <div>
                <span>Permission</span>
                <h2>Hak Akses Per Modul</h2>
            </div>
            <small><i class="fas fa-search mr-1"></i> Gunakan pencarian untuk menemukan permission tertentu.</small>
        </div>

        <div class="form-group">
            <label for="permissionSearch">Cari Permission</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" id="permissionSearch" class="form-control" placeholder="Cari permission, contoh: surat-print">
            </div>
        </div>

        <div class="permission-grid">
            @if($role->permissions->isNotEmpty())
            @foreach($permissionGroups as $group)
                @php
                    $checkedPermissions = $group['permissions']->where('checked', true);
                @endphp
                @if($checkedPermissions->isNotEmpty())
                    <div class="permission-card" data-permission-group="{{ strtolower($group['name']) }}">
                        <div class="permission-card-header">
                            <strong>{{ $group['name'] }}</strong>
                            <span>{{ $checkedPermissions->count() }} permission</span>
                        </div>
                        <div class="permission-list">
                            @foreach($checkedPermissions as $permission)
                                <div class="permission-item" data-permission-name="{{ strtolower($permission['name']) }}">
                                    <span><i class="fas fa-check"></i></span>
                                    <code>{{ $permission['name'] }}</code>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-key"></i>
                    <strong>Belum ada permission</strong>
                    <span>Role ini belum memiliki permission khusus.</span>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .role-detail-page { color: #1f2937; }
    .role-detail-hero {
        display: flex; justify-content: space-between; align-items: flex-end; gap: 1rem;
        margin-bottom: 1rem; padding: 1.3rem; border-radius: 16px;
        background: linear-gradient(135deg, #111827, #0f766e); color: #ffffff;
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.16);
    }
    .role-detail-hero h1 { margin: 0.2rem 0; font-size: 1.85rem; font-weight: 800; letter-spacing: 0; }
    .role-detail-hero p { max-width: 760px; margin: 0; color: rgba(255, 255, 255, 0.78); }
    .eyebrow,
    .panel-heading span {
        display: block; color: #0f766e; font-size: 0.74rem; font-weight: 800;
        letter-spacing: 0.06em; text-transform: uppercase;
    }
    .role-detail-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .hero-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 0.55rem; }
    .role-alert,
    .role-summary-card,
    .role-detail-panel {
        border: 1px solid #e5e7eb; border-radius: 14px; background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .role-alert {
        display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 1rem; padding: 1rem;
        color: #075985; background: #e0f2fe; border-color: #bae6fd;
    }
    .role-alert i { margin-top: 0.15rem; }
    .role-alert strong,
    .role-alert span { display: block; }
    .role-detail-grid {
        display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1rem;
    }
    .role-summary-card { display: flex; align-items: center; gap: 0.85rem; padding: 1rem; }
    .summary-icon {
        display: inline-flex; align-items: center; justify-content: center; flex: 0 0 46px;
        width: 46px; height: 46px; border-radius: 13px; color: #ffffff; background: #0f766e;
    }
    .role-summary-card span { display: block; color: #6b7280; font-weight: 700; }
    .role-summary-card strong { display: block; color: #111827; font-size: 1.45rem; font-weight: 800; }
    .role-detail-panel { padding: 1rem; }
    .panel-heading { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1rem; }
    .panel-heading h2 { margin: 0.12rem 0 0; font-size: 1.08rem; font-weight: 800; letter-spacing: 0; }
    .panel-heading small { color: #64748b; font-weight: 700; }
    .role-detail-panel label { color: #374151; font-weight: 800; }
    .role-detail-panel .form-control,
    .role-detail-panel .input-group-text { border-color: #dbe3ef; }
    .role-detail-panel .input-group-text { color: #0f766e; background: #f8fafc; }
    .permission-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.85rem; }
    .permission-card { border: 1px solid #e5e7eb; border-radius: 12px; background: #f8fafc; overflow: hidden; }
    .permission-card-header {
        display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;
        padding: 0.8rem 0.9rem; border-bottom: 1px solid #e5e7eb; background: #ffffff;
    }
    .permission-card-header strong { color: #111827; font-weight: 800; }
    .permission-card-header span { color: #64748b; font-size: 0.78rem; font-weight: 800; }
    .permission-list { display: grid; gap: 0.5rem; padding: 0.75rem; }
    .permission-item {
        display: flex; align-items: center; gap: 0.55rem; padding: 0.55rem;
        border: 1px solid #e5e7eb; border-radius: 10px; background: #ffffff;
    }
    .permission-item span {
        display: inline-flex; align-items: center; justify-content: center; flex: 0 0 24px;
        width: 24px; height: 24px; border-radius: 8px; color: #ffffff; background: #0f766e;
    }
    .permission-item code { color: #0f172a; background: transparent; font-weight: 700; white-space: normal; word-break: break-word; }
    .empty-state {
        grid-column: 1 / -1; display: grid; place-items: center; gap: 0.35rem;
        min-height: 170px; color: #64748b; border: 1px dashed #cbd5e1; border-radius: 12px; background: #f8fafc;
    }
    .empty-state i { font-size: 2rem; color: #94a3b8; }
    .empty-state strong { color: #334155; }
    @media (max-width: 991.98px) {
        .permission-grid,
        .role-detail-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 767.98px) {
        .role-detail-hero,
        .panel-heading { align-items: stretch; flex-direction: column; }
        .role-detail-hero h1 { font-size: 1.5rem; }
        .hero-actions .btn { width: 100%; }
    }
</style>
@endpush

@push('scripts')
<script>
    $(function () {
        $('#permissionSearch').on('keyup', function () {
            var keyword = $(this).val().toLowerCase();
            $('.permission-card').each(function () {
                var $card = $(this);
                var matchedItems = 0;

                $card.find('.permission-item').each(function () {
                    var matched = $(this).data('permission-name').indexOf(keyword) !== -1 || $card.data('permission-group').indexOf(keyword) !== -1;
                    $(this).toggle(matched);
                    matchedItems += matched ? 1 : 0;
                });

                $card.toggle(matchedItems > 0);
            });
        });
    });
</script>
@endpush
