@extends('layouts.app-frontend')

@section('content')
<section class="complaint-hero">
  <div class="container">
    <div class="row align-items-center gy-10">
      <div class="col-lg-7">
        <span class="page-eyebrow"><i class="uil uil-comment-message"></i> Layanan Pengaduan {{ $villageName }}</span>
        <h1>Sampaikan laporan warga dengan jelas dan mudah ditindaklanjuti.</h1>
        <p>Gunakan formulir ini untuk mengirim keluhan, aspirasi, atau laporan kejadian. Setelah terkirim, sistem akan membuat kode tiket yang bisa dipakai untuk melacak status aduan.</p>
        <div class="hero-actions">
          <a href="{{ route('public.pengaduan.track') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-search-alt"></i> Lacak Status Aduan
          </a>
          <a href="{{ route('public.home') }}" class="btn btn-outline-primary rounded-pill">
            <i class="uil uil-estate"></i> Beranda
          </a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="hero-visual">
          <img src="{{ asset('assets/sandbox/img/illustrations/i11.png') }}" alt="Ilustrasi layanan pengaduan warga" loading="lazy">
          <div class="visual-badge">
            <i class="uil uil-ticket"></i>
            <span>Kode tiket dikirim setelah aduan berhasil masuk dan bisa digunakan untuk tracking.</span>
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
            <span>Panduan Aduan</span>
            <h2>Agar laporan cepat dipahami</h2>
          </div>
          <div class="requirement-list">
            <div><i class="uil uil-id-card"></i><span>Isi identitas sesuai KTP agar petugas bisa memverifikasi laporan.</span></div>
            <div><i class="uil uil-map-marker-info"></i><span>Tulis lokasi, waktu kejadian, dan kronologi singkat pada detail aduan.</span></div>
            <div><i class="uil uil-image-plus"></i><span>Lampirkan foto bukti bila ada. Format JPG, PNG, atau WEBP maksimal 2MB.</span></div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="complaint-panel">
          <div class="panel-heading">
            <span>Form Pengaduan</span>
            <h2>Kirim aduan warga</h2>
          </div>

          <form action="{{ route('public.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row gx-4 gy-4">
              <div class="col-md-6">
                <label for="name">Nama Lengkap</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="uil uil-user"></i></span>
                  <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" maxlength="255" required autofocus>
                </div>
                @error('name')<small class="field-error">{{ $message }}</small>@enderror
              </div>

              <div class="col-md-6">
                <label for="nik">NIK</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="uil uil-id-card"></i></span>
                  <input id="nik" type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" placeholder="Masukkan 16 digit NIK" inputmode="numeric" maxlength="16" required>
                </div>
                @error('nik')<small class="field-error">{{ $message }}</small>@enderror
              </div>

              <div class="col-md-6">
                <label for="phone">No. WhatsApp Aktif</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="uil uil-whatsapp"></i></span>
                  <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" inputmode="tel" maxlength="20" required>
                </div>
                @error('phone')<small class="field-error">{{ $message }}</small>@enderror
              </div>

              <div class="col-md-6">
                <label for="category">Kategori Aduan</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="uil uil-apps"></i></span>
                  <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                    <option disabled value="" {{ old('category') ? '' : 'selected' }}>Pilih kategori aduan</option>
                    <option value="infrastruktur" {{ old('category') === 'infrastruktur' ? 'selected' : '' }}>Infrastruktur / Jalan</option>
                    <option value="keamanan" {{ old('category') === 'keamanan' ? 'selected' : '' }}>Keamanan / Ketertiban</option>
                    <option value="pelayanan" {{ old('category') === 'pelayanan' ? 'selected' : '' }}>Pelayanan Publik</option>
                    <option value="sosial" {{ old('category') === 'sosial' ? 'selected' : '' }}>Sosial / Ekonomi</option>
                    <option value="lainnya" {{ old('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                  </select>
                </div>
                @error('category')<small class="field-error">{{ $message }}</small>@enderror
              </div>

              <div class="col-12">
                <label for="content">Detail Pengaduan / Masalah</label>
                <div class="input-group align-items-stretch">
                  <span class="input-group-text textarea-icon"><i class="uil uil-comment-alt-message"></i></span>
                  <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" placeholder="Tuliskan masalah, lokasi, waktu kejadian, dan harapan tindak lanjut" rows="6" maxlength="5000" required>{{ old('content') }}</textarea>
                </div>
                <div class="helper-row">
                  @error('content')<small class="field-error">{{ $message }}</small>@enderror
                  <small><span id="contentCount">0</span>/5000 karakter</small>
                </div>
              </div>

              <div class="col-12">
                <label for="image">Foto Bukti <span>Opsional</span></label>
                <div class="upload-box">
                  <div class="upload-control">
                    <i class="uil uil-image-upload"></i>
                    <div>
                      <strong>Pilih foto pendukung</strong>
                      <span>JPG, JPEG, PNG, atau WEBP maksimal 2MB.</span>
                    </div>
                    <input id="image" type="file" name="image" class="@error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                  </div>
                  <div id="imagePreview" class="image-preview d-none">
                    <img src="" alt="Preview foto bukti" loading="lazy">
                    <button type="button" id="clearImage" class="btn btn-sm btn-outline-danger rounded-pill">
                      <i class="uil uil-trash-alt"></i> Hapus Foto
                    </button>
                  </div>
                </div>
                @error('image')<small class="field-error">{{ $message }}</small>@enderror
              </div>

              <div class="col-12">
                <div class="privacy-note">
                  <i class="uil uil-shield-check"></i>
                  <span>Pastikan data yang dikirim benar dan dapat dipertanggungjawabkan. Petugas desa akan menindaklanjuti sesuai kategori dan prioritas laporan.</span>
                </div>
              </div>

              <div class="col-12">
                <div class="submit-row">
                  <a href="{{ route('public.pengaduan.track') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="uil uil-search-alt"></i> Lacak Aduan
                  </a>
                  <button type="submit" class="btn btn-primary rounded-pill">
                    <i class="uil uil-message"></i> Kirim Aduan
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  .complaint-hero {
    overflow: hidden;
    padding: 5rem 0 3.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 52%, #fef3c7 100%);
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
  .complaint-hero h1 {
    max-width: 780px;
    margin: 0.8rem 0 1rem;
    color: #111827;
    font-size: clamp(2.25rem, 5vw, 4.35rem);
    line-height: 1.04;
    letter-spacing: 0;
    font-weight: 800;
  }
  .complaint-hero p {
    max-width: 700px;
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
  .upload-box .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
  }
  .hero-visual {
    position: relative;
    min-height: 340px;
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
    max-width: 340px;
    padding: 1rem;
    border: 1px solid #dbeafe;
    border-radius: 16px;
    color: #334155;
    background: rgba(255, 255, 255, 0.92);
    box-shadow: 0 18px 44px rgba(15, 23, 42, 0.12);
  }
  .visual-badge i {
    color: #0f766e;
    font-size: 1.5rem;
  }
  .info-panel,
  .complaint-panel {
    padding: 1.25rem;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
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
  .complaint-panel label {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    color: #334155;
    font-weight: 800;
  }
  .complaint-panel label span {
    color: #64748b;
    font-size: 0.82rem;
    font-weight: 700;
  }
  .complaint-panel .input-group-text {
    min-width: 48px;
    justify-content: center;
    color: #0f766e;
    border-color: #dbe3ef;
    background: #f8fafc;
  }
  .complaint-panel .form-control {
    min-height: 52px;
    border-color: #dbe3ef;
    border-radius: 0.45rem;
    font-weight: 700;
  }
  .complaint-panel textarea.form-control {
    min-height: 156px;
    resize: vertical;
  }
  .textarea-icon {
    align-items: flex-start;
    padding-top: 1rem;
  }
  .field-error {
    display: block;
    margin-top: 0.35rem;
    color: #dc2626;
    font-weight: 700;
  }
  .helper-row {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 0.35rem;
    color: #64748b;
    font-weight: 700;
  }
  .upload-box {
    display: grid;
    gap: 0.9rem;
    padding: 1rem;
    border: 1px dashed #cbd5e1;
    border-radius: 16px;
    background: #f8fafc;
  }
  .upload-control {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    min-height: 86px;
    padding: 0.85rem;
    border-radius: 14px;
    background: #ffffff;
  }
  .upload-control > i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 14px;
    color: #0f766e;
    background: #ccfbf1;
    font-size: 1.55rem;
  }
  .upload-control strong,
  .upload-control span {
    display: block;
  }
  .upload-control strong {
    color: #111827;
    font-weight: 800;
  }
  .upload-control span {
    color: #64748b;
    font-weight: 700;
  }
  .upload-control input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
  }
  .image-preview {
    display: grid;
    gap: 0.75rem;
  }
  .image-preview img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
  }
  .privacy-note {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    border: 1px solid #bae6fd;
    border-radius: 14px;
    color: #075985;
    background: #e0f2fe;
    font-weight: 700;
  }
  .privacy-note i {
    font-size: 1.5rem;
  }
  .submit-row {
    justify-content: flex-end;
  }
  @media (max-width: 991.98px) {
    .visual-badge {
      position: relative;
      left: auto;
      bottom: auto;
      margin-top: 1rem;
    }
  }
  @media (max-width: 767.98px) {
    .complaint-hero {
      padding-top: 3.5rem;
    }
    .hero-actions .btn,
    .submit-row .btn {
      width: 100%;
    }
    .helper-row {
      display: block;
    }
  }
</style>
@endpush

@push('scripts')
<script>
  $(function () {
    const imageInput = $('#image');
    const preview = $('#imagePreview');
    const previewImage = preview.find('img');
    const content = $('#content');

    function updateContentCount() {
      $('#contentCount').text((content.val() || '').length);
    }

    $('#nik').on('input', function () {
      this.value = this.value.replace(/\D/g, '').slice(0, 16);
    });

    $('#phone').on('input', function () {
      this.value = this.value.replace(/[^\d+]/g, '').slice(0, 20);
    });

    content.on('input', updateContentCount);
    updateContentCount();

    imageInput.on('change', function () {
      const file = this.files && this.files[0];
      if (!file) {
        preview.addClass('d-none');
        previewImage.attr('src', '');
        return;
      }

      if (!file.type.match(/^image\/(jpeg|png|webp)$/)) {
        this.value = '';
        preview.addClass('d-none');
        previewImage.attr('src', '');
        if (window.showToast) {
          window.showToast('error', 'Format foto harus JPG, PNG, atau WEBP.');
        }
        return;
      }

      if (file.size > 2 * 1024 * 1024) {
        this.value = '';
        preview.addClass('d-none');
        previewImage.attr('src', '');
        if (window.showToast) {
          window.showToast('error', 'Ukuran foto maksimal 2MB.');
        }
        return;
      }

      previewImage.attr('src', URL.createObjectURL(file));
      preview.removeClass('d-none');
    });

    $('#clearImage').on('click', function () {
      imageInput.val('');
      preview.addClass('d-none');
      previewImage.attr('src', '');
    });
  });
</script>
@endpush
