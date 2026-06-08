@extends('layouts.app-backend')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

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

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">List Data Penduduk</h3>
                <div class="card-tools">
                    @can('penduduk-create')
                    <a href="{{ route('penduduk.create') }}" class="btn btn-primary btn-sm"><i
                            class="fas fa-plus mr-1"></i> Tambah Penduduk</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('penduduk.index') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" value="{{ $q ?? '' }}"
                            placeholder="Cari NIK atau nama penduduk...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                            @if(!empty($q))
                            <a href="{{ route('penduduk.index') }}" class="btn btn-default"><i class="fas fa-times"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
                <table id="datatableDefault" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="1%">No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>No. KK</th>
                            <th>L/P</th>
                            <th>TTL</th>
                            <th>Alamat</th>
                            <th>Pekerjaan</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penduduks as $key => $row)
                        <tr>
                            <td>{{ $penduduks->firstItem() + $loop->index }}</td>
                            <td>{{ $row->nik }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->kartuKeluarga->no_kk ?? '-' }}</td>
                            <td>{{ $row->jenis_kelamin }}</td>
                            <td>{{ $row->tempat_lahir }}, {{ \Carbon\Carbon::parse($row->tgl_lahir)->format('d-m-Y') }}
                            </td>
                            <td>{{ $row->alamat }} RT {{ $row->rt }} / RW {{ $row->rw }}</td>
                            <td>{{ $row->pekerjaan }}</td>
                            <td>
                                @can('penduduk-edit')
                                <a href="{{ route('penduduk.edit', $row->id) }}" class="btn btn-sm btn-warning"
                                    title="Edit"><i class="fas fa-edit"></i></a>
                                @endcan
                                @can('penduduk-destroy')
                                <form action="{{ route('penduduk.destroy', $row->id) }}" method="POST" class="d-inline js-confirm-submit"
                                    data-confirm-text="Yakin ingin menghapus data penduduk ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $penduduks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
      $("#datatableDefault").DataTable({
        "responsive": true,
        "lengthChange": true,
        "searching": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#datatableDefault_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush
