@extends('layouts.app-backend')

@section('content')
<div class="content-header ps-0 pe-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">{{ $title }}</h1></div>
            <div class="col-sm-6 text-right">
                @can('ai-setting-create')
                <a href="{{ route('ai-settings.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Tambah Provider</a>
                @endcan
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header"><h3 class="card-title">Daftar Provider AI</h3></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Provider</th>
                    <th>Model</th>
                    <th>Status</th>
                    <th width="18%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($providers as $provider)
                <tr>
                    <td>{{ $provider->name }}</td>
                    <td>{{ strtoupper($provider->provider_type) }}</td>
                    <td>{{ $provider->model }}</td>
                    <td>
                        @if($provider->is_active)<span class="badge badge-success">Aktif</span>@endif
                        @if($provider->is_fallback)<span class="badge badge-info">Fallback</span>@endif
                    </td>
                    <td>
                        @can('ai-setting-test')
                        <form action="{{ route('ai-settings.test', $provider) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-info" title="Test"><i class="fas fa-vial"></i></button>
                        </form>
                        @endcan
                        @can('ai-setting-edit')
                        <a href="{{ route('ai-settings.edit', $provider) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('ai-setting-destroy')
                        <form action="{{ route('ai-settings.destroy', $provider) }}" method="POST" class="d-inline js-confirm-submit" data-confirm-text="Yakin ingin menghapus provider AI ini?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $providers->links() }}
    </div>
</div>
@endsection
