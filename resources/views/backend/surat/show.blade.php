@extends('layouts.app-backend')

@section('title', $title)

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