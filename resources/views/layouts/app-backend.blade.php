<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? \App\Facades\Setting::get('site_name', config('app.name', 'SIMADES')) }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .main-sidebar {
            background: linear-gradient(180deg, #111827 0%, #0f2f4d 56%, #0f3f3a 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.06);
        }

        .brand-link {
            min-height: 64px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            display: flex;
            align-items: center;
            padding: 0.78rem 1rem;
        }

        .brand-link .brand-image {
            width: 38px;
            height: 38px;
            max-height: 38px;
            object-fit: contain;
            background: #ffffff;
            border-radius: 11px;
            padding: 4px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.24);
            margin-left: 0;
            margin-right: 0.75rem;
        }

        .brand-link .brand-text {
            font-weight: 700 !important;
            color: #ffffff;
            letter-spacing: 0;
            line-height: 1.2;
            white-space: nowrap;
        }

        .sidebar {
            padding: 0 0.75rem 1rem;
        }

        .user-panel {
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 0.75rem !important;
            margin: 0.9rem 0 1rem !important;
            align-items: center;
        }

        .user-panel .image {
            padding-left: 0;
        }

        .user-panel img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.42);
        }

        .user-panel .info {
            padding: 0 0 0 0.7rem;
            min-width: 0;
        }

        .user-panel .info a {
            color: #ffffff;
            font-weight: 700;
            line-height: 1.1;
            max-width: 168px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-panel .user-role {
            display: block;
            color: rgba(255, 255, 255, 0.68);
            font-size: 0.74rem;
            margin-top: 4px;
        }

        .nav-sidebar {
            padding-bottom: 0.75rem;
        }

        .nav-sidebar .nav-header {
            color: rgba(255, 255, 255, 0.48);
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            padding: 1rem 0.75rem 0.45rem;
            margin-top: 0.2rem;
        }

        .nav-sidebar .nav-header::after {
            content: "";
            display: block;
            height: 1px;
            margin-top: 0.42rem;
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-sidebar .nav-item {
            margin-bottom: 0.18rem;
        }

        .nav-sidebar .nav-link {
            border-radius: 11px;
            color: rgba(255, 255, 255, 0.78);
            padding: 0.62rem 0.78rem;
            min-height: 42px;
            transition: background-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
        }

        .nav-sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: translateX(2px);
        }

        .nav-sidebar .nav-link.active {
            background: linear-gradient(135deg, #1d6dde, #119c8d) !important;
            color: #ffffff !important;
            box-shadow: 0 10px 22px rgba(13, 110, 253, 0.28);
        }

        .nav-sidebar .nav-icon {
            width: 1.5rem;
            margin-right: 0.48rem;
            text-align: center;
            color: inherit;
        }

        .nav-sidebar .nav-link p {
            font-weight: 600;
            font-size: 0.92rem;
            white-space: normal;
            line-height: 1.25;
        }

        .nav-sidebar .logout-link {
            color: #fecaca;
        }

        .nav-sidebar .logout-link:hover {
            background: rgba(239, 68, 68, 0.14);
            color: #ffffff;
        }

        .sidebar-mini.sidebar-collapse .brand-link .brand-text,
        .sidebar-mini.sidebar-collapse .user-panel .info {
            opacity: 0;
        }

        .select2-container--bootstrap4 .select2-selection {
            min-height: 38px;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }

        .input-group .select2-container {
            flex: 1 1 auto;
            width: 1% !important;
        }

        html,
        body,
        .wrapper,
        .content-wrapper {
            background: #f4f6f9;
        }

        .content-wrapper {
            min-height: 0 !important;
            padding-bottom: 0.75rem;
        }

    </style>

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link">
                <img src="{{ asset(\App\Facades\Setting::get('village_logo', 'assets/img/favicon.png')) }}" alt="Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ \App\Facades\Setting::get('site_name', 'SIMADES') }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                            alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()->name ?? 'Administrator' }}</a>
                        <span class="user-role">{{ Auth::user()?->roles?->pluck('name')->first() ?? 'Staff Desa' }}</span>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @canany(['kartu-keluarga-index', 'penduduk-index', 'jenis-surat-index', 'import-penduduk-index'])
                        <li class="nav-header">MASTER DATA</li>

                        @can('kartu-keluarga-index')
                        <li class="nav-item">
                            <a href="{{ route('kartu-keluarga.index') }}"
                                class="nav-link {{ request()->is('kartu-keluarga*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-address-card"></i>
                                <p>Kartu Keluarga</p>
                            </a>
                        </li>
                        @endcan

                        @can('penduduk-index')
                        <li class="nav-item">
                            <a href="{{ route('penduduk.index') }}"
                                class="nav-link {{ request()->is('penduduk*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Data Penduduk</p>
                            </a>
                        </li>
                        @endcan

                        @can('import-penduduk-index')
                        <li class="nav-item">
                            <a href="{{ route('import-penduduk.index') }}"
                                class="nav-link {{ request()->routeIs('import-penduduk.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-import"></i>
                                <p>Import Penduduk</p>
                            </a>
                        </li>
                        @endcan

                        @can('jenis-surat-index')
                        <li class="nav-item">
                            <a href="{{ route('jenis-surat.index') }}"
                                class="nav-link {{ request()->is('jenis-surat*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Jenis Surat</p>
                            </a>
                        </li>
                        @endcan
                        @endcanany

                        @canany(['surat-create', 'surat-index'])
                        <li class="nav-header">TRANSAKSI</li>

                        @can('surat-create')
                        <li class="nav-item">
                            <a href="{{ route('surat.create') }}"
                                class="nav-link {{ request()->routeIs('surat.create') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-pen"></i>
                                <p>Buat Surat</p>
                            </a>
                        </li>
                        @endcan

                        @can('surat-index')
                        <li class="nav-item">
                            <a href="{{ route('surat.index') }}"
                                class="nav-link {{ request()->routeIs('surat.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-archive"></i>
                                <p>Arsip Surat</p>
                            </a>
                        </li>
                        @endcan
                        @endcanany

                        @canany(['post-index', 'pengaduan-index'])
                        <li class="nav-header">INFORMASI & LAYANAN</li>

                        @can('post-index')
                        <li class="nav-item">
                            <a href="{{ route('post.index') }}"
                                class="nav-link {{ request()->is('post*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>Pengumuman Desa</p>
                            </a>
                        </li>
                        @endcan

                        @can('pengaduan-index')
                        <li class="nav-item">
                            <a href="{{ route('pengaduan.index') }}"
                                class="nav-link {{ request()->is('pengaduan*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>Pengaduan Warga</p>
                            </a>
                        </li>
                        @endcan
                        @endcanany

                        @canany(['report-index', 'whatsapp-test-index', 'ai-setting-index', 'ai-log-index', 'ai-playground-send'])
                        <li class="nav-header">LAPORAN & INTEGRASI</li>

                        @can('report-index')
                        <li class="nav-item">
                            <a href="{{ route('report.index') }}" class="nav-link {{ request()->routeIs('report.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>Laporan & Rekap</p>
                            </a>
                        </li>
                        @endcan

                        @can('ai-playground-send')
                        <li class="nav-item">
                            <a href="{{ route('ai-assistant.index') }}" class="nav-link {{ request()->routeIs('ai-assistant.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>AI Assistant</p>
                            </a>
                        </li>
                        @endcan

                        @can('whatsapp-test-index')
                        <li class="nav-item">
                            <a href="{{ route('whatsapp.test.index') }}" class="nav-link {{ request()->routeIs('whatsapp.test.*') ? 'active' : '' }}">
                                <i class="nav-icon fab fa-whatsapp"></i>
                                <p>Test WA Gateway</p>
                            </a>
                        </li>
                        @endcan

                        @can('ai-setting-index')
                        <li class="nav-item">
                            <a href="{{ route('ai-settings.index') }}" class="nav-link {{ request()->routeIs('ai-settings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-robot"></i>
                                <p>AI Gateway</p>
                            </a>
                        </li>
                        @endcan

                        @can('ai-log-index')
                        <li class="nav-item">
                            <a href="{{ route('ai-logs.index') }}" class="nav-link {{ request()->routeIs('ai-logs.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>AI Usage Log</p>
                            </a>
                        </li>
                        @endcan
                        @endcanany

                        @canany(['user-index', 'role-index', 'setting-index', 'activity-log-index'])
                        <li class="nav-header">SISTEM</li>

                        @can('user-index')
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}" class="nav-link {{ request()->is('user*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>
                        @endcan

                        @can('role-index')
                        <li class="nav-item">
                            <a href="{{ route('role.index') }}" class="nav-link {{ request()->is('role*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-key"></i>
                                <p>Role & Permission</p>
                            </a>
                        </li>
                        @endcan

                        @can('setting-index')
                        <li class="nav-item">
                            <a href="{{ route('setting.index') }}" class="nav-link {{ request()->is('setting*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Pengaturan Web</p>
                            </a>
                        </li>
                        @endcan

                        @can('activity-log-index')
                        <li class="nav-item">
                            <a href="{{ route('activity-log.index') }}" class="nav-link {{ request()->routeIs('activity-log.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Activity Log</p>
                            </a>
                        </li>
                        @endcan
                        @endcanany

                        <li class="nav-header">AKUN</li>

                        @can('profile-index')
                        <li class="nav-item">
                            <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-edit"></i>
                                <p>Edit Profil</p>
                            </a>
                        </li>
                        @endcan

                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link logout-link border-0 bg-transparent text-left w-100">
                                    <i class="nav-icon fas fa-sign-out-alt"></i>
                                    <p>Logout</p>
                                </button>
                            </form>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content pt-3">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        $(function () {
            $('form select.form-control').not('.select2-hidden-accessible').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>

    @include('partials.sweetalert')

    @stack('scripts')
</body>

</html>
