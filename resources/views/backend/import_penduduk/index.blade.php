@extends('layouts.app-backend')

@section('content')
<div class="content-header ps-0 pe-0">
    <div class="container-fluid"><div class="row mb-2"><div class="col-sm-6"><h1 class="m-0">{{ $title }}</h1></div></div></div>
</div>

<div class="card card-outline card-primary">
    <form action="{{ route('import-penduduk.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>File Excel/CSV</label>
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                <small class="text-muted">Header wajib: no_kk, kepala_keluarga, nik, nama, tempat_lahir, tgl_lahir, jenis_kelamin, alamat, rt, rw, agama, status_perkawinan, pekerjaan.</small>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-primary"><i class="fas fa-upload mr-1"></i> Upload & Preview</button>
        </div>
    </form>
</div>

<div class="card card-outline card-info">
    <div class="card-header"><h3 class="card-title">Riwayat Import</h3></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>File</th><th>Status</th><th>Total</th><th>Valid</th><th>Invalid</th><th>Diproses</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($batches as $batch)
                <tr>
                    <td>{{ $batch->file_name }}</td>
                    <td>{{ $batch->status }}</td>
                    <td>{{ $batch->total_rows }}</td>
                    <td>{{ $batch->valid_rows }}</td>
                    <td>{{ $batch->invalid_rows }}</td>
                    <td>{{ $batch->processed_rows }}</td>
                    <td><a href="{{ route('import-penduduk.preview', $batch) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $batches->links() }}
    </div>
</div>
@endsection
