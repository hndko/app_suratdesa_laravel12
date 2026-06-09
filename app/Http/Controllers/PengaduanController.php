<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Jobs\SendWhatsAppMessage;
use App\Services\PengaduanAiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->dataTable($request);
        }

        $data = [
            'title' => 'Pengaduan Warga',
            'totalPengaduan' => Pengaduan::count(),
            'totalPending' => Pengaduan::where('status', 'pending')->count(),
            'totalProcess' => Pengaduan::where('status', 'process')->count(),
            'totalResolved' => Pengaduan::where('status', 'resolved')->count(),
        ];

        return view('backend.pengaduan.index', $data);
    }

    public function edit(Pengaduan $pengaduan)
    {
        $data = [
            'title' => 'Tanggapi Pengaduan',
            'pengaduan' => $pengaduan->load('latestAiSuggestion'),
        ];
        return view('backend.pengaduan.edit', $data);
    }

    public function analyze(Pengaduan $pengaduan, PengaduanAiService $service)
    {
        try {
            $service->analyze($pengaduan);

            return redirect()->route('pengaduan.edit', $pengaduan)->with('success', 'Analisis AI pengaduan berhasil dibuat.');
        } catch (\Throwable $e) {
            return redirect()->route('pengaduan.edit', $pengaduan)->with('error', 'Analisis AI gagal: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status' => 'required|in:pending,process,resolved',
            'reply' => 'nullable|string',
        ]);

        $input = $request->only(['status', 'reply']);

        if ($request->filled('reply')) {
            $input['replied_by'] = auth()->id();
            $input['replied_at'] = now();
        }

        $pengaduan->update($input);

        // WhatsApp Notification
        if ($pengaduan->phone) {
            $statusText = $pengaduan->status === 'resolved' ? 'SELESAI' : 'DIPROSES';
            $message = "Halo {$pengaduan->name}, pengaduan Anda (#{$pengaduan->ticket_code}) saat ini berstatus: {$statusText}.";
            
            if ($request->filled('reply')) {
                $message .= "\n\nTanggapan Admin: " . $request->reply;
            }
            
            $message .= "\n\nTerima kasih atas laporan Anda.";
            
            SendWhatsAppMessage::dispatch($pengaduan->phone, $message);
        }

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil ditanggapi.');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        if ($pengaduan->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($pengaduan->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($pengaduan->image);
        }
        $pengaduan->delete();

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dihapus.');
    }

    private function dataTable(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'ticket_code',
            2 => 'name',
            3 => 'category',
            4 => 'status',
            5 => 'created_at',
        ];

        $baseQuery = Pengaduan::query()->with('latestAiSuggestion');
        $recordsTotal = (clone $baseQuery)->count();
        $search = trim((string) $request->input('search.value'));

        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('ticket_code', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $orderColumnIndex = (int) $request->input('order.0.column', 5);
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

        $canEdit = $request->user()?->can('pengaduan-edit') ?? false;
        $canDestroy = $request->user()?->can('pengaduan-destroy') ?? false;

        $data = $rows->map(function (Pengaduan $row, int $index) use ($start, $canEdit, $canDestroy) {
            $statusMeta = $this->statusMeta((string) $row->status);
            $actions = '<div class="action-group">';

            if ($canEdit) {
                $actions .= '<a href="' . route('pengaduan.edit', $row->id) . '" class="btn btn-sm btn-info" title="Tanggapi"><i class="fas fa-reply"></i></a>';
            }

            if ($canDestroy) {
                $actions .= '<form action="' . route('pengaduan.destroy', $row->id) . '" method="POST" class="d-inline js-pengaduan-confirm" data-confirm-text="Yakin ingin menghapus pengaduan ' . e($row->ticket_code) . '?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>'
                    . '</form>';
            }

            $actions .= '</div>';

            return [
                'no' => $start + $index + 1,
                'ticket_code' => '<span class="ticket-pill"><i class="fas fa-ticket-alt"></i>' . e($row->ticket_code) . '</span>',
                'pelapor' => '<strong>' . e($row->name) . '</strong><small>' . e($row->nik) . ' | ' . e($row->phone ?: 'Nomor HP belum diisi') . '</small>',
                'category' => '<span class="category-pill"><i class="fas fa-tag"></i>' . e($row->category ?: 'Tidak berkategori') . '</span>',
                'status' => '<span class="status-pill status-' . $statusMeta['class'] . '">' . e($statusMeta['label']) . '</span>' . ($row->latestAiSuggestion ? '<span class="ai-pill"><i class="fas fa-robot"></i> AI</span>' : ''),
                'created_at' => $row->created_at?->format('d-m-Y H:i') ?? '-',
                'content' => '<span class="complaint-text">' . e(str($row->content)->limit(100)) . '</span>',
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

    private function statusMeta(string $status): array
    {
        return match ($status) {
            'pending' => ['label' => 'Pending', 'class' => 'danger'],
            'process' => ['label' => 'Diproses', 'class' => 'warning'],
            'resolved' => ['label' => 'Selesai', 'class' => 'success'],
            default => ['label' => ucfirst($status), 'class' => 'secondary'],
        };
    }
}
