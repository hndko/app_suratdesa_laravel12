@extends('layouts.app-backend')

@section('content')
<div class="assistant-page">
    <div class="assistant-hero">
        <div>
            <span class="eyebrow">AI Internal</span>
            <h1>{{ $title }}</h1>
            <p>Tanyakan ringkasan operasional surat dan pengaduan tanpa membuka detail sensitif warga. Jawaban AI bersifat rekomendasi kerja internal.</p>
        </div>
        <div class="hero-badge">
            <i class="fas fa-shield-alt"></i>
            <span>Data sensitif dibatasi</span>
        </div>
    </div>

    <div class="assistant-metric-grid">
        <div class="assistant-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-file-signature"></i></div>
            <div>
                <span>Total Surat</span>
                <strong>{{ number_format($totalSurat, 0, ',', '.') }}</strong>
                <small>{{ number_format($suratPending, 0, ',', '.') }} menunggu</small>
            </div>
        </div>
        <div class="assistant-metric metric-green">
            <div class="metric-icon"><i class="fas fa-check-double"></i></div>
            <div>
                <span>Surat Selesai</span>
                <strong>{{ number_format($suratDone, 0, ',', '.') }}</strong>
                <small>Siap direkap</small>
            </div>
        </div>
        <div class="assistant-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-comments"></i></div>
            <div>
                <span>Pengaduan</span>
                <strong>{{ number_format($totalPengaduan, 0, ',', '.') }}</strong>
                <small>{{ number_format($pengaduanPending, 0, ',', '.') }} pending</small>
            </div>
        </div>
        <div class="assistant-metric metric-purple">
            <div class="metric-icon"><i class="fas fa-robot"></i></div>
            <div>
                <span>Teratasi</span>
                <strong>{{ number_format($pengaduanResolved, 0, ',', '.') }}</strong>
                <small>Pengaduan selesai</small>
            </div>
        </div>
    </div>

    <div class="assistant-grid">
        <form action="{{ route('ai-assistant.send') }}" method="POST" class="prompt-panel">
            @csrf
            <div class="panel-heading">
                <div>
                    <span>Prompt</span>
                    <h2>Tanya Assistant</h2>
                </div>
                <i class="fas fa-paper-plane"></i>
            </div>

            <div class="form-group">
                <label for="message">Pertanyaan Internal <span class="text-danger">*</span></label>
                <div class="input-group textarea-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-comment-dots"></i></span></div>
                    <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="9" maxlength="2000" required placeholder="Contoh: ringkas kondisi surat dan pengaduan hari ini, lalu beri prioritas tindak lanjut.">{{ old('message', session('ai_question')) }}</textarea>
                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <small class="form-text text-muted"><span id="messageCounter">0</span>/2000 karakter</small>
            </div>

            <div class="quick-prompts">
                <button type="button" class="btn btn-light js-prompt-template" data-prompt="Ringkas kondisi surat dan pengaduan hari ini, lalu beri 3 prioritas tindak lanjut untuk operator.">
                    <i class="fas fa-tasks mr-1"></i> Prioritas Hari Ini
                </button>
                <button type="button" class="btn btn-light js-prompt-template" data-prompt="Buat ringkasan singkat untuk Kepala Desa berdasarkan jumlah surat dan pengaduan yang tersedia.">
                    <i class="fas fa-user-tie mr-1"></i> Ringkasan Kades
                </button>
                <button type="button" class="btn btn-light js-prompt-template" data-prompt="Apa risiko operasional yang perlu diperhatikan dari data surat dan pengaduan saat ini?">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Risiko Operasional
                </button>
            </div>

            <div class="form-actions">
                <button type="reset" class="btn btn-light" id="btnResetPrompt">
                    <i class="fas fa-eraser mr-1"></i> Bersihkan
                </button>
                <button type="submit" class="btn btn-primary" id="btnSendPrompt">
                    <i class="fas fa-paper-plane mr-1"></i> Kirim Pertanyaan
                </button>
            </div>
        </form>

        <div class="answer-panel">
            <div class="panel-heading">
                <div>
                    <span>Jawaban</span>
                    <h2>Respons AI</h2>
                </div>
                <i class="fas fa-robot"></i>
            </div>

            @if(session('ai_answer'))
            <div class="question-card">
                <span>Pertanyaan terakhir</span>
                <p>{{ session('ai_question') }}</p>
            </div>
            <div class="answer-card">
                {{ session('ai_answer') }}
            </div>
            @else
            <div class="empty-answer">
                <i class="fas fa-comments"></i>
                <strong>Belum ada jawaban</strong>
                <span>Tulis pertanyaan atau pilih template cepat untuk memulai analisis internal.</span>
            </div>
            @endif
        </div>
    </div>

    <div class="assistant-note">
        <i class="fas fa-lock"></i>
        <div>
            <strong>Batasan keamanan</strong>
            <span>Assistant hanya menerima konteks statistik agregat dari surat dan pengaduan. Jangan memasukkan NIK lengkap, alamat lengkap, API key, atau data pribadi sensitif ke prompt manual.</span>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .assistant-page { color: #1f2937; }
    .assistant-hero {
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
    .assistant-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .assistant-hero p {
        max-width: 820px;
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }
    .eyebrow,
    .panel-heading span,
    .question-card span {
        display: block;
        color: #0f766e;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .assistant-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 0.8rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-weight: 800;
        white-space: nowrap;
    }
    .assistant-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .assistant-metric,
    .prompt-panel,
    .answer-panel,
    .assistant-note {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .assistant-metric {
        --metric-color: #2563eb;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 1rem;
    }
    .metric-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        border-radius: 13px;
        color: #ffffff;
        background: var(--metric-color);
    }
    .assistant-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .assistant-metric strong {
        display: block;
        color: #111827;
        font-size: 1.6rem;
        font-weight: 800;
    }
    .assistant-metric small {
        color: #64748b;
        font-weight: 700;
    }
    .metric-blue { --metric-color: #2563eb; }
    .metric-green { --metric-color: #059669; }
    .metric-cyan { --metric-color: #0891b2; }
    .metric-purple { --metric-color: #7c3aed; }
    .assistant-grid {
        display: grid;
        grid-template-columns: minmax(0, 0.95fr) minmax(360px, 1.05fr);
        gap: 1rem;
        margin-bottom: 1rem;
        align-items: start;
    }
    .prompt-panel,
    .answer-panel {
        padding: 1rem;
    }
    .answer-panel {
        min-height: 430px;
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
    .prompt-panel label {
        color: #334155;
        font-weight: 800;
    }
    .prompt-panel .input-group-text {
        min-width: 42px;
        align-items: flex-start;
        justify-content: center;
        padding-top: 0.85rem;
        border-color: #dbe3ef;
        color: #0f766e;
        background: #f8fafc;
    }
    .prompt-panel .form-control {
        border-color: #dbe3ef;
        border-radius: 0 8px 8px 0;
        resize: vertical;
    }
    .quick-prompts {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.55rem;
        margin-bottom: 1rem;
    }
    .quick-prompts .btn {
        min-height: 44px;
        font-weight: 800;
        white-space: normal;
    }
    .form-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.55rem;
    }
    .question-card,
    .answer-card,
    .empty-answer {
        border-radius: 12px;
        background: #f8fafc;
    }
    .question-card {
        margin-bottom: 0.8rem;
        padding: 0.85rem;
    }
    .question-card p {
        margin: 0.35rem 0 0;
        color: #334155;
        font-weight: 700;
    }
    .answer-card {
        padding: 1rem;
        color: #1f2937;
        font-weight: 700;
        line-height: 1.7;
        white-space: pre-wrap;
    }
    .empty-answer {
        min-height: 310px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        padding: 1rem;
        color: #64748b;
        text-align: center;
    }
    .empty-answer i {
        color: #94a3b8;
        font-size: 3.2rem;
    }
    .empty-answer strong {
        color: #0f172a;
        font-size: 1.05rem;
    }
    .assistant-note {
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
        padding: 1rem;
    }
    .assistant-note i {
        color: #0f766e;
        font-size: 1.2rem;
        margin-top: 0.15rem;
    }
    .assistant-note strong {
        display: block;
        color: #0f172a;
    }
    .assistant-note span {
        color: #64748b;
        font-weight: 700;
    }
    @media (max-width: 1199.98px) {
        .assistant-metric-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .assistant-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 767.98px) {
        .assistant-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .assistant-hero h1 { font-size: 1.5rem; }
        .hero-badge { justify-content: center; }
        .assistant-metric-grid,
        .quick-prompts {
            grid-template-columns: 1fr;
        }
        .form-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(function () {
        var textarea = $('#message');
        var counter = $('#messageCounter');

        function updateCounter() {
            counter.text(textarea.val().length);
        }

        textarea.on('input', updateCounter);
        updateCounter();

        $('.js-prompt-template').on('click', function () {
            textarea.val($(this).data('prompt')).trigger('input').focus();
        });

        $('#btnResetPrompt').on('click', function () {
            setTimeout(updateCounter, 0);
        });

        $('.prompt-panel').on('submit', function () {
            $('#btnSendPrompt')
                .prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...');
        });
    });
</script>
@endpush
