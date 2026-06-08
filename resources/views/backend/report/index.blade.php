@extends('layouts.app-backend')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Card Penduduk -->
            <div class="col-md-4">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Data Penduduk</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Unduh seluruh data kependudukan desa dalam format Excel.</p>
                        <a href="{{ route('report.penduduk.excel') }}" class="btn btn-info btn-block">
                            <i class="fas fa-file-excel mr-2"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Pengaduan -->
            <div class="col-md-4">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Rekap Pengaduan</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Unduh riwayat pengaduan warga beserta status tindak lanjutnya.</p>
                        <a href="{{ route('report.pengaduan.excel') }}" class="btn btn-success btn-block">
                            <i class="fas fa-file-excel mr-2"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Surat -->
            <div class="col-md-4">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Laporan Arsip Surat</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('report.surat.excel') }}" method="GET" class="mb-3">
                            <div class="form-group">
                                <label>Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-file-excel mr-1"></i> Excel
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="submit" formaction="{{ route('report.surat.pdf') }}" formtarget="_blank" class="btn btn-danger btn-block">
                                        <i class="fas fa-file-pdf mr-1"></i> PDF
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
