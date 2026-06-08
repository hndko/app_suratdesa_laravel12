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
                    <li class="breadcrumb-item"><a href="{{ route('kartu-keluarga.index') }}">Data Kartu Keluarga</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $kartuKeluarga->no_kk }}</h3>
        <div class="card-tools">
            @can('kartu-keluarga-edit')
            <a href="{{ route('kartu-keluarga.edit', $kartuKeluarga->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit mr-1"></i> Edit</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Kepala Keluarga</dt>
            <dd class="col-sm-9">{{ $kartuKeluarga->kepala_keluarga }}</dd>
            <dt class="col-sm-3">Alamat</dt>
            <dd class="col-sm-9">{{ $kartuKeluarga->alamat }} RT {{ $kartuKeluarga->rt }} / RW {{ $kartuKeluarga->rw }}</dd>
            <dt class="col-sm-3">Wilayah</dt>
            <dd class="col-sm-9">{{ $kartuKeluarga->desa ?? '-' }}, {{ $kartuKeluarga->kecamatan ?? '-' }}, {{ $kartuKeluarga->kabupaten ?? '-' }}, {{ $kartuKeluarga->provinsi ?? '-' }}</dd>
        </dl>

        <h5 class="mt-4">Anggota Keluarga</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>SHDK</th>
                        <th>Jenis Kelamin</th>
                        <th>Pekerjaan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kartuKeluarga->penduduks as $penduduk)
                    <tr>
                        <td>{{ $penduduk->nik }}</td>
                        <td>{{ $penduduk->nama }}</td>
                        <td>{{ $penduduk->shdk ?? '-' }}</td>
                        <td>{{ $penduduk->jenis_kelamin }}</td>
                        <td>{{ $penduduk->pekerjaan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada anggota keluarga.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
