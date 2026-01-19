@extends('layouts.app-backend')

@push('css')
<link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
    rel="stylesheet" />
<style>
    /* Force wrap on mobile detail view */
    table.dataTable>tbody>tr.child span.dtr-data {
        white-space: normal !important;
        word-wrap: break-word;
        word-break: break-all;
        /* Ensures really long strings break */
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center mb-3">
    <div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item active">{{ $title }}</li>
        </ul>
        <h1 class="page-header mb-0">
            {{ $title }}
        </h1>
    </div>
    <div class="ms-auto">
        <a href="{{ route('penduduk.create') }}" class="btn btn-theme"><i class="fa fa-plus-circle fa-fw me-1"></i>
            Tambah Penduduk</a>
    </div>
</div>



<!-- DataTables Container -->
<div id="datatable" class="mb-5">
    <div class="card">
        <div class="card-body">
            <table id="datatableDefault" class="table text-nowrap w-100">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th>NIK</th>
                        <th>Nama</th>
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
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->nik }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->jenis_kelamin }}</td>
                        <td>{{ $row->tempat_lahir }}, {{ \Carbon\Carbon::parse($row->tgl_lahir)->format('d-m-Y') }}</td>
                        <td>{{ $row->alamat }} RT {{ $row->rt }} / RW {{ $row->rw }}</td>
                        <td>{{ $row->pekerjaan }}</td>
                        <td>
                            <a href="{{ route('penduduk.edit', $row->id) }}" class="btn btn-sm btn-info text-white me-1"
                                title="Edit"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('penduduk.destroy', $row->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i
                                        class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script>
    $('#datatableDefault').DataTable({
        dom: "<'row mb-3'<'col-12 col-md-6 d-flex justify-content-center justify-content-md-start mb-2 mb-md-0'l><'col-12 col-md-6 d-flex justify-content-center justify-content-md-end align-items-center gap-2 flex-wrap'fB>>t<'row align-items-center mt-3'<'mr-auto col-md-6'i><'mb-0 col-md-6'p>>",
        lengthMenu: [ 10, 20, 30, 40, 50 ],
        responsive: true,
        buttons: [
            { extend: 'print', className: 'btn btn-default btn-sm' },
            { extend: 'csv', className: 'btn btn-default btn-sm' }
        ]
    });
</script>
@endpush