@extends('layouts.app-frontend-sandbox')

@section('title', 'Kirim Pengaduan Warga - ' . \App\Facades\Setting::get('site_name', 'SIMADES'))

@section('content')
<section class="wrapper bg-soft-primary">
  <div class="container pt-10 pb-12 pt-md-14 pb-md-16 text-center">
    <div class="row">
      <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
        <h1 class="display-1 mb-3">Layanan Pengaduan Online</h1>
        <p class="lead px-xxl-10">Sampaikan keluhan, aspirasi, atau laporan Anda secara langsung kepada kami. Kami berkomitmen untuk menindaklanjuti setiap aduan demi kemajuan desa.</p>
      </div>
      <!-- /column -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->
</section>

<section class="wrapper bg-light">
  <div class="container pb-14 pb-md-16">
    <div class="row">
      <div class="col-lg-10 col-xl-8 mx-auto mt-n10">
        <div class="card shadow-lg">
          <div class="card-body p-11">
            
            <div class="text-center mb-8">
                <a href="{{ route('public.pengaduan.track') }}" class="btn btn-sm btn-soft-primary rounded-pill">Lacak Status Aduan Sebelumnya</a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-icon" role="alert">
              <i class="uil uil-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('public.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row gx-4">
                <div class="col-md-6">
                  <div class="form-floating mb-4">
                    <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                    <label for="name">Nama Lengkap</label>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-floating mb-4">
                    <input id="nik" type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" placeholder="NIK" value="{{ old('nik') }}" required>
                    <label for="nik">NIK (Sesuai KTP)</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating mb-4">
                    <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="No. WhatsApp" value="{{ old('phone') }}" required>
                    <label for="phone">No. WhatsApp Aktif</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-select-wrapper mb-4">
                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                      <option selected disabled value="">Kategori Aduan</option>
                      <option value="infrastruktur">Infrastruktur / Jalan</option>
                      <option value="keamanan">Keamanan / Ketertiban</option>
                      <option value="pelayanan">Pelayanan Publik</option>
                      <option value="sosial">Sosial / Ekonomi</option>
                      <option value="lainnya">Lainnya</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-4">
                    <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" placeholder="Isi Aduan" style="height: 150px" required>{{ old('content') }}</textarea>
                    <label for="content">Detail Pengaduan / Masalah</label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="mb-4">
                    <label class="form-label text-muted">Foto Bukti (Opsional)</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                  </div>
                </div>

                <div class="col-12 text-center mt-3">
                  <button type="submit" class="btn btn-primary rounded-pill btn-send mb-3">Kirim Aduan</button>
                  <p class="text-muted small">Dengan mengirim aduan, Anda setuju bahwa data yang Anda berikan adalah benar dan dapat dipertanggungjawabkan.</p>
                </div>
              </div>
            </form>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /column -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->
</section>
@endsection
