```
@extends('layouts.app-backend')

@section('title', $title)

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex align-items-center mb-3">
    <div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
            <li class="breadcrumb-item"><a href="{{ route('surat.index') }}">Arsip Surat</a></li>
            <li class="breadcrumb-item active">Buat Surat</li>
        </ul>
        <h1 class="page-header mb-0">Buat Surat Baru</h1>
    </div>
</div>

<div class="row">
    <!-- Left Column: Form -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header bg-none fw-bold">
                <i class="fa fa-edit me-1"></i> Form Data Surat
            </div>
            <div class="card-body">
                <form action="{{ route('surat.store') }}" method="POST" id="formSurat">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="jenis_surat_id" required>
                            <option value="">-- Pilih Jenis Surat --</option>
                            @foreach($jenis_surats as $js)
                            <option value="{{ $js->id }}" {{ old('jenis_surat_id')==$js->id ? 'selected' : '' }}>
                                {{ $js->kode_surat }} - {{ $js->nama_surat }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_surat"
                            value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Penduduk <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="penduduk_id" required>
                            <option value="">-- Cari Penduduk (Nama / NIK) --</option>
                            @foreach($penduduks as $p)
                            <option value="{{ $p->id }}" {{ old('penduduk_id')==$p->id ? 'selected' : '' }}>
                                {{ $p->nik }} - {{ $p->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="keperluan" rows="3"
                            placeholder="Contoh: Persyaratan Administrasi Bank"
                            required>{{ old('keperluan') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan (Opsional)</label>
                        <textarea class="form-control" name="keterangan" rows="3"
                            placeholder="Tambahkan catatan jika perlu...">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('surat.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="button" class="btn btn-info text-white" id="btnPreview"><i
                                class="fa fa-eye me-1"></i> Lihat Preview</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan
                            Surat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Preview -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header bg-none fw-bold">
                <i class="fa fa-file-alt me-1"></i> Preview Dokumen
            </div>
            <div class="card-body bg-light overflow-auto" id="previewContainer" style="max-height: 800px;">
                <div class="text-center p-5 text-muted" id="previewPlaceholder">
                    <i class="fa fa-file-circle-question fa-4x mb-3 text-opacity-25 text-body"></i>
                    <p>Silahkan lengkapi form dan klik tombol <b>Lihat Preview</b> untuk melihat hasil surat.</p>
                </div>
                <div id="previewContent" class="d-none"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
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
```