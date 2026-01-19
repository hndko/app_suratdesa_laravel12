@extends('layouts.app-backend')

@section('title', $title)

@section('content')
<div class="d-flex align-items-center mb-3">
    <div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item active">Jenis Surat</li>
        </ul>
        <h1 class="page-header mb-0">Data Jenis Surat</h1>
    </div>
    <div class="ms-auto">
        <a href="{{ route('jenis-surat.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Tambah Jenis
            Surat</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('jenis-surat.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Cari Kode atau Nama Surat..."
                    value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i> Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th width="1%">No</th>
                        <th width="15%">Kode Surat</th>
                        <th>Nama Surat</th>
                        <th>Kop Judul</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenis_surats as $key => $item)
                    <tr>
                        <td>{{ $jenis_surats->firstItem() + $key }}</td>
                        <td>{{ $item->kode_surat }}</td>
                        <td>{{ $item->nama_surat }}</td>
                        <td>{{ $item->kop_judul }}</td>
                        <td>
                            <a href="{{ route('jenis-surat.edit', $item->id) }}" class="btn btn-sm btn-warning"><i
                                    class="fa fa-edit"></i></a>
                            <form action="{{ route('jenis-surat.destroy', $item->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $jenis_surats->links() }}
        </div>
    </div>
</div>
@endsection