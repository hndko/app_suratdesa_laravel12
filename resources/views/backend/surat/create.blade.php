@extends('layouts.app-backend')

@section('title', $title)

@push('css')
<link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
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

<div class="card">
    <div class="card-body">
        <form action="{{ route('surat.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
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
                <div class="col-md-6">
                    <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_surat"
                        value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                </div>
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
                    placeholder="Contoh: Persyaratan Administrasi Bank" required>{{ old('keperluan') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan Tambahan (Opsional)</label>
                <textarea class="form-control" name="keterangan" rows="3"
                    placeholder="Tambahkan catatan jika perlu...">{{ old('keterangan') }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('surat.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Buat Surat</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'default', // Studio template usually overrides default or uses bootstrap4 theme if available
            width: '100%'
        });
    });
</script>
@endpush