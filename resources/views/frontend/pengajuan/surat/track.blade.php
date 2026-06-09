@extends('layouts.app-frontend')

@section('content')
@php
  $status = $surat->status ?? null;
  $statusLabels = [
    'pending' => ['label' => 'Menunggu', 'class' => 'status-warning', 'icon' => 'uil-clock'],
    'process' => ['label' => 'Sedang Diproses', 'class' => 'status-info', 'icon' => 'uil-sync'],
    'verified' => ['label' => 'Diverifikasi', 'class' => 'status-info', 'icon' => 'uil-check-circle'],
    'approved' => ['label' => 'Disetujui', 'class' => 'status-primary', 'icon' => 'uil-thumbs-up'],
    'rejected' => ['label' => 'Ditolak', 'class' => 'status-danger', 'icon' => 'uil-times-circle'],
    'done' => ['label' => 'Selesai', 'class' => 'status-success', 'icon' => 'uil-check-circle'],
  ];
  $currentStatus = $statusLabels[$status] ?? null;
  $steps = ['pending' => 'Diterima', 'verified' => 'Diverifikasi', 'approved' => 'Disetujui', 'done' => 'Selesai'];
  $stepKeys = array_keys($steps);
  $activeIndex = $status ? array_search($status, $stepKeys, true) : false;
  if ($status === 'process') {
      $activeIndex = 0;
  }
  if ($status === 'rejected') {
      $activeIndex = 0;
  }
@endphp

<section class="track-hero">
  <div class="container">
    <div class="row align-items-center gy-10">
      <div class="col-lg-7">
        <span class="page-eyebrow"><i class="uil uil-search-alt"></i> Tracking Surat {{ $villageName }}</span>
        <h1>Lacak status pengajuan surat secara mandiri.</h1>
        <p>Masukkan kode tracking dan NIK pemohon untuk melihat posisi pengajuan, jenis surat, nomor surat, dan catatan approval bila tersedia.</p>
        <div class="hero-actions">
          <a href="{{ route('public.surat.create') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-file-plus-alt"></i> Ajukan Surat Baru
          </a>
          <a href="{{ route('public.surat.verify') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-qrcode-scan"></i> Verifikasi Surat
          </a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="hero-visual">
          <img src="{{ asset('assets/sandbox/img/illustrations/i7.png') }}" alt="Ilustrasi pelacakan surat" loading="lazy">
          <div class="visual-badge">
            <i class="uil uil-shield-check"></i>
            <span>Data tracking hanya tampil jika kode dan NIK cocok.</span>
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
            <span>Data Dibutuhkan</span>
            <h2>Siapkan sebelum melacak</h2>
          </div>
          <div class="requirement-list">
            <div><i class="uil uil-bookmark"></i><span>Kode tracking dari hasil pengajuan surat.</span></div>
            <div><i class="uil uil-id-card"></i><span>NIK pemohon yang digunakan saat pengajuan.</span></div>
            <div><i class="uil uil-lock-access"></i><span>Data tidak akan tampil bila salah satu tidak cocok.</span></div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="track-panel">
          <div class="panel-heading">
            <span>Form Tracking</span>
            <h2>Cek status pengajuan</h2>
          </div>

          <form action="{{ route('public.surat.status') }}" method="POST">
            @csrf
            <div class="row gx-4 gy-4">
              <div class="col-md-6">
                <label for="tracking_code">Kode Tracking</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="uil uil-bookmark"></i></span>
                  <input id="tracking_code" type="text" name="tracking_code" class="form-control @error('tracking_code') is-invalid @enderror" value="{{ old('tracking_code', request('tracking_code')) }}" placeholder="Contoh: SRT-XXXXXXXXXX" maxlength="24" required autofocus>
                  @error('tracking_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
              <div class="col-md-6">
                <label for="nik">NIK Pemohon</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="uil uil-id-card"></i></span>
                  <input id="nik" type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', request('nik')) }}" placeholder="Masukkan 16 digit NIK" inputmode="numeric" maxlength="16" required>
                  @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
              <div class="col-12">
                <div class="submit-row">
                  <a href="{{ route('public.home') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="uil uil-arrow-left"></i> Kembali
                  </a>
                  <button type="submit" class="btn btn-primary rounded-pill">
                    <i class="uil uil-search"></i> Cek Status Sekarang
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>

        @if(isset($surat) && $surat)
          <div class="result-panel">
            <div class="result-heading">
              <div>
                <span>Hasil Tracking</span>
                <h2>{{ $surat->jenisSurat?->nama_surat ?? 'Surat Desa' }}</h2>
              </div>
              <span class="status-pill {{ $currentStatus['class'] ?? 'status-info' }}">
                <i class="uil {{ $currentStatus['icon'] ?? 'uil-info-circle' }}"></i> {{ $currentStatus['label'] ?? ucfirst($surat->status) }}
              </span>
            </div>

            <div class="tracking-timeline">
              @foreach($steps as $key => $label)
                @php
                  $index = array_search($key, $stepKeys, true);
                  $done = $activeIndex !== false && $index <= $activeIndex && $status !== 'rejected';
                @endphp
                <div class="timeline-step {{ $done ? 'done' : '' }}">
                  <b>{{ $index + 1 }}</b>
                  <span>{{ $label }}</span>
                </div>
              @endforeach
            </div>

            @if($status === 'rejected')
              <div class="rejected-note">
                <i class="uil uil-exclamation-triangle"></i>
                <span>Pengajuan ditolak. Silakan cek catatan approval atau hubungi kantor desa untuk tindak lanjut.</span>
              </div>
            @endif

            <div class="detail-grid">
              <div><span>Nama Pemohon</span><strong>{{ $surat->penduduk?->nama ?? '-' }}</strong></div>
              <div><span>NIK</span><strong>{{ $surat->penduduk?->nik ? substr($surat->penduduk->nik, 0, 6) . '******' . substr($surat->penduduk->nik, -4) : '-' }}</strong></div>
              <div><span>No. Surat</span><strong>{{ $surat->no_surat ?: 'Belum diterbitkan' }}</strong></div>
              <div><span>Kode Tracking</span><strong>{{ $surat->tracking_code }}</strong></div>
              <div><span>Tanggal Pengajuan</span><strong>{{ $surat->tanggal_surat?->format('d M Y') ?? $surat->created_at?->format('d M Y') }}</strong></div>
              <div><span>Keperluan</span><strong>{{ $surat->keperluan }}</strong></div>
            </div>

            @if($surat->approval_note)
              <div class="approval-note">
                <i class="uil uil-comment-notes"></i>
                <div>
                  <strong>Catatan Approval</strong>
                  <span>{{ $surat->approval_note }}</span>
                </div>
              </div>
            @endif
          </div>
        @elseif(request()->isMethod('POST'))
          <div class="not-found-panel">
            <i class="uil uil-search-alt"></i>
            <div>
              <strong>Data pengajuan tidak ditemukan</strong>
              <span>Periksa kembali kode tracking dan NIK pemohon. Pastikan keduanya sesuai dengan data pengajuan surat.</span>
            </div>
          </div>
          @push('scripts')
          <script>
            document.addEventListener('DOMContentLoaded', function () {
              if (window.showToast) {
                window.showToast('error', 'Kode Tracking atau NIK tidak ditemukan. Silakan periksa kembali.');
              }
            });
          </script>
          @endpush
        @endif
      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  .track-hero {
    overflow: hidden;
    padding: 5rem 0 3.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 50%, #ccfbf1 100%);
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
  .track-hero h1 {
    max-width: 760px;
    margin: 0.8rem 0 1rem;
    color: #111827;
    font-size: clamp(2.25rem, 5vw, 4.5rem);
    line-height: 1.03;
    letter-spacing: 0;
    font-weight: 800;
  }
  .track-hero p {
    max-width: 680px;
    color: #475569;
    font-size: 1.16rem;
    line-height: 1.7;
  }
  .hero-actions,
  .submit-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.65rem;
    margin-top: 1.25rem;
  }
  .hero-actions .btn,
  .submit-row .btn {
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
    max-width: 330px;
    padding: 1rem;
    border: 1px solid #dbeafe;
    border-radius: 16px;
    color: #334155;
    background: rgba(255, 255, 255, 0.92);
    box-shadow: 0 18px 44px rgba(15, 23, 42, 0.12);
  }
  .visual-badge i { color: #059669; font-size: 1.5rem; }
  .info-panel,
  .track-panel,
  .result-panel,
  .not-found-panel {
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
  }
  .info-panel,
  .track-panel,
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
  .requirement-list i { color: #0f766e; font-size: 1.35rem; }
  .track-panel label {
    color: #334155;
    font-weight: 800;
  }
  .track-panel .input-group-text {
    min-width: 48px;
    justify-content: center;
    color: #0f766e;
    border-color: #dbe3ef;
    background: #f8fafc;
  }
  .track-panel .form-control {
    min-height: 52px;
    border-color: #dbe3ef;
    border-radius: 0.45rem;
    font-weight: 800;
  }
  .submit-row {
    justify-content: flex-end;
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
  .status-warning { color: #92400e; background: #fef3c7; }
  .status-info { color: #075985; background: #e0f2fe; }
  .status-primary { color: #1d4ed8; background: #dbeafe; }
  .status-danger { color: #b91c1c; background: #fee2e2; }
  .status-success { color: #047857; background: #d1fae5; }
  .tracking-timeline {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.75rem;
    margin-bottom: 1rem;
  }
  .timeline-step {
    padding: 0.85rem;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    color: #64748b;
    background: #f8fafc;
    font-weight: 800;
  }
  .timeline-step b {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    margin-right: 0.4rem;
    border-radius: 11px;
    color: #64748b;
    background: #e2e8f0;
  }
  .timeline-step.done {
    color: #047857;
    border-color: #bbf7d0;
    background: #f0fdf4;
  }
  .timeline-step.done b {
    color: #ffffff;
    background: #059669;
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
  .approval-note,
  .rejected-note,
  .not-found-panel {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 14px;
  }
  .approval-note {
    margin-top: 1rem;
    color: #075985;
    background: #e0f2fe;
    border: 1px solid #bae6fd;
  }
  .rejected-note {
    margin-bottom: 1rem;
    color: #b91c1c;
    background: #fee2e2;
    border: 1px solid #fecaca;
  }
  .not-found-panel {
    color: #92400e;
    background: #fffbeb;
    border-color: #fde68a;
  }
  .approval-note i,
  .rejected-note i,
  .not-found-panel i {
    font-size: 1.5rem;
  }
  .approval-note strong,
  .approval-note span,
  .not-found-panel strong,
  .not-found-panel span {
    display: block;
  }
  @media (max-width: 991.98px) {
    .visual-badge { position: relative; left: auto; bottom: auto; margin-top: 1rem; }
    .tracking-timeline { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  }
  @media (max-width: 767.98px) {
    .track-hero { padding-top: 3.5rem; }
    .hero-actions .btn,
    .submit-row .btn { width: 100%; }
    .result-heading { flex-direction: column; }
    .tracking-timeline,
    .detail-grid { grid-template-columns: 1fr; }
  }
</style>
@endpush

@push('scripts')
<script>
  $(function () {
    $('#nik').on('input', function () {
      this.value = this.value.replace(/\D/g, '').slice(0, 16);
    });

    $('#tracking_code').on('input', function () {
      this.value = this.value.toUpperCase().replace(/\s/g, '').slice(0, 24);
    });
  });
</script>
@endpush
