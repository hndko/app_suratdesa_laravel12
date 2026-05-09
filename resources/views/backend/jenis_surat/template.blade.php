@extends('layouts.app-backend')

@section('title', $title)

@push('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.min.css') }}">
<style>
    .placeholder-badge {
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 5px;
        display: inline-block;
    }
    .placeholder-badge:hover {
        transform: scale(1.1);
        filter: brightness(1.1);
    }
</style>
@endpush

@section('content')
<div class="content-header ps-0 pe-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Atur Template Surat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('jenis-surat.index') }}">Jenis Surat</a></li>
                    <li class="breadcrumb-item active">Template</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-9">
        <form action="{{ route('jenis-surat.template.update', $jenis_surat->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Editor Template: <strong>{{ $jenis_surat->nama_surat }}</strong></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea name="template_isi" id="summernote" class="form-control" rows="20">{{ $jenis_surat->template_isi }}</textarea>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('jenis-surat.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save mr-1"></i> Simpan Template</button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-3">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Variabel Data</h3>
            </div>
            <div class="card-body">
                <p class="small text-muted">Klik label di bawah untuk menyalin kode placeholder, lalu tempelkan ke dalam editor.</p>
                
                <div class="mb-3">
                    <label class="small font-weight-bold d-block">Data Penduduk:</label>
                    <span class="badge badge-info placeholder-badge" data-code="[nama]">Nama Lengkap</span>
                    <span class="badge badge-info placeholder-badge" data-code="[nik]">NIK</span>
                    <span class="badge badge-info placeholder-badge" data-code="[tempat_lahir]">Tempat Lahir</span>
                    <span class="badge badge-info placeholder-badge" data-code="[tgl_lahir]">Tanggal Lahir</span>
                    <span class="badge badge-info placeholder-badge" data-code="[jenis_kelamin]">Jenis Kelamin</span>
                    <span class="badge badge-info placeholder-badge" data-code="[agama]">Agama</span>
                    <span class="badge badge-info placeholder-badge" data-code="[pekerjaan]">Pekerjaan</span>
                    <span class="badge badge-info placeholder-badge" data-code="[alamat]">Alamat Lengkap</span>
                    <span class="badge badge-info placeholder-badge" data-code="[rt]">RT</span>
                    <span class="badge badge-info placeholder-badge" data-code="[rw]">RW</span>
                </div>

                <div class="mb-0">
                    <label class="small font-weight-bold d-block">Data Lainnya:</label>
                    <span class="badge badge-success placeholder-badge" data-code="[keperluan]">Keperluan Surat</span>
                    <span class="badge badge-success placeholder-badge" data-code="[tanggal_surat]">Tanggal Surat</span>
                </div>
                <hr>
                <div class="alert alert-light border small">
                    <i class="fas fa-lightbulb text-warning mr-1"></i> <strong>Tips:</strong> Gunakan tabel di editor untuk merapikan bagian identitas (Nama: [nama], dsb).
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
    $(function () {
        $('#summernote').summernote({
            height: 500,
            placeholder: 'Desain template surat Anda di sini...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('.placeholder-badge').on('click', function() {
            var code = $(this).data('code');
            // Copy to clipboard or just alert for now, but better to insert into summernote
            $('#summernote').summernote('editor.insertText', code);
        });
    });
</script>
@endpush
