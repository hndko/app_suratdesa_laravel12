@extends('layouts.app-frontend')

@section('content')
<section class="wrapper bg-light">
    <div class="container py-12 py-md-14">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="mb-4">Verifikasi Keaslian Surat</h1>
                <form action="{{ route('public.surat.verify.status') }}" method="POST" class="mb-5">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="verification_code" class="form-control" placeholder="Masukkan kode verifikasi..." value="{{ old('verification_code') }}" required>
                        <button class="btn btn-primary" type="submit">Cek</button>
                    </div>
                </form>

                @if(request()->isMethod('POST') || request()->filled('code'))
                    @if($verification)
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <span class="badge bg-success mb-3">Surat Valid</span>
                            <h3>{{ $verification->surat->jenisSurat->nama_surat }}</h3>
                            <p class="mb-1">Nomor Surat: <strong>{{ $verification->surat->no_surat }}</strong></p>
                            <p class="mb-1">Tanggal: {{ $verification->surat->tanggal_surat->format('d/m/Y') }}</p>
                            <p class="mb-0">Pemohon: {{ \Illuminate\Support\Str::mask($verification->surat->penduduk->nama, '*', 2, 4) }}</p>
                        </div>
                    </div>
                    @else
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark">Tidak Ditemukan</span>
                            <p class="mb-0 mt-3">Kode verifikasi tidak ditemukan atau sudah tidak aktif.</p>
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
