<?php

namespace App\Http\Controllers;

use App\Models\AiProvider;
use App\Services\AI\AiGatewayService;
use Illuminate\Http\Request;

class AiSettingController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'AI Provider Gateway',
            'providers' => AiProvider::latest()->paginate(20),
        ];

        return view('backend.ai_settings.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah AI Provider',
            'provider' => new AiProvider(),
        ];

        return view('backend.ai_settings.form', $data);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_fallback'] = $request->boolean('is_fallback');

        $provider = AiProvider::create($data);
        $this->normalizeFlags($provider);

        return redirect()->route('ai-settings.index')->with('success', 'Provider AI berhasil dibuat.');
    }

    public function edit(AiProvider $aiSetting)
    {
        $data = [
            'title' => 'Edit AI Provider',
            'provider' => $aiSetting,
        ];

        return view('backend.ai_settings.form', $data);
    }

    public function update(Request $request, AiProvider $aiSetting)
    {
        $data = $this->validated($request, true);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_fallback'] = $request->boolean('is_fallback');

        if (!$request->filled('api_key')) {
            unset($data['api_key']);
        }

        $aiSetting->update($data);
        $this->normalizeFlags($aiSetting);

        return redirect()->route('ai-settings.index')->with('success', 'Provider AI berhasil diperbarui.');
    }

    public function destroy(AiProvider $aiSetting)
    {
        $aiSetting->delete();

        return redirect()->route('ai-settings.index')->with('success', 'Provider AI berhasil dihapus.');
    }

    public function test(AiProvider $aiSetting, AiGatewayService $aiGateway)
    {
        try {
            $aiGateway->chat([
                ['role' => 'system', 'content' => 'Balas singkat dalam bahasa Indonesia.'],
                ['role' => 'user', 'content' => 'Tes koneksi SIMADES AI Gateway.'],
            ], 'ai-provider-test', ['provider' => $aiSetting]);

            return redirect()->route('ai-settings.index')->with('success', 'Test koneksi AI berhasil.');
        } catch (\Throwable $e) {
            return redirect()->route('ai-settings.index')->with('error', 'Test koneksi AI gagal: ' . $e->getMessage());
        }
    }

    private function validated(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'provider_type' => 'required|in:openai,openrouter,deepseek,gemini,claude,custom',
            'base_url' => 'nullable|url|max:500',
            'api_key' => ($isUpdate ? 'nullable' : 'required') . '|string|max:5000',
            'model' => 'required|string|max:255',
            'temperature' => 'required|numeric|min:0|max:2',
            'max_tokens' => 'required|integer|min:64|max:8000',
            'timeout' => 'required|integer|min:5|max:120',
            'retry' => 'required|integer|min:0|max:5',
        ]);
    }

    private function normalizeFlags(AiProvider $provider): void
    {
        if ($provider->is_active) {
            AiProvider::where('id', '!=', $provider->id)->update(['is_active' => false]);
        }

        if ($provider->is_fallback) {
            AiProvider::where('id', '!=', $provider->id)->update(['is_fallback' => false]);
        }
    }
}
