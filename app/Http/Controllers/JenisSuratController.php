<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Services\SuratAiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JenisSuratController extends Controller
{
    // NOTE: Tampilkan daftar jenis surat
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->dataTable($request);
        }

        $data = [
            'title' => 'Jenis Surat',
            'totalJenisSurat' => JenisSurat::count(),
            'totalDenganTemplate' => JenisSurat::whereNotNull('template_isi')->where('template_isi', '!=', '')->count(),
            'totalTanpaTemplate' => JenisSurat::where(function ($query) {
                $query->whereNull('template_isi')->orWhere('template_isi', '');
            })->count(),
            'totalDigunakan' => JenisSurat::has('surats')->count(),
        ];

        return view('backend.jenis_surat.index', $data);
    }

    // NOTE: Form tambah jenis surat
    public function create()
    {
        $data = [
            'title' => 'Tambah Jenis Surat',
        ];

        return view('backend.jenis_surat.create', $data);
    }

    // NOTE: Proses simpan jenis surat
    public function store(Request $request)
    {
        $request->validate([
            'kode_surat' => 'required|unique:jenis_surats,kode_surat|max:50',
            'nama_surat' => 'required|max:255',
            'kop_judul' => 'required|max:255',
            'template_isi' => 'nullable',
        ]);

        JenisSurat::create([
            'kode_surat' => $request->kode_surat,
            'nama_surat' => $request->nama_surat,
            'kop_judul' => $request->kop_judul,
            'template_isi' => $request->template_isi ?? '',
        ]);

        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil ditambahkan.');
    }

    // NOTE: Form edit jenis surat
    public function edit(string $id)
    {
        $jenis_surat = JenisSurat::findOrFail($id);

        $data = [
            'title' => 'Edit Jenis Surat',
            'jenis_surat' => $jenis_surat,
        ];

        return view('backend.jenis_surat.edit', $data);
    }

    // NOTE: Proses update jenis surat
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_surat' => 'required|max:50|unique:jenis_surats,kode_surat,' . $id,
            'nama_surat' => 'required|max:255',
            'kop_judul' => 'required|max:255',
        ]);

        $jenis_surat = JenisSurat::findOrFail($id);
        $jenis_surat->update($request->only(['kode_surat', 'nama_surat', 'kop_judul']));

        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil diperbarui.');
    }

    public function template(string $id)
    {
        $jenis_surat = JenisSurat::findOrFail($id);
        $data = [
            'title' => 'Atur Template: ' . $jenis_surat->nama_surat,
            'jenis_surat' => $jenis_surat,
            'latestSuggestion' => $jenis_surat->aiSuggestions()->where('suggestion_type', 'template')->latest()->first(),
        ];

        return view('backend.jenis_surat.template', $data);
    }

    public function suggestTemplate(string $id, SuratAiService $service)
    {
        $jenis_surat = JenisSurat::findOrFail($id);

        try {
            $service->suggestTemplate($jenis_surat);

            return redirect()->route('jenis-surat.template', $jenis_surat->id)->with('success', 'Saran AI template berhasil dibuat.');
        } catch (\Throwable $e) {
            return redirect()->route('jenis-surat.template', $jenis_surat->id)->with('error', 'Saran AI gagal: ' . $e->getMessage());
        }
    }

    public function applyTemplateSuggestion(Request $request, string $id)
    {
        $request->validate([
            'suggested_text' => 'required|string',
        ]);

        $jenis_surat = JenisSurat::findOrFail($id);
        $jenis_surat->update(['template_isi' => $request->suggested_text]);

        return redirect()->route('jenis-surat.template', $jenis_surat->id)->with('success', 'Saran AI berhasil diterapkan ke template.');
    }

    public function exportTemplate(string $id)
    {
        $jenis_surat = JenisSurat::findOrFail($id);
        $fileName = str($jenis_surat->kode_surat . '-' . $jenis_surat->nama_surat)->slug()->toString() . '.txt';

        return response($jenis_surat->template_isi)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function importTemplate(Request $request, string $id)
    {
        $request->validate([
            'template_file' => 'required|file|mimes:txt,html|max:512',
        ]);

        $content = file_get_contents($request->file('template_file')->getRealPath());

        if (trim((string) $content) === '') {
            return redirect()->route('jenis-surat.template', $id)->with('error', 'File template kosong.');
        }

        $jenis_surat = JenisSurat::findOrFail($id);
        $jenis_surat->update(['template_isi' => $content]);

        return redirect()->route('jenis-surat.template', $jenis_surat->id)->with('success', 'Template berhasil diimport.');
    }

    public function updateTemplate(Request $request, string $id)
    {
        $request->validate([
            'template_isi' => 'required',
        ]);

        $jenis_surat = JenisSurat::findOrFail($id);
        $jenis_surat->update([
            'template_isi' => $request->template_isi
        ]);

        return redirect()->route('jenis-surat.index')->with('success', 'Template surat berhasil diperbarui.');
    }

    // NOTE: Hapus jenis surat
    public function destroy(string $id)
    {
        $jenis_surat = JenisSurat::findOrFail($id);
        
        if ($jenis_surat->surats()->exists()) {
            return redirect()->route('jenis-surat.index')->with('error', 'Gagal: Jenis surat ini masih digunakan di arsip surat.');
        }

        $jenis_surat->delete();
        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil dihapus.');
    }

    private function dataTable(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'kode_surat',
            2 => 'nama_surat',
            3 => 'kop_judul',
            4 => 'surats_count',
            5 => 'updated_at',
        ];

        $baseQuery = JenisSurat::query()->withCount('surats');
        $recordsTotal = (clone $baseQuery)->count();
        $search = trim((string) $request->input('search.value'));

        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('kode_surat', 'like', '%' . $search . '%')
                    ->orWhere('nama_surat', 'like', '%' . $search . '%')
                    ->orWhere('kop_judul', 'like', '%' . $search . '%');
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $orderColumnIndex = (int) $request->input('order.0.column', 5);
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

        $canTemplate = $request->user()?->can('jenis-surat-template') ?? false;
        $canEdit = $request->user()?->can('jenis-surat-edit') ?? false;
        $canDestroy = $request->user()?->can('jenis-surat-destroy') ?? false;

        $data = $rows->map(function (JenisSurat $row, int $index) use ($start, $canTemplate, $canEdit, $canDestroy) {
            $hasTemplate = trim((string) $row->template_isi) !== '';
            $actions = '<div class="action-group">';

            if ($canTemplate) {
                $actions .= '<a href="' . route('jenis-surat.template', $row->id) . '" class="btn btn-sm btn-info" title="Atur Template"><i class="fas fa-file-code"></i></a>';
            }

            if ($canEdit) {
                $actions .= '<a href="' . route('jenis-surat.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>';
            }

            if ($canDestroy) {
                $actions .= '<form action="' . route('jenis-surat.destroy', $row->id) . '" method="POST" class="d-inline js-confirm-submit" data-confirm-text="Yakin ingin menghapus jenis surat ' . e($row->nama_surat) . '?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>'
                    . '</form>';
            }

            $actions .= '</div>';

            return [
                'no' => $start + $index + 1,
                'kode_surat' => '<span class="code-pill"><i class="fas fa-hashtag"></i>' . e($row->kode_surat) . '</span>',
                'nama_surat' => '<strong>' . e($row->nama_surat) . '</strong><small>' . ($hasTemplate ? 'Template tersedia' : 'Template belum diatur') . '</small>',
                'kop_judul' => '<span>' . e($row->kop_judul) . '</span>',
                'template' => '<span class="status-pill status-' . ($hasTemplate ? 'success' : 'warning') . '">' . ($hasTemplate ? 'Tersedia' : 'Belum ada') . '</span>',
                'digunakan' => '<span class="usage-count">' . number_format($row->surats_count, 0, ',', '.') . '</span>',
                'updated_at' => $row->updated_at?->format('d-m-Y H:i') ?? '-',
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
