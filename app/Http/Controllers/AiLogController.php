<?php

namespace App\Http\Controllers;

use App\Models\AiProvider;
use App\Models\AiUsageLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AiLogController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->dataTable($request);
        }

        $data = [
            'title' => 'AI Usage Logs',
            'totalLogs' => AiUsageLog::count(),
            'totalSuccess' => AiUsageLog::where('status', 'success')->count(),
            'totalError' => AiUsageLog::where('status', 'error')->count(),
            'totalTokens' => AiUsageLog::sum('total_tokens'),
            'averageLatency' => (int) AiUsageLog::whereNotNull('latency_ms')->avg('latency_ms'),
            'providers' => AiProvider::orderBy('name')->get(['id', 'name', 'provider_type']),
        ];

        return view('backend.ai_logs.index', $data);
    }

    private function dataTable(Request $request): JsonResponse
    {
        $columns = [
            0 => 'created_at',
            1 => 'feature',
            3 => 'model',
            4 => 'status',
            5 => 'total_tokens',
            6 => 'latency_ms',
        ];

        $baseQuery = AiUsageLog::query()->with(['provider:id,name,provider_type', 'user:id,name']);
        $recordsTotal = (clone $baseQuery)->count();

        $this->applyFilters($baseQuery, $request);

        $search = trim((string) $request->input('search.value'));
        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('feature', 'like', '%' . $search . '%')
                    ->orWhere('model', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('error_code', 'like', '%' . $search . '%')
                    ->orWhere('error_message', 'like', '%' . $search . '%')
                    ->orWhereHas('provider', function ($providerQuery) use ($search) {
                        $providerQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('provider_type', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'created_at';
        $orderDirection = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $length = (int) $request->input('length', 10);
        $start = max((int) $request->input('start', 0), 0);
        $length = $length > 0 ? min($length, 100) : 10;

        $rows = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(function (AiUsageLog $log) {
            $statusClass = $log->status === 'success' ? 'status-success' : 'status-danger';
            $statusIcon = $log->status === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            $providerType = $log->provider?->provider_type ? strtoupper($log->provider->provider_type) : 'UNKNOWN';
            $errorMessage = $log->error_message ? e(Str::limit($log->error_message, 180)) : 'Tidak ada error.';

            return [
                'created_at' => '<strong>' . e($log->created_at?->format('d/m/Y')) . '</strong><small>' . e($log->created_at?->format('H:i:s')) . '</small>',
                'feature' => '<strong>' . e($log->feature) . '</strong><small>User: ' . e($log->user?->name ?? 'System') . '</small>',
                'provider' => '<span class="provider-pill">' . e($log->provider?->name ?? 'Tanpa provider') . '</span><small>' . e($providerType) . '</small>',
                'model' => '<span>' . e($log->model ?? '-') . '</span><small>Prompt ' . number_format((int) $log->prompt_tokens, 0, ',', '.') . ' | Completion ' . number_format((int) $log->completion_tokens, 0, ',', '.') . '</small>',
                'status' => '<span class="status-pill ' . $statusClass . '"><i class="fas ' . $statusIcon . '"></i> ' . e(strtoupper($log->status)) . '</span><small>' . e($log->error_code ?: 'OK') . '</small>',
                'tokens' => '<span class="token-pill"><i class="fas fa-coins"></i>' . number_format((int) $log->total_tokens, 0, ',', '.') . '</span>',
                'latency' => '<span class="latency-pill"><i class="fas fa-stopwatch"></i>' . number_format((int) $log->latency_ms, 0, ',', '.') . ' ms</span>',
                'error' => '<button type="button" class="btn btn-sm btn-outline-secondary js-log-detail" title="Detail Log" data-feature="' . e($log->feature) . '" data-status="' . e($log->status) . '" data-error="' . $errorMessage . '" data-metadata="' . e(Str::limit(json_encode($log->metadata ?? [], JSON_PRETTY_PRINT), 800)) . '"><i class="fas fa-eye"></i></button>',
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('feature')) {
            $query->where('feature', 'like', '%' . trim((string) $request->input('feature')) . '%');
        }

        if ($request->filled('provider_id')) {
            $query->where('ai_provider_id', $request->input('provider_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
    }
}
