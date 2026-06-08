@extends('layouts.app-backend')

@section('content')
<div class="content-header ps-0 pe-0">
    <div class="container-fluid"><div class="row mb-2"><div class="col-sm-6"><h1 class="m-0">{{ $title }}</h1></div></div></div>
</div>

<div class="row">
    <div class="col-md-6">
        <form action="{{ route('ai-assistant.send') }}" method="POST">
            @csrf
            <div class="card card-outline card-primary">
                <div class="card-header"><h3 class="card-title">Tanya Assistant Internal</h3></div>
                <div class="card-body">
                    <textarea name="message" class="form-control" rows="8" required placeholder="Contoh: ringkas kondisi surat dan pengaduan hari ini...">{{ session('ai_question') }}</textarea>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-1"></i> Kirim</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-6">
        <div class="card card-outline card-info">
            <div class="card-header"><h3 class="card-title">Jawaban AI</h3></div>
            <div class="card-body">
                @if(session('ai_answer'))
                <div class="bg-light border rounded p-3" style="white-space: pre-wrap;">{{ session('ai_answer') }}</div>
                @else
                <p class="text-muted mb-0">Belum ada jawaban.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
