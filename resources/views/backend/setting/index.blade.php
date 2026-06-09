@extends('layouts.app-backend')

@section('content')
@php
    $logoPath = $settings['village_logo'] ?? 'assets/img/logo.png';
@endphp

<div class="setting-page">
    <div class="setting-hero">
        <div>
            <span class="eyebrow">Konfigurasi SIMADES</span>
            <h1>Pengaturan Website & Desa</h1>
            <p>Atur identitas aplikasi, kontak publik, kop surat, profil desa, dan logo yang tampil di dashboard, portal publik, serta dokumen surat.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Dashboard
        </a>
    </div>

    <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="setting-shell">
            <div class="setting-tabs">
                <a class="setting-tab active" id="general-tab" data-toggle="pill" href="#general" role="tab">
                    <i class="fas fa-globe"></i>
                    <span>Website</span>
                </a>
                <a class="setting-tab" id="village-tab" data-toggle="pill" href="#village" role="tab">
                    <i class="fas fa-landmark"></i>
                    <span>Identitas Desa</span>
                </a>
                <a class="setting-tab" id="branding-tab" data-toggle="pill" href="#branding" role="tab">
                    <i class="fas fa-image"></i>
                    <span>Logo & Branding</span>
                </a>
            </div>

            <div class="tab-content setting-content" id="settingTabContent">
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <div class="setting-panel">
                        <div class="panel-heading">
                            <div>
                                <span>Website</span>
                                <h2>Informasi Aplikasi</h2>
                            </div>
                            <i class="fas fa-globe"></i>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="site_name">Nama Situs</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                        </div>
                                        <input type="text" id="site_name" name="site_name" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $settings['site_name'] ?? '') }}" placeholder="Contoh: SIMADES" maxlength="100">
                                        @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="contact_whatsapp">Kontak WhatsApp Publik</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                        </div>
                                        <input type="text" id="contact_whatsapp" name="contact_whatsapp" class="form-control @error('contact_whatsapp') is-invalid @enderror" value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '') }}" placeholder="Contoh: 6281234567890" maxlength="20">
                                        @error('contact_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="site_description">Deskripsi Situs</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                        </div>
                                        <textarea id="site_description" name="site_description" class="form-control @error('site_description') is-invalid @enderror" rows="4" placeholder="Contoh: Sistem Informasi Desa Terintegrasi" maxlength="500">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                                        @error('site_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <small class="form-text text-muted">Dipakai untuk metadata portal publik dan konteks branding aplikasi.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="village" role="tabpanel">
                    <div class="setting-panel">
                        <div class="panel-heading">
                            <div>
                                <span>Desa</span>
                                <h2>Identitas & Kontak Desa</h2>
                            </div>
                            <i class="fas fa-landmark"></i>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="village_nama">Nama Desa</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span></div>
                                        <input type="text" id="village_nama" name="village_nama" class="form-control @error('village_nama') is-invalid @enderror" value="{{ old('village_nama', $settings['village_nama'] ?? '') }}" placeholder="Contoh: Desa SIMADES" maxlength="150">
                                        @error('village_nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="village_kecamatan">Kecamatan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-map"></i></span></div>
                                        <input type="text" id="village_kecamatan" name="village_kecamatan" class="form-control @error('village_kecamatan') is-invalid @enderror" value="{{ old('village_kecamatan', $settings['village_kecamatan'] ?? '') }}" placeholder="Contoh: Kecamatan Sukamaju" maxlength="150">
                                        @error('village_kecamatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="village_kabupaten">Kabupaten/Kota</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-city"></i></span></div>
                                        <input type="text" id="village_kabupaten" name="village_kabupaten" class="form-control @error('village_kabupaten') is-invalid @enderror" value="{{ old('village_kabupaten', $settings['village_kabupaten'] ?? '') }}" placeholder="Contoh: Kabupaten Bandung" maxlength="150">
                                        @error('village_kabupaten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="village_provinsi">Provinsi</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-flag"></i></span></div>
                                        <input type="text" id="village_provinsi" name="village_provinsi" class="form-control @error('village_provinsi') is-invalid @enderror" value="{{ old('village_provinsi', $settings['village_provinsi'] ?? '') }}" placeholder="Contoh: Jawa Barat" maxlength="150">
                                        @error('village_provinsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="village_email">Email Desa</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                        <input type="email" id="village_email" name="village_email" class="form-control @error('village_email') is-invalid @enderror" value="{{ old('village_email', $settings['village_email'] ?? '') }}" placeholder="Contoh: desa@simades.id" maxlength="150">
                                        @error('village_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="village_telepon">Telepon Desa</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                                        <input type="text" id="village_telepon" name="village_telepon" class="form-control @error('village_telepon') is-invalid @enderror" value="{{ old('village_telepon', $settings['village_telepon'] ?? '') }}" placeholder="Contoh: 022-123456" maxlength="50">
                                        @error('village_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="village_website">Website Desa</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-link"></i></span></div>
                                        <input type="text" id="village_website" name="village_website" class="form-control @error('village_website') is-invalid @enderror" value="{{ old('village_website', $settings['village_website'] ?? '') }}" placeholder="Contoh: https://desa.simades.id" maxlength="150">
                                        @error('village_website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="village_nama_kades">Nama Kepala Desa</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-tie"></i></span></div>
                                        <input type="text" id="village_nama_kades" name="village_nama_kades" class="form-control @error('village_nama_kades') is-invalid @enderror" value="{{ old('village_nama_kades', $settings['village_nama_kades'] ?? '') }}" placeholder="Contoh: Bapak/Ibu Kepala Desa" maxlength="150">
                                        @error('village_nama_kades')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="village_nip_kades">NIP Kepala Desa</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-id-card"></i></span></div>
                                        <input type="text" id="village_nip_kades" name="village_nip_kades" class="form-control @error('village_nip_kades') is-invalid @enderror" value="{{ old('village_nip_kades', $settings['village_nip_kades'] ?? '') }}" placeholder="Contoh: 198001012010011001" maxlength="50">
                                        @error('village_nip_kades')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="village_alamat">Alamat Lengkap</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-road"></i></span></div>
                                        <textarea id="village_alamat" name="village_alamat" class="form-control @error('village_alamat') is-invalid @enderror" rows="4" placeholder="Contoh: Jl. Raya Desa No. 123, Kecamatan, Kabupaten, Provinsi" maxlength="500">{{ old('village_alamat', $settings['village_alamat'] ?? '') }}</textarea>
                                        @error('village_alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="branding" role="tabpanel">
                    <div class="setting-panel">
                        <div class="panel-heading">
                            <div>
                                <span>Branding</span>
                                <h2>Logo Desa</h2>
                            </div>
                            <i class="fas fa-image"></i>
                        </div>

                        <div class="branding-grid">
                            <div class="logo-preview-card">
                                <span>Preview Logo</span>
                                <img id="logoPreview" src="{{ asset($logoPath) }}" alt="Preview Logo Desa" loading="lazy">
                            </div>

                            <div>
                                <div class="form-group">
                                    <label for="village_logo">Upload Logo Desa</label>
                                    <div class="custom-file setting-file">
                                        <input type="file" id="village_logo" name="village_logo" class="custom-file-input @error('village_logo') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                                        <label class="custom-file-label" for="village_logo">Pilih gambar logo...</label>
                                        @error('village_logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB. Preview akan berubah otomatis setelah file dipilih.</small>
                                </div>

                                <div class="branding-note">
                                    <i class="fas fa-info-circle"></i>
                                    <div>
                                        <strong>Dipakai di kop surat dan layout aplikasi</strong>
                                        <span>Logo ini tampil pada navbar, halaman login, portal publik, dan PDF surat.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-actions">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
                @can('setting-update')
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                </button>
                @endcan
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .setting-page { color: #1f2937; }
    .setting-hero {
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
    .setting-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .setting-hero p {
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
    .setting-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .setting-shell,
    .setting-panel,
    .setting-actions {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .setting-shell {
        display: grid;
        grid-template-columns: 260px minmax(0, 1fr);
        gap: 1rem;
        padding: 1rem;
    }
    .setting-tabs {
        display: grid;
        align-content: start;
        gap: 0.65rem;
    }
    .setting-tab {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.85rem;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        color: #334155;
        background: #f8fafc;
        font-weight: 800;
    }
    .setting-tab:hover { color: #0f766e; }
    .setting-tab.active {
        color: #ffffff;
        border-color: #0f766e;
        background: #0f766e;
    }
    .setting-tab i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 10px;
        background: rgba(15, 118, 110, 0.12);
    }
    .setting-tab.active i { background: rgba(255, 255, 255, 0.2); }
    .setting-panel {
        padding: 1rem;
        box-shadow: none;
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
    .setting-panel label {
        color: #374151;
        font-weight: 800;
    }
    .setting-panel .form-control,
    .setting-panel .input-group-text,
    .setting-file .custom-file-label {
        border-color: #dbe3ef;
    }
    .setting-panel .input-group-text {
        color: #0f766e;
        background: #f8fafc;
    }
    .setting-panel .form-control {
        min-height: 42px;
        border-radius: 8px;
    }
    .setting-panel textarea.form-control { min-height: 112px; }
    .branding-grid {
        display: grid;
        grid-template-columns: 260px minmax(0, 1fr);
        gap: 1rem;
        align-items: start;
    }
    .logo-preview-card {
        display: grid;
        place-items: center;
        gap: 0.75rem;
        min-height: 240px;
        padding: 1rem;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
    }
    .logo-preview-card span {
        color: #64748b;
        font-weight: 800;
    }
    .logo-preview-card img {
        max-width: 170px;
        max-height: 170px;
        object-fit: contain;
        border-radius: 12px;
        background: #ffffff;
        padding: 0.75rem;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.1);
    }
    .branding-note {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        color: #075985;
        background: #e0f2fe;
    }
    .branding-note strong,
    .branding-note span { display: block; }
    .setting-actions {
        grid-column: 2;
        display: flex;
        justify-content: flex-end;
        gap: 0.55rem;
        padding: 1rem;
        box-shadow: none;
    }
    @media (max-width: 991.98px) {
        .setting-shell,
        .branding-grid {
            grid-template-columns: 1fr;
        }
        .setting-tabs {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .setting-actions { grid-column: 1; }
    }
    @media (max-width: 767.98px) {
        .setting-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .setting-hero h1 { font-size: 1.5rem; }
        .setting-hero .btn,
        .setting-actions .btn { width: 100%; }
        .setting-tabs { grid-template-columns: 1fr; }
        .setting-actions { flex-direction: column-reverse; }
    }
</style>
@endpush

@push('scripts')
<script>
    $(function () {
        if (window.bsCustomFileInput) {
            bsCustomFileInput.init();
        }

        $('#village_logo').on('change', function () {
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
                $('#logoPreview').attr('src', event.target.result);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
