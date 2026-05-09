<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMADES - Sistem Informasi Desa')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    
    <!-- Bootstrap 5 CSS (via CDN for simplicity & speed in this phase) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #0f172a;
            --accent: #3b82f6;
            --bg-glass: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            overflow-x: hidden;
        }

        .navbar {
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 500;
            color: #64748b;
            padding: 0.5rem 1.2rem !important;
            transition: color 0.3s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent);
        }

        .btn-primary {
            background-color: var(--accent);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            background-color: #2563eb;
        }

        .hero {
            padding: 8rem 0 5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            position: relative;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        footer {
            background: var(--primary);
            color: white;
            padding: 4rem 0 2rem;
        }

        .toast-container {
            z-index: 1056;
        }
    </style>
    @stack('css')
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('public.home') }}">
                <img src="{{ asset('assets/img/favicon.png') }}" alt="Logo" width="35" height="35" class="me-2">
                SIMADES
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}" href="{{ route('public.home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.surat.create') ? 'active' : '' }}" href="{{ route('public.surat.create') }}">Layanan Surat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.pengaduan.create') ? 'active' : '' }}" href="{{ route('public.pengaduan.create') }}">Pengaduan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.pengaduan.track') ? 'active' : '' }}" href="{{ route('public.pengaduan.track') }}">Lacak Aduan</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill">Login Perangkat</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container text-center">
            <div class="mb-4">
                <h4>SIMADES</h4>
                <p class="text-secondary">Sistem Informasi Desa Terintegrasi untuk Pelayanan yang Lebih Baik.</p>
            </div>
            <div class="social-links mb-4">
                <a href="#" class="text-white mx-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
            </div>
            <hr class="bg-secondary">
            <p class="mb-0 small text-secondary">&copy; {{ date('Y') }} Pemerintah Desa. Dikembangkan oleh Mari Partner.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @if(session('success'))
        <div class="toast show align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif
        
        @if(session('error'))
        <div class="toast show align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif
    </div>

    @stack('js')
</body>

</html>
