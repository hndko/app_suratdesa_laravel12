@extends('layouts.app-auth')

@push('styles')
<style>
    :root {
        --login-ink: #172033;
        --login-muted: #667085;
        --login-line: #e5e7eb;
        --login-primary: #1267d6;
        --login-primary-dark: #0c4fa8;
        --login-soft: #eef6ff;
        --login-green: #0f766e;
    }

    body {
        background:
            radial-gradient(circle at top left, rgba(18, 103, 214, 0.12), transparent 34rem),
            linear-gradient(135deg, #f8fbff 0%, #eef4f8 100%);
        color: var(--login-ink);
    }

    .login-page-shell {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 32px 18px;
    }

    .login-panel {
        width: 100%;
        max-width: 1120px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(360px, 0.95fr);
        background: rgba(255, 255, 255, 0.88);
        border: 1px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0 24px 70px rgba(23, 32, 51, 0.14);
        overflow: hidden;
        border-radius: 22px;
        backdrop-filter: blur(14px);
    }

    .login-identity {
        position: relative;
        padding: 54px;
        background:
            linear-gradient(135deg, rgba(10, 68, 142, 0.96), rgba(15, 118, 110, 0.9)),
            url('{{ asset('assets/img/logo.png') }}');
        background-repeat: no-repeat;
        background-position: right 42px bottom 42px;
        background-size: 180px;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 620px;
    }

    .login-identity::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.06), rgba(0, 0, 0, 0.16));
        pointer-events: none;
    }

    .login-identity > * {
        position: relative;
        z-index: 1;
    }

    .brand-row {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .brand-mark {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.96);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.16);
    }

    .brand-mark img {
        width: 38px;
        height: 38px;
        object-fit: contain;
    }

    .brand-row h1 {
        font-size: 1.28rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: 0;
    }

    .brand-row span {
        display: block;
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.88rem;
        margin-top: 2px;
    }

    .identity-copy {
        max-width: 560px;
        margin: 78px 0 38px;
    }

    .identity-copy .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        color: rgba(255, 255, 255, 0.92);
        font-weight: 600;
        font-size: 0.82rem;
        margin-bottom: 18px;
    }

    .identity-copy h2 {
        font-size: 2.45rem;
        line-height: 1.12;
        font-weight: 800;
        margin: 0 0 18px;
        letter-spacing: 0;
    }

    .identity-copy p {
        font-size: 1.04rem;
        line-height: 1.75;
        color: rgba(255, 255, 255, 0.84);
        margin: 0;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .feature-tile {
        padding: 16px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.16);
    }

    .feature-tile i {
        font-size: 1.1rem;
        margin-bottom: 10px;
    }

    .feature-tile strong {
        display: block;
        font-size: 0.98rem;
        margin-bottom: 4px;
    }

    .feature-tile span {
        display: block;
        color: rgba(255, 255, 255, 0.76);
        font-size: 0.84rem;
        line-height: 1.45;
    }

    .login-form-side {
        padding: 52px;
        display: flex;
        align-items: center;
    }

    .login-form-inner {
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
    }

    .mobile-brand {
        display: none;
        align-items: center;
        gap: 12px;
        margin-bottom: 28px;
    }

    .mobile-brand img {
        width: 44px;
        height: 44px;
        object-fit: contain;
    }

    .login-heading {
        margin-bottom: 26px;
    }

    .login-heading h2 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--login-ink);
        margin: 0 0 8px;
        letter-spacing: 0;
    }

    .login-heading p {
        color: var(--login-muted);
        margin: 0;
        line-height: 1.6;
    }

    .staff-access-note {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        background: var(--login-soft);
        border: 1px solid #d8eaff;
        color: #1d4f86;
        border-radius: 14px;
        padding: 14px;
        margin-bottom: 24px;
        font-size: 0.92rem;
        line-height: 1.5;
    }

    .staff-access-note i {
        margin-top: 3px;
    }

    .form-label {
        font-weight: 700;
        color: #344054;
        margin-bottom: 8px;
        font-size: 0.92rem;
    }

    .login-input-group {
        position: relative;
    }

    .login-input-group .field-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a2b3;
        z-index: 2;
    }

    .login-input {
        height: 52px;
        border: 1px solid var(--login-line);
        border-radius: 14px;
        padding: 13px 46px 13px 44px;
        color: var(--login-ink);
        transition: border-color 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
    }

    .login-input::placeholder {
        color: #98a2b3;
    }

    .login-input:focus {
        border-color: var(--login-primary);
        box-shadow: 0 0 0 4px rgba(18, 103, 214, 0.12);
    }

    .password-toggle {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 38px;
        height: 38px;
        border: 0;
        border-radius: 12px;
        background: transparent;
        color: #667085;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle:hover,
    .password-toggle:focus {
        background: #f2f4f7;
        outline: none;
    }

    .remember-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin: 18px 0 24px;
        color: var(--login-muted);
        font-size: 0.92rem;
    }

    .login-action {
        height: 52px;
        border-radius: 14px;
        font-weight: 700;
        background: var(--login-primary);
        border-color: var(--login-primary);
        box-shadow: 0 14px 26px rgba(18, 103, 214, 0.24);
        transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
    }

    .login-action:hover,
    .login-action:focus {
        background: var(--login-primary-dark);
        border-color: var(--login-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 18px 30px rgba(18, 103, 214, 0.28);
    }

    .portal-link {
        margin-top: 18px;
        text-align: center;
    }

    .portal-link a {
        color: var(--login-primary);
        font-weight: 700;
    }

    .security-row {
        margin-top: 30px;
        padding-top: 22px;
        border-top: 1px solid var(--login-line);
        display: grid;
        gap: 10px;
        color: var(--login-muted);
        font-size: 0.86rem;
    }

    .security-row span {
        display: flex;
        gap: 9px;
        align-items: center;
    }

    .security-row i {
        color: var(--login-green);
    }

    @media (max-width: 991.98px) {
        .login-page-shell {
            align-items: flex-start;
            padding: 18px;
        }

        .login-panel {
            grid-template-columns: 1fr;
            border-radius: 18px;
        }

        .login-identity {
            min-height: auto;
            padding: 34px;
        }

        .identity-copy {
            margin: 38px 0 26px;
        }

        .identity-copy h2 {
            font-size: 2rem;
        }

        .feature-grid {
            grid-template-columns: 1fr 1fr;
        }

        .login-form-side {
            padding: 38px 28px 42px;
        }
    }

    @media (max-width: 575.98px) {
        .login-page-shell {
            padding: 0;
            background: #ffffff;
        }

        .login-panel {
            min-height: 100vh;
            border-radius: 0;
            box-shadow: none;
            border: 0;
        }

        .login-identity {
            display: none;
        }

        .mobile-brand {
            display: flex;
        }

        .login-form-side {
            padding: 28px 20px;
            align-items: flex-start;
        }

        .login-heading h2 {
            font-size: 1.48rem;
        }

        .remember-row {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<main class="login-page-shell">
    <section class="login-panel" aria-label="Login SIMADES">
        <aside class="login-identity">
            <div class="brand-row">
                <div class="brand-mark">
                    <img src="{{ asset(\App\Facades\Setting::get('village_logo', 'assets/img/favicon.png')) }}"
                        alt="Logo {{ \App\Facades\Setting::get('site_name', 'SIMADES') }}"
                        loading="lazy"
                        decoding="async">
                </div>
                <div>
                    <h1>{{ \App\Facades\Setting::get('site_name', 'SIMADES') }}</h1>
                    <span>Dashboard Administrasi Desa</span>
                </div>
            </div>

            <div class="identity-copy">
                <div class="eyebrow"><i class="fas fa-shield-alt"></i> Akses khusus perangkat desa</div>
                <h2>Kelola layanan desa dari satu dashboard yang rapi.</h2>
                <p>Masuk untuk memproses surat, mengelola data penduduk, menanggapi pengaduan, memantau laporan, dan mengatur layanan publik digital SIMADES.</p>
            </div>

            <div class="feature-grid">
                <div class="feature-tile">
                    <i class="fas fa-file-signature"></i>
                    <strong>Pelayanan Surat</strong>
                    <span>Approval, tracking, dan verifikasi dokumen.</span>
                </div>
                <div class="feature-tile">
                    <i class="fas fa-users"></i>
                    <strong>Data Penduduk</strong>
                    <span>Kelola penduduk dan Kartu Keluarga.</span>
                </div>
                <div class="feature-tile">
                    <i class="fas fa-comments"></i>
                    <strong>Pengaduan Warga</strong>
                    <span>Tindak lanjuti laporan dengan lebih cepat.</span>
                </div>
                <div class="feature-tile">
                    <i class="fas fa-chart-line"></i>
                    <strong>Monitoring</strong>
                    <span>Laporan dan statistik operasional desa.</span>
                </div>
            </div>
        </aside>

        <section class="login-form-side">
            <div class="login-form-inner">
                <div class="mobile-brand">
                    <img src="{{ asset(\App\Facades\Setting::get('village_logo', 'assets/img/favicon.png')) }}"
                        alt="Logo {{ \App\Facades\Setting::get('site_name', 'SIMADES') }}"
                        loading="lazy"
                        decoding="async">
                    <div>
                        <strong>{{ \App\Facades\Setting::get('site_name', 'SIMADES') }}</strong>
                        <div class="text-muted small">Dashboard Staff Desa</div>
                    </div>
                </div>

                <div class="login-heading">
                    <h2>Masuk ke Dashboard</h2>
                    <p>Gunakan akun staff yang sudah terdaftar untuk mengakses fitur administrasi desa.</p>
                </div>

                <div class="staff-access-note">
                    <i class="fas fa-info-circle"></i>
                    <div>Halaman ini untuk perangkat desa. Warga dapat memakai portal publik tanpa login melalui tautan di bawah.</div>
                </div>

                <form action="{{ route('login.post') }}" method="POST" autocomplete="on">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <div class="login-input-group">
                            <i class="fas fa-envelope field-icon"></i>
                            <input id="email"
                                type="email"
                                name="email"
                                class="form-control login-input @error('email') is-invalid @enderror"
                                placeholder="contoh: operator@desa.go.id"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                                autofocus>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="login-input-group">
                            <i class="fas fa-lock field-icon"></i>
                            <input id="password"
                                type="password"
                                name="password"
                                class="form-control login-input @error('password') is-invalid @enderror"
                                placeholder="Masukkan kata sandi"
                                autocomplete="current-password"
                                required>
                            <button type="button" class="password-toggle" id="togglePassword" aria-label="Tampilkan kata sandi">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="remember-row">
                        <div class="icheck-primary mb-0">
                            <input type="checkbox" name="remember" id="remember" value="1">
                            <label for="remember">Ingat sesi login</label>
                        </div>
                        <span><i class="fas fa-clock mr-1"></i> Sesi aman untuk perangkat pribadi</span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block login-action">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sekarang
                    </button>
                </form>

                <div class="portal-link">
                    <a href="{{ route('public.home') }}"><i class="fas fa-home mr-1"></i> Kembali ke Portal Warga</a>
                </div>

                <div class="security-row">
                    <span><i class="fas fa-lock"></i> Akses dilindungi autentikasi dan CSRF.</span>
                    <span><i class="fas fa-user-shield"></i> Fitur dashboard mengikuti role dan permission pengguna.</span>
                </div>
            </div>
        </section>
    </section>
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var passwordInput = document.getElementById('password');
        var toggleButton = document.getElementById('togglePassword');

        if (!passwordInput || !toggleButton) {
            return;
        }

        toggleButton.addEventListener('click', function () {
            var isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleButton.setAttribute('aria-label', isPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            toggleButton.querySelector('i').className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    });
</script>
@endpush
