<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Jobs\SendWhatsAppMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendudukController extends Controller
{
    // NOTE: Tampilkan daftar penduduk
    public function index()
    {
        $data = [
            'title' => 'Data Penduduk',
            'penduduks' => Penduduk::latest()->paginate(25),
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
            'phone' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'agama' => 'required',
            'status_perkawinan' => 'required',
            'pekerjaan' => 'required',
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $input = $request->only([
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
        ];
        return view('backend.penduduk.edit', $data);
    }

    // NOTE: Proses update penduduk
    public function update(Request $request, string $id)
    {
        $penduduk = Penduduk::findOrFail($id);

        $request->validate([
            'nik' => 'required|digits:16|numeric|unique:penduduks,nik,' . $id,
            'nama' => 'required|string|max:255',
            'phone' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'agama' => 'required',
            'status_perkawinan' => 'required',
            'pekerjaan' => 'required',
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $input = $request->only([
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
