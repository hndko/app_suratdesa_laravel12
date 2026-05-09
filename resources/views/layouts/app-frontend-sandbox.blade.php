<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Sistem Informasi Desa Terintegrasi - {{ \App\Facades\Setting::get('site_name', 'SIMADES') }}">
  <meta name="author" content="SIMADES">
  <title>@yield('title', \App\Facades\Setting::get('site_name', 'SIMADES'))</title>
  
  <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
  <link rel="stylesheet" href="{{ asset('assets/sandbox/css/plugins.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/sandbox/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/sandbox/css/colors/sky.css') }}">
  <link rel="preload" href="{{ asset('assets/sandbox/css/fonts/thicccboi.css') }}" as="style" onload="this.rel='stylesheet'">

  @stack('css')
</head>

<body>
  <div class="content-wrapper">
    <header class="wrapper bg-light">
      <nav class="navbar navbar-expand-lg center-nav transparent navbar-light">
        <div class="container flex-lg-row flex-nowrap align-items-center">
          <div class="navbar-brand w-100">
            <a href="{{ url('/') }}">
              <img src="{{ asset(\App\Facades\Setting::get('village_logo', 'assets/img/favicon.png')) }}" style="max-height: 40px;" alt="Logo" />
              <span class="ms-2 fw-bold text-dark fs-20">{{ \App\Facades\Setting::get('site_name', 'SIMADES') }}</span>
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
                  <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Layanan Online</a>
                  <ul class="dropdown-menu">
                    <li class="nav-item"><a class="dropdown-item" href="{{ route('public.surat.create') }}">Pengajuan Surat</a></li>
                    <li class="nav-item"><a class="dropdown-item" href="{{ route('public.pengaduan.create') }}">Kirim Pengaduan</a></li>
                    <li class="nav-item"><a class="dropdown-item" href="{{ route('public.pengaduan.track') }}">Lacak Aduan</a></li>
                  </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#pengumuman">Pengumuman</a>
                </li>
              </ul>
              <!-- /.navbar-nav -->
              <div class="offcanvas-footer d-lg-none">
                <div>
                  <a href="mailto:{{ \App\Facades\Setting::get('village_email', 'desa@example.com') }}" class="link-inverse">{{ \App\Facades\Setting::get('village_email', 'desa@example.com') }}</a>
                  <br /> {{ \App\Facades\Setting::get('village_phone', '-') }} <br />
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
                <a href="{{ route('login') }}" class="btn btn-sm btn-primary rounded-pill">Login Staff</a>
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

  <footer class="bg-dark text-inverse">
    <div class="container py-13 py-md-15">
      <div class="row gy-6 gy-lg-0">
        <div class="col-md-4 col-lg-3">
          <div class="widget">
            <img class="mb-4" src="{{ asset(\App\Facades\Setting::get('village_logo', 'assets/img/favicon.png')) }}" style="max-height: 50px;" alt="" />
            <p class="mb-4">© {{ date('Y') }} {{ \App\Facades\Setting::get('site_name', 'SIMADES') }}. <br class="d-none d-lg-block" />All rights reserved.</p>
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="col-md-4 col-lg-3">
          <div class="widget">
            <h4 class="widget-title text-white mb-3">Kontak Kami</h4>
            <address class="pe-xl-15">{{ \App\Facades\Setting::get('village_address', 'Alamat Kantor Desa') }}</address>
            <a href="mailto:{{ \App\Facades\Setting::get('village_email', 'desa@example.com') }}" class="link-body">{{ \App\Facades\Setting::get('village_email', 'desa@example.com') }}</a><br /> {{ \App\Facades\Setting::get('village_phone', '-') }}
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="col-md-4 col-lg-3">
          <div class="widget">
            <h4 class="widget-title text-white mb-3">Tautan Cepat</h4>
            <ul class="list-unstyled  mb-0">
              <li><a href="{{ url('/') }}">Beranda</a></li>
              <li><a href="{{ route('public.surat.create') }}">Pengajuan Surat</a></li>
              <li><a href="{{ route('public.pengaduan.create') }}">Layanan Pengaduan</a></li>
            </ul>
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="col-md-12 col-lg-3">
          <div class="widget">
            <h4 class="widget-title text-white mb-3">Layanan Mandiri</h4>
            <p class="mb-5">Ajukan surat menyurat desa dengan lebih mudah dan cepat melalui portal online kami.</p>
            <a href="{{ route('public.surat.create') }}" class="btn btn-primary rounded-pill">Mulai Pengajuan</a>
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
      </div>
      <!--/.row -->
    </div>
    <!-- /.container -->
  </footer>

  <div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
  </div>
  
  <script src="{{ asset('assets/sandbox/js/plugins.js') }}"></script>
  <script src="{{ asset('assets/sandbox/js/theme.js') }}"></script>
  @stack('js')
</body>

</html>
