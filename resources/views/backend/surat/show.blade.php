@extends('layouts.app-backend')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 no-print bg-light p-3 rounded border">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Detail Surat #{{ $surat->no_surat }}</h1>
        <ol class="breadcrumb mb-0 p-0 bg-transparent">
            <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
            <li class="breadcrumb-item"><a href="{{ route('surat.index') }}">Arsip Surat</a></li>
            <li class="breadcrumb-item active" aria-current="page">Preview & Cetak</li>
        </ol>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('surat.index') }}" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left me-1"></i>
            Kembali</a>
        @if($surat->verification)
        <a href="{{ route('public.surat.verify.status') }}" class="btn btn-info mr-2" onclick="event.preventDefault(); document.getElementById('verifyPreviewForm').submit();"><i class="fa fa-qrcode me-1"></i> Cek Verifikasi</a>
        <form id="verifyPreviewForm" action="{{ route('public.surat.verify.status') }}" method="POST" target="_blank" class="d-none">
            @csrf
            <input type="hidden" name="verification_code" value="{{ $surat->verification->verification_code }}">
        </form>
        @endif
        @can('surat-print')
        <button type="button" class="btn btn-primary" id="printSuratButton"><i class="fa fa-print me-1"></i> Cetak</button>
        @endcan
    </div>
</div>

@if($surat->verification)
<div class="card no-print">
    <div class="card-body d-flex align-items-center justify-content-between">
        <div>
            <strong>Kode Verifikasi:</strong> {{ $surat->verification->verification_code }}<br>
            <small>{{ route('public.surat.verify') }}</small>
        </div>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ urlencode(route('public.surat.verify') . '?code=' . $surat->verification->verification_code) }}" alt="QR Verifikasi">
    </div>
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <!-- Area Cetak -->
        @include('backend.surat.print_template', [
        'header' => $surat->jenisSurat->kop_judul,
        'nomor_surat' => $surat->no_surat,
        'tanggal_surat' => $surat->tanggal_surat,
        'content' => $content,
        'verification' => $surat->verification,
        ])
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .card,
        .card * {
            visibility: visible;
        }

        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }

        .no-print {
            display: none !important;
        }

        .app-header,
        .app-sidebar {
            display: none !important;
        }

        .app-content {
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>
@endpush
@push('scripts')
<script>
    document.getElementById('printSuratButton')?.addEventListener('click', function () {
        window.print();
    });
</script>
@endpush
@endsection
