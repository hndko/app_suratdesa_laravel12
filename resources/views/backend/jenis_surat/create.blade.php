@extends('layouts.app-backend')

@section('title', $title)

@section('content')
<div class="d-flex align-items-center mb-3">
    <div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item"><a href="{{ route('jenis-surat.index') }}">Jenis Surat</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ul>
        <h1 class="page-header mb-0">Tambah Jenis Surat</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('jenis-surat.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Kode Surat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kode_surat') is-invalid @enderror" name="kode_surat"
                        value="{{ old('kode_surat') }}" placeholder="Contoh: 470">
                    @error('kode_surat')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Surat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_surat') is-invalid @enderror" name="nama_surat"
                        value="{{ old('nama_surat') }}" placeholder="Contoh: Surat Keterangan Usaha">
                    @error('nama_surat')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Kop Judul Surat <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('kop_judul') is-invalid @enderror" name="kop_judul"
                    value="{{ old('kop_judul') }}" placeholder="SURAT KETERANGAN USAHA">
                @error('kop_judul')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Template Isi Surat <span class="text-danger">*</span></label>
                <textarea class="form-control @error('template_isi') is-invalid @enderror" name="template_isi" rows="10"
                    placeholder="Tulis template surat disini...">{{ old('template_isi') }}</textarea>
                <small class="text-muted">Gunakan variabel seperti [nama], [nik], [alamat] untuk data dinamis.</small>
                @error('template_isi')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('jenis-surat.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection