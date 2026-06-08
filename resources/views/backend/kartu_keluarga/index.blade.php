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
                    <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List Kartu Keluarga</h3>
        <div class="card-tools">
            @can('kartu-keluarga-create')
            <a href="{{ route('kartu-keluarga.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Tambah KK</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('kartu-keluarga.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="q" class="form-control" value="{{ $q ?? '' }}" placeholder="Cari nomor KK atau kepala keluarga...">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    @if(!empty($q))
                    <a href="{{ route('kartu-keluarga.index') }}" class="btn btn-default"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th>No. KK</th>
                        <th>Kepala Keluarga</th>
                        <th>Alamat</th>
                        <th>Anggota</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kartuKeluargas as $row)
                    <tr>
                        <td>{{ $kartuKeluargas->firstItem() + $loop->index }}</td>
                        <td>{{ $row->no_kk }}</td>
                        <td>{{ $row->kepala_keluarga }}</td>
                        <td>{{ $row->alamat }} RT {{ $row->rt }} / RW {{ $row->rw }}</td>
                        <td><span class="badge badge-info">{{ $row->penduduks_count }}</span></td>
                        <td>
                            @can('kartu-keluarga-show')
                            <a href="{{ route('kartu-keluarga.show', $row->id) }}" class="btn btn-sm btn-info" title="Detail"><i class="fas fa-eye"></i></a>
                            @endcan
                            @can('kartu-keluarga-edit')
                            <a href="{{ route('kartu-keluarga.edit', $row->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                            @endcan
                            @can('kartu-keluarga-destroy')
                            <form action="{{ route('kartu-keluarga.destroy', $row->id) }}" method="POST" class="d-inline js-confirm-submit"
                                data-confirm-text="Yakin ingin menghapus Kartu Keluarga ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data Kartu Keluarga.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $kartuKeluargas->links() }}</div>
    </div>
</div>
@endsection
