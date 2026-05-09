@extends('layouts.app-backend')

@section('title', $title)

@section('content')
<div class="content-header ps-0 pe-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pengaturan Website & Desa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengaturan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="card card-outline card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="settingTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab">Umum</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="village-tab" data-toggle="pill" href="#village" role="tab">Identitas Desa</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="settingTabContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Situs</label>
                                        <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi Situs</label>
                                        <textarea name="site_description" class="form-control" rows="3">{{ $settings['site_description'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kontak WhatsApp (Public)</label>
                                        <input type="text" name="contact_whatsapp" class="form-control" value="{{ $settings['contact_whatsapp'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Village Settings -->
                        <div class="tab-pane fade" id="village" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Desa</label>
                                        <input type="text" name="village_nama" class="form-control" value="{{ $settings['village_nama'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Kecamatan</label>
                                        <input type="text" name="village_kecamatan" class="form-control" value="{{ $settings['village_kecamatan'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Kabupaten</label>
                                        <input type="text" name="village_kabupaten" class="form-control" value="{{ $settings['village_kabupaten'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Provinsi</label>
                                        <input type="text" name="village_provinsi" class="form-control" value="{{ $settings['village_provinsi'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Alamat Lengkap</label>
                                        <textarea name="village_alamat" class="form-control" rows="2">{{ $settings['village_alamat'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email Desa</label>
                                        <input type="email" name="village_email" class="form-control" value="{{ $settings['village_email'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Telepon</label>
                                        <input type="text" name="village_telepon" class="form-control" value="{{ $settings['village_telepon'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input type="text" name="village_website" class="form-control" value="{{ $settings['village_website'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Kepala Desa</label>
                                        <input type="text" name="village_nama_kades" class="form-control" value="{{ $settings['village_nama_kades'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>NIP Kepala Desa</label>
                                        <input type="text" name="village_nip_kades" class="form-control" value="{{ $settings['village_nip_kades'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Logo Desa</label>
                                        <div class="custom-file">
                                            <input type="file" name="village_logo" class="custom-file-input" id="village_logo">
                                            <label class="custom-file-label" for="village_logo">Pilih file...</label>
                                        </div>
                                        <p class="small text-muted mt-1">Logo saat ini:</p>
                                        <img src="{{ asset($settings['village_logo'] ?? 'assets/img/logo.png') }}" alt="Logo" class="img-thumbnail" style="height: 80px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save mr-1"></i> Simpan Pengaturan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        bsCustomFileInput.init();
    });
</script>
@endpush
