@extends('layouts.app-backend')

@section('title', $title)

@section('content')
<div class="d-flex align-items-center mb-3 no-print">
    <div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
            <li class="breadcrumb-item"><a href="{{ route('surat.index') }}">Arsip Surat</a></li>
            <li class="breadcrumb-item active">Detail Surat</li>
        </ul>
        <h1 class="page-header mb-0">Detail Surat #{{ $surat->no_surat }}</h1>
    </div>
    <div class="ms-auto">
        <a href="{{ route('surat.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
        <button onclick="window.print()" class="btn btn-primary"><i class="fa fa-print me-1"></i> Cetak</button>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <!-- Area Cetak -->
        @include('backend.surat.print_template', [
        'header' => $surat->jenisSurat->kop_judul,
        'nomor_surat' => $surat->no_surat,
        'tanggal_surat' => $surat->tanggal_surat,
        'content' => $content
        ])
    </div>
</div>

@push('css')
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
@endsection