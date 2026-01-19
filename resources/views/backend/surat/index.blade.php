@extends('layouts.app-backend')

@section('title', $title)

@push('css')
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
                <h1 class="m-0">Arsip Surat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                    <li class="breadcrumb-item active">Arsip Surat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">List Arsip Surat</h3>
                <div class="card-tools">
                    <a href="{{ route('surat.create') }}" class="btn btn-primary btn-sm"><i
                            class="fas fa-plus mr-1"></i> Buat Surat Baru</a>
                </div>
            </div>
            <div class="card-body">
                <table id="datatableDefault" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="1%">No</th>
                            <th>No Surat</th>
                            <th>Jenis Surat</th>
                            <th>Penduduk</th>
                            <th>Tanggal</th>
                            <th>Keperluan</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($surats as $key => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->no_surat }}</td>
                            <td>{{ $item->jenisSurat->nama_surat }}</td>
                            <td>
                                <div>{{ $item->penduduk->nama }}</div>
                                <small class="text-muted">{{ $item->penduduk->nik }}</small>
                            </td>
                            <td>{{ $item->tanggal_surat->format('d/m/Y') }}</td>
                            <td>{{ $item->keperluan }}</td>
                            <td>
                                <a href="{{ route('surat.show', $item->id) }}"
                                    class="btn btn-sm btn-info text-white mr-1" title="Lihat/Cetak"><i
                                        class="fas fa-print"></i></a>
                                <form action="{{ route('surat.destroy', $item->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus arsip ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
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
        "autoWidth": false,
        "order": [[ 4, "desc" ]],
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#datatableDefault_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush