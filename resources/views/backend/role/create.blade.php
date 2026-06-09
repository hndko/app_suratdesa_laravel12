@extends('layouts.app-backend')

@section('content')
@php
    $selectedPermissions = old('permissions', []);
@endphp

<div class="role-form-page">
    <div class="role-form-hero">
        <div>
            <span class="eyebrow">RBAC SIMADES</span>
            <h1>{{ $title }}</h1>
            <p>Buat role baru dan tentukan permission granular agar akses tombol, request, export, validasi, dan aksi backend tetap konsisten.</p>
        </div>
        <a href="{{ route('role.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('role.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="row">
            <div class="col-lg-4">
                <div class="role-form-panel">
                    <div class="panel-heading">
                        <div>
                            <span>Identitas</span>
                            <h2>Data Role</h2>
                        </div>
                        <i class="fas fa-user-shield"></i>
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Role</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            </div>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: auditor-desa" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <small class="form-text text-muted">Gunakan nama singkat tanpa spasi agar mudah dibaca pada permission dan audit.</small>
                    </div>

                    <div class="permission-tools">
                        <button type="button" class="btn btn-primary js-check-all">
                            <i class="fas fa-check-double mr-1"></i> Pilih Semua
                        </button>
                        <button type="button" class="btn btn-outline-secondary js-uncheck-all">
                            <i class="fas fa-eraser mr-1"></i> Kosongkan
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="role-form-panel">
                    <div class="panel-heading">
                        <div>
                            <span>Permission</span>
                            <h2>Hak Akses Modul</h2>
                        </div>
                        <i class="fas fa-key"></i>
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
                        @foreach($permissionGroups as $group)
                            <div class="permission-card" data-permission-group="{{ strtolower($group['name']) }}">
                                <div class="permission-card-header">
                                    <strong>{{ $group['name'] }}</strong>
                                    <span>{{ $group['permissions']->count() }} permission</span>
                                </div>
                                <div class="permission-list">
                                    @foreach($group['permissions'] as $permission)
                                        <label class="permission-item" data-permission-name="{{ strtolower($permission['name']) }}" for="perm_{{ $permission['id'] }}">
                                            <input type="checkbox" name="permissions[]" id="perm_{{ $permission['id'] }}" value="{{ $permission['name'] }}" {{ in_array($permission['name'], $selectedPermissions, true) ? 'checked' : '' }}>
                                            <span><i class="fas fa-check"></i></span>
                                            <code>{{ $permission['name'] }}</code>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('permissions')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                    @error('permissions.*')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>

                <div class="role-form-actions">
                    <a href="{{ route('role.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Role
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .role-form-page { color: #1f2937; }
    .role-form-hero {
        display: flex; justify-content: space-between; align-items: flex-end; gap: 1rem;
        margin-bottom: 1rem; padding: 1.3rem; border-radius: 16px;
        background: linear-gradient(135deg, #111827, #0f766e); color: #ffffff;
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.16);
    }
    .role-form-hero h1 { margin: 0.2rem 0; font-size: 1.85rem; font-weight: 800; letter-spacing: 0; }
    .role-form-hero p { max-width: 760px; margin: 0; color: rgba(255, 255, 255, 0.78); }
    .eyebrow,
    .panel-heading span {
        display: block; color: #0f766e; font-size: 0.74rem; font-weight: 800;
        letter-spacing: 0.06em; text-transform: uppercase;
    }
    .role-form-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .role-form-panel,
    .role-form-actions {
        border: 1px solid #e5e7eb; border-radius: 14px; background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .role-form-panel { margin-bottom: 1rem; padding: 1rem; }
    .panel-heading { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1rem; }
    .panel-heading h2 { margin: 0.12rem 0 0; font-size: 1.08rem; font-weight: 800; letter-spacing: 0; }
    .panel-heading > i {
        display: inline-flex; align-items: center; justify-content: center;
        width: 42px; height: 42px; border-radius: 12px; color: #0f766e; background: #ccfbf1;
    }
    .role-form-panel label { color: #374151; font-weight: 800; }
    .role-form-panel .form-control,
    .role-form-panel .input-group-text { border-color: #dbe3ef; }
    .role-form-panel .input-group-text { color: #0f766e; background: #f8fafc; }
    .permission-tools { display: grid; gap: 0.55rem; margin-top: 1rem; }
    .permission-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.85rem; }
    .permission-card { border: 1px solid #e5e7eb; border-radius: 12px; background: #f8fafc; overflow: hidden; }
    .permission-card-header {
        display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;
        padding: 0.8rem 0.9rem; border-bottom: 1px solid #e5e7eb; background: #ffffff;
    }
    .permission-card-header strong { color: #111827; font-weight: 800; }
    .permission-card-header span { color: #64748b; font-size: 0.78rem; font-weight: 800; }
    .permission-list { display: grid; gap: 0.5rem; padding: 0.75rem; max-height: 280px; overflow: auto; }
    .permission-item {
        display: flex; align-items: center; gap: 0.55rem; margin: 0; padding: 0.55rem;
        border: 1px solid #e5e7eb; border-radius: 10px; background: #ffffff; cursor: pointer;
    }
    .permission-item input { position: absolute; opacity: 0; pointer-events: none; }
    .permission-item span {
        display: inline-flex; align-items: center; justify-content: center; flex: 0 0 24px;
        width: 24px; height: 24px; border-radius: 8px; color: transparent; background: #e2e8f0;
    }
    .permission-item input:checked + span { color: #ffffff; background: #0f766e; }
    .permission-item code { color: #0f172a; background: transparent; font-weight: 700; white-space: normal; word-break: break-word; }
    .role-form-actions { display: flex; justify-content: flex-end; gap: 0.55rem; padding: 1rem; }
    @media (max-width: 991.98px) { .permission-grid { grid-template-columns: 1fr; } }
    @media (max-width: 767.98px) {
        .role-form-hero { align-items: stretch; flex-direction: column; }
        .role-form-hero h1 { font-size: 1.5rem; }
        .role-form-hero .btn,
        .role-form-actions .btn { width: 100%; }
        .role-form-actions { flex-direction: column-reverse; }
    }
</style>
@endpush

@push('scripts')
<script>
    $(function () {
        $('.js-check-all').on('click', function () {
            $('input[name="permissions[]"]:visible').prop('checked', true);
        });

        $('.js-uncheck-all').on('click', function () {
            $('input[name="permissions[]"]:visible').prop('checked', false);
        });

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
