<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\KartuKeluarga;
use App\Jobs\SendWhatsAppMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PendudukController extends Controller
{
    // NOTE: Tampilkan daftar penduduk
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->dataTable($request);
        }

        $totalPenduduk = Penduduk::count();
        $totalKkTerhubung = Penduduk::whereNotNull('kartu_keluarga_id')->count();
        $totalLakiLaki = Penduduk::where('jenis_kelamin', 'L')->count();
        $totalPerempuan = Penduduk::where('jenis_kelamin', 'P')->count();

        $data = [
            'title' => 'Data Penduduk',
            'totalPenduduk' => $totalPenduduk,
            'totalKkTerhubung' => $totalKkTerhubung,
            'totalLakiLaki' => $totalLakiLaki,
            'totalPerempuan' => $totalPerempuan,
        ];

        return view('backend.penduduk.index', $data);
    }

    private function dataTable(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'nik',
            2 => 'nama',
            3 => 'kartu_keluarga_id',
            4 => 'jenis_kelamin',
            5 => 'tgl_lahir',
            6 => 'alamat',
            7 => 'pekerjaan',
        ];

        $baseQuery = Penduduk::query()->with('kartuKeluarga');
        $recordsTotal = (clone $baseQuery)->count();
        $search = trim((string) $request->input('search.value'));

        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('nik', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('tempat_lahir', 'like', '%' . $search . '%')
                    ->orWhere('alamat', 'like', '%' . $search . '%')
                    ->orWhere('pekerjaan', 'like', '%' . $search . '%')
                    ->orWhereHas('kartuKeluarga', function ($kkQuery) use ($search) {
                        $kkQuery->where('no_kk', 'like', '%' . $search . '%')
                            ->orWhere('kepala_keluarga', 'like', '%' . $search . '%');
                    });
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

        $canEdit = $request->user()?->can('penduduk-edit') ?? false;
        $canDestroy = $request->user()?->can('penduduk-destroy') ?? false;

        $data = $rows->map(function (Penduduk $row, int $index) use ($start, $canEdit, $canDestroy) {
            $actions = '<div class="action-group">';

            if ($canEdit) {
                $actions .= '<a href="' . route('penduduk.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>';
            }

            if ($canDestroy) {
                $actions .= '<form action="' . route('penduduk.destroy', $row->id) . '" method="POST" class="d-inline js-confirm-submit" data-confirm-text="Yakin ingin menghapus data penduduk ' . e($row->nama) . '?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>'
                    . '</form>';
            }

            $actions .= '</div>';

            return [
                'no' => $start + $index + 1,
                'nik' => '<div class="resident-id"><i class="fas fa-id-card"></i><span>' . e($row->nik) . '</span></div>',
                'nama' => '<strong>' . e($row->nama) . '</strong><small>' . e($row->phone ?: 'Nomor HP belum diisi') . '</small>',
                'no_kk' => $row->kartuKeluarga
                    ? '<span>' . e($row->kartuKeluarga->no_kk) . '</span><small>' . e($row->kartuKeluarga->kepala_keluarga) . '</small>'
                    : '<span class="text-muted">Belum ditautkan</span>',
                'jenis_kelamin' => '<span class="gender-badge gender-' . e($row->jenis_kelamin) . '">' . ($row->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan') . '</span>',
                'ttl' => '<span>' . e($row->tempat_lahir) . '</span><small>' . ($row->tgl_lahir ? e(\Carbon\Carbon::parse($row->tgl_lahir)->format('d-m-Y')) : '-') . '</small>',
                'alamat' => '<span>' . e($row->alamat) . '</span><small>RT ' . e($row->rt) . ' / RW ' . e($row->rw) . '</small>',
                'pekerjaan' => '<span>' . e($row->pekerjaan) . '</span><small>' . e($row->pendidikan ?: '-') . '</small>',
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

    // NOTE: Form tambah penduduk
    public function create()
    {
        $data = [
            'title' => 'Tambah Penduduk',
            'kartuKeluargas' => KartuKeluarga::orderBy('kepala_keluarga')->get(),
        ];
        return view('backend.penduduk.create', $data);
    }

    // NOTE: Proses simpan penduduk
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:penduduks,nik|digits:16|numeric',
            'kartu_keluarga_id' => 'nullable|exists:kartu_keluargas,id',
            'nama' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'tempat_lahir' => 'required|string|max:150',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:1000',
            'rt' => 'required|digits_between:1,3',
            'rw' => 'required|digits_between:1,3',
            'agama' => 'required|string|max:50',
            'pendidikan' => 'required|string|max:100',
            'golongan_darah' => 'nullable|string|max:3',
            'shdk' => 'required|string|max:100',
            'status_perkawinan' => 'required|string|max:100',
            'pekerjaan' => 'required|string|max:150',
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $input = $request->only([
            'kartu_keluarga_id',
            'nik',
            'nama',
            'phone',
            'tempat_lahir',
            'tgl_lahir',
            'jenis_kelamin',
            'alamat',
            'rt',
            'rw',
            'agama',
            'pendidikan',
            'golongan_darah',
            'shdk',
            'status_perkawinan',
            'pekerjaan',
        ]);

        // Upload Foto KTP
        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $filename = $file->hashName();
            $path = $file->storeAs('ktp', $filename, 'public');
            $input['foto_ktp'] = 'ktp/' . $filename;
        }

        Penduduk::create($input);

        // WhatsApp Notification
        if ($request->phone) {
            $siteName = \App\Facades\Setting::get('site_name', 'SIMADES');
            $message = "Halo {$request->nama}, data kependudukan Anda di {$siteName} telah berhasil ditambahkan.";
            SendWhatsAppMessage::dispatch($request->phone, $message);
        }

        return redirect()->route('penduduk.index')->with('success', 'Data Penduduk berhasil ditambahkan.');
    }

    // NOTE: Form edit penduduk
    public function edit(string $id)
    {
        $penduduk = Penduduk::findOrFail($id);
        $data = [
            'title' => 'Edit Penduduk',
            'penduduk' => $penduduk,
            'kartuKeluargas' => KartuKeluarga::orderBy('kepala_keluarga')->get(),
        ];
        return view('backend.penduduk.edit', $data);
    }

    // NOTE: Proses update penduduk
    public function update(Request $request, string $id)
    {
        $penduduk = Penduduk::findOrFail($id);

        $request->validate([
            'nik' => 'required|digits:16|numeric|unique:penduduks,nik,' . $id,
            'kartu_keluarga_id' => 'nullable|exists:kartu_keluargas,id',
            'nama' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'tempat_lahir' => 'required|string|max:150',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:1000',
            'rt' => 'required|digits_between:1,3',
            'rw' => 'required|digits_between:1,3',
            'agama' => 'required|string|max:50',
            'pendidikan' => 'required|string|max:100',
            'golongan_darah' => 'nullable|string|max:3',
            'shdk' => 'required|string|max:100',
            'status_perkawinan' => 'required|string|max:100',
            'pekerjaan' => 'required|string|max:150',
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $input = $request->only([
            'kartu_keluarga_id',
            'nik',
            'nama',
            'phone',
            'tempat_lahir',
            'tgl_lahir',
            'jenis_kelamin',
            'alamat',
            'rt',
            'rw',
            'agama',
            'pendidikan',
            'golongan_darah',
            'shdk',
            'status_perkawinan',
            'pekerjaan',
        ]);

        // Upload Foto KTP
        if ($request->hasFile('foto_ktp')) {
            // Hapus file lama jika ada
            if ($penduduk->foto_ktp && Storage::disk('public')->exists($penduduk->foto_ktp)) {
                Storage::disk('public')->delete($penduduk->foto_ktp);
            }

            $file = $request->file('foto_ktp');
            $filename = $file->hashName();
            $path = $file->storeAs('ktp', $filename, 'public');
            $input['foto_ktp'] = 'ktp/' . $filename;
        }

        $penduduk->update($input);

        return redirect()->route('penduduk.index')->with('success', 'Data Penduduk berhasil diperbarui.');
    }

    // NOTE: Hapus penduduk
    public function destroy(string $id)
    {
        $penduduk = Penduduk::findOrFail($id);

        if ($penduduk->surats()->exists()) {
            return redirect()->route('penduduk.index')->with('error', 'Gagal: Penduduk ini memiliki riwayat surat. Hapus riwayat surat terlebih dahulu.');
        }

        if ($penduduk->foto_ktp && Storage::disk('public')->exists($penduduk->foto_ktp)) {
            Storage::disk('public')->delete($penduduk->foto_ktp);
        }

        $penduduk->delete();
        return redirect()->route('penduduk.index')->with('success', 'Penduduk berhasil dihapus.');
    }
}
