@extends('layouts.app-frontend')

@section('content')
@php
  $surat = $verification?->surat;
  $verificationCodeValue = old('verification_code', $verificationCode ?? request('code'));
@endphp

<section class="verify-hero">
  <div class="container">
    <div class="row align-items-center gy-10">
      <div class="col-lg-7">
        <span class="page-eyebrow"><i class="uil uil-shield-check"></i> Verifikasi Dokumen {{ $villageName }}</span>
        <h1>{{ \App\Facades\Setting::get('public_verifikasi_hero_title', 'Pastikan surat desa benar-benar diterbitkan oleh ' . $siteName . '.') }}</h1>
        <p>{{ \App\Facades\Setting::get('public_verifikasi_hero_description', 'Masukkan kode verifikasi dari QR atau PDF surat untuk melihat status validitas dokumen tanpa membuka data pribadi warga secara lengkap.') }}</p>
        <div class="hero-actions">
          <a href="{{ route('public.surat.track') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-search-alt"></i> Lacak Pengajuan
          </a>
          <a href="{{ route('public.surat.create') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-file-plus-alt"></i> Ajukan Surat
          </a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="hero-visual">
          <img src="{{ asset('assets/sandbox/img/illustrations/i10.png') }}" alt="Ilustrasi verifikasi dokumen digital" loading="lazy">
          <div class="visual-badge">
            <i class="uil uil-lock-access"></i>
            <span>Hanya ringkasan dokumen yang ditampilkan untuk menjaga privasi pemohon.</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="wrapper bg-light">
  <div class="container py-12 py-md-14">
    <div class="row gy-8">
      <div class="col-lg-4">
        <div class="info-panel">
          <div class="panel-heading">
            <span>Panduan Verifikasi</span>
            <h2>Cek kode dari QR surat</h2>
          </div>
          <div class="requirement-list">
            <div><i class="uil uil-qrcode-scan"></i><span>Pindai QR pada PDF surat atau salin kode verifikasinya.</span></div>
            <div><i class="uil uil-bookmark"></i><span>Kode biasanya berupa kombinasi huruf dan angka maksimal 32 karakter.</span></div>
            <div><i class="uil uil-eye-slash"></i><span>NIK, alamat lengkap, dan lampiran sensitif tidak ditampilkan di halaman publik.</span></div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="verify-panel">
          <div class="panel-heading">
            <span>Form Verifikasi</span>
            <h2>Masukkan kode keaslian surat</h2>
          </div>

          <form action="{{ route('public.surat.verify.status') }}" method="POST">
            @csrf
            <label for="verification_code">Kode Verifikasi</label>
            <div class="input-group">
              <span class="input-group-text"><i class="uil uil-shield-check"></i></span>
              <input id="verification_code" type="text" name="verification_code" class="form-control @error('verification_code') is-invalid @enderror" value="{{ $verificationCodeValue }}" placeholder="Contoh: VRF-XXXXXXXXXX" maxlength="32" required autofocus>
              <button class="btn btn-primary" type="submit">
                <i class="uil uil-search"></i> Cek Keaslian
              </button>
              @error('verification_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </form>
        </div>

        @if(request()->isMethod('POST') || request()->filled('code'))
          @if($verification && $surat)
            <div class="result-panel">
              <div class="result-heading">
                <div>
                  <span>Hasil Verifikasi</span>
                  <h2>Surat valid dan aktif</h2>
                </div>
                <span class="status-pill status-success">
                  <i class="uil uil-check-circle"></i> Valid
                </span>
              </div>

              <div class="valid-summary">
                <i class="uil uil-shield-check"></i>
                <div>
                  <strong>Dokumen ini tercatat pada sistem {{ $siteName }}.</strong>
                  <span>Gunakan informasi ringkas di bawah ini untuk mencocokkan dokumen fisik atau PDF yang Anda terima.</span>
                </div>
              </div>

              <div class="detail-grid">
                <div><span>Jenis Surat</span><strong>{{ $surat->jenisSurat?->nama_surat ?? '-' }}</strong></div>
                <div><span>Nomor Surat</span><strong>{{ $surat->no_surat ?: 'Belum diterbitkan' }}</strong></div>
                <div><span>Tanggal Surat</span><strong>{{ $surat->tanggal_surat?->format('d M Y') ?? '-' }}</strong></div>
                <div><span>Pemohon</span><strong>{{ $surat->penduduk?->nama ? \Illuminate\Support\Str::mask($surat->penduduk->nama, '*', 2, 4) : '-' }}</strong></div>
                <div><span>Kode Verifikasi</span><strong>{{ $verification->verification_code }}</strong></div>
                <div><span>Terakhir Dicek</span><strong>{{ $verification->verified_at?->format('d M Y H:i') ?? now()->format('d M Y H:i') }}</strong></div>
              </div>
            </div>
          @else
            <div class="not-found-panel">
              <i class="uil uil-exclamation-triangle"></i>
              <div>
                <strong>Kode verifikasi tidak ditemukan</strong>
                <span>Pastikan kode sesuai dengan yang tertera pada QR/PDF surat dan surat masih aktif di sistem.</span>
              </div>
            </div>
            @push('scripts')
            <script>
              document.addEventListener('DOMContentLoaded', function () {
                if (window.showToast) {
                  window.showToast('error', 'Kode verifikasi tidak ditemukan atau sudah tidak aktif.');
                }
              });
            </script>
            @endpush
          @endif
        @endif
      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  .verify-hero {
    overflow: hidden;
    padding: 5rem 0 3.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 52%, #dcfce7 100%);
  }
  .page-eyebrow,
  .panel-heading span,
  .result-heading > div > span {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    color: #0f766e;
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
  }
  .verify-hero h1 {
    max-width: 780px;
    margin: 0.8rem 0 1rem;
    color: #111827;
    font-size: clamp(2.25rem, 5vw, 4.35rem);
    line-height: 1.04;
    letter-spacing: 0;
    font-weight: 800;
  }
  .verify-hero p {
    max-width: 690px;
    color: #475569;
    font-size: 1.16rem;
    line-height: 1.7;
  }
  .hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.65rem;
    margin-top: 1.25rem;
  }
  .hero-actions .btn,
  .verify-panel .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
  }
  .hero-visual {
    position: relative;
    min-height: 340px;
    display: grid;
    place-items: center;
  }
  .hero-visual img {
    max-width: 100%;
    filter: drop-shadow(0 26px 36px rgba(15, 23, 42, 0.14));
  }
  .visual-badge {
    position: absolute;
    left: 0;
    bottom: 1.25rem;
    display: flex;
    gap: 0.7rem;
    max-width: 340px;
    padding: 1rem;
    border: 1px solid #dbeafe;
    border-radius: 16px;
    color: #334155;
    background: rgba(255, 255, 255, 0.92);
    box-shadow: 0 18px 44px rgba(15, 23, 42, 0.12);
  }
  .visual-badge i {
    color: #059669;
    font-size: 1.5rem;
  }
  .info-panel,
  .verify-panel,
  .result-panel,
  .not-found-panel {
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
  }
  .info-panel,
  .verify-panel,
  .result-panel {
    padding: 1.25rem;
  }
  .panel-heading,
  .result-heading {
    margin-bottom: 1rem;
  }
  .panel-heading h2,
  .result-heading h2 {
    margin: 0.35rem 0 0;
    color: #111827;
    font-size: 1.35rem;
    font-weight: 800;
    letter-spacing: 0;
  }
  .requirement-list {
    display: grid;
    gap: 0.75rem;
  }
  .requirement-list div {
    display: flex;
    gap: 0.65rem;
    padding: 0.85rem;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    color: #475569;
    background: #f8fafc;
    font-weight: 700;
  }
  .requirement-list i {
    color: #0f766e;
    font-size: 1.35rem;
  }
  .verify-panel label {
    color: #334155;
    font-weight: 800;
  }
  .verify-panel .input-group-text {
    min-width: 48px;
    justify-content: center;
    color: #0f766e;
    border-color: #dbe3ef;
    background: #f8fafc;
  }
  .verify-panel .form-control {
    min-height: 52px;
    border-color: #dbe3ef;
    border-radius: 0.45rem 0 0 0.45rem;
    font-weight: 800;
  }
  .verify-panel .btn {
    min-height: 52px;
    white-space: nowrap;
  }
  .result-panel,
  .not-found-panel {
    margin-top: 1rem;
  }
  .result-heading {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
  }
  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.42rem 0.75rem;
    border-radius: 999px;
    font-weight: 800;
    white-space: nowrap;
  }
  .status-success {
    color: #047857;
    background: #d1fae5;
  }
  .valid-summary,
  .not-found-panel {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 14px;
  }
  .valid-summary {
    margin-bottom: 1rem;
    color: #047857;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
  }
  .not-found-panel {
    color: #92400e;
    background: #fffbeb;
    border-color: #fde68a;
  }
  .valid-summary i,
  .not-found-panel i {
    font-size: 1.5rem;
  }
  .valid-summary strong,
  .valid-summary span,
  .not-found-panel strong,
  .not-found-panel span {
    display: block;
  }
  .detail-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.85rem;
  }
  .detail-grid div {
    padding: 0.85rem;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #f8fafc;
  }
  .detail-grid span {
    display: block;
    color: #64748b;
    font-size: 0.76rem;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }
  .detail-grid strong {
    display: block;
    color: #111827;
    font-weight: 800;
    word-break: break-word;
  }
  @media (max-width: 991.98px) {
    .visual-badge {
      position: relative;
      left: auto;
      bottom: auto;
      margin-top: 1rem;
    }
  }
  @media (max-width: 767.98px) {
    .verify-hero {
      padding-top: 3.5rem;
    }
    .hero-actions .btn,
    .verify-panel .btn {
      width: 100%;
    }
    .verify-panel .input-group {
      display: grid;
      grid-template-columns: 48px minmax(0, 1fr);
    }
    .verify-panel .btn {
      grid-column: 1 / -1;
      margin-top: 0.75rem;
      border-radius: 999px !important;
    }
    .result-heading {
      flex-direction: column;
    }
    .detail-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@push('scripts')
<script>
  $(function () {
    $('#verification_code').on('input', function () {
      this.value = this.value.toUpperCase().replace(/\s/g, '').slice(0, 32);
    });
  });
</script>
@endpush
