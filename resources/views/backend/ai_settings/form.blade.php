@extends('layouts.app-backend')

@section('content')
<div class="content-header ps-0 pe-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">{{ $title }}</h1></div>
        </div>
    </div>
</div>

<form action="{{ $provider->exists ? route('ai-settings.update', $provider) : route('ai-settings.store') }}" method="POST">
    @csrf
    @if($provider->exists)
    @method('PUT')
    @endif
    <div class="card card-outline card-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Provider</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $provider->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Tipe Provider</label>
                        <select name="provider_type" class="form-control" required>
                            @foreach(['openai' => 'OpenAI', 'openrouter' => 'OpenRouter', 'deepseek' => 'DeepSeek', 'gemini' => 'Gemini', 'claude' => 'Claude', 'custom' => 'Custom OpenAI Compatible'] as $value => $label)
                            <option value="{{ $value }}" {{ old('provider_type', $provider->provider_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Base URL</label>
                        <input type="url" name="base_url" class="form-control" value="{{ old('base_url', $provider->base_url) }}" placeholder="Opsional untuk provider default">
                    </div>
                    <div class="form-group">
                        <label>API Key</label>
                        <input type="password" name="api_key" class="form-control" value="" {{ $provider->exists ? '' : 'required' }}>
                        @if($provider->exists)<small class="text-muted">Kosongkan jika tidak ingin mengganti API key.</small>@endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Model</label>
                        <input type="text" name="model" class="form-control" value="{{ old('model', $provider->model) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Temperature</label>
                                <input type="number" step="0.01" min="0" max="2" name="temperature" class="form-control" value="{{ old('temperature', $provider->temperature ?? 0.3) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Max Tokens</label>
                                <input type="number" name="max_tokens" class="form-control" value="{{ old('max_tokens', $provider->max_tokens ?? 800) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Timeout</label>
                                <input type="number" name="timeout" class="form-control" value="{{ old('timeout', $provider->timeout ?? 20) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Retry</label>
                                <input type="number" name="retry" class="form-control" value="{{ old('retry', $provider->retry ?? 1) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $provider->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Provider aktif utama</label>
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" name="is_fallback" value="1" class="form-check-input" id="is_fallback" {{ old('is_fallback', $provider->is_fallback) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_fallback">Provider fallback</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('ai-settings.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
    </div>
</form>
@endsection
