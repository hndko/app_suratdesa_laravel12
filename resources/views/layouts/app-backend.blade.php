<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', config('app.name', 'Surat Desa'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- ================== BEGIN core-css ================== -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <!-- ================== END core-css ================== -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
                    <span class="fw-bold ms-2">{{ config('app.name') }}</span>
                </a>
            </div>
            <!-- END brand -->

            <!-- BEGIN menu -->
            <div class="menu">
                <div class="menu-search">
                </div>
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
                    <div class="menu-item {{ request()->is('penduduk*') ? 'active' : '' }}">
                        <a href="{{ route('penduduk.index') }}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-users"></i></span>
                            <span class="menu-text">Data Penduduk</span>
                        </a>
                    </div>
                    <div class="menu-item {{ request()->is('jenis-surat*') ? 'active' : '' }}">
                        <a href="{{ route('jenis-surat.index') }}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-file-alt"></i></span>
                            <span class="menu-text">Jenis Surat</span>
                        </a>
                    </div>

                    <div class="menu-header">Transaksi</div>
                    <div class="menu-item {{ request()->routeIs('surat.create') ? 'active' : '' }}">
                        <a href="{{ route('surat.create') }}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-print"></i></span>
                            <span class="menu-text">Buat Surat</span>
                        </a>
                    </div>
                    <div class="menu-item {{ request()->routeIs('surat.index') ? 'active' : '' }}">
                        <a href="{{ route('surat.index') }}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-archive"></i></span>
                            <span class="menu-text">Arsip Surat</span>
                        </a>
                    </div>

                    <div class="p-3 px-4 mt-auto hide-on-minified">
                        <div class="text-body-emphasis fs-12px fw-bold">{{ config('app.name') }} App</div>
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

    <!-- BEGIN toasts-container -->
    <div class="toasts-container">
        @if(session('success'))
        <div class="toast fade show mb-3" data-autohide="true" id="toast-success">
            <div class="toast-header">
                <i class="far fa-check-circle text-success me-2"></i>
                <strong class="me-auto">Sukses</strong>
                <small>Baru saja</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="toast fade show mb-3" data-autohide="true" id="toast-error">
            <div class="toast-header">
                <i class="far fa-times-circle text-danger me-2"></i>
                <strong class="me-auto">Error</strong>
                <small>Baru saja</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
        @endif
    </div>
    <!-- END toasts-container -->

    <!-- ================== BEGIN core-js ================== -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <!-- ================== END core-js ================== -->

    <script>
        // Auto show toasts
        $(document).ready(function() {
            $('.toast').each(function() {
                var toast = new bootstrap.Toast($(this));
                toast.show();
            });
        });
    </script>

    @stack('js')

</body>

</html>