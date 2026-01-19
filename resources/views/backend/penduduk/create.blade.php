@extends('layouts.app-backend')

@section('title', $title)

@section('content')
<div class="d-flex align-items-center mb-3">
    <div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item"><a href="{{ route('penduduk.index') }}">Data Penduduk</a></li>
            <li class="breadcrumb-item active">{{ $title }}</li>
        </ul>
        <h1 class="page-header mb-0">
            {{ $title }}
        </h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('penduduk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIK <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                        <input type="number" name="nik" class="form-control" placeholder="16 digit NIK"
                            value="{{ old('nik') }}" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap sesuai KTP"
                            value="{{ old('nama') }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                        <input type="text" name="tempat_lahir" class="form-control" placeholder="Kota Kelahiran"
                            value="{{ old('tempat_lahir') }}" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir') }}"
                            required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-venus-mars"></i></span>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin')=='L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin')=='P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Agama <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-pray"></i></span>
                        <select name="agama" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Islam" {{ old('agama')=='Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama')=='Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama')=='Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama')=='Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama')=='Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama')=='Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status Perkawinan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-ring"></i></span>
                        <select name="status_perkawinan" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Belum Kawin" {{ old('status_perkawinan')=='Belum Kawin' ? 'selected' : '' }}>
                                Belum Kawin</option>
                            <option value="Kawin" {{ old('status_perkawinan')=='Kawin' ? 'selected' : '' }}>Kawin
                            </option>
                            <option value="Cerai Hidup" {{ old('status_perkawinan')=='Cerai Hidup' ? 'selected' : '' }}>
                                Cerai Hidup</option>
                            <option value="Cerai Mati" {{ old('status_perkawinan')=='Cerai Mati' ? 'selected' : '' }}>
                                Cerai Mati</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-briefcase"></i></span>
                        <input type="text" name="pekerjaan" class="form-control" placeholder="Pekerjaan"
                            value="{{ old('pekerjaan') }}" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-map-marked-alt"></i></span>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat Jalan/Dusun"
                        required>{{ old('alamat') }}</textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">RT <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-home"></i></span>
                        <input type="number" name="rt" class="form-control" placeholder="001" value="{{ old('rt') }}"
                            required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">RW <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                        <input type="number" name="rw" class="form-control" placeholder="001" value="{{ old('rw') }}"
                            required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto KTP (Opsional)</label>
                <div class="input-group">
                    <input type="file" name="foto_ktp" class="form-control" id="fotoKtpInput" accept="image/*">
                </div>
                <div class="mt-2">
                    <img id="fotoKtpPreview" src="{{ asset('assets/img/user/user.jpg') }}" alt="Preview"
                        class="img-thumbnail" style="max-height: 200px;">
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('penduduk.index') }}" class="btn btn-default me-2">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan Data</button>
            </div>
        </form>
    </div>
</div>

@push('js')
<script>
    document.getElementById('fotoKtpInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('fotoKtpPreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection