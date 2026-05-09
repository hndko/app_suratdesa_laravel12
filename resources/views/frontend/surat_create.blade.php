@extends('layouts.app-frontend')

@section('title', 'Buat Surat Online - SIMADES')

@section('content')
<section class="py-5 bg-white border-bottom">
    <div class="container text-center py-4">
        <h1 class="fw-bold">Layanan Surat Online</h1>
        <p class="text-secondary">Silakan isi formulir di bawah ini untuk mengajukan permohonan surat.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg p-4 p-md-5 border-0">
                    <form action="{{ route('public.surat.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" name="nik" class="form-control form-control-lg bg-light border-0 @error('nik') is-invalid @enderror" 
                                placeholder="Masukkan 16 digit NIK Anda" value="{{ old('nik') }}" required>
                            <div class="form-text">NIK harus sudah terdaftar di database kependudukan desa.</div>
                            @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Jenis Surat</label>
                            <select name="jenis_surat_id" class="form-select form-select-lg bg-light border-0 @error('jenis_surat_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih jenis surat...</option>
                                @foreach($jenisSurats as $js)
                                <option value="{{ $js->id }}" {{ old('jenis_surat_id') == $js->id ? 'selected' : '' }}>{{ $js->nama_surat }}</option>
                                @endforeach
                            </select>
                            @error('jenis_surat_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Keperluan</label>
                            <textarea name="keperluan" rows="4" class="form-control bg-light border-0 @error('keperluan') is-invalid @enderror" 
                                placeholder="Jelaskan tujuan atau keperluan pembuatan surat ini..." required>{{ old('keperluan') }}</textarea>
                            @error('keperluan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="alert alert-info border-0 bg-info-subtle small mb-4">
                            <i class="fas fa-info-circle me-2"></i>Setelah mengirim, admin desa akan memproses ajuan Anda. Anda dapat mengambil fisik surat di kantor desa dengan membawa fotokopi KTP/KK.
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">Ajukan Permohonan Surat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
