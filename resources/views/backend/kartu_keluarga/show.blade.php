@extends('layouts.app-backend')

@section('content')
<div class="kk-show-page">
    <div class="kk-show-hero">
        <div>
            <span class="eyebrow">Detail Kartu Keluarga</span>
            <h1>{{ $kartuKeluarga->no_kk }}</h1>
            <p>{{ $kartuKeluarga->kepala_keluarga }} - RT {{ $kartuKeluarga->rt }} / RW {{ $kartuKeluarga->rw }}</p>
        </div>
        <div class="hero-actions">
            @can('kartu-keluarga-edit')
            <a href="{{ route('kartu-keluarga.edit', $kartuKeluarga->id) }}" class="btn btn-light">
                <i class="fas fa-edit mr-1"></i> Edit KK
            </a>
            @endcan
            <a href="{{ route('kartu-keluarga.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="kk-profile-card">
                <div class="profile-icon"><i class="fas fa-home"></i></div>
                <span>Kepala Keluarga</span>
                <strong>{{ $kartuKeluarga->kepala_keluarga }}</strong>
                <p>{{ $kartuKeluarga->alamat }}</p>
                <div class="profile-meta">
                    <div>
                        <small>Anggota</small>
                        <b>{{ number_format($kartuKeluarga->penduduks->count(), 0, ',', '.') }}</b>
                    </div>
                    <div>
                        <small>Kode Pos</small>
                        <b>{{ $kartuKeluarga->kode_pos ?: '-' }}</b>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="kk-info-card">
                <div class="card-heading">
                    <div>
                        <span>Informasi Domisili</span>
                        <h2>Alamat dan Wilayah</h2>
                    </div>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <span>Alamat</span>
                            <strong>{{ $kartuKeluarga->alamat }}</strong>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-pin"></i>
                        <div>
                            <span>RT / RW</span>
                            <strong>{{ $kartuKeluarga->rt }} / {{ $kartuKeluarga->rw }}</strong>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-landmark"></i>
                        <div>
                            <span>Desa</span>
                            <strong>{{ $kartuKeluarga->desa ?: '-' }}</strong>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-city"></i>
                        <div>
                            <span>Kecamatan</span>
                            <strong>{{ $kartuKeluarga->kecamatan ?: '-' }}</strong>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-building"></i>
                        <div>
                            <span>Kabupaten</span>
                            <strong>{{ $kartuKeluarga->kabupaten ?: '-' }}</strong>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-globe-asia"></i>
                        <div>
                            <span>Provinsi</span>
                            <strong>{{ $kartuKeluarga->provinsi ?: '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="kk-info-card">
        <div class="card-heading">
            <div>
                <span>Anggota Keluarga</span>
                <h2>Data Penduduk Terhubung</h2>
            </div>
            @can('penduduk-create')
            <a href="{{ route('penduduk.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus mr-1"></i> Tambah Penduduk
            </a>
            @endcan
        </div>

        <div class="member-grid">
            @forelse($kartuKeluarga->penduduks as $penduduk)
            <div class="member-card js-lazy-member">
                <div class="member-avatar">
                    @if($penduduk->foto_ktp)
                    <img src="{{ asset('storage/' . $penduduk->foto_ktp) }}" alt="{{ $penduduk->nama }}" loading="lazy">
                    @else
                    <i class="fas fa-user"></i>
                    @endif
                </div>
                <div class="member-content">
                    <strong>{{ $penduduk->nama }}</strong>
                    <span>{{ $penduduk->nik }}</span>
                    <small>{{ $penduduk->shdk ?: 'SHDK belum diisi' }} - {{ $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</small>
                </div>
                <div class="member-foot">
                    <span><i class="fas fa-briefcase mr-1"></i>{{ $penduduk->pekerjaan ?: '-' }}</span>
                    @can('penduduk-edit')
                    <a href="{{ route('penduduk.edit', $penduduk->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Penduduk">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endcan
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-users-slash"></i>
                <strong>Belum ada anggota keluarga.</strong>
                <span>Hubungkan data penduduk ke KK ini melalui modul Data Penduduk.</span>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .kk-show-page { color: #1f2937; }
    .kk-show-hero {
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
    .kk-show-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .kk-show-hero p {
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }
    .eyebrow,
    .card-heading span {
        display: block;
        color: #0f766e;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .kk-show-hero .eyebrow {
        color: rgba(255, 255, 255, 0.72);
    }
    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        justify-content: flex-end;
    }
    .kk-profile-card,
    .kk-info-card {
        margin-bottom: 1rem;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    .kk-profile-card {
        min-height: calc(100% - 1rem);
        background: linear-gradient(180deg, #ffffff, #ecfdf5);
    }
    .profile-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        height: 54px;
        border-radius: 15px;
        color: #ffffff;
        background: #0f766e;
        margin-bottom: 1rem;
    }
    .kk-profile-card span,
    .profile-meta small,
    .info-item span,
    .member-content span,
    .member-content small {
        color: #64748b;
    }
    .kk-profile-card > span {
        display: block;
        font-weight: 800;
    }
    .kk-profile-card strong {
        display: block;
        font-size: 1.35rem;
        color: #111827;
    }
    .kk-profile-card p {
        margin: 0.65rem 0 1rem;
        color: #475569;
    }
    .profile-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.7rem;
    }
    .profile-meta div {
        padding: 0.8rem;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #d1fae5;
    }
    .profile-meta b {
        display: block;
        font-size: 1.1rem;
        color: #0f766e;
    }
    .card-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .card-heading h2 {
        margin: 0.12rem 0 0;
        font-size: 1.08rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
    }
    .info-item {
        display: flex;
        gap: 0.75rem;
        padding: 0.85rem;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #eef2f7;
    }
    .info-item i {
        color: #0f766e;
        margin-top: 0.25rem;
    }
    .info-item strong {
        display: block;
        color: #111827;
    }
    .member-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.85rem;
    }
    .member-card {
        opacity: 0;
        transform: translateY(8px);
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding: 0.9rem;
        border-radius: 14px;
        background: #f8fafc;
        border: 1px solid #eef2f7;
        transition: opacity 0.24s ease, transform 0.24s ease;
    }
    .member-card.is-visible,
    .member-card:not(.js-lazy-member) {
        opacity: 1;
        transform: none;
    }
    .member-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        color: #ffffff;
        background: #0f766e;
        overflow: hidden;
    }
    .member-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .member-content strong,
    .member-content span,
    .member-content small {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .member-foot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        color: #475569;
    }
    .empty-state {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.35rem;
        padding: 2rem 1rem;
        color: #64748b;
        text-align: center;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
    }
    .empty-state i {
        font-size: 2rem;
        color: #0f766e;
    }
    .empty-state strong {
        color: #111827;
    }
    @media (max-width: 1199.98px) {
        .member-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 767.98px) {
        .kk-show-hero,
        .card-heading {
            flex-direction: column;
            align-items: stretch;
        }
        .kk-show-hero h1 {
            font-size: 1.45rem;
            word-break: break-word;
        }
        .hero-actions .btn,
        .card-heading .btn {
            width: 100%;
        }
        .info-grid,
        .member-grid,
        .profile-meta {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var members = document.querySelectorAll('.js-lazy-member');

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '80px 0px' });

            members.forEach(function (member) {
                observer.observe(member);
            });
            return;
        }

        members.forEach(function (member) {
            member.classList.add('is-visible');
        });
    });
</script>
@endpush
