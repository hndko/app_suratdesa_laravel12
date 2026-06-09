@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="import-page">
    <div class="import-hero">
        <div>
            <span class="eyebrow">Import Data</span>
            <h1>{{ $title }}</h1>
            <p>Upload file Excel atau CSV untuk membuat preview Penduduk dan Kartu Keluarga sebelum data benar-benar disimpan ke sistem.</p>
        </div>
        <a href="{{ route('penduduk.index') }}" class="btn btn-outline-light">
            <i class="fas fa-users mr-1"></i> Data Penduduk
        </a>
    </div>

    <div class="import-metric-grid">
        <div class="import-metric metric-blue">
            <div class="metric-icon"><i class="fas fa-layer-group"></i></div>
            <div>
                <span>Total Batch</span>
                <strong>{{ number_format($totalBatch, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="import-metric metric-cyan">
            <div class="metric-icon"><i class="fas fa-search"></i></div>
            <div>
                <span>Menunggu Preview</span>
                <strong>{{ number_format($totalPreview, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="import-metric metric-green">
            <div class="metric-icon"><i class="fas fa-check-double"></i></div>
            <div>
                <span>Sudah Diproses</span>
                <strong>{{ number_format($totalProcessed, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="import-metric metric-red">
            <div class="metric-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <span>Baris Invalid</span>
                <strong>{{ number_format($totalInvalidRows, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <div class="import-grid">
        <div class="import-panel upload-panel">
            <div class="panel-heading">
                <div>
                    <span>Upload File</span>
                    <h2>Preview Import Baru</h2>
                </div>
                <i class="fas fa-file-import"></i>
            </div>

            <form action="{{ route('import-penduduk.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file">File Excel/CSV</label>
                    <label class="upload-dropzone" for="file">
                        <input type="file" id="file" name="file" class="form-control d-none" accept=".xlsx,.xls,.csv" required>
                        <span class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></span>
                        <strong id="filePreviewName">Pilih atau tarik file ke sini</strong>
                        <small id="filePreviewMeta">Format .xlsx, .xls, atau .csv. Maksimal 4 MB.</small>
                    </label>
                    @error('file')
                        <small class="text-danger d-block mt-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="requirement-box">
                    <strong><i class="fas fa-tasks mr-1"></i> Header wajib</strong>
                    <p>no_kk, kepala_keluarga, nik, nama, tempat_lahir, tgl_lahir, jenis_kelamin, alamat, rt, rw, agama, status_perkawinan, pekerjaan.</p>
                </div>

                @can('import-penduduk-upload')
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-upload mr-1"></i> Upload & Preview
                </button>
                @endcan
            </form>
        </div>

        <div class="import-panel guide-panel">
            <div class="panel-heading">
                <div>
                    <span>Panduan</span>
                    <h2>Validasi Sebelum Import</h2>
                </div>
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="guide-list">
                <div><i class="fas fa-id-card"></i><span>NIK dan No. KK wajib 16 digit angka.</span></div>
                <div><i class="fas fa-venus-mars"></i><span>Jenis kelamin hanya menerima nilai L atau P.</span></div>
                <div><i class="fas fa-table"></i><span>Maksimal 1.000 baris per batch preview.</span></div>
                <div><i class="fas fa-eye"></i><span>Data dipreview dulu, baru diproses jika semua baris valid.</span></div>
            </div>
        </div>
    </div>

    <div class="import-panel history-panel">
        <div class="panel-heading">
            <div>
                <span>Riwayat</span>
                <h2>Batch Import</h2>
            </div>
            <small><i class="fas fa-server mr-1"></i> Search, pagination, dan sorting memakai DataTables server-side.</small>
        </div>

        <div class="table-responsive">
            <table id="datatableImportBatch" class="table import-table table-hover nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Valid</th>
                        <th>Invalid</th>
                        <th>Diproses</th>
                        <th>Dibuat</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .import-page { color: #1f2937; }
    .import-hero {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 1.3rem;
        border-radius: 16px;
        background: linear-gradient(135deg, #0f172a, #0f766e);
        color: #ffffff;
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.16);
    }
    .import-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .import-hero p {
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
    .import-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .import-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .import-metric,
    .import-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .import-metric {
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
    .import-metric span {
        color: #6b7280;
        font-weight: 700;
    }
    .import-metric strong {
        display: block;
        color: #111827;
        font-size: 1.6rem;
        font-weight: 800;
    }
    .metric-blue { --metric-color: #2563eb; }
    .metric-cyan { --metric-color: #0891b2; }
    .metric-green { --metric-color: #059669; }
    .metric-red { --metric-color: #dc2626; }
    .import-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.8fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .import-panel { padding: 1rem; }
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
    .panel-heading small {
        color: #64748b;
        font-weight: 700;
    }
    .upload-dropzone {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 190px;
        padding: 1.25rem;
        border: 1px dashed #9ca3af;
        border-radius: 14px;
        background: #f8fafc;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s ease, background 0.2s ease, transform 0.2s ease;
    }
    .upload-dropzone:hover,
    .upload-dropzone.is-active {
        border-color: #0f766e;
        background: #ecfdf5;
        transform: translateY(-1px);
    }
    .upload-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        margin-bottom: 0.7rem;
        border-radius: 16px;
        color: #ffffff;
        background: #0f766e;
        font-size: 1.45rem;
    }
    .upload-dropzone strong {
        color: #0f172a;
        font-size: 1rem;
    }
    .upload-dropzone small {
        color: #64748b;
        font-weight: 700;
    }
    .requirement-box {
        margin-bottom: 1rem;
        padding: 0.85rem;
        border-radius: 12px;
        color: #475569;
        background: #f1f5f9;
    }
    .requirement-box strong {
        display: block;
        margin-bottom: 0.25rem;
        color: #0f172a;
    }
    .requirement-box p { margin: 0; }
    .guide-list {
        display: grid;
        gap: 0.8rem;
    }
    .guide-list div {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 12px;
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
    }
    .guide-list i {
        color: #0f766e;
        margin-top: 0.18rem;
    }
    .import-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .import-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #eef2f7;
    }
    .import-table small {
        display: block;
        color: #64748b;
    }
    .file-cell {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
    }
    .file-cell i {
        color: #0f766e;
        font-size: 1.2rem;
    }
    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        padding: 0.33rem 0.62rem;
        border-radius: 999px;
        font-weight: 800;
    }
    .status-success { color: #047857; background: #d1fae5; }
    .status-info { color: #075985; background: #e0f2fe; }
    .status-warning { color: #92400e; background: #fef3c7; }
    .status-danger { color: #991b1b; background: #fee2e2; }
    .status-secondary { color: #475569; background: #e2e8f0; }
    .count-good { color: #047857; font-weight: 800; }
    .count-bad { color: #dc2626; font-weight: 800; }
    .action-group {
        display: inline-flex;
        gap: 0.35rem;
    }
    .action-group .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        padding: 0;
    }
    .history-panel .dataTables_filter input,
    .history-panel .dataTables_length select {
        border-radius: 8px;
        border-color: #dbe3ef;
    }
    .history-panel .dataTables_length label,
    .history-panel .dataTables_filter label,
    .history-panel .dataTables_info {
        color: #475569;
        font-weight: 700;
    }
    .history-panel .pagination .page-link {
        border-radius: 8px;
        margin-left: 0.18rem;
    }
    @media (max-width: 1199.98px) {
        .import-metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .import-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 767.98px) {
        .import-hero,
        .panel-heading {
            align-items: stretch;
            flex-direction: column;
        }
        .import-hero h1 { font-size: 1.5rem; }
        .import-metric-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(function () {
        var fileInput = $('#file');
        var dropzone = $('.upload-dropzone');
        var filePreviewName = $('#filePreviewName');
        var filePreviewMeta = $('#filePreviewMeta');

        fileInput.on('change', function () {
            var file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                filePreviewName.text('Pilih atau tarik file ke sini');
                filePreviewMeta.text('Format .xlsx, .xls, atau .csv. Maksimal 4 MB.');
                dropzone.removeClass('is-active');
                return;
            }

            filePreviewName.text(file.name);
            filePreviewMeta.text((file.size / 1024 / 1024).toFixed(2) + ' MB - siap dipreview');
            dropzone.addClass('is-active');
        });

        $('#datatableImportBatch').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('import-penduduk.index') }}',
            scrollX: true,
            lengthChange: true,
            searching: true,
            paging: true,
            info: true,
            autoWidth: false,
            deferRender: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[7, 'desc']],
            columns: [
                { data: 'no', name: 'id' },
                { data: 'file_name', name: 'file_name' },
                { data: 'status', name: 'status' },
                { data: 'total_rows', name: 'total_rows' },
                { data: 'valid_rows', name: 'valid_rows' },
                { data: 'invalid_rows', name: 'invalid_rows' },
                { data: 'processed_rows', name: 'processed_rows' },
                { data: 'created_at', name: 'created_at' },
                { data: 'aksi', name: 'aksi' }
            ],
            columnDefs: [
                { orderable: false, searchable: false, targets: 8 },
                { className: 'text-right', targets: 8 }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat riwayat import...',
                search: 'Cari:',
                searchPlaceholder: 'Nama file atau status...',
                lengthMenu: 'Tampilkan _MENU_ batch',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ batch',
                infoEmpty: 'Belum ada batch import',
                infoFiltered: '(difilter dari _MAX_ total batch)',
                zeroRecords: 'Batch import tidak ditemukan',
                emptyTable: 'Belum ada riwayat import',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });
    });
</script>
@endpush
