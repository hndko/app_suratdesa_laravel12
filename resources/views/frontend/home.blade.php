@extends('layouts.app-frontend')

@section('content')
<section class="public-hero">
  <div class="container">
    <div class="row align-items-center gy-10">
      <div class="col-lg-7">
        <span class="hero-eyebrow"><i class="uil uil-shield-check"></i> Portal Resmi {{ $villageName }}</span>
        <h1>Pelayanan desa digital yang mudah dipantau dari rumah.</h1>
        <p class="hero-copy">Ajukan surat, kirim pengaduan, lacak proses layanan, dan cek keaslian dokumen melalui satu portal publik {{ $siteName }}.</p>
        <div class="hero-actions">
          <a href="{{ route('public.surat.create') }}" class="btn btn-primary rounded-pill">
            <i class="uil uil-file-plus-alt"></i> Ajukan Surat
          </a>
          <a href="{{ route('public.pengaduan.create') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-comment-exclamation"></i> Kirim Pengaduan
          </a>
        </div>
        <div class="hero-address">
          <i class="uil uil-map-marker"></i>
          <span>{{ $villageAddress }}</span>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="hero-visual">
          <img src="{{ asset('assets/sandbox/img/illustrations/i8.png') }}" srcset="{{ asset('assets/sandbox/img/illustrations/i8@2x.png') }} 2x" alt="Ilustrasi pelayanan digital desa" loading="lazy">
          <div class="hero-status-card">
            <i class="uil uil-check-circle"></i>
            <div>
              <strong>Layanan Online Aktif</strong>
              <span>Surat, pengaduan, tracking, dan verifikasi dokumen.</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="public-stat-grid">
      <div class="public-stat">
        <i class="uil uil-file-alt"></i>
        <span>Jenis Surat</span>
        <strong>{{ number_format($totalJenisSurat, 0, ',', '.') }}</strong>
      </div>
      <div class="public-stat">
        <i class="uil uil-file-check-alt"></i>
        <span>Surat Selesai</span>
        <strong>{{ number_format($totalSuratSelesai, 0, ',', '.') }}</strong>
      </div>
      <div class="public-stat">
        <i class="uil uil-comment-verify"></i>
        <span>Aduan Selesai</span>
        <strong>{{ number_format($totalPengaduanSelesai, 0, ',', '.') }}</strong>
      </div>
      <div class="public-stat">
        <i class="uil uil-megaphone"></i>
        <span>Pengumuman</span>
        <strong>{{ number_format($totalPengumuman, 0, ',', '.') }}</strong>
      </div>
    </div>
  </div>
</section>

<section class="wrapper bg-light">
  <div class="container py-12 py-md-14">
    <div class="section-heading">
      <span>Layanan Publik</span>
      <h2>Pilih layanan sesuai kebutuhan warga</h2>
    </div>

    <div class="service-grid">
      <a href="{{ route('public.surat.create') }}" class="service-card">
        <i class="uil uil-file-plus-alt"></i>
        <strong>Pengajuan Surat</strong>
        <span>Ajukan surat administrasi tanpa perlu datang berkali-kali ke kantor desa.</span>
      </a>
      <a href="{{ route('public.surat.track') }}" class="service-card">
        <i class="uil uil-search-alt"></i>
        <strong>Lacak Surat</strong>
        <span>Pantau status pengajuan memakai kode tracking dan NIK pemohon.</span>
      </a>
      <a href="{{ route('public.surat.verify') }}" class="service-card">
        <i class="uil uil-qrcode-scan"></i>
        <strong>Verifikasi Dokumen</strong>
        <span>Cek keaslian surat menggunakan kode verifikasi publik.</span>
      </a>
      <a href="{{ route('public.pengaduan.create') }}" class="service-card">
        <i class="uil uil-comment-exclamation"></i>
        <strong>Pengaduan Warga</strong>
        <span>Sampaikan aduan infrastruktur, pelayanan, keamanan, sosial, atau lainnya.</span>
      </a>
    </div>
  </div>
</section>

<section class="wrapper bg-gray">
  <div class="container py-12 py-md-14">
    <div class="row align-items-center gy-10">
      <div class="col-lg-5">
        <div class="section-heading text-start mb-5">
          <span>Alur Layanan</span>
          <h2>Proses dibuat jelas dari awal sampai selesai</h2>
        </div>
        <p class="mb-0">Setiap layanan publik memberi kode pelacakan agar warga bisa memantau proses dan menerima informasi status layanan dengan lebih transparan.</p>
      </div>
      <div class="col-lg-7">
        <div class="flow-grid">
          <div class="flow-step"><b>1</b><strong>Isi Form</strong><span>Masukkan data sesuai NIK atau kebutuhan pengaduan.</span></div>
          <div class="flow-step"><b>2</b><strong>Dapat Kode</strong><span>Simpan kode tracking surat atau tiket pengaduan.</span></div>
          <div class="flow-step"><b>3</b><strong>Diproses Staff</strong><span>Operator memverifikasi dan memproses permintaan.</span></div>
          <div class="flow-step"><b>4</b><strong>Lacak Hasil</strong><span>Cek status layanan atau verifikasi dokumen selesai.</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="wrapper bg-light" id="pengumuman">
  <div class="container py-12 py-md-14">
    <div class="section-heading">
      <span>Informasi Desa</span>
      <h2>Pengumuman terbaru</h2>
    </div>

    <div class="announcement-grid">
      @forelse($posts as $post)
        <article class="announcement-card">
          <figure>
            <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('assets/sandbox/img/photos/b4.jpg') }}" alt="{{ $post->title }}" loading="lazy">
          </figure>
          <div class="announcement-body">
            <span><i class="uil uil-calendar-alt"></i> {{ $post->created_at->format('d M Y') }}</span>
            <h3>{{ $post->title }}</h3>
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 130) }}</p>
          </div>
        </article>
      @empty
        <div class="empty-announcement">
          <i class="uil uil-megaphone"></i>
          <strong>Belum ada pengumuman terbaru</strong>
          <span>Informasi desa akan tampil di sini setelah dipublikasikan oleh operator.</span>
        </div>
      @endforelse
    </div>

    <div class="announcement-action">
      <a href="{{ route('public.pengumuman.index') }}" class="btn btn-primary rounded-pill">
        <i class="uil uil-megaphone"></i> Lihat Semua Pengumuman
      </a>
    </div>
  </div>
</section>

<section class="public-cta">
  <div class="container">
    <div class="cta-panel">
      <div>
        <span>Butuh Bantuan?</span>
        <h2>Hubungi desa atau kirim pengaduan langsung dari portal.</h2>
      </div>
      <div class="cta-actions">
        @if($contactWhatsapp)
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactWhatsapp) }}" target="_blank" rel="noopener" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-whatsapp"></i> WhatsApp Desa
          </a>
        @endif
        <a href="{{ route('public.pengaduan.create') }}" class="btn btn-primary rounded-pill">
          <i class="uil uil-message"></i> Buat Pengaduan
        </a>
      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  .public-hero {
    position: relative;
    overflow: hidden;
    padding: 5rem 0 3rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 48%, #ccfbf1 100%);
  }
  .hero-eyebrow,
  .section-heading span,
  .cta-panel span {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    color: #0f766e;
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
  }
  .public-hero h1 {
    max-width: 760px;
    margin: 0.8rem 0 1rem;
    color: #111827;
    font-size: clamp(2.3rem, 5vw, 4.7rem);
    line-height: 1.03;
    letter-spacing: 0;
    font-weight: 800;
  }
  .hero-copy {
    max-width: 690px;
    color: #475569;
    font-size: 1.18rem;
    line-height: 1.7;
  }
  .hero-actions,
  .cta-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.65rem;
    margin-top: 1.4rem;
  }
  .hero-actions .btn,
  .cta-actions .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
  }
  .hero-address {
    display: flex;
    align-items: flex-start;
    gap: 0.55rem;
    max-width: 620px;
    margin-top: 1.3rem;
    color: #334155;
    font-weight: 700;
  }
  .hero-address i { color: #0f766e; font-size: 1.25rem; }
  .hero-visual {
    position: relative;
    min-height: 360px;
    display: grid;
    place-items: center;
  }
  .hero-visual img {
    max-width: 100%;
    filter: drop-shadow(0 26px 36px rgba(15, 23, 42, 0.14));
  }
  .hero-status-card {
    position: absolute;
    left: 0;
    bottom: 1.25rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    max-width: 310px;
    padding: 1rem;
    border: 1px solid #dbeafe;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.92);
    box-shadow: 0 18px 44px rgba(15, 23, 42, 0.12);
    backdrop-filter: blur(10px);
  }
  .hero-status-card i {
    color: #059669;
    font-size: 1.55rem;
  }
  .hero-status-card strong,
  .hero-status-card span {
    display: block;
  }
  .hero-status-card strong { color: #111827; font-weight: 800; }
  .hero-status-card span { color: #64748b; font-size: 0.9rem; }
  .public-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
    margin-top: 3rem;
  }
  .public-stat,
  .service-card,
  .flow-step,
  .announcement-card,
  .cta-panel {
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
  }
  .public-stat {
    display: grid;
    gap: 0.25rem;
    padding: 1.1rem;
  }
  .public-stat i {
    color: #0f766e;
    font-size: 1.7rem;
  }
  .public-stat span { color: #64748b; font-weight: 800; }
  .public-stat strong { color: #111827; font-size: 1.9rem; font-weight: 800; }
  .section-heading {
    max-width: 760px;
    margin: 0 auto 2rem;
    text-align: center;
  }
  .section-heading h2,
  .cta-panel h2 {
    margin: 0.45rem 0 0;
    color: #111827;
    font-size: clamp(1.7rem, 3vw, 2.5rem);
    line-height: 1.15;
    letter-spacing: 0;
    font-weight: 800;
  }
  .service-grid,
  .announcement-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
  }
  .service-card {
    display: grid;
    gap: 0.65rem;
    padding: 1.15rem;
    color: #334155;
    min-height: 210px;
  }
  .service-card:hover {
    color: #0f766e;
    transform: translateY(-2px);
  }
  .service-card i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    border-radius: 14px;
    color: #ffffff;
    background: #0f766e;
    font-size: 1.5rem;
  }
  .service-card strong { color: #111827; font-size: 1.05rem; font-weight: 800; }
  .service-card span { color: #64748b; line-height: 1.6; }
  .flow-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
  }
  .flow-step {
    padding: 1rem;
  }
  .flow-step b {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 12px;
    color: #ffffff;
    background: #0f766e;
  }
  .flow-step strong,
  .flow-step span {
    display: block;
  }
  .flow-step strong { margin-top: 0.75rem; color: #111827; font-weight: 800; }
  .flow-step span { margin-top: 0.25rem; color: #64748b; }
  .announcement-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }
  .announcement-card {
    overflow: hidden;
  }
  .announcement-card figure {
    margin: 0;
    aspect-ratio: 16 / 10;
    background: #e2e8f0;
  }
  .announcement-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .announcement-body {
    padding: 1rem;
  }
  .announcement-body > span {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: #0f766e;
    font-weight: 800;
  }
  .announcement-body h3 {
    margin: 0.65rem 0 0.55rem;
    color: #111827;
    font-size: 1.05rem;
    line-height: 1.35;
    font-weight: 800;
    letter-spacing: 0;
  }
  .announcement-body p { color: #64748b; margin: 0; }
  .announcement-action {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
  }
  .announcement-action .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
  }
  .empty-announcement {
    grid-column: 1 / -1;
    display: grid;
    place-items: center;
    gap: 0.35rem;
    min-height: 180px;
    color: #64748b;
    border: 1px dashed #cbd5e1;
    border-radius: 16px;
    background: #f8fafc;
    text-align: center;
  }
  .empty-announcement i { color: #0f766e; font-size: 2rem; }
  .empty-announcement strong { color: #111827; }
  .public-cta {
    padding: 4rem 0;
    background: #f8fafc;
  }
  .cta-panel {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.4rem;
  }
  @media (max-width: 1199.98px) {
    .service-grid,
    .public-stat-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  }
  @media (max-width: 991.98px) {
    .announcement-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .hero-status-card { position: relative; left: auto; bottom: auto; margin-top: 1rem; }
    .cta-panel { align-items: stretch; flex-direction: column; }
  }
  @media (max-width: 767.98px) {
    .public-hero { padding-top: 3.5rem; }
    .hero-actions .btn,
    .cta-actions .btn { width: 100%; justify-content: center; }
    .service-grid,
    .public-stat-grid,
    .flow-grid,
    .announcement-grid { grid-template-columns: 1fr; }
  }
</style>
@endpush
