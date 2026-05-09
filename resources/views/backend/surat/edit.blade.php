@extends('layouts.app-backend')

@section('title', $title)

@section('content')
<div class="content-header ps-0 pe-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('surat.index') }}">Arsip Surat</a></li>
                    <li class="breadcrumb-item active">Update Status</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Detail Surat</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm border-0">
                    <tr><td width="30%">Nomor Surat</td><td>: <strong>{{ $surat->no_surat }}</strong></td></tr>
                    <tr><td>Jenis Surat</td><td>: {{ $surat->jenisSurat->nama_surat }}</td></tr>
                    <tr><td>Nama Warga</td><td>: {{ $surat->penduduk->nama }}</td></tr>
                    <tr><td>NIK</td><td>: {{ $surat->penduduk->nik }}</td></tr>
                    <tr><td>Tanggal Buat</td><td>: {{ $surat->tanggal_surat->format('d/m/Y') }}</td></tr>
                    <tr><td>Keperluan</td><td>: {{ $surat->keperluan }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <form action="{{ route('surat.update', $surat->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Update Status</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Status Saat Ini</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ $surat->status == 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                            <option value="process" {{ $surat->status == 'process' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="done" {{ $surat->status == 'done' ? 'selected' : '' }}>Selesai (Siap Diambil)</option>
                        </select>
                        <small class="text-muted">Notifikasi WhatsApp otomatis akan dikirim ke warga jika status diubah ke 'Selesai'.</small>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('surat.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning px-4">Update Status</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
