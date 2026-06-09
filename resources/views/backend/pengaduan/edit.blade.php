@extends('layouts.app-backend')

@section('content')
<div class="complaint-edit-page">
    <div class="complaint-edit-hero">
        <div>
            <span class="eyebrow">Layanan Warga</span>
            <h1>{{ $title }}</h1>
            <p>Tinjau detail pengaduan, gunakan saran AI bila tersedia, lalu kirim tanggapan yang jelas kepada warga.</p>
        </div>
        <a href="{{ route('pengaduan.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="complaint-edit-grid">
        <div class="detail-column">
            <div class="detail-panel">
                <div class="panel-heading">
                    <div>
                        <span>Detail Aduan</span>
                        <h2>{{ $pengaduan->ticket_code }}</h2>
                    </div>
                    @can('pengaduan-ai-analyze')
                    <form action="{{ route('pengaduan.ai-analyze', $pengaduan) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info btn-sm">
                            <i class="fas fa-robot mr-1"></i> Analisis AI
                        </button>
                    </form>
                    @endcan
                </div>

                <div class="info-grid">
                    <div><i class="fas fa-calendar-alt"></i><span>Tanggal</span><strong>{{ $pengaduan->created_at->format('d-m-Y H:i') }}</strong></div>
                    <div><i class="fas fa-user"></i><span>Pelapor</span><strong>{{ $pengaduan->name }}</strong></div>
                    <div><i class="fas fa-id-card"></i><span>NIK</span><strong>{{ $pengaduan->nik }}</strong></div>
                    <div><i class="fas fa-phone"></i><span>No. HP</span><strong>{{ $pengaduan->phone ?? '-' }}</strong></div>
                    <div><i class="fas fa-tag"></i><span>Kategori</span><strong>{{ $pengaduan->category }}</strong></div>
                    <div><i class="fas fa-traffic-light"></i><span>Status</span><strong>{{ ucfirst($pengaduan->status) }}</strong></div>
                </div>

                <div class="content-box">
                    <span>Isi Laporan</span>
                    <p>{{ $pengaduan->content }}</p>
                </div>

                @if($pengaduan->image)
                <div class="attachment-box">
                    <span>Lampiran</span>
                    <img src="{{ asset('storage/' . $pengaduan->image) }}" alt="Lampiran pengaduan" loading="lazy">
                </div>
                @endif
            </div>

            @if($pengaduan->latestAiSuggestion)
            <div class="ai-panel">
                <div class="panel-heading">
                    <div>
                        <span>Saran AI</span>
                        <h2>Rekomendasi Terbaru</h2>
                    </div>
                    <i class="fas fa-robot"></i>
                </div>
                <div class="ai-summary">
                    <strong>Ringkasan</strong>
                    <p>{{ $pengaduan->latestAiSuggestion->summary }}</p>
                </div>
                <div class="ai-meta-grid">
                    <div><span>Kategori</span><strong>{{ $pengaduan->latestAiSuggestion->recommended_category ?? '-' }}</strong></div>
                    <div><span>Prioritas</span><strong>{{ $pengaduan->latestAiSuggestion->priority ?? '-' }}</strong></div>
                </div>
                <div class="ai-draft">
                    <span>Draft Balasan</span>
                    <p>{{ $pengaduan->latestAiSuggestion->draft_reply ?? '-' }}</p>
                </div>
            </div>
            @endif
        </div>

        <form action="{{ route('pengaduan.update', $pengaduan->id) }}" method="POST" class="response-panel">
            @csrf
            @method('PUT')
            <div class="panel-heading">
                <div>
                    <span>Tanggapan</span>
                    <h2>Update Penanganan</h2>
                </div>
                <i class="fas fa-reply"></i>
            </div>

            <div class="form-group">
                <label for="status">Status Laporan <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-traffic-light"></i></span></div>
                    <select id="status" name="status" class="form-control" required>
                        <option value="pending" {{ old('status', $pengaduan->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="process" {{ old('status', $pengaduan->status) === 'process' ? 'selected' : '' }}>Diproses</option>
                        <option value="resolved" {{ old('status', $pengaduan->status) === 'resolved' ? 'selected' : '' }}>Selesai / Teratasi</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="reply">Tanggapan / Jawaban</label>
                <div class="input-group textarea-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-comment-dots"></i></span></div>
                    <textarea id="reply" name="reply" class="form-control @error('reply') is-invalid @enderror" rows="10" placeholder="Tulis tanggapan yang jelas, sopan, dan mudah dipahami warga...">{{ old('reply', $pengaduan->reply ?: optional($pengaduan->latestAiSuggestion)->draft_reply) }}</textarea>
                    @error('reply')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            @if($pengaduan->replied_by)
            <div class="reply-history">
                <i class="fas fa-history"></i>
                <span>Terakhir ditanggapi oleh <strong>{{ $pengaduan->repliedBy->name }}</strong> pada {{ \Carbon\Carbon::parse($pengaduan->replied_at)->format('d-m-Y H:i') }}</span>
            </div>
            @endif

            <div class="form-actions">
                <a href="{{ route('pengaduan.index') }}" class="btn btn-light">
                    <i class="fas fa-times mr-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane mr-1"></i> Kirim Tanggapan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .complaint-edit-page { color: #1f2937; }
    .complaint-edit-hero {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 1.3rem;
        border-radius: 16px;
        background: linear-gradient(135deg, #111827, #0f766e);
        color: #ffffff;
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.16);
    }
    .complaint-edit-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .complaint-edit-hero p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }
    .eyebrow,
    .panel-heading span,
    .content-box span,
    .attachment-box span,
    .ai-draft span,
    .ai-meta-grid span {
        display: block;
        color: #0f766e;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .complaint-edit-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .complaint-edit-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(360px, 0.85fr);
        gap: 1rem;
        align-items: start;
    }
    .detail-column {
        display: grid;
        gap: 1rem;
    }
    .detail-panel,
    .ai-panel,
    .response-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
        padding: 1rem;
    }
    .response-panel {
        position: sticky;
        top: 1rem;
    }
    .panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .panel-heading h2 {
        margin: 0.12rem 0 0;
        font-size: 1.08rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .panel-heading > i {
        color: #0f766e;
        font-size: 1.45rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .info-grid div {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        padding: 0.75rem;
        border-radius: 12px;
        background: #f8fafc;
    }
    .info-grid i {
        color: #0f766e;
        margin-top: 0.18rem;
    }
    .info-grid span {
        display: block;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
    }
    .info-grid strong {
        display: block;
        color: #0f172a;
    }
    .content-box,
    .attachment-box,
    .ai-summary,
    .ai-draft,
    .reply-history {
        padding: 0.85rem;
        border-radius: 12px;
        background: #f8fafc;
    }
    .content-box p,
    .ai-summary p,
    .ai-draft p {
        margin: 0.35rem 0 0;
        color: #334155;
        font-weight: 700;
        white-space: pre-line;
    }
    .attachment-box {
        margin-top: 1rem;
    }
    .attachment-box img {
        width: 100%;
        margin-top: 0.6rem;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        object-fit: cover;
    }
    .ai-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
        margin: 0.75rem 0;
    }
    .ai-meta-grid div {
        padding: 0.75rem;
        border-radius: 12px;
        background: #f8fafc;
    }
    .ai-meta-grid strong {
        display: block;
        color: #0f172a;
        margin-top: 0.2rem;
    }
    .response-panel label {
        color: #334155;
        font-weight: 800;
    }
    .response-panel .input-group-text {
        min-width: 42px;
        justify-content: center;
        border-color: #dbe3ef;
        color: #0f766e;
        background: #f8fafc;
    }
    .response-panel .form-control {
        border-color: #dbe3ef;
        border-radius: 0 8px 8px 0;
    }
    .textarea-group .input-group-prepend .input-group-text {
        align-items: flex-start;
        padding-top: 0.8rem;
    }
    .reply-history {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        color: #475569;
        font-weight: 700;
    }
    .reply-history i {
        color: #0f766e;
        margin-top: 0.2rem;
    }
    .form-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.55rem;
        padding-top: 0.75rem;
    }
    @media (max-width: 1199.98px) {
        .complaint-edit-grid { grid-template-columns: 1fr; }
        .response-panel { position: static; }
    }
    @media (max-width: 767.98px) {
        .complaint-edit-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .complaint-edit-hero h1 { font-size: 1.5rem; }
        .complaint-edit-hero .btn,
        .form-actions .btn { width: 100%; }
        .info-grid,
        .ai-meta-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush
