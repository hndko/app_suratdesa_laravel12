@extends('layouts.app-frontend')

@section('content')
<section class="surat-hero">
  <div class="container">
    <div class="row align-items-center gy-10">
      <div class="col-lg-7">
        <span class="page-eyebrow"><i class="uil uil-file-plus-alt"></i> Layanan Surat Online</span>
        <h1>Ajukan surat desa tanpa antre berulang.</h1>
        <p>Isi NIK, pilih jenis surat, tulis keperluan, lalu simpan kode tracking yang diberikan sistem untuk memantau proses pengajuan.</p>
        <div class="hero-actions">
          <a href="{{ route('public.surat.track') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-search-alt"></i> Lacak Surat
          </a>
          <a href="{{ route('public.surat.verify') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-qrcode-scan"></i> Verifikasi Surat
          </a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="hero-visual">
          <img src="{{ asset('assets/sandbox/img/illustrations/i8.png') }}" srcset="{{ asset('assets/sandbox/img/illustrations/i8@2x.png') }} 2x" alt="Ilustrasi pengajuan surat online" loading="lazy">
          <div class="visual-badge">
            <i class="uil uil-whatsapp"></i>
            <span>Notifikasi status dikirim melalui WhatsApp jika nomor warga tersedia.</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="wrapper bg-light">
  <div class="container py-12 py-md-14">
    <div class="row gy-8">
      <div class="col-lg-4">
        <div class="info-panel">
          <div class="panel-heading">
            <span>Persiapan</span>
            <h2>Sebelum mengajukan</h2>
          </div>
          <div class="requirement-list">
            <div><i class="uil uil-id-card"></i><span>NIK harus sudah terdaftar pada data penduduk desa.</span></div>
            <div><i class="uil uil-file-info-alt"></i><span>Pilih jenis surat sesuai kebutuhan administrasi.</span></div>
            <div><i class="uil uil-edit"></i><span>Tulis keperluan dengan jelas agar operator mudah memproses.</span></div>
            <div><i class="uil uil-bookmark"></i><span>Simpan kode tracking setelah pengajuan berhasil dikirim.</span></div>
          </div>

          <div class="contact-box">
            <strong>{{ $villageName }}</strong>
            <span>Butuh bantuan saat mengisi formulir?</span>
            @if($contactWhatsapp)
              <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactWhatsapp) }}" target="_blank" rel="noopener">
                <i class="uil uil-whatsapp"></i> Hubungi Admin Desa
              </a>
            @else
              <small>Kontak WhatsApp desa belum diatur.</small>
            @endif
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="form-panel">
          <div class="panel-heading">
            <span>Form Pengajuan</span>
            <h2>Data permohonan surat</h2>
          </div>

          <form action="{{ route('public.surat.store') }}" method="POST">
            @csrf
            <div class="row gx-4 gy-4">
              <div class="col-md-6">
                <label for="nik">Nomor Induk Kependudukan</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="uil uil-id-card"></i></span>
                  <input id="nik" type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" placeholder="Masukkan 16 digit NIK" inputmode="numeric" maxlength="16" required>
                  @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <small>Pastikan NIK sesuai data penduduk yang terdaftar.</small>
              </div>

              <div class="col-md-6">
                <label for="jenis_surat_id">Jenis Surat</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="uil uil-file-alt"></i></span>
                  <select class="form-control @error('jenis_surat_id') is-invalid @enderror" id="jenis_surat_id" name="jenis_surat_id" required>
                    <option selected disabled value="">Pilih jenis surat</option>
                    @foreach($jenisSurats as $js)
                      <option value="{{ $js->id }}" {{ old('jenis_surat_id') == $js->id ? 'selected' : '' }}>{{ $js->nama_surat }}</option>
                    @endforeach
                  </select>
                  @error('jenis_surat_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <small>Tersedia {{ number_format($jenisSurats->count(), 0, ',', '.') }} jenis surat.</small>
              </div>

              <div class="col-12">
                <label for="keperluan">Keperluan / Alasan Pengajuan</label>
                <div class="input-group textarea-group">
                  <span class="input-group-text"><i class="uil uil-align-left"></i></span>
                  <textarea id="keperluan" name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" rows="5" placeholder="Contoh: Untuk melengkapi persyaratan administrasi pekerjaan, sekolah, bantuan, atau kebutuhan lainnya." maxlength="2000" required>{{ old('keperluan') }}</textarea>
                  @error('keperluan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <small>Gunakan kalimat singkat dan jelas. Maksimal 2000 karakter.</small>
              </div>

              <div class="col-12">
                <div class="submit-row">
                  <a href="{{ route('public.home') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="uil uil-arrow-left"></i> Kembali
                  </a>
                  <button type="submit" class="btn btn-primary rounded-pill">
                    <i class="uil uil-message"></i> Kirim Pengajuan
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="process-panel">
          <div><b>1</b><span>Pengajuan diterima</span></div>
          <div><b>2</b><span>Operator memverifikasi data</span></div>
          <div><b>3</b><span>Surat diproses sesuai alur approval</span></div>
          <div><b>4</b><span>Status bisa dilacak memakai kode tracking</span></div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  .surat-hero {
    overflow: hidden;
    padding: 5rem 0 3.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 50%, #ccfbf1 100%);
  }
  .page-eyebrow,
  .panel-heading span {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    color: #0f766e;
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
  }
  .surat-hero h1 {
    max-width: 760px;
    margin: 0.8rem 0 1rem;
    color: #111827;
    font-size: clamp(2.25rem, 5vw, 4.5rem);
    line-height: 1.03;
    letter-spacing: 0;
    font-weight: 800;
  }
  .surat-hero p {
    max-width: 680px;
    color: #475569;
    font-size: 1.16rem;
    line-height: 1.7;
  }
  .hero-actions,
  .submit-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.65rem;
    margin-top: 1.25rem;
  }
  .hero-actions .btn,
  .submit-row .btn,
  .contact-box a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
  }
  .hero-visual {
    position: relative;
    min-height: 360px;
    display: grid;
    place-items: center;
  }
  .hero-visual img {
    max-width: 100%;
    filter: drop-shadow(0 26px 36px rgba(15, 23, 42, 0.14));
  }
  .visual-badge {
    position: absolute;
    left: 0;
    bottom: 1.25rem;
    display: flex;
    gap: 0.7rem;
    max-width: 330px;
    padding: 1rem;
    border: 1px solid #dbeafe;
    border-radius: 16px;
    color: #334155;
    background: rgba(255, 255, 255, 0.92);
    box-shadow: 0 18px 44px rgba(15, 23, 42, 0.12);
  }
  .visual-badge i {
    color: #059669;
    font-size: 1.5rem;
  }
  .info-panel,
  .form-panel,
  .process-panel {
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
  }
  .info-panel,
  .form-panel {
    padding: 1.25rem;
  }
  .panel-heading {
    margin-bottom: 1rem;
  }
  .panel-heading h2 {
    margin: 0.35rem 0 0;
    color: #111827;
    font-size: 1.35rem;
    font-weight: 800;
    letter-spacing: 0;
  }
  .requirement-list {
    display: grid;
    gap: 0.75rem;
  }
  .requirement-list div {
    display: flex;
    gap: 0.65rem;
    padding: 0.85rem;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    color: #475569;
    background: #f8fafc;
    font-weight: 700;
  }
  .requirement-list i {
    color: #0f766e;
    font-size: 1.35rem;
  }
  .contact-box {
    display: grid;
    gap: 0.35rem;
    margin-top: 1rem;
    padding: 1rem;
    border: 1px solid #bae6fd;
    border-radius: 14px;
    color: #075985;
    background: #e0f2fe;
  }
  .contact-box strong {
    color: #0f172a;
    font-weight: 800;
  }
  .contact-box a {
    margin-top: 0.4rem;
    color: #0f766e;
    font-weight: 800;
  }
  .form-panel label {
    color: #334155;
    font-weight: 800;
  }
  .form-panel small {
    display: block;
    margin-top: 0.35rem;
    color: #64748b;
    font-weight: 700;
  }
  .form-panel .input-group-text {
    min-width: 48px;
    justify-content: center;
    color: #0f766e;
    border-color: #dbe3ef;
    background: #f8fafc;
  }
  .form-panel .form-control {
    min-height: 52px;
    border-color: #dbe3ef;
    border-radius: 0.45rem;
  }
  .textarea-group .input-group-text {
    align-items: flex-start;
    padding-top: 1rem;
  }
  .textarea-group textarea.form-control {
    min-height: 150px;
  }
  .submit-row {
    justify-content: flex-end;
    padding-top: 0.5rem;
  }
  .process-panel {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.75rem;
    margin-top: 1rem;
    padding: 1rem;
  }
  .process-panel div {
    display: grid;
    gap: 0.45rem;
  }
  .process-panel b {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 11px;
    color: #ffffff;
    background: #0f766e;
  }
  .process-panel span {
    color: #475569;
    font-weight: 800;
    line-height: 1.4;
  }
  @media (max-width: 991.98px) {
    .visual-badge { position: relative; left: auto; bottom: auto; margin-top: 1rem; }
    .process-panel { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  }
  @media (max-width: 767.98px) {
    .surat-hero { padding-top: 3.5rem; }
    .hero-actions .btn,
    .submit-row .btn { width: 100%; }
    .process-panel { grid-template-columns: 1fr; }
  }
</style>
@endpush

@push('scripts')
<script>
  $(function () {
    $('#nik').on('input', function () {
      this.value = this.value.replace(/\D/g, '').slice(0, 16);
    });
  });
</script>
@endpush
