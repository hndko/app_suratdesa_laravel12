<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\KartuKeluarga;
use App\Jobs\SendWhatsAppMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendudukController extends Controller
{
    // NOTE: Tampilkan daftar penduduk
    public function index(Request $request)
    {
        $query = Penduduk::with('kartuKeluarga')->latest();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($builder) use ($keyword) {
                $builder->where('nik', 'like', '%' . $keyword . '%')
                    ->orWhere('nama', 'like', '%' . $keyword . '%');
            });
        }

        $data = [
            'title' => 'Data Penduduk',
            'penduduks' => $query->paginate(25)->withQueryString(),
            'q' => $request->q,
        ];

        return view('backend.penduduk.index', $data);
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
