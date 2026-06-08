@extends('layouts.app-backend')

@section('content')
<div class="content-header ps-0 pe-0">
    <div class="container-fluid"><div class="row mb-2"><div class="col-sm-6"><h1 class="m-0">{{ $title }}</h1></div></div></div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $batch->file_name }}</h3>
        <div class="card-tools">
            @if($batch->status === 'preview' && $batch->invalid_rows === 0)
            <form action="{{ route('import-penduduk.process', $batch) }}" method="POST" class="d-inline js-confirm-submit" data-confirm-text="Proses import valid ini sekarang?">
                @csrf
                <button class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i> Proses Import</button>
            </form>
            @endif
        </div>
    </div>
    <div class="card-body table-responsive">
        <p>Total: {{ $batch->total_rows }} | Valid: {{ $batch->valid_rows }} | Invalid: {{ $batch->invalid_rows }}</p>
        <table class="table table-bordered table-sm">
            <thead><tr><th>Baris</th><th>NIK</th><th>Nama</th><th>No KK</th><th>Status</th><th>Error</th></tr></thead>
            <tbody>
                @foreach($batch->rows as $row)
                <tr>
                    <td>{{ $row->row_number }}</td>
                    <td>{{ $row->payload['nik'] ?? '-' }}</td>
                    <td>{{ $row->payload['nama'] ?? '-' }}</td>
                    <td>{{ $row->payload['no_kk'] ?? '-' }}</td>
                    <td><span class="badge badge-{{ $row->status === 'invalid' ? 'danger' : 'success' }}">{{ $row->status }}</span></td>
                    <td>{{ $row->errors ? implode(', ', $row->errors) : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
