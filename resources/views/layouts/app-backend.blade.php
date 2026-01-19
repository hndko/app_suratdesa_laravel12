<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Surat Desa')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- ================== BEGIN core-css ================== -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <!-- ================== END core-css ================== -->
    @stack('css')
</head>

<body>
    <!-- BEGIN #app -->
    <div id="app" class="app">
        <!-- BEGIN #header -->
        <div id="header" class="app-header">
            <!-- BEGIN mobile-toggler -->
            <div class="mobile-toggler">
                <button type="button" class="menu-toggler" data-toggle="sidebar-mobile">
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
            </div>
            <!-- END mobile-toggler -->

            <!-- BEGIN brand -->
            <div class="brand">
                <div class="desktop-toggler">
                    <button type="button" class="menu-toggler" data-toggle="sidebar-minify">
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                </div>

                <a href="{{ route('dashboard') }}" class="brand-logo">
                    <img src="{{ asset('assets/img/logo.png') }}" class="invert-dark" alt="" height="20">
                    <span class="fw-bold ms-2">Surat Desa</span>
                </a>
            </div>
            <!-- END brand -->

            <!-- BEGIN menu -->
            <div class="menu">
                <div class="menu-item dropdown">
                    <a href="#" data-bs-toggle="dropdown" data-display="static" class="menu-link">
                        <div class="menu-img online">
                            <img src="{{ asset('assets/img/user/user.jpg') }}" alt=""
                                class="ms-100 mh-100 rounded-circle">
                        </div>
                        <div class="menu-text">{{ Auth::user()->name ?? 'User' }}</div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end me-lg-3">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center">Log Out <i
                                    class="fa fa-toggle-off fa-fw ms-auto text-body text-opacity-50"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END menu -->
        </div>
        <!-- END #header -->

        <!-- BEGIN #sidebar -->
        <div id="sidebar" class="app-sidebar">
            <!-- BEGIN scrollbar -->
            <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
                <!-- BEGIN menu -->
                <div class="menu">
                    <div class="menu-header">Navigation</div>
                    <div class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-laptop"></i></span>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </div>

                    <div class="menu-header">Master Data</div>
                    <div class="menu-item has-sub {{ request()->is('penduduk*') ? 'active' : '' }}">
                        <a href="#" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-users"></i></span>
                            <span class="menu-text">Data Penduduk</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item {{ request()->routeIs('penduduk.index') ? 'active' : '' }}">
                                <a href="#" class="menu-link">
                                    <span class="menu-text">List Penduduk</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('penduduk.create') ? 'active' : '' }}">
                                <a href="#" class="menu-link">
                                    <span class="menu-text">Tambah Penduduk</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item request()->is('jenis-surat*') ? 'active' : '' }}">
                        <a href="#" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-file-alt"></i></span>
                            <span class="menu-text">Jenis Surat</span>
                        </a>
                    </div>

                    <div class="menu-header">Transaksi</div>
                    <div class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-print"></i></span>
                            <span class="menu-text">Buat Surat</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-archive"></i></span>
                            <span class="menu-text">Arsip Surat</span>
                        </a>
                    </div>

                    <div class="p-3 px-4 mt-auto hide-on-minified">
                        <div class="text-body-emphasis fs-12px fw-bold">Surat Desa App</div>
                        <div class="text-muted fs-10px">v1.0.0</div>
                    </div>
                </div>
                <!-- END menu -->
            </div>
            <!-- END scrollbar -->

            <!-- BEGIN mobile-sidebar-backdrop -->
            <button class="app-sidebar-mobile-backdrop" data-dismiss="sidebar-mobile"></button>
            <!-- END mobile-sidebar-backdrop -->
        </div>
        <!-- END #sidebar -->

        <!-- BEGIN #content -->
        <div id="content" class="app-content">
            @yield('content')
        </div>
        <!-- END #content -->

        <!-- BEGIN btn-scroll-top -->
        <a href="#" data-click="scroll-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
        <!-- END btn-scroll-top -->
    </div>
    <!-- END #app -->

    <!-- ================== BEGIN core-js ================== -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <!-- ================== END core-js ================== -->
    @stack('js')

</body>

</html>