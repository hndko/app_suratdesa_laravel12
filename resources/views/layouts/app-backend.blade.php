<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', \App\Facades\Setting::get('site_name', config('app.name', 'Surat Desa')))</title>

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

    @stack('css')
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

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- User Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                        {{ Auth::user()->name ?? 'User' }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">Menu Pengguna</span>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <i class="fas fa-user-edit mr-2"></i> Edit Profil
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
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

                        @canany(['penduduk-index', 'jenis-surat-index'])
                        <li class="nav-header">MASTER DATA</li>

                        @can('penduduk-index')
                        <li class="nav-item">
                            <a href="{{ route('penduduk.index') }}"
                                class="nav-link {{ request()->is('penduduk*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Data Penduduk</p>
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

                        @canany(['user-index', 'role-index', 'setting-index', 'report-index', 'whatsapp-test'])
                        <li class="nav-header">PENGATURAN</li>

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

                        @can('report-index')
                        <li class="nav-item">
                            <a href="{{ route('report.index') }}" class="nav-link {{ request()->routeIs('report.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>Laporan & Rekap</p>
                            </a>
                        </li>
                        @endcan

                        @can('whatsapp-test')
                        <li class="nav-item">
                            <a href="{{ route('whatsapp.test.index') }}" class="nav-link {{ request()->routeIs('whatsapp.test.*') ? 'active' : '' }}">
                                <i class="nav-icon fab fa-whatsapp"></i>
                                <p>Test WA Gateway</p>
                            </a>
                        </li>
                        @endcan
                        @endcanany

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

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                v1.0.0
            </div>
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">{{ config('app.name') }}</a>.</strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

    @include('partials.sweetalert')

    @stack('js')
</body>

</html>
