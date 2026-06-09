@extends('layouts.app-frontend')

@section('content')
<section class="announcement-hero">
  <div class="container">
    <div class="row align-items-center gy-10">
      <div class="col-lg-7">
        <span class="page-eyebrow"><i class="uil uil-megaphone"></i> Pengumuman {{ $villageName }}</span>
        <h1>{{ \App\Facades\Setting::get('public_pengumuman_hero_title', 'Informasi resmi desa dalam satu halaman yang mudah dipantau.') }}</h1>
        <p>{{ \App\Facades\Setting::get('public_pengumuman_hero_description', 'Lihat pengumuman terbaru, agenda layanan, informasi kegiatan, dan kabar penting yang dipublikasikan oleh petugas ' . $siteName . '.') }}</p>
        <div class="hero-actions">
          <a href="#daftar-pengumuman" class="btn btn-primary rounded-pill">
            <i class="uil uil-list-ul"></i> Lihat Daftar
          </a>
          <a href="{{ route('public.home') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-estate"></i> Beranda
          </a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="hero-visual">
          <img src="{{ asset('assets/sandbox/img/illustrations/i13.png') }}" alt="Ilustrasi pengumuman desa" loading="lazy">
          <div class="visual-badge">
            <i class="uil uil-check-circle"></i>
            <span>{{ number_format($totalPengumuman, 0, ',', '.') }} pengumuman aktif tersedia untuk warga.</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="wrapper bg-light" id="daftar-pengumuman">
  <div class="container py-12 py-md-14">
    <div class="row gy-8">
      <div class="col-lg-4">
        <div class="info-panel">
          <div class="panel-heading">
            <span>Ringkasan</span>
            <h2>Informasi publik terbaru</h2>
          </div>
          <div class="summary-list">
            <div><i class="uil uil-megaphone"></i><span>Total Pengumuman</span><strong>{{ number_format($totalPengumuman, 0, ',', '.') }}</strong></div>
            <div><i class="uil uil-calendar-alt"></i><span>Terakhir Dipublikasi</span><strong>{{ $latestPost?->created_at?->format('d M Y') ?? '-' }}</strong></div>
            <div><i class="uil uil-shield-check"></i><span>Sumber</span><strong>{{ $siteName }}</strong></div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="search-panel">
          <div class="panel-heading">
            <span>Pencarian</span>
            <h2>Temukan pengumuman</h2>
          </div>
          <form action="{{ route('public.pengumuman.index') }}" method="GET">
            <label for="q">Kata Kunci</label>
            <div class="input-group">
              <span class="input-group-text"><i class="uil uil-search"></i></span>
              <input id="q" type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Cari judul atau isi pengumuman..." maxlength="100">
              <button type="submit" class="btn btn-primary">
                <i class="uil uil-search"></i> Cari
              </button>
            </div>
            @if($search !== '')
              <a href="{{ route('public.pengumuman.index') }}" class="reset-link">
                <i class="uil uil-times-circle"></i> Reset pencarian
              </a>
            @endif
          </form>
        </div>

        @if($search !== '')
          <div class="filter-note">
            <i class="uil uil-filter"></i>
            <span>Menampilkan hasil pencarian untuk <strong>{{ $search }}</strong>.</span>
          </div>
        @endif
      </div>
    </div>

    <div class="announcement-grid">
      @forelse($posts as $post)
        <article class="announcement-card">
          <figure>
            <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('assets/sandbox/img/photos/b4.jpg') }}" alt="{{ $post->title }}" loading="lazy">
          </figure>
          <div class="announcement-body">
            <span class="date-pill"><i class="uil uil-calendar-alt"></i> {{ $post->created_at?->format('d M Y') }}</span>
            <h2>{{ $post->title }}</h2>
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 170) }}</p>
          </div>
        </article>
      @empty
        <div class="empty-announcement">
          <i class="uil uil-search-alt"></i>
          <strong>Pengumuman tidak ditemukan</strong>
          <span>Coba gunakan kata kunci lain atau kembali ke daftar semua pengumuman.</span>
          <a href="{{ route('public.pengumuman.index') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-refresh"></i> Tampilkan Semua
          </a>
        </div>
      @endforelse
    </div>

    @if($posts->hasPages())
      <div class="pagination-wrap">
        {{ $posts->links() }}
      </div>
    @endif
  </div>
</section>
@endsection

@push('styles')
<style>
  .announcement-hero {
    overflow: hidden;
    padding: 5rem 0 3.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 52%, #dcfce7 100%);
  }
  .page-eyebrow,
  .panel-heading span {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    color: #0f766e;
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
  }
  .announcement-hero h1 {
    max-width: 780px;
    margin: 0.8rem 0 1rem;
    color: #111827;
    font-size: clamp(2.25rem, 5vw, 4.35rem);
    line-height: 1.04;
    letter-spacing: 0;
    font-weight: 800;
  }
  .announcement-hero p {
    max-width: 700px;
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
  .search-panel .btn,
  .empty-announcement .btn {
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
  .search-panel,
  .announcement-card,
  .empty-announcement {
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
  }
  .info-panel,
  .search-panel {
    padding: 1.25rem;
  }
  .panel-heading {
    margin-bottom: 1rem;
  }
  .panel-heading h2 {
    margin: 0.35rem 0 0;
    color: #111827;
    font-size: 1.35rem;
    font-weight: 800;
    letter-spacing: 0;
  }
  .summary-list {
    display: grid;
    gap: 0.75rem;
  }
  .summary-list div {
    display: grid;
    grid-template-columns: 42px 1fr;
    gap: 0 0.65rem;
    padding: 0.85rem;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #f8fafc;
  }
  .summary-list i {
    grid-row: span 2;
    color: #0f766e;
    font-size: 1.35rem;
  }
  .summary-list span {
    color: #64748b;
    font-weight: 800;
  }
  .summary-list strong {
    color: #111827;
    font-weight: 800;
    word-break: break-word;
  }
  .search-panel label {
    color: #334155;
    font-weight: 800;
  }
  .search-panel .input-group-text {
    min-width: 48px;
    justify-content: center;
    color: #0f766e;
    border-color: #dbe3ef;
    background: #f8fafc;
  }
  .search-panel .form-control {
    min-height: 52px;
    border-color: #dbe3ef;
    border-radius: 0.45rem 0 0 0.45rem;
    font-weight: 700;
  }
  .search-panel .btn {
    min-height: 52px;
    white-space: nowrap;
  }
  .reset-link {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    margin-top: 0.75rem;
    color: #0f766e;
    font-weight: 800;
  }
  .filter-note {
    display: flex;
    gap: 0.55rem;
    margin-top: 1rem;
    padding: 0.9rem 1rem;
    border: 1px solid #bae6fd;
    border-radius: 14px;
    color: #075985;
    background: #e0f2fe;
    font-weight: 700;
  }
  .announcement-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
    margin-top: 2rem;
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
  .date-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: #0f766e;
    font-weight: 800;
  }
  .announcement-body h2 {
    margin: 0.65rem 0 0.55rem;
    color: #111827;
    font-size: 1.08rem;
    line-height: 1.35;
    font-weight: 800;
    letter-spacing: 0;
  }
  .announcement-body p {
    color: #64748b;
    margin: 0;
    line-height: 1.65;
  }
  .empty-announcement {
    grid-column: 1 / -1;
    display: grid;
    place-items: center;
    gap: 0.55rem;
    min-height: 220px;
    padding: 1.25rem;
    color: #64748b;
    text-align: center;
  }
  .empty-announcement i {
    color: #0f766e;
    font-size: 2rem;
  }
  .empty-announcement strong {
    color: #111827;
    font-size: 1.1rem;
  }
  .pagination-wrap {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
  }
  @media (max-width: 991.98px) {
    .visual-badge {
      position: relative;
      left: auto;
      bottom: auto;
      margin-top: 1rem;
    }
    .announcement-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }
  @media (max-width: 767.98px) {
    .announcement-hero {
      padding-top: 3.5rem;
    }
    .hero-actions .btn,
    .search-panel .btn {
      width: 100%;
    }
    .search-panel .input-group {
      display: grid;
      grid-template-columns: 48px minmax(0, 1fr);
    }
    .search-panel .btn {
      grid-column: 1 / -1;
      margin-top: 0.75rem;
      border-radius: 999px !important;
    }
    .announcement-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush
