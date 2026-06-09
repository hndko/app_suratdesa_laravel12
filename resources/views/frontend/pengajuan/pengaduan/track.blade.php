@extends('layouts.app-frontend')

@section('content')
@php
  $status = $pengaduan->status ?? null;
  $statusLabels = [
    'pending' => ['label' => 'Menunggu Antrian', 'class' => 'status-warning', 'icon' => 'uil-clock'],
    'process' => ['label' => 'Sedang Diproses', 'class' => 'status-info', 'icon' => 'uil-sync'],
    'resolved' => ['label' => 'Selesai Ditangani', 'class' => 'status-success', 'icon' => 'uil-check-circle'],
  ];
  $categoryLabels = [
    'infrastruktur' => 'Infrastruktur / Jalan',
    'keamanan' => 'Keamanan / Ketertiban',
    'pelayanan' => 'Pelayanan Publik',
    'sosial' => 'Sosial / Ekonomi',
    'lainnya' => 'Lainnya',
  ];
  $currentStatus = $statusLabels[$status] ?? null;
  $steps = ['pending' => 'Diterima', 'process' => 'Diproses', 'resolved' => 'Selesai'];
  $stepKeys = array_keys($steps);
  $activeIndex = $status ? array_search($status, $stepKeys, true) : false;
  $ticketValue = old('ticket_code', $ticketCode ?? request('ticket_code'));
@endphp

<section class="complaint-track-hero">
  <div class="container">
    <div class="row align-items-center gy-10">
      <div class="col-lg-7">
        <span class="page-eyebrow"><i class="uil uil-search-alt"></i> Tracking Pengaduan {{ $villageName }}</span>
        <h1>{{ \App\Facades\Setting::get('public_pengaduan_track_hero_title', 'Pantau progres tindak lanjut pengaduan Anda.') }}</h1>
        <p>{{ \App\Facades\Setting::get('public_pengaduan_track_hero_description', 'Masukkan kode tiket dan NIK pelapor untuk melihat status antrian, kategori laporan, isi aduan, dan tanggapan petugas bila sudah tersedia.') }}</p>
        <div class="hero-actions">
          <a href="{{ route('public.pengaduan.create') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-comment-plus"></i> Kirim Aduan Baru
          </a>
          <a href="{{ route('public.home') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-estate"></i> Beranda
          </a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="hero-visual">
          <img src="{{ asset('assets/sandbox/img/illustrations/i12.png') }}" alt="Ilustrasi tracking pengaduan warga" loading="lazy">
          <div class="visual-badge">
            <i class="uil uil-shield-check"></i>
            <span>Data tracking hanya tampil jika kode tiket dan NIK pelapor cocok.</span>
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
            <div><i class="uil uil-ticket"></i><span>Kode tiket pengaduan yang diterima setelah laporan berhasil dikirim.</span></div>
            <div><i class="uil uil-id-card"></i><span>NIK pelapor sesuai data yang digunakan saat mengirim aduan.</span></div>
            <div><i class="uil uil-lock-access"></i><span>Informasi aduan tidak akan tampil bila salah satu data tidak cocok.</span></div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="track-panel">
          <div class="panel-heading">
            <span>Form Tracking</span>
            <h2>Cek status pengaduan</h2>
          </div>

          <form action="{{ route('public.pengaduan.status') }}" method="POST">
            @csrf
            <div class="row gx-4 gy-4">
              <div class="col-md-6">
                <label for="ticket_code">Kode Tiket</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="uil uil-ticket"></i></span>
                  <input id="ticket_code" type="text" name="ticket_code" class="form-control @error('ticket_code') is-invalid @enderror" value="{{ $ticketValue }}" placeholder="Contoh: TKT-XXXXXXXXXX" maxlength="20" required autofocus>
                </div>
                @error('ticket_code')<small class="field-error">{{ $message }}</small>@enderror
              </div>
              <div class="col-md-6">
                <label for="nik">NIK Pelapor</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="uil uil-id-card"></i></span>
                  <input id="nik" type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', request('nik')) }}" placeholder="Masukkan 16 digit NIK" inputmode="numeric" maxlength="16" required>
                </div>
                @error('nik')<small class="field-error">{{ $message }}</small>@enderror
              </div>
              <div class="col-12">
                <div class="submit-row">
                  <a href="{{ route('public.pengaduan.create') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="uil uil-comment-plus"></i> Kirim Aduan
                  </a>
                  <button type="submit" class="btn btn-primary rounded-pill">
                    <i class="uil uil-search"></i> Cek Status Sekarang
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>

        @if(isset($pengaduan) && $pengaduan)
          <div class="result-panel">
            <div class="result-heading">
              <div>
                <span>Hasil Tracking</span>
                <h2>{{ $categoryLabels[$pengaduan->category] ?? ucfirst($pengaduan->category) }}</h2>
              </div>
              <span class="status-pill {{ $currentStatus['class'] ?? 'status-info' }}">
                <i class="uil {{ $currentStatus['icon'] ?? 'uil-info-circle' }}"></i> {{ $currentStatus['label'] ?? ucfirst($pengaduan->status) }}
              </span>
            </div>

            <div class="tracking-timeline">
              @foreach($steps as $key => $label)
                @php
                  $index = array_search($key, $stepKeys, true);
                  $done = $activeIndex !== false && $index <= $activeIndex;
                @endphp
                <div class="timeline-step {{ $done ? 'done' : '' }}">
                  <b>{{ $index + 1 }}</b>
                  <span>{{ $label }}</span>
                </div>
              @endforeach
            </div>

            <div class="detail-grid">
              <div><span>Kode Tiket</span><strong>{{ $pengaduan->ticket_code }}</strong></div>
              <div><span>Pelapor</span><strong>{{ \Illuminate\Support\Str::mask($pengaduan->name, '*', 2, 4) }}</strong></div>
              <div><span>NIK</span><strong>{{ substr($pengaduan->nik, 0, 6) . '******' . substr($pengaduan->nik, -4) }}</strong></div>
              <div><span>Tanggal Lapor</span><strong>{{ $pengaduan->created_at?->format('d M Y H:i') }}</strong></div>
              <div><span>Kategori</span><strong>{{ $categoryLabels[$pengaduan->category] ?? ucfirst($pengaduan->category) }}</strong></div>
              <div><span>Kontak</span><strong>{{ \Illuminate\Support\Str::mask($pengaduan->phone, '*', 4, 4) }}</strong></div>
            </div>

            <div class="content-box">
              <i class="uil uil-comment-alt-message"></i>
              <div>
                <strong>Isi Laporan</strong>
                <span>{{ $pengaduan->content }}</span>
              </div>
            </div>

            @if($pengaduan->image)
              <div class="evidence-box">
                <div class="evidence-heading">
                  <i class="uil uil-image"></i>
                  <strong>Foto Bukti</strong>
                </div>
                <img src="{{ asset('storage/' . $pengaduan->image) }}" alt="Foto bukti pengaduan" loading="lazy">
              </div>
            @endif

            @if($pengaduan->reply)
              <div class="reply-box">
                <i class="uil uil-comment-verify"></i>
                <div>
                  <strong>Tanggapan Petugas</strong>
                  <span>{{ $pengaduan->reply }}</span>
                  <small>Diperbarui pada {{ $pengaduan->updated_at?->format('d M Y H:i') }}</small>
                </div>
              </div>
            @else
              <div class="waiting-box">
                <i class="uil uil-hourglass"></i>
                <div>
                  <strong>Belum ada tanggapan tertulis</strong>
                  <span>Pengaduan sudah masuk sistem dan akan ditindaklanjuti sesuai antrean serta prioritas penanganan.</span>
                </div>
              </div>
            @endif
          </div>
        @elseif(request()->isMethod('POST'))
          <div class="not-found-panel">
            <i class="uil uil-search-alt"></i>
            <div>
              <strong>Data pengaduan tidak ditemukan</strong>
              <span>Periksa kembali kode tiket dan NIK pelapor. Pastikan keduanya sama seperti saat pengaduan dikirim.</span>
            </div>
          </div>
          @push('scripts')
          <script>
            document.addEventListener('DOMContentLoaded', function () {
              if (window.showToast) {
                window.showToast('error', 'Kode Tiket atau NIK tidak ditemukan. Silakan periksa kembali.');
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
  .complaint-track-hero {
    overflow: hidden;
    padding: 5rem 0 3.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #dbeafe 52%, #ccfbf1 100%);
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
  .complaint-track-hero h1 {
    max-width: 780px;
    margin: 0.8rem 0 1rem;
    color: #111827;
    font-size: clamp(2.25rem, 5vw, 4.35rem);
    line-height: 1.04;
    letter-spacing: 0;
    font-weight: 800;
  }
  .complaint-track-hero p {
    max-width: 700px;
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
  .requirement-list i {
    color: #0f766e;
    font-size: 1.35rem;
  }
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
  .field-error {
    display: block;
    margin-top: 0.35rem;
    color: #dc2626;
    font-weight: 700;
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
  .status-success { color: #047857; background: #d1fae5; }
  .tracking-timeline {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
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
  .detail-grid div,
  .content-box,
  .reply-box,
  .waiting-box,
  .evidence-box,
  .not-found-panel {
    border-radius: 14px;
  }
  .detail-grid div {
    padding: 0.85rem;
    border: 1px solid #e5e7eb;
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
  .content-box,
  .reply-box,
  .waiting-box,
  .not-found-panel {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
  }
  .content-box {
    margin-top: 1rem;
    color: #334155;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
  }
  .reply-box {
    margin-top: 1rem;
    color: #075985;
    background: #e0f2fe;
    border: 1px solid #bae6fd;
  }
  .waiting-box {
    margin-top: 1rem;
    color: #92400e;
    background: #fffbeb;
    border: 1px solid #fde68a;
  }
  .not-found-panel {
    color: #92400e;
    background: #fffbeb;
    border-color: #fde68a;
  }
  .content-box i,
  .reply-box i,
  .waiting-box i,
  .not-found-panel i {
    font-size: 1.5rem;
  }
  .content-box strong,
  .content-box span,
  .reply-box strong,
  .reply-box span,
  .reply-box small,
  .waiting-box strong,
  .waiting-box span,
  .not-found-panel strong,
  .not-found-panel span {
    display: block;
  }
  .reply-box small {
    margin-top: 0.35rem;
    color: #64748b;
    font-weight: 700;
  }
  .evidence-box {
    margin-top: 1rem;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    background: #f8fafc;
  }
  .evidence-heading {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    margin-bottom: 0.75rem;
    color: #334155;
  }
  .evidence-box img {
    width: 100%;
    max-height: 360px;
    object-fit: cover;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
  }
  @media (max-width: 991.98px) {
    .visual-badge { position: relative; left: auto; bottom: auto; margin-top: 1rem; }
  }
  @media (max-width: 767.98px) {
    .complaint-track-hero { padding-top: 3.5rem; }
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

    $('#ticket_code').on('input', function () {
      this.value = this.value.toUpperCase().replace(/\s/g, '').slice(0, 20);
    });
  });
</script>
@endpush
