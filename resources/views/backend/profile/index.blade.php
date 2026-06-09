@extends('layouts.app-backend')

@section('content')
@php
    $avatarPath = $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/dist/img/avatar5.png');
    $roleName = $roleContext['label'] ?? ($user->getRoleNames()->first() ?? 'Tidak ada role');
@endphp

<div class="profile-page">
    <div class="profile-hero">
        <div>
            <span class="eyebrow">Akun Saya</span>
            <h1>{{ $title }}</h1>
            <p>Perbarui identitas akun, nomor WhatsApp, avatar, dan password login yang digunakan untuk mengakses SIMADES.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Dashboard
        </a>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-4">
                <div class="profile-card">
                    <div class="avatar-preview-wrap">
                        <img id="avatarPreview" src="{{ $avatarPath }}" alt="Avatar {{ $user->name }}" loading="lazy">
                    </div>
                    <h2>{{ $user->name }}</h2>
                    <span class="role-pill"><i class="{{ $roleContext['icon'] ?? 'fas fa-user-shield' }}"></i> {{ $roleName }}</span>

                    <div class="profile-meta">
                        <div>
                            <span>Email</span>
                            <strong>{{ $user->email }}</strong>
                        </div>
                        <div>
                            <span>WhatsApp</span>
                            <strong>{{ $user->phone ?: 'Belum diisi' }}</strong>
                        </div>
                        <div>
                            <span>Terdaftar</span>
                            <strong>{{ $user->created_at?->isoFormat('D MMMM Y') }}</strong>
                        </div>
                    </div>

                    <div class="role-context-card">
                        <div class="context-title">
                            <i class="{{ $roleContext['icon'] ?? 'fas fa-user-shield' }}"></i>
                            <strong>{{ $roleContext['label'] ?? $roleName }}</strong>
                        </div>
                        <p>{{ $roleContext['description'] ?? 'Data profil mengikuti role dan permission akun.' }}</p>
                        <div class="context-list">
                            @foreach(($roleContext['items'] ?? []) as $item)
                                <span><i class="fas fa-check-circle"></i> {{ $item }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="avatar">Upload Avatar</label>
                        <div class="custom-file profile-file">
                            <input type="file" id="avatar" name="avatar" class="custom-file-input @error('avatar') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                            <label class="custom-file-label" for="avatar">Pilih foto profil...</label>
                            @error('avatar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <small class="form-text text-muted">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="profile-form-panel">
                    <div class="panel-heading">
                        <div>
                            <span>Identitas</span>
                            <h2>Informasi Pribadi</h2>
                        </div>
                        <i class="fas fa-id-card"></i>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" placeholder="Contoh: Operator Desa" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Login</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="nama@simades.local" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-md-0">
                                <label for="phone">Nomor WhatsApp</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fab fa-whatsapp"></i></span></div>
                                    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 6281234567890">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-form-panel">
                    <div class="panel-heading">
                        <div>
                            <span>Sinkron Data</span>
                            <h2>Data Penduduk & Kartu Keluarga</h2>
                        </div>
                        <i class="fas fa-address-card"></i>
                    </div>

                    @if($linkedPenduduk)
                        <div class="linked-grid">
                            <div class="linked-card">
                                <span>Nama Penduduk</span>
                                <strong>{{ $linkedPenduduk->nama }}</strong>
                                <small>NIK {{ $linkedPenduduk->nik }}</small>
                            </div>
                            <div class="linked-card">
                                <span>Data KK</span>
                                <strong>{{ $linkedKartuKeluarga?->no_kk ?? 'Belum terhubung KK' }}</strong>
                                <small>{{ $linkedKartuKeluarga?->kepala_keluarga ? 'Kepala keluarga: ' . $linkedKartuKeluarga->kepala_keluarga : 'Data penduduk belum tersambung ke Kartu Keluarga' }}</small>
                            </div>
                            <div class="linked-card">
                                <span>Alamat</span>
                                <strong>RT {{ $linkedPenduduk->rt }} / RW {{ $linkedPenduduk->rw }}</strong>
                                <small>{{ $linkedKartuKeluarga?->alamat ?? $linkedPenduduk->alamat }}</small>
                            </div>
                            <div class="linked-card">
                                <span>Status Keluarga</span>
                                <strong>{{ $linkedPenduduk->shdk ?: 'Belum diisi' }}</strong>
                                <small>{{ $linkedPenduduk->pekerjaan ?: 'Pekerjaan belum diisi' }}</small>
                            </div>
                        </div>
                    @else
                        <div class="sync-empty">
                            <i class="fas fa-link"></i>
                            <div>
                                <strong>Belum ada data penduduk yang tersinkron</strong>
                                <span>Sistem mencocokkan akun ke data penduduk memakai nomor WhatsApp terlebih dahulu, lalu nama persis. Isi nomor WhatsApp yang sama dengan data penduduk agar konteks KK bisa tampil.</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="profile-form-panel">
                    <div class="panel-heading">
                        <div>
                            <span>Keamanan</span>
                            <h2>Password Login</h2>
                        </div>
                        <i class="fas fa-lock"></i>
                    </div>

                    <div class="security-note">
                        <i class="fas fa-info-circle"></i>
                        <span>Kosongkan password jika tidak ingin mengubah password login saat ini.</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-md-0">
                                <label for="password">Password Baru</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-key"></i></span></div>
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter">
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="password_confirmation">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-shield-alt"></i></span></div>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    @can('profile-update')
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .profile-page { color: #1f2937; }
    .profile-hero {
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
    .profile-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .profile-hero p {
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
    .profile-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .profile-card,
    .profile-form-panel,
    .profile-actions {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .profile-card {
        display: grid;
        gap: 1rem;
        padding: 1rem;
        text-align: center;
    }
    .avatar-preview-wrap {
        display: grid;
        place-items: center;
        min-height: 190px;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
    }
    .avatar-preview-wrap img {
        width: 132px;
        height: 132px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid #ffffff;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.14);
    }
    .profile-card h2 {
        margin: 0;
        color: #111827;
        font-size: 1.3rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .role-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        justify-self: center;
        padding: 0.34rem 0.7rem;
        border-radius: 999px;
        color: #075985;
        background: #e0f2fe;
        font-weight: 800;
    }
    .profile-meta {
        display: grid;
        gap: 0.55rem;
        text-align: left;
    }
    .profile-meta div {
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #f8fafc;
    }
    .profile-meta span {
        display: block;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
    }
    .profile-meta strong {
        display: block;
        color: #111827;
        font-weight: 800;
        word-break: break-word;
    }
    .role-context-card {
        display: grid;
        gap: 0.65rem;
        padding: 0.9rem;
        border: 1px solid #bae6fd;
        border-radius: 14px;
        text-align: left;
        color: #075985;
        background: #e0f2fe;
    }
    .context-title {
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }
    .context-title i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        color: #ffffff;
        background: #0f766e;
    }
    .role-context-card p {
        margin: 0;
        color: #0f3f5f;
        font-weight: 700;
    }
    .context-list {
        display: grid;
        gap: 0.35rem;
    }
    .context-list span {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: #0f3f5f;
        font-weight: 800;
    }
    .profile-form-panel {
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
    .profile-card label,
    .profile-form-panel label {
        color: #374151;
        font-weight: 800;
    }
    .profile-form-panel .form-control,
    .profile-form-panel .input-group-text,
    .profile-file .custom-file-label {
        border-color: #dbe3ef;
    }
    .profile-form-panel .input-group-text {
        color: #0f766e;
        background: #f8fafc;
    }
    .profile-form-panel .form-control {
        min-height: 42px;
        border-radius: 8px;
    }
    .security-note {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        margin-bottom: 1rem;
        padding: 0.85rem;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        color: #075985;
        background: #e0f2fe;
        font-weight: 700;
    }
    .linked-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.85rem;
    }
    .linked-card {
        padding: 0.9rem;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #f8fafc;
    }
    .linked-card span {
        display: block;
        color: #64748b;
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .linked-card strong {
        display: block;
        color: #111827;
        font-size: 1rem;
        font-weight: 800;
        word-break: break-word;
    }
    .linked-card small {
        display: block;
        color: #64748b;
        margin-top: 0.25rem;
        font-weight: 700;
    }
    .sync-empty {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        padding: 1rem;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        color: #475569;
        background: #f8fafc;
    }
    .sync-empty i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        color: #0f766e;
        background: #ccfbf1;
    }
    .sync-empty strong,
    .sync-empty span {
        display: block;
    }
    .sync-empty strong {
        color: #111827;
        font-weight: 800;
    }
    .profile-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.55rem;
        padding: 1rem;
    }
    @media (max-width: 767.98px) {
        .profile-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .profile-hero h1 { font-size: 1.5rem; }
        .profile-hero .btn,
        .profile-actions .btn { width: 100%; }
        .profile-actions { flex-direction: column-reverse; }
        .linked-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
    $(function () {
        if (window.bsCustomFileInput) {
            bsCustomFileInput.init();
        }

        $('#avatar').on('change', function () {
            var file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                return;
            }

            if (!file.type.match(/^image\/(jpeg|png|webp)$/)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format tidak didukung',
                    text: 'Gunakan file JPG, JPEG, PNG, atau WEBP.',
                    confirmButtonText: 'Mengerti',
                    buttonsStyling: false,
                    customClass: { confirmButton: 'btn btn-primary' }
                });
                $(this).val('');
                return;
            }

            var reader = new FileReader();
            reader.onload = function (event) {
                $('#avatarPreview').attr('src', event.target.result);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
