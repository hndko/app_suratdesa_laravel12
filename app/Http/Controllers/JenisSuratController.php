<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Services\SuratAiService;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    // NOTE: Tampilkan daftar jenis surat
    public function index(Request $request)
    {
        $data = [
            'title' => 'Jenis Surat',
            'jenis_surats' => JenisSurat::latest()->get(),
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

        JenisSurat::create($request->only(['kode_surat', 'nama_surat', 'kop_judul', 'template_isi']));

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
}
