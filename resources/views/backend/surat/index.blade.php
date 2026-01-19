@extends('layouts.app-backend')

@section('title', $title)

@push('css')
<link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
    rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex align-items-center mb-3">
    <div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
            <li class="breadcrumb-item active">Arsip Surat</li>
        </ul>
        <h1 class="page-header mb-0">Arsip Surat</h1>
    </div>
    <div class="ms-auto">
        <a href="{{ route('surat.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Buat Surat
            Baru</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div id="datatable" class="mb-5">
    <div class="card">
        <div class="card-body">
            <table id="datatableDefault" class="table text-nowrap w-100">
                <thead class="table-light">
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
                            <a href="{{ route('surat.show', $item->id) }}" class="btn btn-sm btn-info text-white me-1"
                                title="Lihat/Cetak"><i class="fa fa-print"></i></a>
                            <form action="{{ route('surat.destroy', $item->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus arsip ini?')">
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
        dom: "<'row mb-3'<'col-md-4 mb-3 mb-md-0'l><'col-md-8 text-right'<'d-flex justify-content-end'f<'ms-2'B>>>>t<'row align-items-center mt-3'<'mr-auto col-md-6'i><'mb-0 col-md-6'p>>",
        lengthMenu: [ 10, 20, 30, 40, 50 ],
        responsive: true,
        order: [[ 4, "desc" ]], // Keep sort by date
        buttons: [
            { extend: 'print', className: 'btn btn-default btn-sm' },
            { extend: 'csv', className: 'btn btn-default btn-sm' }
        ]
    });
</script>
@endpush