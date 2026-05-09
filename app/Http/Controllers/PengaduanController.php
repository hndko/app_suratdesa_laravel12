<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Pengaduan Warga',
            'pengaduans' => Pengaduan::latest()->get(),
        ];
        return view('backend.pengaduan.index', $data);
    }

    public function edit(Pengaduan $pengaduan)
    {
        $data = [
            'title' => 'Tanggapi Pengaduan',
            'pengaduan' => $pengaduan,
        ];
        return view('backend.pengaduan.edit', $data);
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
}
