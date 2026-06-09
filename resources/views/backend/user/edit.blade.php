@extends('layouts.app-backend')

@section('content')
<div class="user-form-page">
    <div class="user-form-hero">
        <div>
            <span class="eyebrow">Access Control</span>
            <h1>{{ $title }}</h1>
            <p>Perbarui data akun, role akses, nomor WhatsApp, atau password bila memang perlu diganti.</p>
        </div>
        <a href="{{ route('user.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('user.update', $user->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-7">
                <div class="user-form-panel">
                    <div class="panel-heading">
                        <div>
                            <span>Identitas</span>
                            <h2>Data Akun</h2>
                        </div>
                        <i class="fas fa-user-edit"></i>
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" placeholder="Contoh: Operator Desa" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Login</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="nama@simades.local" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="phone">Nomor WhatsApp</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                            </div>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 6281234567890">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <small class="form-text text-muted">Nomor ini dipakai untuk notifikasi akun dan integrasi WhatsApp bila aktif.</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="user-form-panel">
                    <div class="panel-heading">
                        <div>
                            <span>Akses</span>
                            <h2>Role & Password</h2>
                        </div>
                        <i class="fas fa-user-shield"></i>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                            </div>
                            <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="" disabled>Pilih role user</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ (old('role') ?? ($user->getRoleNames()->first() ?? '')) == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak ingin mengganti">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <small class="form-text text-muted">Password lama tetap digunakan bila field ini dikosongkan.</small>
                    </div>

                    <div class="form-group mb-0">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <div class="user-form-actions">
                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .user-form-page { color: #1f2937; }
    .user-form-hero {
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
    .user-form-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .user-form-hero p {
        max-width: 760px;
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
    .user-form-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .user-form-panel,
    .user-form-actions {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .user-form-panel {
        min-height: calc(100% - 74px);
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
    .panel-heading > i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        color: #0f766e;
        background: #ccfbf1;
    }
    .user-form-panel label {
        color: #374151;
        font-weight: 800;
    }
    .user-form-panel .form-control,
    .user-form-panel .input-group-text {
        border-color: #dbe3ef;
    }
    .user-form-panel .input-group-text {
        color: #0f766e;
        background: #f8fafc;
    }
    .user-form-panel .form-control {
        min-height: 42px;
        border-radius: 8px;
    }
    .user-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.55rem;
        padding: 1rem;
    }
    @media (max-width: 767.98px) {
        .user-form-hero {
            align-items: stretch;
            flex-direction: column;
        }
        .user-form-hero h1 { font-size: 1.5rem; }
        .user-form-hero .btn,
        .user-form-actions .btn { width: 100%; }
        .user-form-actions { flex-direction: column-reverse; }
    }
</style>
@endpush
