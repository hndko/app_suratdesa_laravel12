@extends('layouts.app-backend')

@section('content')
@php
    $logoPath = $settings['village_logo'] ?? 'assets/img/logo.png';
    $siteLogoPath = $settings['site_logo'] ?? $logoPath;
    $faviconPath = $settings['site_favicon'] ?? 'assets/img/favicon.png';
    $ogImagePath = $settings['seo_og_image'] ?? $siteLogoPath;
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
                <a class="setting-tab" id="public-tab" data-toggle="pill" href="#public" role="tab">
                    <i class="fas fa-desktop"></i>
                    <span>Portal Publik</span>
                </a>
                <a class="setting-tab" id="branding-tab" data-toggle="pill" href="#branding" role="tab">
                    <i class="fas fa-image"></i>
                    <span>Logo & Branding</span>
                </a>
                <a class="setting-tab" id="seo-tab" data-toggle="pill" href="#seo" role="tab">
                    <i class="fas fa-chart-line"></i>
                    <span>SEO</span>
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

                <div class="tab-pane fade" id="public" role="tabpanel">
                    <div class="setting-panel">
                        <div class="panel-heading">
                            <div>
                                <span>Frontend</span>
                                <h2>Konten Portal Publik</h2>
                            </div>
                            <i class="fas fa-desktop"></i>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="public_brand_tagline">Tagline Brand Navbar</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-id-badge"></i></span></div>
                                        <input type="text" id="public_brand_tagline" name="public_brand_tagline" class="form-control @error('public_brand_tagline') is-invalid @enderror" value="{{ old('public_brand_tagline', $settings['public_brand_tagline'] ?? '') }}" placeholder="Contoh: Portal Layanan Desa" maxlength="100">
                                        @error('public_brand_tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="public_footer_cta_title">Judul CTA Footer</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-bullhorn"></i></span></div>
                                        <input type="text" id="public_footer_cta_title" name="public_footer_cta_title" class="form-control @error('public_footer_cta_title') is-invalid @enderror" value="{{ old('public_footer_cta_title', $settings['public_footer_cta_title'] ?? '') }}" placeholder="Contoh: Layanan Mandiri" maxlength="100">
                                        @error('public_footer_cta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="public_footer_description">Deskripsi Footer</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-align-left"></i></span></div>
                                        <textarea id="public_footer_description" name="public_footer_description" class="form-control @error('public_footer_description') is-invalid @enderror" rows="3" placeholder="Deskripsi singkat portal publik yang tampil di footer" maxlength="500">{{ old('public_footer_description', $settings['public_footer_description'] ?? '') }}</textarea>
                                        @error('public_footer_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="public_footer_cta_text">Deskripsi CTA Footer</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-comment-alt"></i></span></div>
                                        <textarea id="public_footer_cta_text" name="public_footer_cta_text" class="form-control @error('public_footer_cta_text') is-invalid @enderror" rows="2" placeholder="Kalimat ajakan layanan mandiri di footer" maxlength="300">{{ old('public_footer_cta_text', $settings['public_footer_cta_text'] ?? '') }}</textarea>
                                        @error('public_footer_cta_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="public_footer_cta_button">Label Tombol CTA Footer</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-mouse-pointer"></i></span></div>
                                        <input type="text" id="public_footer_cta_button" name="public_footer_cta_button" class="form-control @error('public_footer_cta_button') is-invalid @enderror" value="{{ old('public_footer_cta_button', $settings['public_footer_cta_button'] ?? '') }}" placeholder="Contoh: Mulai Pengajuan" maxlength="80">
                                        @error('public_footer_cta_button')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="public-copy-grid">
                            @php
                                $publicPages = [
                                    ['prefix' => 'public_home_hero', 'icon' => 'fas fa-home', 'title' => 'Beranda', 'titlePlaceholder' => 'Pelayanan desa digital yang mudah dipantau dari rumah.', 'descriptionPlaceholder' => 'Ajukan surat, kirim pengaduan, lacak proses layanan, dan cek keaslian dokumen melalui satu portal publik.'],
                                    ['prefix' => 'public_surat_create_hero', 'icon' => 'fas fa-file-signature', 'title' => 'Pengajuan Surat', 'titlePlaceholder' => 'Ajukan surat desa tanpa antre berulang.', 'descriptionPlaceholder' => 'Isi NIK, pilih jenis surat, tulis keperluan, lalu simpan kode tracking.'],
                                    ['prefix' => 'public_surat_track_hero', 'icon' => 'fas fa-search', 'title' => 'Lacak Surat', 'titlePlaceholder' => 'Lacak status pengajuan surat secara mandiri.', 'descriptionPlaceholder' => 'Masukkan kode tracking dan NIK pemohon untuk melihat posisi pengajuan.'],
                                    ['prefix' => 'public_pengaduan_create_hero', 'icon' => 'fas fa-comments', 'title' => 'Kirim Pengaduan', 'titlePlaceholder' => 'Sampaikan laporan warga dengan jelas dan mudah ditindaklanjuti.', 'descriptionPlaceholder' => 'Gunakan formulir untuk mengirim keluhan, aspirasi, atau laporan kejadian.'],
                                    ['prefix' => 'public_pengaduan_track_hero', 'icon' => 'fas fa-ticket-alt', 'title' => 'Lacak Pengaduan', 'titlePlaceholder' => 'Pantau progres tindak lanjut pengaduan Anda.', 'descriptionPlaceholder' => 'Masukkan kode tiket dan NIK pelapor untuk melihat status aduan.'],
                                    ['prefix' => 'public_verifikasi_hero', 'icon' => 'fas fa-qrcode', 'title' => 'Verifikasi Surat', 'titlePlaceholder' => 'Pastikan surat desa benar-benar diterbitkan oleh SIMADES.', 'descriptionPlaceholder' => 'Masukkan kode verifikasi dari QR atau PDF surat untuk melihat status validitas dokumen.'],
                                    ['prefix' => 'public_pengumuman_hero', 'icon' => 'fas fa-bullhorn', 'title' => 'Pengumuman', 'titlePlaceholder' => 'Informasi resmi desa dalam satu halaman yang mudah dipantau.', 'descriptionPlaceholder' => 'Lihat pengumuman terbaru, agenda layanan, informasi kegiatan, dan kabar penting desa.'],
                                ];
                            @endphp
                            @foreach($publicPages as $page)
                                <div class="public-copy-card">
                                    <div class="public-copy-heading">
                                        <i class="{{ $page['icon'] }}"></i>
                                        <strong>{{ $page['title'] }}</strong>
                                    </div>
                                    @if($page['prefix'] === 'public_home_hero')
                                        <div class="form-group">
                                            <label for="{{ $page['prefix'] }}_eyebrow">Eyebrow Beranda</label>
                                            <input type="text" id="{{ $page['prefix'] }}_eyebrow" name="{{ $page['prefix'] }}_eyebrow" class="form-control @error($page['prefix'] . '_eyebrow') is-invalid @enderror" value="{{ old($page['prefix'] . '_eyebrow', $settings[$page['prefix'] . '_eyebrow'] ?? '') }}" placeholder="Contoh: Portal Resmi Desa" maxlength="100">
                                            @error($page['prefix'] . '_eyebrow')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="{{ $page['prefix'] }}_title">Judul Hero</label>
                                        <input type="text" id="{{ $page['prefix'] }}_title" name="{{ $page['prefix'] }}_title" class="form-control @error($page['prefix'] . '_title') is-invalid @enderror" value="{{ old($page['prefix'] . '_title', $settings[$page['prefix'] . '_title'] ?? '') }}" placeholder="{{ $page['titlePlaceholder'] }}" maxlength="150">
                                        @error($page['prefix'] . '_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="{{ $page['prefix'] }}_description">Deskripsi Hero</label>
                                        <textarea id="{{ $page['prefix'] }}_description" name="{{ $page['prefix'] }}_description" class="form-control @error($page['prefix'] . '_description') is-invalid @enderror" rows="3" placeholder="{{ $page['descriptionPlaceholder'] }}" maxlength="300">{{ old($page['prefix'] . '_description', $settings[$page['prefix'] . '_description'] ?? '') }}</textarea>
                                        @error($page['prefix'] . '_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-6">
                                <div class="form-group mb-lg-0">
                                    <label for="public_home_service_title">Judul Section Layanan Beranda</label>
                                    <input type="text" id="public_home_service_title" name="public_home_service_title" class="form-control @error('public_home_service_title') is-invalid @enderror" value="{{ old('public_home_service_title', $settings['public_home_service_title'] ?? '') }}" placeholder="Contoh: Pilih layanan sesuai kebutuhan warga" maxlength="150">
                                    @error('public_home_service_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-0">
                                    <label for="public_home_flow_title">Judul Section Alur Beranda</label>
                                    <input type="text" id="public_home_flow_title" name="public_home_flow_title" class="form-control @error('public_home_flow_title') is-invalid @enderror" value="{{ old('public_home_flow_title', $settings['public_home_flow_title'] ?? '') }}" placeholder="Contoh: Proses dibuat jelas dari awal sampai selesai" maxlength="150">
                                    @error('public_home_flow_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0 mt-3">
                                    <label for="public_home_flow_description">Deskripsi Section Alur Beranda</label>
                                    <textarea id="public_home_flow_description" name="public_home_flow_description" class="form-control @error('public_home_flow_description') is-invalid @enderror" rows="2" placeholder="Deskripsi alur layanan yang tampil di beranda" maxlength="300">{{ old('public_home_flow_description', $settings['public_home_flow_description'] ?? '') }}</textarea>
                                    @error('public_home_flow_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

                        <div class="asset-grid">
                            <div class="asset-card">
                                <div class="logo-preview-card">
                                    <span>Logo Aplikasi</span>
                                    <img id="siteLogoPreview" src="{{ asset($siteLogoPath) }}" alt="Preview Logo Aplikasi" loading="lazy">
                                </div>
                                <div class="form-group mb-0">
                                    <label for="site_logo">Upload Logo Aplikasi</label>
                                    <div class="custom-file setting-file">
                                        <input type="file" id="site_logo" name="site_logo" class="custom-file-input js-image-preview @error('site_logo') is-invalid @enderror" data-preview="#siteLogoPreview" accept="image/jpeg,image/png,image/webp">
                                        <label class="custom-file-label" for="site_logo">Pilih logo aplikasi...</label>
                                        @error('site_logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                    <small class="form-text text-muted">Dipakai di navbar, login, dan portal publik. Maksimal 2 MB.</small>
                                </div>
                            </div>

                            <div class="asset-card">
                                <div class="logo-preview-card">
                                    <span>Logo Desa</span>
                                    <img id="logoPreview" src="{{ asset($logoPath) }}" alt="Preview Logo Desa" loading="lazy">
                                </div>
                                <div class="form-group mb-0">
                                    <label for="village_logo">Upload Logo Desa/Kop Surat</label>
                                    <div class="custom-file setting-file">
                                        <input type="file" id="village_logo" name="village_logo" class="custom-file-input js-image-preview @error('village_logo') is-invalid @enderror" data-preview="#logoPreview" accept="image/jpeg,image/png,image/webp">
                                        <label class="custom-file-label" for="village_logo">Pilih logo desa...</label>
                                        @error('village_logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                    <small class="form-text text-muted">Dipakai di kop surat dan PDF. Maksimal 2 MB.</small>
                                </div>
                            </div>

                            <div class="asset-card">
                                <div class="logo-preview-card compact">
                                    <span>Favicon</span>
                                    <img id="faviconPreview" src="{{ asset($faviconPath) }}" alt="Preview Favicon" loading="lazy">
                                </div>
                                <div class="form-group mb-0">
                                    <label for="site_favicon">Upload Favicon</label>
                                    <div class="custom-file setting-file">
                                        <input type="file" id="site_favicon" name="site_favicon" class="custom-file-input js-image-preview @error('site_favicon') is-invalid @enderror" data-preview="#faviconPreview" accept="image/x-icon,image/vnd.microsoft.icon,image/png,image/jpeg,image/webp">
                                        <label class="custom-file-label" for="site_favicon">Pilih favicon...</label>
                                        @error('site_favicon')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                    <small class="form-text text-muted">Format ICO/PNG/JPG/WEBP. Maksimal 1 MB.</small>
                                </div>
                            </div>

                            <div class="asset-card">
                                <div class="logo-preview-card">
                                    <span>OG Image</span>
                                    <img id="ogImagePreview" src="{{ asset($ogImagePath) }}" alt="Preview OG Image" loading="lazy">
                                </div>
                                <div class="form-group mb-0">
                                    <label for="seo_og_image">Upload Gambar SEO/Social Share</label>
                                    <div class="custom-file setting-file">
                                        <input type="file" id="seo_og_image" name="seo_og_image" class="custom-file-input js-image-preview @error('seo_og_image') is-invalid @enderror" data-preview="#ogImagePreview" accept="image/jpeg,image/png,image/webp">
                                        <label class="custom-file-label" for="seo_og_image">Pilih OG image...</label>
                                        @error('seo_og_image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                    <small class="form-text text-muted">Dipakai saat link portal dibagikan. Rekomendasi 1200x630 px.</small>
                                </div>
                            </div>
                        </div>

                        <div class="branding-note mt-3">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <strong>Branding dipakai lintas layout</strong>
                                <span>Logo aplikasi, favicon, dan OG image akan dipakai oleh backend, login, portal publik, dan metadata SEO.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="seo" role="tabpanel">
                    <div class="setting-panel">
                        <div class="panel-heading">
                            <div>
                                <span>SEO</span>
                                <h2>Metadata Portal Publik</h2>
                            </div>
                            <i class="fas fa-chart-line"></i>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="seo_title">SEO Title</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-heading"></i></span></div>
                                        <input type="text" id="seo_title" name="seo_title" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title', $settings['seo_title'] ?? '') }}" placeholder="Contoh: SIMADES - Portal Pelayanan Desa" maxlength="150">
                                        @error('seo_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="seo_author">SEO Author</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-edit"></i></span></div>
                                        <input type="text" id="seo_author" name="seo_author" class="form-control @error('seo_author') is-invalid @enderror" value="{{ old('seo_author', $settings['seo_author'] ?? '') }}" placeholder="Contoh: Pemerintah Desa SIMADES" maxlength="100">
                                        @error('seo_author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="seo_keywords">SEO Keywords</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-tags"></i></span></div>
                                        <input type="text" id="seo_keywords" name="seo_keywords" class="form-control @error('seo_keywords') is-invalid @enderror" value="{{ old('seo_keywords', $settings['seo_keywords'] ?? '') }}" placeholder="Contoh: desa digital, surat desa, pengaduan warga" maxlength="300">
                                        @error('seo_keywords')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="seo_robots">SEO Robots</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-robot"></i></span></div>
                                        <input type="text" id="seo_robots" name="seo_robots" class="form-control @error('seo_robots') is-invalid @enderror" value="{{ old('seo_robots', $settings['seo_robots'] ?? 'index, follow') }}" placeholder="index, follow" maxlength="100">
                                        @error('seo_robots')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="seo_description">SEO Description</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-align-left"></i></span></div>
                                        <textarea id="seo_description" name="seo_description" class="form-control @error('seo_description') is-invalid @enderror" rows="3" placeholder="Ringkasan singkat portal publik untuk mesin pencari" maxlength="300">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                                        @error('seo_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-lg-0">
                                    <label for="seo_og_title">Open Graph Title</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-share-alt"></i></span></div>
                                        <input type="text" id="seo_og_title" name="seo_og_title" class="form-control @error('seo_og_title') is-invalid @enderror" value="{{ old('seo_og_title', $settings['seo_og_title'] ?? '') }}" placeholder="Judul saat link dibagikan" maxlength="150">
                                        @error('seo_og_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-0">
                                    <label for="seo_og_description">Open Graph Description</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-comment-alt"></i></span></div>
                                        <textarea id="seo_og_description" name="seo_og_description" class="form-control @error('seo_og_description') is-invalid @enderror" rows="2" placeholder="Deskripsi saat link dibagikan" maxlength="300">{{ old('seo_og_description', $settings['seo_og_description'] ?? '') }}</textarea>
                                        @error('seo_og_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
    .public-copy-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    .public-copy-card {
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #f8fafc;
    }
    .public-copy-heading {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.85rem;
        color: #111827;
        font-weight: 800;
    }
    .public-copy-heading i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 11px;
        color: #ffffff;
        background: #0f766e;
    }
    .branding-grid,
    .asset-grid {
        display: grid;
        grid-template-columns: 260px minmax(0, 1fr);
        gap: 1rem;
        align-items: start;
    }
    .asset-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .asset-card {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr);
        gap: 1rem;
        align-items: center;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #f8fafc;
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
    .logo-preview-card.compact {
        min-height: 180px;
    }
    .logo-preview-card.compact img {
        max-width: 96px;
        max-height: 96px;
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
        .branding-grid,
        .asset-grid,
        .asset-card {
            grid-template-columns: 1fr;
        }
        .setting-tabs {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .public-copy-grid {
            grid-template-columns: 1fr;
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

        $('.js-image-preview').on('change', function () {
            var file = this.files && this.files[0] ? this.files[0] : null;
            var $input = $(this);
            var previewTarget = $input.data('preview');
            if (!file) {
                return;
            }

            var allowedExtensions = /\.(ico|jpg|jpeg|png|webp)$/i;
            if (!allowedExtensions.test(file.name)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format tidak didukung',
                    text: 'Gunakan file ICO, JPG, JPEG, PNG, atau WEBP sesuai kebutuhan.',
                    confirmButtonText: 'Mengerti',
                    buttonsStyling: false,
                    customClass: { confirmButton: 'btn btn-primary' }
                });
                $input.val('');
                return;
            }

            var reader = new FileReader();
            reader.onload = function (event) {
                $(previewTarget).attr('src', event.target.result);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
