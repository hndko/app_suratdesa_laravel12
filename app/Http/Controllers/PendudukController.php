<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendudukController extends Controller
{
    // NOTE: Tampilkan daftar penduduk
    public function index()
    {
        $data = [
            'title' => 'Data Penduduk',
            'penduduks' => Penduduk::latest()->get(),
        ];

        return view('backend.penduduk.index', $data);
    }

    // NOTE: Form tambah penduduk
    public function create()
    {
        $data = [
            'title' => 'Tambah Penduduk',
        ];
        return view('backend.penduduk.create', $data);
    }

    // NOTE: Proses simpan penduduk
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:penduduks,nik|digits:16|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'agama' => 'required',
            'status_perkawinan' => 'required',
            'pekerjaan' => 'required',
            'foto_ktp' => 'nullable|image|max:2048',
        ]);

        $input = $request->all();

        // Upload Foto KTP
        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/ktp', $filename);
            $input['foto_ktp'] = 'ktp/' . $filename;
        }

        Penduduk::create($input);

        return redirect()->route('penduduk.index')->with('success', 'Data Penduduk berhasil ditambahkan.');
    }

    // NOTE: Form edit penduduk
    public function edit($id)
    {
        $penduduk = Penduduk::findOrFail($id);
        $data = [
            'title' => 'Edit Penduduk',
            'penduduk' => $penduduk,
        ];
        return view('backend.penduduk.edit', $data);
    }

    // NOTE: Proses update penduduk
    public function update(Request $request, $id)
    {
        $penduduk = Penduduk::findOrFail($id);

        $request->validate([
            'nik' => 'required|digits:16|numeric|unique:penduduks,nik,' . $id,
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'agama' => 'required',
            'status_perkawinan' => 'required',
            'pekerjaan' => 'required',
            'foto_ktp' => 'nullable|image|max:2048',
        ]);

        $input = $request->all();

        // Upload Foto KTP
        if ($request->hasFile('foto_ktp')) {
            // Hapus file lama jika ada
            if ($penduduk->foto_ktp && Storage::disk('public')->exists($penduduk->foto_ktp)) {
                Storage::start('public')->delete($penduduk->foto_ktp);
            }

            $file = $request->file('foto_ktp');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/ktp', $filename);
            $input['foto_ktp'] = 'ktp/' . $filename;
        } else {
            // Keep old photo
            unset($input['foto_ktp']);
        }

        $penduduk->update($input);

        return redirect()->route('penduduk.index')->with('success', 'Data Penduduk berhasil diperbarui.');
    }

    // NOTE: Hapus penduduk
    public function destroy($id)
    {
        $penduduk = Penduduk::findOrFail($id);

        if ($penduduk->foto_ktp && Storage::disk('public')->exists($penduduk->foto_ktp)) {
            Storage::disk('public')->delete($penduduk->foto_ktp);
        }

        $penduduk->delete();

        return redirect()->route('penduduk.index')->with('success', 'Data Penduduk berhasil dihapus.');
    }
}
