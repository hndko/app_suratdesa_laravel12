@extends('layouts.app-backend')

@section('title', $title)

@push('css')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<!-- Content Header -->
<div class="content-header ps-0 pe-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Buat Surat Baru</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('surat.index') }}">Arsip Surat</a></li>
                    <li class="breadcrumb-item active">Buat Surat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Form -->
    <div class="col-md-6">
        <div class="card card-primary card-outline h-100">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i> Form Data Surat
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('surat.store') }}" method="POST" id="formSurat">
                    @csrf

                    <div class="form-group">
                        <label>Jenis Surat <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="jenis_surat_id" style="width: 100%;" required>
                            <option value="">-- Pilih Jenis Surat --</option>
                            @foreach($jenis_surats as $js)
                            <option value="{{ $js->id }}" {{ old('jenis_surat_id')==$js->id ? 'selected' : '' }}>
                                {{ $js->kode_surat }} - {{ $js->nama_surat }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Surat <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="date" class="form-control" name="tanggal_surat"
                                value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Penduduk <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="penduduk_id" style="width: 100%;" required>
                            <option value="">-- Cari Penduduk (Nama / NIK) --</option>
                            @foreach($penduduks as $p)
                            <option value="{{ $p->id }}" {{ old('penduduk_id')==$p->id ? 'selected' : '' }}>
                                {{ $p->nik }} - {{ $p->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Keperluan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="keperluan" rows="3"
                            placeholder="Contoh: Persyaratan Administrasi Bank"
                            required>{{ old('keperluan') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Keterangan Tambahan (Opsional)</label>
                        <textarea class="form-control" name="keterangan" rows="3"
                            placeholder="Tambahkan catatan jika perlu...">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('surat.index') }}" class="btn btn-default mr-2">Batal</a>
                        <button type="button" class="btn btn-info mr-2" id="btnPreview"><i class="fas fa-eye mr-1"></i>
                            Preview</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Preview -->
    <div class="col-md-6">
        <div class="card card-info card-outline h-100">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-alt mr-1"></i> Preview Dokumen
                </h3>
            </div>
            <div class="card-body bg-light overflow-auto" id="previewContainer" style="max-height: 800px;">
                <div class="text-center p-5 text-muted" id="previewPlaceholder">
                    <i class="fas fa-file-invoice fa-4x mb-3 text-secondary"></i>
                    <p>Silahkan lengkapi form dan klik tombol <b>Preview</b>.</p>
                </div>
                <div id="previewContent" class="d-none"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        $('#btnPreview').click(function() {
            var form = $('#formSurat');
            var data = form.serialize();

            // Basic validation check
            if(!$('select[name="jenis_surat_id"]').val() || !$('select[name="penduduk_id"]').val()) {
                alert('Mohon pilih Jenis Surat dan Penduduk terlebih dahulu.');
                return;
            }

            // Show loading state in right column
            $('#previewPlaceholder').addClass('d-none');
            $('#previewContent').removeClass('d-none').html('<div class="text-center p-5"><div class="spinner-border text-primary"></div><p class="mt-2">Sedang membuat preview...</p></div>');

            $.ajax({
                url: "{{ route('surat.preview') }}",
                type: "POST",
                data: data,
                success: function(response) {
                    $('#previewContent').html(response.html);
                },
                error: function(xhr) {
                    $('#previewContent').html('<div class="alert alert-danger">Gagal memuat preview. Pastikan semua data wajib diisi.</div>');
                }
            });
        });
    });
</script>
@endpush