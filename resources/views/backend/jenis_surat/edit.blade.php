@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dist/css/simades-jenis-surat-form.css') }}">
@endpush

@section('content')
<div class="letter-form-page">
    <div class="letter-form-hero">
        <div>
            <span class="eyebrow">Master Data</span>
            <h1>{{ $title }}</h1>
            <p>Perbarui identitas jenis surat tanpa mengubah riwayat surat yang sudah pernah diterbitkan.</p>
        </div>
        <a href="{{ route('jenis-surat.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="letter-form-grid">
        <form action="{{ route('jenis-surat.update', $jenis_surat->id) }}" method="POST" class="letter-form-panel">
            @csrf
            @method('PUT')
            <div class="panel-heading">
                <div>
                    <span>Form Data</span>
                    <h2>Identitas Jenis Surat</h2>
                </div>
                <i class="fas fa-envelope-open-text"></i>
            </div>

            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="kode_surat">Kode Surat <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-hashtag"></i></span></div>
                        <input type="text" id="kode_surat" class="form-control @error('kode_surat') is-invalid @enderror" name="kode_surat" value="{{ old('kode_surat', $jenis_surat->kode_surat) }}" placeholder="Contoh: 470" required>
                        @error('kode_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group col-md-7">
                    <label for="nama_surat">Nama Surat <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-file-alt"></i></span></div>
                        <input type="text" id="nama_surat" class="form-control @error('nama_surat') is-invalid @enderror" name="nama_surat" value="{{ old('nama_surat', $jenis_surat->nama_surat) }}" placeholder="Contoh: Surat Keterangan Usaha" required>
                        @error('nama_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="kop_judul">Kop Judul Surat <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-heading"></i></span></div>
                    <input type="text" id="kop_judul" class="form-control @error('kop_judul') is-invalid @enderror" name="kop_judul" value="{{ old('kop_judul', $jenis_surat->kop_judul) }}" placeholder="Contoh: SURAT KETERANGAN USAHA" required>
                    @error('kop_judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('jenis-surat.index') }}" class="btn btn-light">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>

        <div class="letter-guide-panel">
            <div class="panel-heading">
                <div>
                    <span>Template</span>
                    <h2>Editor Template</h2>
                </div>
                <i class="fas fa-file-code"></i>
            </div>
            <div class="template-summary">
                <strong>{{ trim((string) $jenis_surat->template_isi) !== '' ? 'Template tersedia' : 'Template belum diatur' }}</strong>
                <span>Gunakan editor template untuk mengatur isi surat, import/export template, atau meminta saran AI Surat.</span>
                @can('jenis-surat-template')
                <a href="{{ route('jenis-surat.template', $jenis_surat->id) }}" class="btn btn-info btn-block">
                    <i class="fas fa-file-code mr-1"></i> Buka Editor Template
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
