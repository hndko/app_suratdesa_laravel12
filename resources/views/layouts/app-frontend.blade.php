<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @php
    $siteName = \App\Facades\Setting::get('site_name', 'SIMADES');
    $seoTitle = \App\Facades\Setting::get('seo_title', $siteName);
    $seoDescription = \App\Facades\Setting::get('seo_description', \App\Facades\Setting::get('site_description', 'Sistem Informasi Desa Terintegrasi - ' . $siteName));
    $seoKeywords = \App\Facades\Setting::get('seo_keywords', 'simades, sistem informasi desa, surat desa, pelayanan desa');
    $seoAuthor = \App\Facades\Setting::get('seo_author', $siteName);
    $seoRobots = \App\Facades\Setting::get('seo_robots', 'index, follow');
    $favicon = \App\Facades\Setting::get('site_favicon', 'assets/img/favicon.png');
    $brandLogo = \App\Facades\Setting::get('site_logo', \App\Facades\Setting::get('village_logo', 'assets/img/favicon.png'));
    $publicBrandTagline = \App\Facades\Setting::get('public_brand_tagline', 'Portal Layanan Desa');
    $publicFooterDescription = \App\Facades\Setting::get('public_footer_description', 'Portal layanan mandiri untuk surat, pengaduan, verifikasi dokumen, dan informasi publik desa.');
    $publicFooterCtaTitle = \App\Facades\Setting::get('public_footer_cta_title', 'Layanan Mandiri');
    $publicFooterCtaText = \App\Facades\Setting::get('public_footer_cta_text', 'Ajukan surat, cek status layanan, atau kirim pengaduan langsung dari portal publik.');
    $publicFooterCtaButton = \App\Facades\Setting::get('public_footer_cta_button', 'Mulai Pengajuan');
    $ogTitle = \App\Facades\Setting::get('seo_og_title', $seoTitle);
    $ogDescription = \App\Facades\Setting::get('seo_og_description', $seoDescription);
    $ogImage = \App\Facades\Setting::get('seo_og_image', $brandLogo);
  @endphp
  <meta name="description" content="{{ $seoDescription }}">
  <meta name="keywords" content="{{ $seoKeywords }}">
  <meta name="author" content="{{ $seoAuthor }}">
  <meta name="robots" content="{{ $seoRobots }}">
  <meta property="og:title" content="{{ $ogTitle }}">
  <meta property="og:description" content="{{ $ogDescription }}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset($ogImage) }}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $ogTitle }}">
  <meta name="twitter:description" content="{{ $ogDescription }}">
  <meta name="twitter:image" content="{{ asset($ogImage) }}">
  <title>{{ $title ?? $seoTitle }}</title>
  
  <link rel="shortcut icon" href="{{ asset($favicon) }}">
  <link rel="stylesheet" href="{{ asset('assets/sandbox/css/plugins.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/sandbox/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/sandbox/css/colors/sky.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
  <link rel="preload" href="{{ asset('assets/sandbox/css/fonts/thicccboi.css') }}" as="style" onload="this.rel='stylesheet'">

  <style>
    .public-navbar {
      border-bottom: 1px solid rgba(15, 23, 42, 0.08);
      background: rgba(255, 255, 255, 0.94);
      backdrop-filter: blur(14px);
      box-shadow: 0 12px 34px rgba(15, 23, 42, 0.06);
    }

    .public-brand {
      display: inline-flex;
      align-items: center;
      gap: 0.7rem;
      min-width: 0;
      color: #111827;
    }

    .public-brand img {
      width: 44px;
      height: 44px;
      object-fit: contain;
      border-radius: 12px;
      background: #ffffff;
      box-shadow: 0 10px 24px rgba(15, 23, 42, 0.09);
    }

    .brand-text {
      display: grid;
      line-height: 1.1;
    }

    .brand-text strong {
      color: #111827;
      font-size: 1.08rem;
      font-weight: 800;
      letter-spacing: 0;
    }

    .brand-text span {
      color: #64748b;
      font-size: 0.78rem;
      font-weight: 800;
    }

    .public-navbar .navbar-nav .nav-link,
    .public-navbar .dropdown-item {
      display: inline-flex;
      align-items: center;
      gap: 0.42rem;
      color: #334155;
      font-weight: 800;
    }

    .public-navbar .navbar-nav .nav-link:hover,
    .public-navbar .dropdown-item:hover {
      color: #0f766e;
    }

    .public-navbar .dropdown-menu {
      overflow: hidden;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      box-shadow: 0 18px 42px rgba(15, 23, 42, 0.12);
    }

    .public-navbar .dropdown-item {
      padding: 0.65rem 1rem;
    }

    .public-navbar .dropdown-item i,
    .public-navbar .nav-link i {
      color: #0f766e;
      font-size: 1.05rem;
    }

    .public-nav-cta {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.45rem;
      white-space: nowrap;
    }

    .public-footer {
      position: relative;
      overflow: hidden;
      background: #0f172a;
    }

    .public-footer::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(14, 116, 144, 0.18), rgba(15, 118, 110, 0.16));
      pointer-events: none;
    }

    .public-footer .container {
      position: relative;
      z-index: 1;
    }

    .footer-brand {
      display: flex;
      gap: 0.85rem;
      align-items: center;
      margin-bottom: 1rem;
    }

    .footer-brand img {
      width: 52px;
      height: 52px;
      object-fit: contain;
      border-radius: 14px;
      background: #ffffff;
      padding: 0.3rem;
    }

    .footer-brand strong,
    .footer-brand span {
      display: block;
    }

    .footer-brand strong {
      color: #ffffff;
      font-size: 1.1rem;
      font-weight: 800;
    }

    .footer-brand span,
    .footer-muted {
      color: #cbd5e1;
      font-weight: 700;
    }

    .footer-list {
      display: grid;
      gap: 0.55rem;
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .footer-contact {
      display: grid;
      gap: 0.65rem;
    }

    .footer-list a,
    .footer-contact a,
    .footer-contact span {
      display: inline-flex;
      align-items: flex-start;
      gap: 0.45rem;
      color: #e2e8f0;
      font-weight: 700;
    }

    .footer-list a:hover,
    .footer-contact a:hover {
      color: #67e8f9;
    }

    .footer-list i,
    .footer-contact i {
      color: #5eead4;
      font-size: 1.05rem;
      margin-top: 0.08rem;
    }

    .footer-cta {
      padding: 1rem;
      border: 1px solid rgba(226, 232, 240, 0.14);
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.06);
    }

    .footer-cta .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.45rem;
    }

    @media (max-width: 991.98px) {
      .public-brand img {
        width: 40px;
        height: 40px;
      }

      .brand-text strong {
        max-width: 170px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

      .public-navbar .navbar-nav .nav-link {
        font-size: 1rem;
      }
    }

    .select2-container {
      width: 100% !important;
    }

    .select2-container .select2-selection--single {
      min-height: 52px;
      border: 1px solid rgba(8, 60, 130, 0.2);
      border-radius: 0.4rem;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
      line-height: 52px;
      padding-left: 1rem;
    }

    .select2-container .select2-selection--single .select2-selection__arrow {
      height: 52px;
      right: 0.75rem;
    }
  </style>

  @stack('styles')
</head>

<body>
  <div class="content-wrapper">
    <header class="wrapper public-navbar">
      <nav class="navbar navbar-expand-lg center-nav navbar-light">
        <div class="container flex-lg-row flex-nowrap align-items-center">
          <div class="navbar-brand w-100">
            <a href="{{ url('/') }}" class="public-brand">
              <img src="{{ asset($brandLogo) }}" alt="Logo {{ $siteName }}" />
              <span class="brand-text">
                <strong>{{ $siteName }}</strong>
                <span>{{ $publicBrandTagline }}</span>
              </span>
            </a>
          </div>
          <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
            <div class="offcanvas-header d-lg-none">
              <h3 class="text-white fs-30 mb-0">{{ \App\Facades\Setting::get('site_name', 'SIMADES') }}</h3>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body ms-lg-auto d-flex flex-column h-100">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link" href="{{ url('/') }}"><i class="uil uil-estate"></i> Beranda</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="uil uil-apps"></i> Layanan Online</a>
                  <ul class="dropdown-menu">
                    <li class="nav-item"><a class="dropdown-item" href="{{ route('public.surat.create') }}"><i class="uil uil-file-plus-alt"></i> Pengajuan Surat</a></li>
                    <li class="nav-item"><a class="dropdown-item" href="{{ route('public.surat.track') }}"><i class="uil uil-search-alt"></i> Lacak Surat</a></li>
                    <li class="nav-item"><a class="dropdown-item" href="{{ route('public.surat.verify') }}"><i class="uil uil-qrcode-scan"></i> Verifikasi Surat</a></li>
                    <li class="nav-item"><a class="dropdown-item" href="{{ route('public.pengaduan.create') }}"><i class="uil uil-comment-plus"></i> Kirim Pengaduan</a></li>
                    <li class="nav-item"><a class="dropdown-item" href="{{ route('public.pengaduan.track') }}"><i class="uil uil-ticket"></i> Lacak Aduan</a></li>
                  </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('public.pengumuman.index') }}"><i class="uil uil-megaphone"></i> Pengumuman</a>
                </li>
              </ul>
              <!-- /.navbar-nav -->
              <div class="offcanvas-footer d-lg-none">
                <div>
                  <a href="mailto:{{ \App\Facades\Setting::get('village_email', 'desa@example.com') }}" class="link-inverse"><i class="uil uil-envelope"></i> {{ \App\Facades\Setting::get('village_email', 'desa@example.com') }}</a>
                  <br /> <i class="uil uil-phone"></i> {{ \App\Facades\Setting::get('village_telepon', '-') }} <br />
                </div>
              </div>
              <!-- /.offcanvas-footer -->
            </div>
            <!-- /.offcanvas-body -->
          </div>
          <!-- /.navbar-collapse -->
          <div class="navbar-other w-100 d-flex ms-auto">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <li class="nav-item d-none d-md-block">
                <a href="{{ route('public.surat.create') }}" class="btn btn-sm btn-primary rounded-pill public-nav-cta"><i class="uil uil-file-plus-alt"></i> Ajukan Surat</a>
              </li>
              <li class="nav-item d-lg-none">
                <button class="hamburger offcanvas-nav-btn"><span></span></button>
              </li>
            </ul>
            <!-- /.navbar-nav -->
          </div>
          <!-- /.navbar-other -->
        </div>
        <!-- /.container -->
      </nav>
      <!-- /.navbar -->
    </header>
    <!-- /header -->

    @yield('content')

  </div>
  <!-- /.content-wrapper -->

  <footer class="public-footer text-inverse">
    <div class="container py-13 py-md-15">
      <div class="row gy-6 gy-lg-0">
        <div class="col-md-6 col-lg-3">
          <div class="widget">
            <div class="footer-brand">
              <img src="{{ asset($brandLogo) }}" alt="Logo {{ $siteName }}" loading="lazy" />
              <div>
                <strong>{{ $siteName }}</strong>
                <span>{{ \App\Facades\Setting::get('village_nama', 'Desa Kami') }}</span>
              </div>
            </div>
            <p class="footer-muted mb-0">{{ $publicFooterDescription }}</p>
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="col-md-6 col-lg-3">
          <div class="widget">
            <h4 class="widget-title text-white mb-3">Kontak Kami</h4>
            <div class="footer-contact">
              <span><i class="uil uil-map-marker"></i> {{ \App\Facades\Setting::get('village_alamat', 'Alamat Kantor Desa') }}</span>
              <a href="mailto:{{ \App\Facades\Setting::get('village_email', 'desa@example.com') }}"><i class="uil uil-envelope"></i> {{ \App\Facades\Setting::get('village_email', 'desa@example.com') }}</a>
              <span><i class="uil uil-phone"></i> {{ \App\Facades\Setting::get('village_telepon', '-') }}</span>
            </div>
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="col-md-6 col-lg-3">
          <div class="widget">
            <h4 class="widget-title text-white mb-3">Tautan Cepat</h4>
            <ul class="footer-list">
              <li><a href="{{ url('/') }}"><i class="uil uil-estate"></i> Beranda</a></li>
              <li><a href="{{ route('public.surat.create') }}"><i class="uil uil-file-plus-alt"></i> Pengajuan Surat</a></li>
              <li><a href="{{ route('public.surat.track') }}"><i class="uil uil-search-alt"></i> Lacak Surat</a></li>
              <li><a href="{{ route('public.surat.verify') }}"><i class="uil uil-qrcode-scan"></i> Verifikasi Surat</a></li>
              <li><a href="{{ route('public.pengaduan.create') }}"><i class="uil uil-comment-plus"></i> Layanan Pengaduan</a></li>
              <li><a href="{{ route('public.pengumuman.index') }}"><i class="uil uil-megaphone"></i> Pengumuman</a></li>
            </ul>
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="col-md-6 col-lg-3">
          <div class="widget footer-cta">
            <h4 class="widget-title text-white mb-3">{{ $publicFooterCtaTitle }}</h4>
            <p class="footer-muted mb-4">{{ $publicFooterCtaText }}</p>
            <a href="{{ route('public.surat.create') }}" class="btn btn-primary rounded-pill"><i class="uil uil-file-plus-alt"></i> {{ $publicFooterCtaButton }}</a>
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
      </div>
      <!--/.row -->
      <div class="footer-bottom mt-8 pt-5 border-top border-secondary">
        <p class="footer-muted mb-0">© {{ date('Y') }} {{ $siteName }}. Semua hak cipta dilindungi.</p>
      </div>
    </div>
    <!-- /.container -->
  </footer>

  <div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
  </div>
  
  <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('assets/sandbox/js/plugins.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
  <script src="{{ asset('assets/sandbox/js/theme.js') }}"></script>
  <script>
    $(function () {
      $('form select.form-control').not('.select2-hidden-accessible').select2({
        width: '100%'
      });
    });
  </script>
  @include('partials.sweetalert')
  @stack('scripts')
</body>

</html>
