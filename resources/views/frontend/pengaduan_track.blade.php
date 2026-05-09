@extends('layouts.app-frontend')

@section('title', 'Lacak Pengaduan - SIMADES')

@section('content')
<section class="py-5 bg-white border-bottom">
    <div class="container text-center py-4">
        <h1 class="fw-bold">Lacak Status Pengaduan</h1>
        <p class="text-secondary">Masukkan nomor tiket untuk melihat perkembangan laporan Anda.</p>
    </div>
</section>

<section class="py-5" style="min-height: 60vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm p-4 border-0 mb-4">
                    <form action="{{ route('public.pengaduan.status') }}" method="POST">
                        @csrf
                        <div class="input-group input-group-lg">
                            <input type="text" name="ticket_code" class="form-control bg-light border-0" 
                                placeholder="Masukkan Kode Tiket (Contoh: TKT-XXXXXX)" value="{{ old('ticket_code', request('ticket_code')) }}" required>
                            <button class="btn btn-primary" type="submit">Cari Laporan</button>
                        </div>
                    </form>
                </div>

                @if(isset($pengaduan))
                    @if($pengaduan)
                    <div class="card shadow p-4 border-0 animate__animated animate__fadeIn">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0">Detail Laporan</h4>
                            @if($pengaduan->status == 'pending')
                            <span class="badge bg-danger p-2 px-3 rounded-pill">Pending</span>
                            @elseif($pengaduan->status == 'process')
                            <span class="badge bg-warning p-2 px-3 rounded-pill text-dark">Sedang Diproses</span>
                            @else
                            <span class="badge bg-success p-2 px-3 rounded-pill">Selesai</span>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <small class="text-muted d-block mb-1">Nomor Tiket</small>
                            <span class="fs-5 fw-bold text-primary">{{ $pengaduan->ticket_code }}</span>
                        </div>

                        <div class="mb-4">
                            <small class="text-muted d-block mb-1">Isi Laporan</small>
                            <p class="mb-0">{{ $pengaduan->content }}</p>
                        </div>

                        @if($pengaduan->reply)
                        <div class="p-3 bg-success-subtle border border-success-subtle rounded-3 mt-2">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-reply me-2 text-success"></i>
                                <strong class="text-success">Tanggapan Admin:</strong>
                            </div>
                            <p class="mb-0">{{ $pengaduan->reply }}</p>
                            <hr class="opacity-25">
                            <small class="text-muted">Dijawab pada {{ \Carbon\Carbon::parse($pengaduan->replied_at)->format('d M Y H:i') }}</small>
                        </div>
                        @else
                        <div class="p-3 bg-light rounded-3 mt-2 text-center py-4">
                            <i class="fas fa-hourglass-half mb-2 text-muted fs-3"></i>
                            <p class="mb-0 text-muted small">Belum ada tanggapan dari admin desa. Mohon tunggu beberapa saat.</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="alert alert-warning border-0 p-4 text-center">
                        <i class="fas fa-search-minus fa-3x mb-3 opacity-25"></i>
                        <h5 class="fw-bold">Laporan Tidak Ditemukan</h5>
                        <p class="mb-0 opacity-75">Pastikan kode tiket yang Anda masukkan sudah benar.</p>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
