<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    // NOTE: Tampilkan daftar jenis surat
    public function index(Request $request)
    {
        $query = JenisSurat::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_surat', 'like', "%{$search}%")
                    ->orWhere('nama_surat', 'like', "%{$search}%");
            });
        }

        $data = [
            'title' => 'Jenis Surat',
            'jenis_surats' => $query->latest()->paginate(10),
        ];

        return view('backend.jenis_surat.index', $data);
    }

    // NOTE: Form tambah jenis surat
    public function create()
    {
        return view('backend.jenis_surat.create', ['title' => 'Tambah Jenis Surat']);
    }

    // NOTE: Proses simpan jenis surat
    public function store(Request $request)
    {
        $request->validate([
            'kode_surat' => 'required|unique:jenis_surats,kode_surat|max:50',
            'nama_surat' => 'required|max:255',
            'kop_judul' => 'required|max:255',
            'template_isi' => 'required',
        ]);

        JenisSurat::create($request->all());

        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil ditambahkan.');
    }

    // NOTE: Form edit jenis surat
    public function edit($id)
    {
        $jenis_surat = JenisSurat::findOrFail($id);

        $data = [
            'title' => 'Edit Jenis Surat',
            'jenis_surat' => $jenis_surat,
        ];

        return view('backend.jenis_surat.edit', $data);
    }

    // NOTE: Proses update jenis surat
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_surat' => 'required|max:50|unique:jenis_surats,kode_surat,' . $id,
            'nama_surat' => 'required|max:255',
            'kop_judul' => 'required|max:255',
            'template_isi' => 'required',
        ]);

        $jenis_surat = JenisSurat::findOrFail($id);
        $jenis_surat->update($request->all());

        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil diperbarui.');
    }

    // NOTE: Hapus jenis surat
    public function destroy($id)
    {
        JenisSurat::findOrFail($id)->delete();
        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil dihapus.');
    }
}
