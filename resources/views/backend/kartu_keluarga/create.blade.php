@extends('layouts.app-backend')

@section('content')
<div class="d-flex align-items-center mb-3">
    <div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kartu-keluarga.index') }}">Data Kartu Keluarga</a></li>
            <li class="breadcrumb-item active">{{ $title }}</li>
        </ul>
        <h1 class="page-header mb-0">{{ $title }}</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('kartu-keluarga.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>No. KK <span class="text-danger">*</span></label>
                    <input type="text" name="no_kk" class="form-control" value="{{ old('no_kk') }}" maxlength="16" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Kepala Keluarga <span class="text-danger">*</span></label>
                    <input type="text" name="kepala_keluarga" class="form-control" value="{{ old('kepala_keluarga') }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Alamat <span class="text-danger">*</span></label>
                <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label>RT <span class="text-danger">*</span></label>
                    <input type="text" name="rt" class="form-control" value="{{ old('rt') }}" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label>RW <span class="text-danger">*</span></label>
                    <input type="text" name="rw" class="form-control" value="{{ old('rw') }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Desa</label>
                    <input type="text" name="desa" class="form-control" value="{{ old('desa') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Kabupaten</label>
                    <input type="text" name="kabupaten" class="form-control" value="{{ old('kabupaten') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Provinsi</label>
                    <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos') }}">
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('kartu-keluarga.index') }}" class="btn btn-default mr-2">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
