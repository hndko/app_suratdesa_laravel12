@extends('layouts.app-backend')

@section('content')
@php
    $isEdit = $provider->exists;
    $providerTypes = [
        'openai' => ['label' => 'OpenAI', 'hint' => 'Provider resmi OpenAI atau endpoint compatible.'],
        'openrouter' => ['label' => 'OpenRouter', 'hint' => 'Gateway multi model berbasis OpenAI-compatible.'],
        'deepseek' => ['label' => 'DeepSeek', 'hint' => 'Model DeepSeek dengan endpoint compatible.'],
        'gemini' => ['label' => 'Gemini', 'hint' => 'Provider Google Gemini memakai adapter khusus.'],
        'claude' => ['label' => 'Claude', 'hint' => 'Provider Anthropic Claude memakai adapter khusus.'],
        'custom' => ['label' => 'Custom OpenAI Compatible', 'hint' => 'Endpoint custom dengan base URL dan API key sendiri.'],
    ];
@endphp

<div class="ai-provider-form-page">
    <div class="ai-provider-form-hero">
        <div>
            <span class="eyebrow">AI Gateway</span>
            <h1>{{ $title }}</h1>
            <p>{{ $isEdit ? 'Perbarui konfigurasi provider AI tanpa membuka ulang API key yang sudah terenkripsi.' : 'Tambahkan provider AI untuk analisis pengaduan, surat, assistant internal, dan fitur AI SIMADES lainnya.' }}</p>
        </div>
        <a href="{{ route('ai-settings.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ $isEdit ? route('ai-settings.update', $provider) : route('ai-settings.store') }}" method="POST" autocomplete="off">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-7">
                <div class="ai-form-panel">
                    <div class="panel-heading">
                        <div>
                            <span>Identitas</span>
                            <h2>Provider & Akses</h2>
                        </div>
                        <i class="fas fa-key"></i>
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Provider</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            </div>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $provider->name) }}" placeholder="Contoh: OpenRouter Production" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="provider_type">Tipe Provider</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                            </div>
                            <select id="provider_type" name="provider_type" class="form-control @error('provider_type') is-invalid @enderror" required>
                                @foreach($providerTypes as $value => $meta)
                                    <option value="{{ $value }}" data-hint="{{ $meta['hint'] }}" {{ old('provider_type', $provider->provider_type) === $value ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                            @error('provider_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <small class="form-text text-muted" id="providerTypeHint">{{ $providerTypes[old('provider_type', $provider->provider_type ?: 'openai')]['hint'] ?? 'Pilih provider sesuai adapter yang digunakan.' }}</small>
                    </div>

                    <div class="form-group">
                        <label for="base_url">Base URL</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-link"></i></span>
                            </div>
                            <input type="url" id="base_url" name="base_url" class="form-control @error('base_url') is-invalid @enderror" value="{{ old('base_url', $provider->base_url) }}" placeholder="Opsional, contoh: https://openrouter.ai/api/v1">
                            @error('base_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <small class="form-text text-muted">Kosongkan jika provider memakai endpoint default dari adapter SIMADES.</small>
                    </div>

                    <div class="form-group mb-0">
                        <label for="api_key">API Key</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" id="api_key" name="api_key" class="form-control @error('api_key') is-invalid @enderror" value="" placeholder="{{ $isEdit ? 'Kosongkan jika tidak ingin mengganti API key' : 'Masukkan API key provider' }}" {{ $isEdit ? '' : 'required' }}>
                            @error('api_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <small class="form-text text-muted">API key disimpan terenkripsi dan tidak ditampilkan kembali setelah tersimpan.</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="ai-form-panel">
                    <div class="panel-heading">
                        <div>
                            <span>Runtime</span>
                            <h2>Model & Reliability</h2>
                        </div>
                        <i class="fas fa-sliders-h"></i>
                    </div>

                    <div class="form-group">
                        <label for="model">Model</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-microchip"></i></span>
                            </div>
                            <input type="text" id="model" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model', $provider->model) }}" placeholder="Contoh: gpt-4o-mini, deepseek-chat, gemini-1.5-flash" required>
                            @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="temperature">Temperature</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-thermometer-half"></i></span>
                                    </div>
                                    <input type="number" id="temperature" step="0.01" min="0" max="2" name="temperature" class="form-control @error('temperature') is-invalid @enderror" value="{{ old('temperature', $provider->temperature ?? 0.3) }}" placeholder="0.30" required>
                                    @error('temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="max_tokens">Max Tokens</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                    </div>
                                    <input type="number" id="max_tokens" min="64" max="8000" name="max_tokens" class="form-control @error('max_tokens') is-invalid @enderror" value="{{ old('max_tokens', $provider->max_tokens ?? 800) }}" placeholder="800" required>
                                    @error('max_tokens')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="timeout">Timeout</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                    </div>
                                    <input type="number" id="timeout" min="5" max="120" name="timeout" class="form-control @error('timeout') is-invalid @enderror" value="{{ old('timeout', $provider->timeout ?? 20) }}" placeholder="20" required>
                                    <div class="input-group-append"><span class="input-group-text">detik</span></div>
                                    @error('timeout')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="retry">Retry</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-redo-alt"></i></span>
                                    </div>
                                    <input type="number" id="retry" min="0" max="5" name="retry" class="form-control @error('retry') is-invalid @enderror" value="{{ old('retry', $provider->retry ?? 1) }}" placeholder="1" required>
                                    <div class="input-group-append"><span class="input-group-text">kali</span></div>
                                    @error('retry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ai-toggle-list">
                        <label class="ai-toggle-card" for="is_active">
                            <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $provider->is_active) ? 'checked' : '' }}>
                            <span><i class="fas fa-check-circle"></i></span>
                            <div>
                                <strong>Provider aktif utama</strong>
                                <small>Dipakai sebagai provider default untuk request AI.</small>
                            </div>
                        </label>
                        <label class="ai-toggle-card" for="is_fallback">
                            <input type="checkbox" name="is_fallback" value="1" id="is_fallback" {{ old('is_fallback', $provider->is_fallback) ? 'checked' : '' }}>
                            <span><i class="fas fa-life-ring"></i></span>
                            <div>
                                <strong>Provider fallback</strong>
                                <small>Dicoba saat provider utama gagal atau timeout.</small>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="ai-form-actions">
                    <a href="{{ route('ai-settings.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Provider' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .ai-provider-form-page { color: #1f2937; }
    .ai-provider-form-hero {
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
    .ai-provider-form-hero h1 {
        margin: 0.2rem 0;
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: 0;
    }
    .ai-provider-form-hero p {
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
    .ai-provider-form-hero .eyebrow { color: rgba(255, 255, 255, 0.72); }
    .ai-form-panel {
        min-height: calc(100% - 74px);
        margin-bottom: 1rem;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
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
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        color: #0f766e;
        background: #ccfbf1;
    }
    .ai-form-panel label {
        color: #374151;
        font-weight: 800;
    }
    .ai-form-panel .form-control,
    .ai-form-panel .input-group-text {
        border-color: #dbe3ef;
    }
    .ai-form-panel .input-group-text {
        color: #0f766e;
        background: #f8fafc;
        font-weight: 800;
    }
    .ai-form-panel .form-control {
        min-height: 42px;
        border-radius: 8px;
    }
    .ai-form-panel .input-group > .form-control:not(:first-child) { border-top-left-radius: 0; border-bottom-left-radius: 0; }
    .ai-form-panel .input-group > .form-control:not(:last-child) { border-top-right-radius: 0; border-bottom-right-radius: 0; }
    .ai-toggle-list {
        display: grid;
        gap: 0.75rem;
        margin-top: 0.25rem;
    }
    .ai-toggle-card {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin: 0;
        padding: 0.9rem;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #f8fafc;
        cursor: pointer;
        transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
    }
    .ai-toggle-card:hover {
        border-color: #99f6e4;
        box-shadow: 0 12px 26px rgba(15, 118, 110, 0.11);
        transform: translateY(-1px);
    }
    .ai-toggle-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .ai-toggle-card > span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        color: #64748b;
        background: #e2e8f0;
    }
    .ai-toggle-card input:checked + span {
        color: #ffffff;
        background: #0f766e;
    }
    .ai-toggle-card strong {
        display: block;
        color: #111827;
        font-weight: 800;
    }
    .ai-toggle-card small {
        display: block;
        color: #64748b;
        font-weight: 600;
    }
    .ai-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.55rem;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
    }
    @media (max-width: 767.98px) {
        .ai-provider-form-hero {
            align-items: stretch;
            flex-direction: column;
        }
        .ai-provider-form-hero h1 { font-size: 1.5rem; }
        .ai-provider-form-hero .btn,
        .ai-form-actions .btn { width: 100%; }
        .ai-form-actions { flex-direction: column-reverse; }
    }
</style>
@endpush

@push('scripts')
<script>
    $(function () {
        var $providerType = $('#provider_type');
        var $providerHint = $('#providerTypeHint');

        $providerType.on('change', function () {
            var hint = $(this).find(':selected').data('hint') || 'Pilih provider sesuai adapter yang digunakan.';
            $providerHint.text(hint);
        });
    });
</script>
@endpush
