@extends('layouts.app-backend')

@section('content')
<div class="letter-create-page">
    <div class="letter-create-hero">
        <div>
            <span class="eyebrow">Transaksi Surat</span>
            <h1>{{ $title }}</h1>
            <p>Buat draft surat resmi dari data penduduk, jenis surat, tanggal penerbitan, dan keperluan layanan.</p>
        </div>
        <a href="{{ route('surat.index') }}" class="btn btn-outline-light">
            <i class="fas fa-archive mr-1"></i> Arsip Surat
        </a>
    </div>

    <div class="letter-create-grid">
        <form action="{{ route('surat.store') }}" method="POST" id="formSurat" class="letter-form-panel">
            @csrf
            <div class="panel-heading">
                <div>
                    <span>Form Data</span>
                    <h2>Informasi Surat</h2>
                </div>
                <i class="fas fa-pen-nib"></i>
            </div>

            <div class="form-group">
                <label for="jenis_surat_id">Jenis Surat <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-file-signature"></i></span></div>
                    <select id="jenis_surat_id" class="form-control" name="jenis_surat_id" required data-placeholder="Pilih jenis surat">
                        <option value="">Pilih jenis surat</option>
                        @foreach($jenis_surats as $js)
                        <option
                            value="{{ $js->id }}"
                            data-kode="{{ $js->kode_surat }}"
                            data-kop="{{ $js->kop_judul }}"
                            data-template="{{ trim((string) $js->template_isi) !== '' ? '1' : '0' }}"
                            {{ old('jenis_surat_id') == $js->id ? 'selected' : '' }}
                        >
                            {{ $js->kode_surat }} - {{ $js->nama_surat }}
                        </option>
                        @endforeach
                    </select>
                    @error('jenis_surat_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <small class="form-text text-muted">Pastikan jenis surat sudah memiliki template agar preview dokumen lengkap.</small>
            </div>

            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="tanggal_surat">Tanggal Surat <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-calendar-alt"></i></span></div>
                        <input type="date" id="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                        @error('tanggal_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group col-md-7">
                    <label for="penduduk_id">Penduduk <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-id-card"></i></span></div>
                        <select id="penduduk_id" class="form-control js-penduduk-select" name="penduduk_id" required data-placeholder="Cari NIK, nama, nomor HP, atau alamat">
                            @if($selectedPenduduk)
                            <option
                                value="{{ $selectedPenduduk->id }}"
                                selected
                                data-nik="{{ $selectedPenduduk->nik }}"
                                data-nama="{{ $selectedPenduduk->nama }}"
                                data-phone="{{ $selectedPenduduk->phone }}"
                                data-alamat="{{ $selectedPenduduk->alamat }}"
                            >
                                {{ $selectedPenduduk->nik }} - {{ $selectedPenduduk->nama }}
                            </option>
                            @endif
                        </select>
                        @error('penduduk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <small class="form-text text-muted">Data penduduk dimuat saat dicari agar halaman tetap ringan.</small>
                </div>
            </div>

            <div class="selection-summary">
                <div>
                    <i class="fas fa-user-check"></i>
                    <div>
                        <strong id="selectedResidentName">Penduduk belum dipilih</strong>
                        <span id="selectedResidentMeta">Cari dan pilih penduduk untuk melihat ringkasannya.</span>
                    </div>
                </div>
                <div>
                    <i class="fas fa-file-alt"></i>
                    <div>
                        <strong id="selectedLetterName">Jenis surat belum dipilih</strong>
                        <span id="selectedLetterMeta">Pilih jenis surat untuk melihat kode dan status template.</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="keperluan">Keperluan <span class="text-danger">*</span></label>
                <div class="input-group textarea-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-clipboard-list"></i></span></div>
                    <textarea id="keperluan" class="form-control @error('keperluan') is-invalid @enderror" name="keperluan" rows="4" placeholder="Contoh: Persyaratan administrasi bank" required>{{ old('keperluan') }}</textarea>
                    @error('keperluan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan Tambahan</label>
                <div class="input-group textarea-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-sticky-note"></i></span></div>
                    <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" rows="3" placeholder="Opsional. Tambahkan catatan khusus jika diperlukan.">{{ old('keterangan') }}</textarea>
                    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('surat.index') }}" class="btn btn-light">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
                @can('surat-preview')
                <button type="button" class="btn btn-info" id="btnPreview">
                    <i class="fas fa-eye mr-1"></i> Preview
                </button>
                @endcan
                @can('surat-store')
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan Surat
                </button>
                @endcan
            </div>
        </form>

        <div class="preview-column">
            <div class="preview-panel">
                <div class="panel-heading">
                    <div>
                        <span>Preview</span>
                        <h2>Dokumen Surat</h2>
                    </div>
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="preview-frame" id="previewContainer">
                    <div class="preview-placeholder" id="previewPlaceholder">
                        <i class="fas fa-file-invoice"></i>
                        <strong>Preview belum dibuat</strong>
                        <span>Lengkapi form lalu klik tombol Preview untuk melihat dokumen.</span>
                    </div>
                    <div id="previewContent" class="d-none"></div>
                </div>
            </div>

            <div class="flow-panel">
                <div class="flow-step is-active"><i class="fas fa-edit"></i><span>Draft dibuat oleh operator</span></div>
                <div class="flow-step"><i class="fas fa-user-check"></i><span>Diverifikasi sesuai alur approval</span></div>
                <div class="flow-step"><i class="fas fa-qrcode"></i><span>QR verifikasi tersedia saat selesai</span></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .letter-create-page { color: #1f2937; }
    .letter-create-hero {
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
    .letter-create-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .letter-create-hero p {
        max-width: 760px;
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
    .letter-create-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .letter-create-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(360px, 0.9fr);
        gap: 1rem;
        align-items: start;
    }
    .letter-form-panel,
    .preview-panel,
    .flow-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
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
    .letter-form-panel label {
        color: #334155;
        font-weight: 800;
    }
    .letter-form-panel .input-group-text {
        min-width: 42px;
        justify-content: center;
        border-color: #dbe3ef;
        color: #0f766e;
        background: #f8fafc;
    }
    .letter-form-panel .form-control {
        border-color: #dbe3ef;
        border-radius: 0 8px 8px 0;
    }
    .letter-form-panel .select2-container--bootstrap4 .select2-selection {
        border-color: #dbe3ef;
        border-radius: 0 8px 8px 0;
    }
    .textarea-group .input-group-prepend .input-group-text {
        align-items: flex-start;
        padding-top: 0.8rem;
    }
    .selection-summary {
        display: grid;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .selection-summary > div {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.8rem;
        border-radius: 12px;
        background: #f8fafc;
    }
    .selection-summary i {
        color: #0f766e;
        margin-top: 0.18rem;
    }
    .selection-summary strong {
        display: block;
        color: #0f172a;
    }
    .selection-summary span {
        color: #64748b;
        font-weight: 700;
    }
    .form-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.55rem;
        padding-top: 0.5rem;
    }
    .preview-column {
        position: sticky;
        top: 1rem;
        display: grid;
        gap: 1rem;
    }
    .preview-frame {
        min-height: 560px;
        max-height: 760px;
        overflow: auto;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        padding: 1rem;
    }
    .preview-placeholder {
        min-height: 520px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        color: #64748b;
        text-align: center;
    }
    .preview-placeholder i {
        color: #94a3b8;
        font-size: 3.6rem;
    }
    .preview-placeholder strong {
        color: #0f172a;
        font-size: 1.05rem;
    }
    .preview-loading {
        min-height: 520px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #475569;
        font-weight: 700;
    }
    .flow-panel {
        display: grid;
        gap: 0.7rem;
    }
    .flow-step {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        color: #64748b;
        font-weight: 700;
    }
    .flow-step i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        color: #0f766e;
        background: #ccfbf1;
    }
    .flow-step.is-active {
        color: #0f172a;
    }
    @media (max-width: 1199.98px) {
        .letter-create-grid { grid-template-columns: 1fr; }
        .preview-column { position: static; }
    }
    @media (max-width: 767.98px) {
        .letter-create-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .letter-create-hero h1 { font-size: 1.5rem; }
        .letter-create-hero .btn,
        .form-actions .btn { width: 100%; }
        .preview-frame,
        .preview-placeholder,
        .preview-loading { min-height: 360px; }
    }
</style>
@endpush

@push('scripts')
<script>
    $(function () {
        var pendudukSelect = $('#penduduk_id');
        var jenisSuratSelect = $('#jenis_surat_id');

        function escapeHtml(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        if (pendudukSelect.hasClass('select2-hidden-accessible')) {
            pendudukSelect.select2('destroy');
        }

        pendudukSelect.select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: pendudukSelect.data('placeholder'),
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('surat.penduduk-options') }}',
                dataType: 'json',
                delay: 350,
                data: function (params) {
                    return { q: params.term || '' };
                },
                processResults: function (data) {
                    return data;
                },
                cache: true
            },
            templateResult: function (item) {
                if (!item.id) {
                    return item.text;
                }

                return $('<div class="select2-result-resident"><strong>' + escapeHtml(item.nama) + '</strong><small>' + escapeHtml(item.nik) + ' - ' + escapeHtml(item.phone || 'Nomor HP belum diisi') + '</small></div>');
            },
            templateSelection: function (item) {
                return item.text || item.nama || 'Pilih penduduk';
            }
        });

        function selectedPendudukData() {
            var selected = pendudukSelect.select2('data')[0];
            var option = pendudukSelect.find(':selected');

            return {
                nama: selected && selected.nama ? selected.nama : option.data('nama'),
                nik: selected && selected.nik ? selected.nik : option.data('nik'),
                phone: selected && selected.phone ? selected.phone : option.data('phone'),
                alamat: selected && selected.alamat ? selected.alamat : option.data('alamat')
            };
        }

        function updateSelectionSummary() {
            var penduduk = selectedPendudukData();
            var selectedJenis = jenisSuratSelect.find(':selected');
            var jenisText = selectedJenis.val() ? selectedJenis.text().trim() : '';

            if (penduduk.nama) {
                $('#selectedResidentName').text(penduduk.nama);
                $('#selectedResidentMeta').text((penduduk.nik || '-') + ' | ' + (penduduk.phone || 'Nomor HP belum diisi'));
            } else {
                $('#selectedResidentName').text('Penduduk belum dipilih');
                $('#selectedResidentMeta').text('Cari dan pilih penduduk untuk melihat ringkasannya.');
            }

            if (jenisText) {
                var templateText = selectedJenis.data('template') === 1 || selectedJenis.data('template') === '1' ? 'Template tersedia' : 'Template belum diatur';
                $('#selectedLetterName').text(jenisText);
                $('#selectedLetterMeta').text('Kop: ' + (selectedJenis.data('kop') || '-') + ' | ' + templateText);
            } else {
                $('#selectedLetterName').text('Jenis surat belum dipilih');
                $('#selectedLetterMeta').text('Pilih jenis surat untuk melihat kode dan status template.');
            }
        }

        pendudukSelect.on('select2:select change', updateSelectionSummary);
        jenisSuratSelect.on('change', updateSelectionSummary);
        updateSelectionSummary();

        $('#btnPreview').on('click', function () {
            var form = $('#formSurat');
            var required = [
                ['jenis_surat_id', 'Mohon pilih Jenis Surat terlebih dahulu.'],
                ['penduduk_id', 'Mohon pilih Penduduk terlebih dahulu.'],
                ['tanggal_surat', 'Mohon isi Tanggal Surat terlebih dahulu.'],
                ['keperluan', 'Mohon isi Keperluan terlebih dahulu.']
            ];

            for (var index = 0; index < required.length; index++) {
                var field = required[index][0];
                var message = required[index][1];
                var input = $('[name="' + field + '"]');

                if (!input.val()) {
                    if (window.showToast) {
                        window.showToast('warning', message);
                    }
                    input.focus();
                    return;
                }
            }

            $('#previewPlaceholder').addClass('d-none');
            $('#previewContent')
                .removeClass('d-none')
                .html('<div class="preview-loading"><div class="spinner-border text-primary"></div><span class="mt-2">Sedang membuat preview...</span></div>');

            $.ajax({
                url: '{{ route('surat.preview') }}',
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    $('#previewContent').html(response.html);
                    if (window.showToast) {
                        window.showToast('success', 'Preview surat berhasil dibuat.');
                    }
                },
                error: function () {
                    $('#previewContent').html('<div class="alert alert-danger mb-0"><i class="fas fa-exclamation-triangle mr-1"></i> Gagal memuat preview. Pastikan semua data wajib diisi dan template surat tersedia.</div>');
                    if (window.showToast) {
                        window.showToast('error', 'Gagal memuat preview. Pastikan semua data wajib diisi.');
                    }
                }
            });
        });
    });
</script>
@endpush
