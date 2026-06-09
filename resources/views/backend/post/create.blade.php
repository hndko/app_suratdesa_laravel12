@extends('layouts.app-backend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/dist/css/simades-post-form.css') }}">
@endpush

@section('content')
<div class="post-form-page">
    <div class="post-form-hero">
        <div>
            <span class="eyebrow">Informasi Desa</span>
            <h1>{{ $title }}</h1>
            <p>Buat pengumuman baru untuk warga dengan judul yang jelas, konten ringkas, status publikasi, dan gambar pendukung.</p>
        </div>
        <a href="{{ route('post.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data" class="post-form-grid">
        @csrf
        <div class="post-form-panel">
            <div class="panel-heading">
                <div>
                    <span>Form Data</span>
                    <h2>Konten Pengumuman</h2>
                </div>
                <i class="fas fa-bullhorn"></i>
            </div>

            <div class="form-group">
                <label for="title">Judul Pengumuman <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-heading"></i></span></div>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Jadwal Pelayanan Desa Minggu Ini" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="summernote">Konten / Isi Pengumuman <span class="text-danger">*</span></label>
                <textarea name="content" id="summernote" class="form-control @error('content') is-invalid @enderror" rows="10" placeholder="Tulis konten pengumuman di sini..." required>{{ old('content') }}</textarea>
                @error('content')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('post.index') }}" class="btn btn-light">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan Pengumuman
                </button>
            </div>
        </div>

        <div class="post-side-panel">
            <div class="panel-heading">
                <div>
                    <span>Publikasi</span>
                    <h2>Gambar & Status</h2>
                </div>
                <i class="fas fa-image"></i>
            </div>

            <div class="form-group">
                <label for="image">Gambar Unggulan</label>
                <label class="upload-preview" for="image">
                    <input type="file" id="image" name="image" class="form-control d-none @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                    <img id="imagePreview" src="{{ asset('assets/dist/img/avatar5.png') }}" alt="Preview gambar" loading="lazy">
                    <span><i class="fas fa-camera mr-1"></i> Pilih gambar</span>
                    <small id="imageMeta">JPG, PNG, atau WEBP. Maksimal 2 MB.</small>
                </label>
                @error('image')<small class="text-danger d-block mt-2">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-toggle-on"></i></span></div>
                    <select id="status" name="status" class="form-control" required>
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            <div class="guide-box">
                <i class="fas fa-info-circle"></i>
                <span>Gunakan status Draft jika pengumuman belum siap tampil di halaman frontend.</span>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
    $(function () {
        $('#summernote').summernote({
            height: 320,
            placeholder: 'Tulis konten pengumuman di sini...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });

        $('#image').on('change', function () {
            var file = this.files && this.files[0] ? this.files[0] : null;
            var preview = $('#imagePreview');
            var meta = $('#imageMeta');
            var wrap = $('.upload-preview');

            if (!file) {
                wrap.removeClass('is-active');
                return;
            }

            var reader = new FileReader();
            reader.onload = function (event) {
                preview.attr('src', event.target.result);
            };
            reader.readAsDataURL(file);

            meta.text(file.name + ' - ' + (file.size / 1024 / 1024).toFixed(2) + ' MB');
            wrap.addClass('is-active');
        });
    });
</script>
@endpush
