@extends('layouts.app-frontend')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Selamat Datang di SIMADES</h1>
                <p class="lead text-secondary mb-5">Platform pelayanan administrasi dan informasi desa yang cepat, transparan, dan terpercaya.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('public.surat.create') }}" class="btn btn-primary btn-lg shadow">
                        <i class="fas fa-file-signature me-2"></i>Buat Surat Online
                    </a>
                    <a href="{{ route('public.pengaduan.create') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-bullhorn me-2"></i>Lapor Pengaduan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats / Features -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm border border-light h-100">
                    <div class="icon-box mb-3 text-primary fs-1">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4>Hemat Waktu</h4>
                    <p class="text-secondary">Ajukan surat dari mana saja tanpa harus antre lama di kantor desa.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm border border-light h-100">
                    <div class="icon-box mb-3 text-success fs-1">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h4>Mudah & Praktis</h4>
                    <p class="text-secondary">Cukup masukkan NIK dan data Anda akan otomatis terisi di sistem.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm border border-light h-100">
                    <div class="icon-box mb-3 text-info fs-1">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Keamanan Data</h4>
                    <p class="text-secondary">Data kependudukan Anda aman dan terlindungi di sistem internal kami.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Pengumuman Terbaru</h2>
            <a href="#" class="text-decoration-none">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            @forelse($posts as $post)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    @if($post->image)
                    <img src="{{ asset('storage/'.$post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                    @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-image fa-3x opacity-25"></i>
                    </div>
                    @endif
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-light text-primary border border-primary-subtle">Berita</span>
                            <small class="text-muted">{{ $post->created_at->format('d M Y') }}</small>
                        </div>
                        <h5 class="card-title fw-bold mb-3">{{ $post->title }}</h5>
                        <div class="text-secondary small mb-4">
                            {!! \Illuminate\Support\Str::limit(strip_tags($post->content), 120) !!}
                        </div>
                        <a href="#" class="stretched-link text-accent fw-semibold text-decoration-none">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada pengumuman desa saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center py-4">
        <h2 class="fw-bold mb-3">Ada Masalah atau Aspirasi?</h2>
        <p class="mb-5 opacity-75">Sampaikan laporan Anda melalui sistem pengaduan kami. Kami siap mendengarkan.</p>
        <a href="{{ route('public.pengaduan.create') }}" class="btn btn-light btn-lg px-5 rounded-pill fw-bold">Lapor Sekarang</a>
    </div>
</section>
@endsection
