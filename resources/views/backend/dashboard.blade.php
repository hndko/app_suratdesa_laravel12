@extends('layouts.app-backend')

@section('title', 'Dashboard')

@section('content')
<ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
</ul>

<h1 class="page-header">
    Dashboard <small>Overview aplikasi admin Surat Desa</small>
</h1>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex fw-bold small mb-3">
                    <span class="flex-grow-1">TOTAL PENDUDUK</span>
                    <a href="#" data-toggle="card-expand" class="text-body text-opacity-50 text-decoration-none"><i
                            class="fa fa-fw fa-expand"></i></a>
                </div>
                <div class="row align-items-center mb-2">
                    <div class="col-7">
                        <h3 class="mb-0">0</h3>
                    </div>
                    <div class="col-5">
                        <div class="mt-n2" data-render="apexchart" data-type="bar" data-title="Visitors"
                            data-alt-title="20% more than last week" data-plot-type="bar"
                            data-data="[10,20,30,40,50,20]" data-units="m" data-height="30"></div>
                    </div>
                </div>
                <div class="small text-body text-opacity-50 text-truncate">
                    <i class="fa fa-user fa-fw me-1"></i> Data warga terdaftar
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex fw-bold small mb-3">
                    <span class="flex-grow-1">SURAT KELUAR</span>
                    <a href="#" data-toggle="card-expand" class="text-body text-opacity-50 text-decoration-none"><i
                            class="fa fa-fw fa-expand"></i></a>
                </div>
                <div class="row align-items-center mb-2">
                    <div class="col-7">
                        <h3 class="mb-0">0</h3>
                    </div>
                    <div class="col-5">
                        <div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors"
                            data-alt-title="20% more than last week" data-plot-type="line"
                            data-data="[10,20,30,40,50,20]" data-units="m" data-height="30"></div>
                    </div>
                </div>
                <div class="small text-body text-opacity-50 text-truncate">
                    <i class="fa fa-file-alt fa-fw me-1"></i> Total surat dicetak
                </div>
            </div>
        </div>
    </div>
</div>
@endsection