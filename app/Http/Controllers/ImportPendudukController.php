<?php

namespace App\Http\Controllers;

use App\Models\ImportBatch;
use App\Services\PendudukImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImportPendudukController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->batchDataTable($request);
        }

        $data = [
            'title' => 'Import Penduduk & KK',
            'totalBatch' => ImportBatch::where('type', 'penduduk_kk')->count(),
            'totalPreview' => ImportBatch::where('type', 'penduduk_kk')->where('status', 'preview')->count(),
            'totalProcessed' => ImportBatch::where('type', 'penduduk_kk')->where('status', 'processed')->count(),
            'totalInvalidRows' => ImportBatch::where('type', 'penduduk_kk')->sum('invalid_rows'),
        ];

        return view('backend.import_penduduk.index', $data);
    }

    public function upload(Request $request, PendudukImportService $service)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:4096',
        ]);

        $batch = $service->preview($request->file('file'));

        return redirect()->route('import-penduduk.preview', $batch)->with('success', 'Preview import berhasil dibuat.');
    }

    public function preview(Request $request, ImportBatch $batch): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->rowDataTable($request, $batch);
        }

        $data = [
            'title' => 'Preview Import Penduduk & KK',
            'batch' => $batch,
        ];

        return view('backend.import_penduduk.preview', $data);
    }

    public function process(ImportBatch $batch, PendudukImportService $service)
    {
        if ($batch->invalid_rows > 0) {
            return redirect()->route('import-penduduk.preview', $batch)->with('error', 'Masih ada baris invalid. Perbaiki file lalu upload ulang.');
        }

        $service->process($batch);

        return redirect()->route('import-penduduk.index')->with('success', 'Import penduduk dan KK berhasil diproses.');
    }

    private function batchDataTable(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'file_name',
            2 => 'status',
            3 => 'total_rows',
            4 => 'valid_rows',
            5 => 'invalid_rows',
            6 => 'processed_rows',
            7 => 'created_at',
        ];

        $baseQuery = ImportBatch::query()->where('type', 'penduduk_kk');
        $recordsTotal = (clone $baseQuery)->count();
        $search = trim((string) $request->input('search.value'));

        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('file_name', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $orderColumnIndex = (int) $request->input('order.0.column', 7);
        $orderColumn = $columns[$orderColumnIndex] ?? 'created_at';
        $orderDirection = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $length = (int) $request->input('length', 10);
        $start = max((int) $request->input('start', 0), 0);
        $length = $length > 0 ? min($length, 100) : 10;
        $canPreview = $request->user()?->can('import-penduduk-preview') ?? false;

        $rows = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(function (ImportBatch $batch, int $index) use ($start, $canPreview) {
            $statusClass = match ($batch->status) {
                'processed' => 'success',
                'preview' => $batch->invalid_rows > 0 ? 'warning' : 'info',
                default => 'secondary',
            };

            $actions = '<div class="action-group">';
            if ($canPreview) {
                $actions .= '<a href="' . route('import-penduduk.preview', $batch) . '" class="btn btn-sm btn-info" title="Lihat Preview"><i class="fas fa-eye"></i></a>';
            }
            $actions .= '</div>';

            return [
                'no' => $start + $index + 1,
                'file_name' => '<div class="file-cell"><i class="fas fa-file-excel"></i><div><strong>' . e($batch->file_name) . '</strong><small>Dibuat ' . e($batch->created_at?->format('d-m-Y H:i')) . '</small></div></div>',
                'status' => '<span class="status-pill status-' . $statusClass . '">' . e(ucfirst($batch->status)) . '</span>',
                'total_rows' => number_format($batch->total_rows, 0, ',', '.'),
                'valid_rows' => '<span class="count-good">' . number_format($batch->valid_rows, 0, ',', '.') . '</span>',
                'invalid_rows' => '<span class="' . ($batch->invalid_rows > 0 ? 'count-bad' : 'count-good') . '">' . number_format($batch->invalid_rows, 0, ',', '.') . '</span>',
                'processed_rows' => number_format($batch->processed_rows, 0, ',', '.'),
                'created_at' => $batch->created_at?->format('d-m-Y H:i') ?? '-',
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

    private function rowDataTable(Request $request, ImportBatch $batch): JsonResponse
    {
        $columns = [
            0 => 'row_number',
            1 => 'row_number',
            4 => 'status',
        ];

        $baseQuery = $batch->rows()->getQuery();
        $recordsTotal = (clone $baseQuery)->count();
        $search = trim((string) $request->input('search.value'));

        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('row_number', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('payload->nik', 'like', '%' . $search . '%')
                    ->orWhere('payload->nama', 'like', '%' . $search . '%')
                    ->orWhere('payload->no_kk', 'like', '%' . $search . '%');
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'row_number';
        $orderDirection = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $length = (int) $request->input('length', 10);
        $start = max((int) $request->input('start', 0), 0);
        $length = $length > 0 ? min($length, 100) : 10;

        $rows = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(function ($row, int $index) use ($start) {
            $payload = $row->payload ?? [];
            $errors = $row->errors ?: [];
            $statusClass = match ($row->status) {
                'processed' => 'success',
                'valid' => 'info',
                'invalid' => 'danger',
                default => 'secondary',
            };

            return [
                'no' => $start + $index + 1,
                'row_number' => '<strong>Baris ' . e($row->row_number) . '</strong>',
                'nik' => '<span>' . e($payload['nik'] ?? '-') . '</span><small>' . e($payload['nama'] ?? '-') . '</small>',
                'no_kk' => '<span>' . e($payload['no_kk'] ?? '-') . '</span><small>' . e($payload['kepala_keluarga'] ?? 'Kepala keluarga belum diisi') . '</small>',
                'status' => '<span class="status-pill status-' . $statusClass . '">' . e(ucfirst($row->status)) . '</span>',
                'error' => $errors
                    ? '<div class="error-list">' . collect($errors)->map(fn ($error) => '<span><i class="fas fa-exclamation-circle"></i>' . e($error) . '</span>')->join('') . '</div>'
                    : '<span class="text-muted">Tidak ada error</span>',
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
