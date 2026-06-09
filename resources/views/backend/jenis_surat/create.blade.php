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
            <p>Tambahkan jenis layanan surat baru beserta kode dan kop judul yang akan muncul pada dokumen resmi.</p>
        </div>
        <a href="{{ route('jenis-surat.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="letter-form-grid">
        <form action="{{ route('jenis-surat.store') }}" method="POST" class="letter-form-panel">
            @csrf
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
                        <input type="text" id="kode_surat" class="form-control @error('kode_surat') is-invalid @enderror" name="kode_surat" value="{{ old('kode_surat') }}" placeholder="Contoh: 470" required>
                        @error('kode_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group col-md-7">
                    <label for="nama_surat">Nama Surat <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-file-alt"></i></span></div>
                        <input type="text" id="nama_surat" class="form-control @error('nama_surat') is-invalid @enderror" name="nama_surat" value="{{ old('nama_surat') }}" placeholder="Contoh: Surat Keterangan Usaha" required>
                        @error('nama_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="kop_judul">Kop Judul Surat <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-heading"></i></span></div>
                    <input type="text" id="kop_judul" class="form-control @error('kop_judul') is-invalid @enderror" name="kop_judul" value="{{ old('kop_judul') }}" placeholder="Contoh: SURAT KETERANGAN USAHA" required>
                    @error('kop_judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="template_isi">Template Awal</label>
                <div class="input-group textarea-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-file-code"></i></span></div>
                    <textarea id="template_isi" name="template_isi" class="form-control @error('template_isi') is-invalid @enderror" rows="7" placeholder="Opsional. Template detail bisa diatur dari tombol template setelah data disimpan.">{{ old('template_isi') }}</textarea>
                    @error('template_isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('jenis-surat.index') }}" class="btn btn-light">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan Data
                </button>
            </div>
        </form>

        <div class="letter-guide-panel">
            <div class="panel-heading">
                <div>
                    <span>Panduan</span>
                    <h2>Tips Penulisan</h2>
                </div>
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="guide-list">
                <div><i class="fas fa-barcode"></i><span>Kode surat sebaiknya singkat dan mengikuti klasifikasi arsip desa.</span></div>
                <div><i class="fas fa-heading"></i><span>Kop judul ditulis formal karena dipakai pada PDF surat.</span></div>
                <div><i class="fas fa-file-code"></i><span>Template bisa dilengkapi nanti lewat editor template dan bantuan AI Surat.</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
