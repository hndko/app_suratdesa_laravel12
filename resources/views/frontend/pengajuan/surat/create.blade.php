@extends('layouts.app-frontend-sandbox')

@section('content')
<section class="wrapper bg-soft-primary">
  <div class="container pt-10 pb-12 pt-md-14 pb-md-16 text-center">
    <div class="row">
      <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
        <h1 class="display-1 mb-3">Pengajuan Surat Online</h1>
        <p class="lead px-xxl-10">Silakan isi formulir di bawah ini dengan data yang benar untuk mengajukan permohonan surat keterangan desa.</p>
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
            
            <form action="{{ route('public.surat.store') }}" method="POST">
              @csrf
              <div class="row gx-4">
                <div class="col-md-12">
                  <div class="form-floating mb-4">
                    <input id="nik" type="text" name="nik" class="form-control" placeholder="NIK" required>
                    <label for="nik">Nomor Induk Kependudukan (NIK)</label>
                    <small class="text-muted mt-1 d-block">Pastikan NIK Anda sudah terdaftar di sistem desa.</small>
                  </div>
                </div>
                
                <div class="col-md-12">
                  <div class="form-select-wrapper mb-4">
                    <select class="form-select" id="jenis_surat_id" name="jenis_surat_id" required>
                      <option selected disabled value="">Pilih Jenis Surat</option>
                      @foreach($jenisSurats as $js)
                        <option value="{{ $js->id }}">{{ $js->nama_surat }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-4">
                    <textarea id="keperluan" name="keperluan" class="form-control" placeholder="Keperluan" style="height: 100px" required></textarea>
                    <label for="keperluan">Keperluan / Alasan Pengajuan</label>
                  </div>
                </div>

                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-primary rounded-pill btn-send mb-3">Kirim Pengajuan</button>
                  <p class="text-muted"><strong>*</strong> Anda akan menerima notifikasi via WhatsApp setelah pengajuan diproses.</p>
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
