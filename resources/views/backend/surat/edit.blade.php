@extends('layouts.app-backend')

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
                            @can('surat-verify')
                            <option value="verified" {{ $surat->status == 'verified' ? 'selected' : '' }}>Diverifikasi Operator</option>
                            @endcan
                            @can('surat-approve')
                            <option value="approved" {{ $surat->status == 'approved' ? 'selected' : '' }}>Disetujui Kades</option>
                            @endcan
                            <option value="done" {{ $surat->status == 'done' ? 'selected' : '' }}>Selesai (Siap Diambil)</option>
                            @can('surat-reject')
                            <option value="rejected" {{ $surat->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            @endcan
                        </select>
                        <small class="text-muted">Notifikasi WhatsApp otomatis dikirim saat status berubah.</small>
                    </div>
                    <div class="form-group">
                        <label>Catatan Approval</label>
                        <textarea name="note" class="form-control" rows="4">{{ old('note', $surat->approval_note) }}</textarea>
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

@if($surat->approvals->isNotEmpty())
<div class="card card-outline card-info">
    <div class="card-header"><h3 class="card-title">Riwayat Approval</h3></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm">
            <thead><tr><th>Waktu</th><th>User</th><th>Dari</th><th>Ke</th><th>Catatan</th></tr></thead>
            <tbody>
                @foreach($surat->approvals as $approval)
                <tr>
                    <td>{{ $approval->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $approval->user->name ?? '-' }}</td>
                    <td>{{ $approval->from_status ?? '-' }}</td>
                    <td>{{ $approval->to_status }}</td>
                    <td>{{ $approval->note ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
