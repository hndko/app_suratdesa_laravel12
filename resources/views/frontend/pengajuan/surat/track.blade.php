@extends('layouts.app-frontend')

@section('content')
<section class="wrapper bg-soft-primary">
  <div class="container pt-10 pb-12 pt-md-14 pb-md-16 text-center">
    <div class="row">
      <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
        <h1 class="display-1 mb-3">Lacak Pengajuan Surat</h1>
        <p class="lead px-xxl-10">Masukkan Kode Tracking dan NIK untuk melihat status pengajuan surat Anda.</p>
      </div>
    </div>
  </div>
</section>

<section class="wrapper bg-light">
  <div class="container pb-14 pb-md-16">
    <div class="row">
      <div class="col-lg-10 col-xl-8 mx-auto mt-n10">
        <div class="card shadow-lg">
          <div class="card-body p-11">
            <form action="{{ route('public.surat.status') }}" method="POST">
              @csrf
              <div class="row gx-4 justify-content-center">
                <div class="col-md-8">
                  <div class="form-floating mb-4">
                    <input id="tracking_code" type="text" name="tracking_code" class="form-control text-center fs-20 fw-bold" placeholder="SRT-XXXXXXXXXX" required autofocus>
                    <label for="tracking_code">Kode Tracking</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-floating mb-4">
                    <input id="nik" type="text" name="nik" class="form-control text-center fs-20 fw-bold" placeholder="NIK" required>
                    <label for="nik">NIK Pemohon</label>
                  </div>
                </div>
                <div class="col-12 text-center mt-2">
                  <button type="submit" class="btn btn-primary rounded-pill btn-send mb-3">Cek Status Sekarang</button>
                </div>
              </div>
            </form>

            @if(isset($surat))
            <hr class="my-8">
            <div class="results mt-5">
              <div class="row gy-4">
                <div class="col-md-6">
                  <h5 class="mb-1 text-muted small uppercase">Jenis Surat</h5>
                  <p class="mb-0 fw-bold">{{ $surat->jenisSurat->nama_surat }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                  <h5 class="mb-1 text-muted small uppercase">Status</h5>
                  @if($surat->status == 'pending')
                  <span class="badge bg-warning rounded-pill">Menunggu</span>
                  @elseif($surat->status == 'process')
                  <span class="badge bg-info rounded-pill">Sedang Diproses</span>
                  @else
                  <span class="badge bg-success rounded-pill">Selesai</span>
                  @endif
                </div>
                <div class="col-12 mt-6">
                  <div class="p-4 bg-gray rounded">
                    <h5 class="mb-2">Detail Pengajuan</h5>
                    <p class="mb-1"><strong>Nama:</strong> {{ $surat->penduduk->nama }}</p>
                    <p class="mb-1"><strong>No. Surat:</strong> {{ $surat->no_surat }}</p>
                    <p class="mb-0"><strong>Keperluan:</strong> {{ $surat->keperluan }}</p>
                  </div>
                </div>
              </div>
            </div>
            @elseif(request()->isMethod('POST'))
            @push('scripts')
            <script>
              document.addEventListener('DOMContentLoaded', function () {
                if (window.showToast) {
                  window.showToast('error', 'Kode Tracking atau NIK tidak ditemukan. Silakan periksa kembali.');
                }
              });
            </script>
            @endpush
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
