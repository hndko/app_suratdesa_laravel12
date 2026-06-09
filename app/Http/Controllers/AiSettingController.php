<?php

namespace App\Http\Controllers;

use App\Models\AiProvider;
use App\Services\AI\AiGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiSettingController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->dataTable($request);
        }

        $data = [
            'title' => 'AI Provider Gateway',
            'totalProviders' => AiProvider::count(),
            'totalActive' => AiProvider::where('is_active', true)->count(),
            'totalFallback' => AiProvider::where('is_fallback', true)->count(),
            'totalTypes' => AiProvider::distinct('provider_type')->count('provider_type'),
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

    private function dataTable(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'provider_type',
            3 => 'model',
            5 => 'timeout',
            6 => 'updated_at',
        ];

        $baseQuery = AiProvider::query()->withCount('usageLogs');
        $recordsTotal = (clone $baseQuery)->count();
        $search = trim((string) $request->input('search.value'));

        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('provider_type', 'like', '%' . $search . '%')
                    ->orWhere('model', 'like', '%' . $search . '%')
                    ->orWhere('base_url', 'like', '%' . $search . '%');
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $orderColumnIndex = (int) $request->input('order.0.column', 6);
        $orderColumn = $columns[$orderColumnIndex] ?? 'updated_at';
        $orderDirection = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $length = (int) $request->input('length', 10);
        $start = max((int) $request->input('start', 0), 0);
        $length = $length > 0 ? min($length, 100) : 10;

        $rows = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length)
            ->get();

        $canTest = $request->user()?->can('ai-setting-test') ?? false;
        $canEdit = $request->user()?->can('ai-setting-edit') ?? false;
        $canDestroy = $request->user()?->can('ai-setting-destroy') ?? false;

        $data = $rows->map(function (AiProvider $provider, int $index) use ($start, $canTest, $canEdit, $canDestroy) {
            $actions = '<div class="action-group">';

            if ($canTest) {
                $actions .= '<form action="' . route('ai-settings.test', $provider) . '" method="POST" class="d-inline">'
                    . csrf_field()
                    . '<button type="submit" class="btn btn-sm btn-info" title="Test Koneksi"><i class="fas fa-vial"></i></button>'
                    . '</form>';
            }

            if ($canEdit) {
                $actions .= '<a href="' . route('ai-settings.edit', $provider) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>';
            }

            if ($canDestroy) {
                $actions .= '<form action="' . route('ai-settings.destroy', $provider) . '" method="POST" class="d-inline js-ai-provider-confirm" data-confirm-text="Yakin ingin menghapus provider AI ' . e($provider->name) . '?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>'
                    . '</form>';
            }

            $actions .= '</div>';

            $status = '';
            if ($provider->is_active) {
                $status .= '<span class="status-pill status-success"><i class="fas fa-check-circle"></i> Aktif</span>';
            }
            if ($provider->is_fallback) {
                $status .= '<span class="status-pill status-info"><i class="fas fa-life-ring"></i> Fallback</span>';
            }
            if ($status === '') {
                $status = '<span class="status-pill status-secondary">Standby</span>';
            }

            return [
                'no' => $start + $index + 1,
                'name' => '<strong>' . e($provider->name) . '</strong><small>' . e($provider->base_url ?: 'Default endpoint') . '</small>',
                'provider_type' => '<span class="provider-pill provider-' . e($provider->provider_type) . '">' . e(strtoupper($provider->provider_type)) . '</span>',
                'model' => '<span>' . e($provider->model) . '</span><small>' . number_format((float) $provider->temperature, 2) . ' temp | ' . number_format($provider->max_tokens, 0, ',', '.') . ' tokens</small>',
                'status' => '<div class="status-stack">' . $status . '</div>',
                'runtime' => '<span class="runtime-pill"><i class="fas fa-clock"></i>' . e($provider->timeout) . 's</span><small>Retry ' . e($provider->retry) . 'x | Log ' . number_format($provider->usage_logs_count, 0, ',', '.') . '</small>',
                'updated_at' => $provider->updated_at?->format('d-m-Y H:i') ?? '-',
                'aksi' => $actions,
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
