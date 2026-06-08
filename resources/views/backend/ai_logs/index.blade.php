@extends('layouts.app-backend')

@section('content')
<div class="content-header ps-0 pe-0">
    <div class="container-fluid">
        <div class="row mb-2"><div class="col-sm-6"><h1 class="m-0">{{ $title }}</h1></div></div>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-4"><input type="text" name="feature" value="{{ $feature }}" class="form-control" placeholder="Cari fitur..."></div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="success" {{ $status === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="error" {{ $status === 'error' ? 'selected' : '' }}>Error</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Filter</button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>Waktu</th><th>Fitur</th><th>Provider</th><th>Model</th><th>Status</th><th>Token</th><th>Error</th></tr></thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->feature }}</td>
                        <td>{{ $log->provider->name ?? '-' }}</td>
                        <td>{{ $log->model }}</td>
                        <td><span class="badge badge-{{ $log->status === 'success' ? 'success' : 'danger' }}">{{ $log->status }}</span></td>
                        <td>{{ $log->total_tokens ?? '-' }}</td>
                        <td>{{ $log->error_message ? \Illuminate\Support\Str::limit($log->error_message, 120) : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</div>
@endsection
