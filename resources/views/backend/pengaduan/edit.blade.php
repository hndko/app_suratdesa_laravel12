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
                    <li class="breadcrumb-item"><a href="{{ route('pengaduan.index') }}">Pengaduan</a></li>
                    <li class="breadcrumb-item active">Tanggapi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Detail Pengaduan</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm border-0">
                    <tr><td width="30%">Tiket</td><td>: <strong>{{ $pengaduan->ticket_code }}</strong></td></tr>
                    <tr><td>Tanggal</td><td>: {{ $pengaduan->created_at->format('d/m/Y H:i') }}</td></tr>
                    <tr><td>Nama</td><td>: {{ $pengaduan->name }}</td></tr>
                    <tr><td>NIK</td><td>: {{ $pengaduan->nik }}</td></tr>
                    <tr><td>No. HP</td><td>: {{ $pengaduan->phone ?? '-' }}</td></tr>
                    <tr><td>Kategori</td><td>: {{ $pengaduan->category }}</td></tr>
                </table>
                <hr>
                <p><strong>Isi Laporan:</strong></p>
                <div class="p-2 bg-light border rounded">
                    {{ $pengaduan->content }}
                </div>
                @if($pengaduan->image)
                <div class="mt-3">
                    <p><strong>Lampiran:</strong></p>
                    <img src="{{ asset('storage/'.$pengaduan->image) }}" alt="lampiran" class="img-fluid rounded border">
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <form action="{{ route('pengaduan.update', $pengaduan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Berikan Tanggapan</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Status Laporan</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ $pengaduan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="process" {{ $pengaduan->status == 'process' ? 'selected' : '' }}>Diproses</option>
                            <option value="resolved" {{ $pengaduan->status == 'resolved' ? 'selected' : '' }}>Selesai / Teratasi</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggapan / Jawaban</label>
                        <textarea name="reply" class="form-control" rows="8" placeholder="Tulis tanggapan untuk warga..." required>{{ old('reply', $pengaduan->reply) }}</textarea>
                    </div>

                    @if($pengaduan->replied_by)
                    <div class="bg-light border rounded p-3">
                        <small>Terakhir ditanggapi oleh <strong>{{ $pengaduan->repliedBy->name }}</strong> pada {{ \Carbon\Carbon::parse($pengaduan->replied_at)->format('d/m/Y H:i') }}</small>
                    </div>
                    @endif
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('pengaduan.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success px-4">Kirim Tanggapan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
