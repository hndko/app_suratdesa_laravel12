<?php

namespace App\Http\Controllers;

use App\Models\KartuKeluarga;
use Illuminate\Http\Request;

class KartuKeluargaController extends Controller
{
    public function index(Request $request)
    {
        $query = KartuKeluarga::withCount('penduduks')->latest();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($builder) use ($keyword) {
                $builder->where('no_kk', 'like', '%' . $keyword . '%')
                    ->orWhere('kepala_keluarga', 'like', '%' . $keyword . '%');
            });
        }

        $data = [
            'title' => 'Data Kartu Keluarga',
            'kartuKeluargas' => $query->paginate(25)->withQueryString(),
            'q' => $request->q,
        ];

        return view('backend.kartu_keluarga.index', $data);
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
        $kartuKeluarga = KartuKeluarga::with('penduduks')->findOrFail($id);

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
