@extends('layouts.app-frontend')

@section('content')
<section class="wrapper bg-light">
  <div class="container pt-11 pt-md-13 pb-11 pb-md-15 text-center">
    <div class="row">
      <div class="col-lg-8 col-xl-7 col-xxl-6 mx-auto" data-cues="slideInDown" data-group="page-title">
        <h1 class="display-1 fs-60 mb-4 px-md-15 px-lg-0">Portal Pelayanan Digital <span class="underline-3 style-3 primary">{{ \App\Facades\Setting::get('village_nama', 'Desa Kami') }}</span></h1>
        <p class="lead fs-24 lh-sm mb-7 mx-md-13 mx-lg-10">Kami menghadirkan solusi digital untuk mempermudah administrasi dan pelayanan publik bagi seluruh warga desa secara transparan dan efisien.</p>
        <div class="d-flex justify-content-center" data-cues="slideInDown" data-delay="600">
          <span><a href="{{ route('public.surat.create') }}" class="btn btn-lg btn-primary rounded-pill mx-1">Ajukan Surat</a></span>
          <span><a href="{{ route('public.pengaduan.create') }}" class="btn btn-lg btn-outline-primary rounded-pill mx-1">Kirim Pengaduan</a></span>
        </div>
      </div>
      <!-- /column -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->
</section>
<!-- /section -->

<section class="wrapper bg-gray">
  <div class="container py-14 py-md-16">
    <div class="row gx-lg-8 gx-xl-12 gy-10 align-items-center">
      <div class="col-lg-6">
        <figure><img class="w-auto" src="{{ asset('assets/sandbox/img/illustrations/i8.png') }}" srcset="{{ asset('assets/sandbox/img/illustrations/i8@2x.png') }} 2x" alt="" /></figure>
      </div>
      <!--/column -->
      <div class="col-lg-6">
        <h3 class="display-4 mb-5">Mengapa Menggunakan Layanan Online Kami?</h3>
        <p class="mb-7">Sistem kami dirancang untuk memangkas birokrasi yang rumit, memberikan kemudahan bagi warga tanpa harus datang berkali-kali ke kantor desa.</p>
        <div class="row gy-3">
          <div class="col-xl-6">
            <ul class="icon-list bullet-bg bullet-soft-primary mb-0">
              <li><span><i class="uil uil-check"></i></span><span>Proses pengajuan surat lebih cepat & praktis.</span></li>
              <li class="mt-3"><span><i class="uil uil-check"></i></span><span>Pantau status permohonan secara real-time.</span></li>
            </ul>
          </div>
          <!--/column -->
          <div class="col-xl-6">
            <ul class="icon-list bullet-bg bullet-soft-primary mb-0">
              <li><span><i class="uil uil-check"></i></span><span>Notifikasi otomatis via WhatsApp.</span></li>
              <li class="mt-3"><span><i class="uil uil-check"></i></span><span>Layanan pengaduan 24/7 yang responsif.</span></li>
            </ul>
          </div>
          <!--/column -->
        </div>
        <!--/.row -->
      </div>
      <!--/column -->
    </div>
    <!--/.row -->
  </div>
  <!-- /.container -->
</section>
<!-- /section -->

<section class="wrapper bg-light" id="pengumuman">
  <div class="container py-14 py-md-16">
    <div class="row align-items-center mb-10">
      <div class="col-md-8 col-lg-9 col-xl-8 col-xxl-7 text-center text-md-start">
        <h3 class="display-4 mb-0">Pengumuman Terbaru</h3>
      </div>
      <!--/column -->
      <div class="col-md-4 col-lg-3 col-xl-4 col-xxl-5 mt-5 mt-md-0 text-center text-md-end">
        <a href="#" class="btn btn-soft-primary rounded-pill">Lihat Semua</a>
      </div>
      <!--/column -->
    </div>
    <!--/.row -->
    <div class="row grid-view gx-md-8 gy-10 gy-md-13">
      @forelse($posts as $post)
      <div class="col-md-6 col-lg-4">
        <article>
          <figure class="overlay overlay-1 hover-scale rounded mb-5">
            <a href="#">
              @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" alt="" />
              @else
                <img src="{{ asset('assets/sandbox/img/photos/b4.jpg') }}" alt="" />
              @endif
            </a>
            <figcaption>
              <h5 class="from-top mb-0">Baca Selengkapnya</h5>
            </figcaption>
          </figure>
          <div class="post-header">
            <div class="post-category text-line">
              <a href="#" class="hover" rel="category">Berita Desa</a>
            </div>
            <!-- /.post-category -->
            <h2 class="post-title h3 mt-1 mb-3"><a class="link-dark" href="#">{{ $post->title }}</a></h2>
          </div>
          <!-- /.post-header -->
          <div class="post-footer">
            <ul class="post-meta">
              <li class="post-date"><i class="uil uil-calendar-alt"></i><span>{{ $post->created_at->format('d M Y') }}</span></li>
            </ul>
            <!-- /.post-meta -->
          </div>
          <!-- /.post-footer -->
        </article>
        <!-- /article -->
      </div>
      <!--/column -->
      @empty
      <div class="col-12 text-center">
        <p class="text-muted">Belum ada pengumuman terbaru.</p>
      </div>
      @endforelse
    </div>
    <!--/.row -->
  </div>
  <!-- /.container -->
</section>
<!-- /section -->

<section class="wrapper bg-soft-primary">
  <div class="container py-14 py-md-16 text-center">
    <div class="row">
      <div class="col-md-10 col-xl-8 mx-auto">
        <h3 class="display-4 mb-5">Butuh Bantuan atau Ingin Melapor?</h3>
        <p class="lead fs-lg mb-7 px-xxl-10">Jangan ragu untuk menghubungi kami jika Anda memiliki pertanyaan atau kendala dalam pelayanan desa. Kami siap melayani Anda.</p>
        <a href="{{ route('public.pengaduan.create') }}" class="btn btn-primary rounded-pill">Hubungi Kami</a>
      </div>
      <!-- /column -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->
</section>
@endsection
