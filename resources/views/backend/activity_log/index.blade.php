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
                    <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Riwayat Aktivitas Sistem</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('activity-log.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="q" class="form-control" value="{{ $q ?? '' }}" placeholder="Cari event, modul, atau deskripsi...">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    @if(!empty($q))
                    <a href="{{ route('activity-log.index') }}" class="btn btn-default"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th>Waktu</th>
                        <th>Event</th>
                        <th>Modul</th>
                        <th>Pelaku</th>
                        <th>Perubahan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>{{ $activities->firstItem() + $loop->index }}</td>
                        <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                        <td><span class="badge badge-info">{{ $activity->event ?? $activity->description }}</span></td>
                        <td>{{ class_basename($activity->subject_type) }}</td>
                        <td>{{ $activity->causer->name ?? '-' }}</td>
                        <td>
                            @if($activity->properties->has('attributes'))
                            <small class="d-block"><strong>Baru:</strong> {{ json_encode($activity->properties->get('attributes'), JSON_UNESCAPED_UNICODE) }}</small>
                            @endif
                            @if($activity->properties->has('old'))
                            <small class="d-block text-muted"><strong>Lama:</strong> {{ json_encode($activity->properties->get('old'), JSON_UNESCAPED_UNICODE) }}</small>
                            @endif
                            @if(! $activity->properties->has('attributes') && ! $activity->properties->has('old'))
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada activity log.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $activities->links() }}</div>
    </div>
</div>
@endsection
