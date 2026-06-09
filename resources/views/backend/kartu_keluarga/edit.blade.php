@extends('layouts.app-backend')

@section('content')
<div class="kk-form-page">
    <div class="kk-form-hero">
        <div>
            <span class="eyebrow">Master Data</span>
            <h1>{{ $title }}</h1>
            <p>Perbarui data KK {{ $kartuKeluarga->no_kk }} tanpa mengubah hubungan anggota keluarga yang sudah terhubung.</p>
        </div>
        <a href="{{ route('kartu-keluarga.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('kartu-keluarga.update', $kartuKeluarga->id) }}" method="POST" class="kk-form-card">
        @csrf
        @method('PUT')
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-id-card"></i>
                <div>
                    <strong>Identitas Keluarga</strong>
                    <span>Nomor KK dan nama kepala keluarga.</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>No. KK <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                        </div>
                        <input type="text" name="no_kk" class="form-control @error('no_kk') is-invalid @enderror" value="{{ old('no_kk', $kartuKeluarga->no_kk) }}" maxlength="16" inputmode="numeric" placeholder="Contoh: 3201010101010001" required>
                        @error('no_kk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Kepala Keluarga <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        </div>
                        <input type="text" name="kepala_keluarga" class="form-control @error('kepala_keluarga') is-invalid @enderror" value="{{ old('kepala_keluarga', $kartuKeluarga->kepala_keluarga) }}" placeholder="Nama kepala keluarga" required>
                        @error('kepala_keluarga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <strong>Alamat Domisili</strong>
                    <span>Alamat rumah dan detail wilayah administrasi.</span>
                </div>
            </div>
            <div class="mb-3">
                <label>Alamat <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                    </div>
                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Contoh: Dusun Mawar, Jalan Melati No. 10" required>{{ old('alamat', $kartuKeluarga->alamat) }}</textarea>
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label>RT <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                        </div>
                        <input type="text" name="rt" class="form-control @error('rt') is-invalid @enderror" value="{{ old('rt', $kartuKeluarga->rt) }}" inputmode="numeric" placeholder="001" required>
                        @error('rt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <label>RW <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                        </div>
                        <input type="text" name="rw" class="form-control @error('rw') is-invalid @enderror" value="{{ old('rw', $kartuKeluarga->rw) }}" inputmode="numeric" placeholder="002" required>
                        @error('rw')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Desa</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-landmark"></i></span>
                        </div>
                        <input type="text" name="desa" class="form-control @error('desa') is-invalid @enderror" value="{{ old('desa', $kartuKeluarga->desa) }}" placeholder="Nama desa">
                        @error('desa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Kecamatan</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-city"></i></span>
                        </div>
                        <input type="text" name="kecamatan" class="form-control @error('kecamatan') is-invalid @enderror" value="{{ old('kecamatan', $kartuKeluarga->kecamatan) }}" placeholder="Nama kecamatan">
                        @error('kecamatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Kabupaten</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                        </div>
                        <input type="text" name="kabupaten" class="form-control @error('kabupaten') is-invalid @enderror" value="{{ old('kabupaten', $kartuKeluarga->kabupaten) }}" placeholder="Nama kabupaten">
                        @error('kabupaten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Provinsi</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                        </div>
                        <input type="text" name="provinsi" class="form-control @error('provinsi') is-invalid @enderror" value="{{ old('provinsi', $kartuKeluarga->provinsi) }}" placeholder="Nama provinsi">
                        @error('provinsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Kode Pos</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                        </div>
                        <input type="text" name="kode_pos" class="form-control @error('kode_pos') is-invalid @enderror" value="{{ old('kode_pos', $kartuKeluarga->kode_pos) }}" inputmode="numeric" placeholder="Kode pos">
                        @error('kode_pos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            @can('kartu-keluarga-show')
            <a href="{{ route('kartu-keluarga.show', $kartuKeluarga->id) }}" class="btn btn-outline-info">
                <i class="fas fa-eye mr-1"></i> Detail
            </a>
            @endcan
            <a href="{{ route('kartu-keluarga.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times mr-1"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .kk-form-page { color: #1f2937; }
    .kk-form-hero {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 1.3rem;
        border-radius: 16px;
        background: linear-gradient(135deg, #111827, #0f766e);
        color: #ffffff;
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.16);
    }
    .kk-form-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .kk-form-hero p {
        max-width: 720px;
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }
    .eyebrow {
        display: block;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .kk-form-card {
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .form-section {
        padding: 1rem;
        border: 1px solid #eef2f7;
        border-radius: 14px;
        background: #f8fafc;
        margin-bottom: 1rem;
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .section-title i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        color: #ffffff;
        background: #0f766e;
    }
    .section-title strong {
        display: block;
        font-size: 1rem;
        color: #111827;
    }
    .section-title span {
        color: #64748b;
    }
    .kk-form-card label {
        font-weight: 800;
        color: #374151;
    }
    .kk-form-card .input-group-text {
        min-width: 43px;
        justify-content: center;
        color: #0f766e;
        background: #ffffff;
    }
    .kk-form-card .form-control,
    .kk-form-card .input-group-text,
    .kk-form-card .btn {
        min-height: 42px;
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.6rem;
    }
    @media (max-width: 767.98px) {
        .kk-form-hero,
        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }
        .kk-form-hero h1 {
            font-size: 1.5rem;
        }
        .form-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush
