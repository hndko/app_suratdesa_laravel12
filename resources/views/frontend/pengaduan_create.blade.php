@extends('layouts.app-frontend')

@section('title', 'Lapor Pengaduan - SIMADES')

@section('content')
<section class="py-5 bg-white border-bottom">
    <div class="container text-center py-4">
        <h1 class="fw-bold">Pusat Pengaduan Masyarakat</h1>
        <p class="text-secondary">Sampaikan keluhan, saran, atau laporan Anda secara langsung kepada pemerintah desa.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg p-4 p-md-5 border-0">
                    <form action="{{ route('public.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control bg-light border-0 @error('name') is-invalid @enderror" 
                                    placeholder="Nama sesuai KTP" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">NIK</label>
                                <input type="text" name="nik" class="form-control bg-light border-0 @error('nik') is-invalid @enderror" 
                                    placeholder="16 digit NIK" value="{{ old('nik') }}" required>
                                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Nomor WhatsApp/HP</label>
                                <input type="text" name="phone" class="form-control bg-light border-0 @error('phone') is-invalid @enderror" 
                                    placeholder="Contoh: 0812..." value="{{ old('phone') }}" required>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Kategori Laporan</label>
                                <select name="category" class="form-select bg-light border-0 @error('category') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih kategori...</option>
                                    <option value="Infrastruktur">Infrastruktur (Jalan, Jembatan, dll)</option>
                                    <option value="Pelayanan">Pelayanan Publik</option>
                                    <option value="Keamanan">Keamanan & Ketertiban</option>
                                    <option value="Bantuan Sosial">Bantuan Sosial</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Isi Laporan / Pengaduan</label>
                            <textarea name="content" rows="6" class="form-control bg-light border-0 @error('content') is-invalid @enderror" 
                                placeholder="Jelaskan detail laporan Anda secara rinci..." required>{{ old('content') }}</textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Lampiran Foto (Opsional)</label>
                            <input type="file" name="image" class="form-control bg-light border-0 @error('image') is-invalid @enderror">
                            <div class="form-text">Gunakan foto untuk memperkuat laporan Anda (Bukti fisik, lokasi, dll).</div>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">Kirim Laporan Pengaduan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
