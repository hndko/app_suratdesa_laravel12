@extends('layouts.app-frontend-sandbox')

@section('title', 'Lacak Status Pengaduan - ' . \App\Facades\Setting::get('site_name', 'SIMADES'))

@section('content')
<section class="wrapper bg-soft-primary">
  <div class="container pt-10 pb-12 pt-md-14 pb-md-16 text-center">
    <div class="row">
      <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
        <h1 class="display-1 mb-3">Lacak Status Pengaduan</h1>
        <p class="lead px-xxl-10">Masukkan Kode Tiket yang Anda terima melalui WhatsApp untuk melihat progres tindak lanjut aduan Anda.</p>
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
            
            <form action="{{ route('public.pengaduan.status') }}" method="POST">
              @csrf
              <div class="row gx-4 justify-content-center">
                <div class="col-md-8">
                  <div class="form-floating mb-4">
                    <input id="ticket_code" type="text" name="ticket_code" class="form-control text-center fs-20 fw-bold" placeholder="TKT-XXXXXXXX" required autofocus>
                    <label for="ticket_code">Kode Tiket (Contoh: TKT-A1B2C3D4)</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-floating mb-4">
                    <input id="nik" type="text" name="nik" class="form-control text-center fs-20 fw-bold" placeholder="NIK" required>
                    <label for="nik">NIK Pelapor</label>
                  </div>
                </div>
                
                <div class="col-12 text-center mt-2">
                  <button type="submit" class="btn btn-primary rounded-pill btn-send mb-3">Cek Status Sekarang</button>
                </div>
              </div>
            </form>

            @if(isset($pengaduan))
            <hr class="my-8">
            <div class="results mt-5">
                <div class="row gy-4">
                    <div class="col-md-6">
                        <h5 class="mb-1 text-muted small uppercase">Status Saat Ini</h5>
                        <div class="d-flex align-items-center">
                            @if($pengaduan->status == 'pending')
                                <span class="badge bg-warning rounded-pill">Menunggu Antrian</span>
                            @elseif($pengaduan->status == 'process')
                                <span class="badge bg-info rounded-pill">Sedang Diproses</span>
                            @elseif($pengaduan->status == 'resolved')
                                <span class="badge bg-success rounded-pill">Selesai Ditangani</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h5 class="mb-1 text-muted small uppercase">Tanggal Lapor</h5>
                        <p class="mb-0 fw-bold">{{ $pengaduan->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <div class="col-12 mt-6">
                        <div class="p-4 bg-gray rounded">
                            <h5 class="mb-2">Isi Laporan:</h5>
                            <p class="mb-0 italic">"{{ $pengaduan->content }}"</p>
                        </div>
                    </div>

                    @if($pengaduan->reply)
                    <div class="col-12 mt-6">
                        <div class="p-4 border-start border-primary border-4 bg-soft-primary rounded">
                            <h5 class="mb-2 text-primary">Tanggapan Admin:</h5>
                            <p class="mb-0 fw-bold">{{ $pengaduan->reply }}</p>
                            <small class="text-muted d-block mt-2">Dibalas pada {{ $pengaduan->updated_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @elseif(request()->isMethod('POST'))
            <div class="alert alert-danger alert-icon mt-8" role="alert">
                <i class="uil uil-times-circle"></i> Maaf, Kode Tiket tidak ditemukan atau salah. Silakan periksa kembali.
            </div>
            @endif

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
