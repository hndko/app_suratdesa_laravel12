@extends('layouts.app-backend')

@section('content')
<div class="wa-page">
    <div class="wa-hero">
        <div>
            <span class="eyebrow">Integrasi Eksternal</span>
            <h1>{{ $title }}</h1>
            <p>Kirim pesan uji coba untuk memastikan koneksi Fonnte aktif sebelum notifikasi otomatis surat dan pengaduan digunakan.</p>
        </div>
        <div class="hero-badge {{ $hasToken ? 'is-ready' : 'is-missing' }}">
            <i class="fab fa-whatsapp"></i>
            <span>{{ $hasToken ? 'Token aktif' : 'Token belum diatur' }}</span>
        </div>
    </div>

    <div class="wa-metric-grid">
        <div class="wa-metric metric-green">
            <div class="metric-icon"><i class="fab fa-whatsapp"></i></div>
            <div>
                <span>Provider</span>
                <strong>Fonnte</strong>
                <small>api.fonnte.com</small>
            </div>
        </div>
        <div class="wa-metric {{ $hasToken ? 'metric-blue' : 'metric-red' }}">
            <div class="metric-icon"><i class="fas fa-key"></i></div>
            <div>
                <span>Status Token</span>
                <strong>{{ $hasToken ? 'Terpasang' : 'Belum Ada' }}</strong>
                <small>{{ $tokenPreview ?? 'Isi FONNTE_TOKEN di .env' }}</small>
            </div>
        </div>
        <div class="wa-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-clock"></i></div>
            <div>
                <span>Timeout</span>
                <strong>5 detik</strong>
                <small>Retry 2x</small>
            </div>
        </div>
    </div>

    <div class="wa-grid">
        <form action="{{ route('whatsapp.test.send') }}" method="POST" class="wa-form-panel">
            @csrf
            <div class="panel-heading">
                <div>
                    <span>Pesan Uji</span>
                    <h2>Kirim WhatsApp</h2>
                </div>
                <i class="fas fa-paper-plane"></i>
            </div>

            <div class="form-group">
                <label for="phone">Nomor WhatsApp Tujuan <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone-alt"></i></span></div>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Contoh: 08123456789 atau 628123456789" required maxlength="20" inputmode="tel">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <small class="form-text text-muted">Gunakan nomor aktif. Akun trial Fonnte biasanya hanya bisa mengirim ke nomor yang sudah terdaftar.</small>
            </div>

            <div class="form-group">
                <label for="message">Pesan <span class="text-danger">*</span></label>
                <div class="input-group textarea-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-comment-dots"></i></span></div>
                    <textarea name="message" id="message" rows="7" class="form-control @error('message') is-invalid @enderror" required maxlength="1000" placeholder="Tulis pesan uji coba WhatsApp...">{{ old('message', 'Halo! Ini adalah pesan uji coba dari sistem SIMADES.') }}</textarea>
                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <small class="form-text text-muted"><span id="messageCounter">0</span>/1000 karakter</small>
            </div>

            <div class="quick-messages">
                <button type="button" class="btn btn-light js-message-template" data-message="Halo! Ini adalah pesan uji coba dari sistem SIMADES.">
                    <i class="fas fa-vial mr-1"></i> Pesan Test
                </button>
                <button type="button" class="btn btn-light js-message-template" data-message="Halo, pengajuan surat Anda telah diterima oleh sistem SIMADES dan akan segera diproses oleh operator desa.">
                    <i class="fas fa-file-signature mr-1"></i> Template Surat
                </button>
                <button type="button" class="btn btn-light js-message-template" data-message="Halo, pengaduan Anda telah kami terima. Tim desa akan menindaklanjuti laporan sesuai antrean dan prioritas.">
                    <i class="fas fa-comments mr-1"></i> Template Pengaduan
                </button>
            </div>

            <div class="form-actions">
                <button type="reset" class="btn btn-light" id="btnResetMessage">
                    <i class="fas fa-eraser mr-1"></i> Bersihkan
                </button>
                @can('whatsapp-test-send')
                <button type="submit" class="btn btn-primary" id="btnSendWhatsapp" {{ $hasToken ? '' : 'disabled' }}>
                    <i class="fas fa-paper-plane mr-1"></i> Kirim Sekarang
                </button>
                @endcan
            </div>
        </form>

        <div class="wa-info-panel">
            <div class="panel-heading">
                <div>
                    <span>Koneksi</span>
                    <h2>Informasi Gateway</h2>
                </div>
                <i class="fas fa-plug"></i>
            </div>

            <div class="connection-list">
                <div>
                    <i class="fas fa-server"></i>
                    <span>Endpoint</span>
                    <strong>https://api.fonnte.com/send</strong>
                </div>
                <div>
                    <i class="fas fa-key"></i>
                    <span>Environment</span>
                    <strong>FONNTE_TOKEN</strong>
                </div>
                <div>
                    <i class="fas fa-redo"></i>
                    <span>Reliability</span>
                    <strong>Timeout 5 detik, retry 2x</strong>
                </div>
            </div>

            <div class="guide-box {{ $hasToken ? 'guide-success' : 'guide-danger' }}">
                <i class="fas {{ $hasToken ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"></i>
                <div>
                    <strong>{{ $hasToken ? 'Gateway siap diuji' : 'Token belum tersedia' }}</strong>
                    <span>{{ $hasToken ? 'Kirim pesan test ke nomor yang aman untuk memastikan notifikasi berjalan.' : 'Isi FONNTE_TOKEN pada file .env lalu jalankan php artisan config:clear.' }}</span>
                </div>
            </div>

            <div class="tips-box">
                <strong><i class="fas fa-lightbulb mr-1"></i> Tips pengujian</strong>
                <ul>
                    <li>Gunakan nomor milik sendiri atau nomor internal tim.</li>
                    <li>Hindari mengirim data pribadi warga saat uji coba.</li>
                    <li>Cek log aplikasi jika respons provider gagal.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .wa-page { color: #1f2937; }
    .wa-hero {
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
    .wa-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .wa-hero p {
        max-width: 790px;
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }
    .eyebrow,
    .panel-heading span {
        display: block;
        color: #0f766e;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .wa-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 0.8rem;
        border-radius: 999px;
        font-weight: 800;
        white-space: nowrap;
    }
    .hero-badge.is-ready { background: rgba(16, 185, 129, 0.22); }
    .hero-badge.is-missing { background: rgba(239, 68, 68, 0.22); }
    .wa-metric-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .wa-metric,
    .wa-form-panel,
    .wa-info-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .wa-metric {
        --metric-color: #059669;
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
    .wa-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .wa-metric strong {
        display: block;
        color: #111827;
        font-size: 1.35rem;
        font-weight: 800;
    }
    .wa-metric small {
        color: #64748b;
        font-weight: 700;
    }
    .metric-green { --metric-color: #059669; }
    .metric-blue { --metric-color: #2563eb; }
    .metric-red { --metric-color: #dc2626; }
    .metric-cyan { --metric-color: #0891b2; }
    .wa-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(340px, 0.82fr);
        gap: 1rem;
        align-items: start;
    }
    .wa-form-panel,
    .wa-info-panel {
        padding: 1rem;
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
    .wa-form-panel label {
        color: #334155;
        font-weight: 800;
    }
    .wa-form-panel .input-group-text {
        min-width: 42px;
        justify-content: center;
        border-color: #dbe3ef;
        color: #0f766e;
        background: #f8fafc;
    }
    .textarea-group .input-group-prepend .input-group-text {
        align-items: flex-start;
        padding-top: 0.85rem;
    }
    .wa-form-panel .form-control {
        border-color: #dbe3ef;
        border-radius: 0 8px 8px 0;
    }
    .quick-messages {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.55rem;
        margin-bottom: 1rem;
    }
    .quick-messages .btn {
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
    .connection-list {
        display: grid;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .connection-list div {
        display: grid;
        grid-template-columns: 34px 1fr;
        gap: 0.2rem 0.65rem;
        padding: 0.75rem;
        border-radius: 12px;
        background: #f8fafc;
    }
    .connection-list i {
        grid-row: span 2;
        color: #0f766e;
        margin-top: 0.18rem;
    }
    .connection-list span {
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
    }
    .connection-list strong {
        color: #0f172a;
        word-break: break-word;
    }
    .guide-box {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1rem;
        padding: 0.85rem;
        border-radius: 12px;
    }
    .guide-success { color: #047857; background: #d1fae5; }
    .guide-danger { color: #991b1b; background: #fee2e2; }
    .guide-box strong {
        display: block;
        color: #0f172a;
    }
    .guide-box span {
        color: #475569;
        font-weight: 700;
    }
    .tips-box {
        padding: 0.85rem;
        border-radius: 12px;
        color: #475569;
        background: #f8fafc;
        font-weight: 700;
    }
    .tips-box strong {
        display: block;
        margin-bottom: 0.45rem;
        color: #0f172a;
    }
    .tips-box ul {
        margin: 0;
        padding-left: 1.1rem;
    }
    @media (max-width: 1199.98px) {
        .wa-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .wa-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 767.98px) {
        .wa-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .wa-hero h1 { font-size: 1.5rem; }
        .hero-badge { justify-content: center; }
        .wa-metric-grid,
        .quick-messages {
            grid-template-columns: 1fr;
        }
        .form-actions .btn { width: 100%; }
    }
</style>
@endpush

@push('scripts')
<script>
    $(function () {
        var message = $('#message');
        var counter = $('#messageCounter');

        function updateCounter() {
            counter.text(message.val().length);
        }

        message.on('input', updateCounter);
        updateCounter();

        $('.js-message-template').on('click', function () {
            message.val($(this).data('message')).trigger('input').focus();
        });

        $('#btnResetMessage').on('click', function () {
            setTimeout(updateCounter, 0);
        });

        $('.wa-form-panel').on('submit', function () {
            $('#btnSendWhatsapp')
                .prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...');
        });
    });
</script>
@endpush
