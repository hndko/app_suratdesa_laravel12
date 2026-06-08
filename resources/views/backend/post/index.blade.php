@extends('layouts.app-backend')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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
                    <li class="breadcrumb-item"><a href="#">Informasi</a></li>
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
                <h3 class="card-title">Daftar Pengumuman</h3>
                <div class="card-tools">
                    @can('post-create')
                    <a href="{{ route('post.create') }}" class="btn btn-primary btn-sm"><i
                            class="fas fa-plus mr-1"></i> Buat Pengumuman</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table id="datatableDefault" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="1%">No</th>
                            <th width="10%">Gambar</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $row)
                        <tr>
                            <td>{{ $posts->firstItem() + $loop->index }}</td>
                            <td>
                                @if($row->image)
                                <img src="{{ asset('storage/'.$row->image) }}" alt="img" width="80" class="img-thumbnail">
                                @else
                                <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $row->title }}</td>
                            <td>{{ $row->user->name }}</td>
                            <td>
                                <span class="badge badge-{{ $row->status == 'published' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>
                            <td>{{ $row->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @can('post-edit')
                                <a href="{{ route('post.edit', $row->id) }}" class="btn btn-sm btn-warning"
                                    title="Edit"><i class="fas fa-edit"></i></a>
                                @endcan
                                @can('post-destroy')
                                <form action="{{ route('post.destroy', $row->id) }}" method="POST" class="d-inline js-confirm-submit"
                                    data-confirm-text="Yakin ingin menghapus pengumuman ini?">
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
                    {{ $posts->links() }}
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
<script>
    $(function () {
      $("#datatableDefault").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
      });
    });
</script>
@endpush
