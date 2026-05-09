@extends('layouts.app-auth')

@section('title', 'Login - ' . \App\Facades\Setting::get('site_name', 'SIMADES'))

@push('css')
<style>
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: stretch;
    }

    .login-side-visual {
        flex: 1.2;
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.9) 0%, rgba(42, 82, 152, 0.8) 100%), 
                    url('{{ asset("login_visual_side_1778309485148.png") }}');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px;
        color: white;
    }

    .login-side-visual h1 {
        font-weight: 700;
        font-size: 3.5rem;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    .login-side-visual p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 500px;
        line-height: 1.6;
    }

    .login-side-form {
        flex: 1;
        background: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 80px;
    }

    .login-form-container {
        max-width: 400px;
        width: 100%;
        margin: 0 auto;
    }

    .brand-logo {
        margin-bottom: 40px;
    }

    .brand-logo img {
        height: 60px;
        margin-bottom: 15px;
    }

    .brand-logo h2 {
        font-weight: 700;
        color: #1a202c;
        margin: 0;
    }

    .login-title {
        margin-bottom: 30px;
    }

    .login-title h3 {
        font-weight: 600;
        margin-bottom: 8px;
        color: #2d3748;
    }

    .login-title p {
        color: #718096;
    }

    .form-control {
        height: 50px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 12px 16px;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: #3182ce;
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
    }

    .input-group-text {
        background: transparent;
        border-color: #e2e8f0;
        border-radius: 0 8px 8px 0;
        color: #a0aec0;
    }

    .btn-login {
        height: 50px;
        border-radius: 8px;
        font-weight: 600;
        background: #3182ce;
        border: none;
        box-shadow: 0 4px 6px rgba(49, 130, 206, 0.15);
        transition: all 0.2s;
    }

    .btn-login:hover {
        background: #2b6cb0;
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(49, 130, 206, 0.2);
    }

    .alert {
        border-radius: 8px;
        border: none;
    }

    @media (max-width: 992px) {
        .login-side-visual {
            display: none;
        }
        .login-side-form {
            padding: 40px 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="login-wrapper">
    <!-- Visual Side -->
    <div class="login-side-visual">
        <h1>{{ \App\Facades\Setting::get('site_name', 'SIMADES') }}</h1>
        <p>Solusi digital terintegrasi untuk manajemen administrasi dan pelayanan publik pemerintahan desa yang lebih transparan dan efisien.</p>
        
        <div class="mt-5 d-flex align-items-center">
            <div class="mr-4">
                <h4 class="mb-0 font-weight-bold">Fase 1</h4>
                <small class="opacity-75">Sistem Persuratan & Pengaduan</small>
            </div>
            <div style="width: 2px; height: 40px; background: rgba(255,255,255,0.2)"></div>
            <div class="ml-4">
                <h4 class="mb-0 font-weight-bold">24/7</h4>
                <small class="opacity-75">Pelayanan Mandiri Online</small>
            </div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="login-side-form">
        <div class="login-form-container">
            <div class="brand-logo text-center">
                <img src="{{ asset(\App\Facades\Setting::get('village_logo', 'assets/img/favicon.png')) }}" alt="Logo">
                <h2>Selamat Datang</h2>
            </div>

            <div class="login-title">
                <h3>Login ke Dashboard</h3>
                <p>Silakan masukkan kredensial Anda untuk melanjutkan ke area administratif.</p>
            </div>

            @if(session('error'))
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="post">
                @csrf
                <div class="form-group mb-4">
                    <label class="font-weight-600 text-dark mb-2">Alamat Email</label>
                    <div class="input-group">
                        <input type="email" name="email" class="form-control" placeholder="nama@instansi.go.id" value="{{ old('email') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-600 text-dark mb-2">Kata Sandi</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="remember" id="remember">
                        <label class="custom-control-label text-muted" for="remember">Ingat Saya</label>
                    </div>
                    <a href="#" class="text-primary font-weight-600 small">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-login">
                    Masuk Sekarang <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>

            <div class="mt-5 text-center text-muted small">
                &copy; {{ date('Y') }} {{ \App\Facades\Setting::get('site_name', 'SIMADES') }}. All rights reserved.
            </div>
        </div>
    </div>
</div>
@endsection