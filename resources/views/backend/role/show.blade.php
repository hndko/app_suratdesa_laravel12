@extends('layouts.app-backend')

@section('title', $title)

@section('content')
<div class="content-header ps-0 pe-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Role: {{ $role->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Role</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Daftar Hak Akses (Permissions)</h3>
            </div>
            <div class="card-body">
                @if($role->name === 'super-admin')
                <div class="bg-info text-white rounded p-3 mb-3">
                    <i class="fas fa-info-circle mr-1"></i> Role ini memiliki akses ke <strong>seluruh fitur</strong> sistem secara otomatis (Bypass Permission).
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th>Nama Permission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($role->permissions as $perm)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><code class="text-primary">{{ $perm->name }}</code></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted italic">Tidak ada permission khusus yang ditetapkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('role.index') }}" class="btn btn-secondary">Kembali</a>
                @if($role->name !== 'super-admin')
                <a href="{{ route('role.edit', $role->id) }}" class="btn btn-warning float-right"><i class="fas fa-edit mr-1"></i> Edit Role</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
