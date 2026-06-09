@extends('layouts.app-backend')

@section('content')
<div class="resident-form-page">
    <div class="resident-form-hero">
        <div>
            <span class="eyebrow">Master Data</span>
            <h1>{{ $title }}</h1>
            <p>Tambahkan identitas penduduk lengkap untuk kebutuhan administrasi, layanan surat, dan validasi data warga.</p>
        </div>
        <a href="{{ route('penduduk.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('penduduk.store') }}" method="POST" enctype="multipart/form-data" class="resident-form-card">
        @csrf

        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-id-card"></i>
                <div>
                    <strong>Identitas Utama</strong>
                    <span>NIK, nama, kontak, dan relasi Kartu Keluarga.</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>NIK <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-hashtag"></i></span></div>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" maxlength="16" inputmode="numeric" placeholder="16 digit NIK" required>
                        @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Kartu Keluarga</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-address-card"></i></span></div>
                        <select name="kartu_keluarga_id" class="form-control">
                            <option value="">Belum ditautkan</option>
                            @foreach($kartuKeluargas as $kk)
                            <option value="{{ $kk->id }}" {{ old('kartu_keluarga_id') == $kk->id ? 'selected' : '' }}>{{ $kk->no_kk }} - {{ $kk->kepala_keluarga }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Nama sesuai KTP" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>No. HP/WhatsApp <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Jenis Kelamin <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-venus-mars"></i></span></div>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Agama <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-pray"></i></span></div>
                        <select name="agama" class="form-control" required>
                            <option value="">Pilih agama</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                            <option value="{{ $agama }}" {{ old('agama') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-calendar-alt"></i>
                <div>
                    <strong>Kelahiran dan Status</strong>
                    <span>Data TTL, pendidikan, pekerjaan, dan hubungan keluarga.</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Tempat Lahir <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span></div>
                        <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}" placeholder="Kota kelahiran" required>
                        @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Tanggal Lahir <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar"></i></span></div>
                        <input type="date" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror" value="{{ old('tgl_lahir') }}" required>
                        @error('tgl_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Status Perkawinan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-ring"></i></span></div>
                        <select name="status_perkawinan" class="form-control" required>
                            <option value="">Pilih status</option>
                            @foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $status)
                            <option value="{{ $status }}" {{ old('status_perkawinan') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Pekerjaan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-briefcase"></i></span></div>
                        <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" value="{{ old('pekerjaan') }}" placeholder="Pekerjaan saat ini" required>
                        @error('pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Pendidikan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-graduation-cap"></i></span></div>
                        <input type="text" name="pendidikan" class="form-control @error('pendidikan') is-invalid @enderror" value="{{ old('pendidikan') }}" placeholder="Pendidikan terakhir" required>
                        @error('pendidikan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Golongan Darah</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-tint"></i></span></div>
                        <select name="golongan_darah" class="form-control">
                            <option value="">Tidak tahu</option>
                            @foreach(['A','B','AB','O'] as $golongan)
                            <option value="{{ $golongan }}" {{ old('golongan_darah') == $golongan ? 'selected' : '' }}>{{ $golongan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Status Hubungan dalam Keluarga <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-users"></i></span></div>
                        <select name="shdk" class="form-control" required>
                            <option value="">Pilih SHDK</option>
                            @foreach(['Kepala Keluarga','Istri','Anak','Menantu','Cucu','Orang Tua','Mertua','Famili Lain','Lainnya'] as $shdk)
                            <option value="{{ $shdk }}" {{ old('shdk') == $shdk ? 'selected' : '' }}>{{ $shdk }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-map-marked-alt"></i>
                <div>
                    <strong>Alamat dan Lampiran</strong>
                    <span>Alamat domisili, RT/RW, serta foto KTP opsional.</span>
                </div>
            </div>
            <div class="mb-3">
                <label>Alamat Lengkap <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-home"></i></span></div>
                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Alamat jalan/dusun lengkap" required>{{ old('alamat') }}</textarea>
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>RT <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-map-pin"></i></span></div>
                        <input type="text" name="rt" class="form-control @error('rt') is-invalid @enderror" value="{{ old('rt') }}" inputmode="numeric" placeholder="001" required>
                        @error('rt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label>RW <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-map-pin"></i></span></div>
                        <input type="text" name="rw" class="form-control @error('rw') is-invalid @enderror" value="{{ old('rw') }}" inputmode="numeric" placeholder="002" required>
                        @error('rw')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Foto KTP (Opsional)</label>
                    <div class="upload-preview">
                        <img id="fotoKtpPreview" src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" alt="Preview KTP" loading="lazy">
                        <div class="upload-content">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-image"></i></span></div>
                                <input type="file" name="foto_ktp" class="form-control @error('foto_ktp') is-invalid @enderror" id="fotoKtpInput" accept="image/*">
                                @error('foto_ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <small>Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('penduduk.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times mr-1"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .resident-form-page { color: #1f2937; }
    .resident-form-hero {
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
    .resident-form-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .resident-form-hero p {
        max-width: 760px;
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
    .resident-form-card {
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
        color: #111827;
    }
    .section-title span,
    .upload-content small {
        color: #64748b;
    }
    .resident-form-card label {
        font-weight: 800;
        color: #374151;
    }
    .resident-form-card .input-group-text {
        min-width: 43px;
        justify-content: center;
        color: #0f766e;
        background: #ffffff;
    }
    .resident-form-card .form-control,
    .resident-form-card .input-group-text,
    .resident-form-card .btn {
        min-height: 42px;
    }
    .upload-preview {
        display: flex;
        gap: 0.8rem;
        padding: 0.75rem;
        border-radius: 12px;
        background: #ffffff;
        border: 1px dashed #cbd5e1;
    }
    .upload-preview img {
        width: 96px;
        height: 96px;
        border-radius: 12px;
        object-fit: cover;
        background: #f1f5f9;
    }
    .upload-content {
        flex: 1;
        min-width: 0;
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.6rem;
    }
    @media (max-width: 767.98px) {
        .resident-form-hero,
        .form-actions,
        .upload-preview {
            flex-direction: column;
            align-items: stretch;
        }
        .resident-form-hero h1 {
            font-size: 1.5rem;
        }
        .form-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById('fotoKtpInput');
        var preview = document.getElementById('fotoKtpPreview');

        if (!input || !preview) {
            return;
        }

        input.addEventListener('change', function (event) {
            var file = event.target.files[0];
            if (!file) {
                return;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
