<?php

namespace App\Http\Controllers;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KartuKeluargaController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->dataTable($request);
        }

        $totalKartuKeluarga = KartuKeluarga::count();
        $totalAnggota = Penduduk::whereNotNull('kartu_keluarga_id')->count();
        $kkKosong = KartuKeluarga::doesntHave('penduduks')->count();

        $data = [
            'title' => 'Data Kartu Keluarga',
            'totalKartuKeluarga' => $totalKartuKeluarga,
            'totalAnggota' => $totalAnggota,
            'kkKosong' => $kkKosong,
            'rataRataAnggota' => $totalKartuKeluarga > 0 ? round($totalAnggota / $totalKartuKeluarga, 1) : 0,
        ];

        return view('backend.kartu_keluarga.index', $data);
    }

    private function dataTable(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'no_kk',
            2 => 'kepala_keluarga',
            3 => 'alamat',
            4 => 'penduduks_count',
        ];

        $baseQuery = KartuKeluarga::query()->withCount('penduduks');
        $recordsTotal = (clone $baseQuery)->count();
        $search = trim((string) $request->input('search.value'));

        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('no_kk', 'like', '%' . $search . '%')
                    ->orWhere('kepala_keluarga', 'like', '%' . $search . '%')
                    ->orWhere('alamat', 'like', '%' . $search . '%')
                    ->orWhere('rt', 'like', '%' . $search . '%')
                    ->orWhere('rw', 'like', '%' . $search . '%')
                    ->orWhere('desa', 'like', '%' . $search . '%')
                    ->orWhere('kecamatan', 'like', '%' . $search . '%');
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $orderDirection = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $length = (int) $request->input('length', 10);
        $start = max((int) $request->input('start', 0), 0);
        $length = $length > 0 ? min($length, 100) : 10;

        $rows = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length)
            ->get();

        $canShow = $request->user()?->can('kartu-keluarga-show') ?? false;
        $canEdit = $request->user()?->can('kartu-keluarga-edit') ?? false;
        $canDestroy = $request->user()?->can('kartu-keluarga-destroy') ?? false;

        $data = $rows->map(function (KartuKeluarga $row, int $index) use ($start, $canShow, $canEdit, $canDestroy) {
            $actions = '<div class="action-group">';

            if ($canShow) {
                $actions .= '<a href="' . route('kartu-keluarga.show', $row->id) . '" class="btn btn-sm btn-info" title="Detail"><i class="fas fa-eye"></i></a>';
            }

            if ($canEdit) {
                $actions .= '<a href="' . route('kartu-keluarga.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>';
            }

            if ($canDestroy) {
                $actions .= '<form action="' . route('kartu-keluarga.destroy', $row->id) . '" method="POST" class="d-inline js-confirm-submit" data-confirm-text="Yakin ingin menghapus Kartu Keluarga ' . e($row->no_kk) . '?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>'
                    . '</form>';
            }

            $actions .= '</div>';

            return [
                'no' => $start + $index + 1,
                'no_kk' => '<div class="kk-number"><i class="fas fa-id-card"></i><span>' . e($row->no_kk) . '</span></div>',
                'kepala_keluarga' => '<strong>' . e($row->kepala_keluarga) . '</strong><small>' . e($row->desa ?: 'Desa belum diisi') . '</small>',
                'domisili' => '<span>' . e($row->alamat) . '</span><small>RT ' . e($row->rt) . ' / RW ' . e($row->rw) . ($row->kecamatan ? ' - ' . e($row->kecamatan) : '') . '</small>',
                'anggota' => '<span class="member-badge">' . number_format($row->penduduks_count, 0, ',', '.') . ' orang</span>',
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

    public function create()
    {
        $data = [
            'title' => 'Tambah Kartu Keluarga',
        ];

        return view('backend.kartu_keluarga.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_kk' => 'required|digits:16|unique:kartu_keluargas,no_kk',
            'kepala_keluarga' => 'required|string|max:255',
            'alamat' => 'required|string|max:1000',
            'rt' => 'required|digits_between:1,3',
            'rw' => 'required|digits_between:1,3',
            'desa' => 'nullable|string|max:150',
            'kecamatan' => 'nullable|string|max:150',
            'kabupaten' => 'nullable|string|max:150',
            'provinsi' => 'nullable|string|max:150',
            'kode_pos' => 'nullable|string|max:10',
        ]);

        KartuKeluarga::create($validated);

        return redirect()->route('kartu-keluarga.index')->with('success', 'Data Kartu Keluarga berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $kartuKeluarga = KartuKeluarga::with(['penduduks' => fn ($query) => $query->orderBy('shdk')->orderBy('nama')])->findOrFail($id);

        $data = [
            'title' => 'Detail Kartu Keluarga',
            'kartuKeluarga' => $kartuKeluarga,
        ];

        return view('backend.kartu_keluarga.show', $data);
    }

    public function edit(string $id)
    {
        $kartuKeluarga = KartuKeluarga::findOrFail($id);

        $data = [
            'title' => 'Edit Kartu Keluarga',
            'kartuKeluarga' => $kartuKeluarga,
        ];

        return view('backend.kartu_keluarga.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $kartuKeluarga = KartuKeluarga::findOrFail($id);

        $validated = $request->validate([
            'no_kk' => 'required|digits:16|unique:kartu_keluargas,no_kk,' . $id,
            'kepala_keluarga' => 'required|string|max:255',
            'alamat' => 'required|string|max:1000',
            'rt' => 'required|digits_between:1,3',
            'rw' => 'required|digits_between:1,3',
            'desa' => 'nullable|string|max:150',
            'kecamatan' => 'nullable|string|max:150',
            'kabupaten' => 'nullable|string|max:150',
            'provinsi' => 'nullable|string|max:150',
            'kode_pos' => 'nullable|string|max:10',
        ]);

        $kartuKeluarga->update($validated);

        return redirect()->route('kartu-keluarga.index')->with('success', 'Data Kartu Keluarga berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $kartuKeluarga = KartuKeluarga::findOrFail($id);

        if ($kartuKeluarga->penduduks()->exists()) {
            return back()->with('error', 'Gagal: Kartu Keluarga ini masih memiliki anggota.');
        }

        $kartuKeluarga->delete();

        return redirect()->route('kartu-keluarga.index')->with('success', 'Data Kartu Keluarga berhasil dihapus.');
    }
}
