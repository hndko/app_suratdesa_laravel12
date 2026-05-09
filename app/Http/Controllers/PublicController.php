<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $posts = \App\Models\Post::where('status', 'published')->latest()->take(6)->get();
        return view('frontend.home', compact('posts'));
    }

    public function suratCreate()
    {
        $jenisSurats = \App\Models\JenisSurat::all();
        return view('frontend.surat_create', compact('jenisSurats'));
    }

    public function suratStore(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|exists:penduduks,nik',
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'keperluan' => 'required|string',
        ]);

        $penduduk = \App\Models\Penduduk::where('nik', $request->nik)->first();

        // In public submission, status is usually 'pending' or similar if implemented.
        // Based on PRD FR-4.05: Admin can change status.
        // But our Surat model doesn't have status yet. Let's add it via migration if needed,
        // or just store it as is for now.
        // Let's assume for now we just create the record and admin will see it in Arsip.

        $jenisSurat = \App\Models\JenisSurat::find($request->jenis_surat_id);
        $count = \App\Models\Surat::whereYear('tanggal_surat', date('Y'))->count() + 1;
        $no_surat = sprintf("%s/%03d/%s/%s", $jenisSurat->kode_surat, $count, date('m'), date('Y'));

        \App\Models\Surat::create([
            'no_surat' => $no_surat,
            'penduduk_id' => $penduduk->id,
            'jenis_surat_id' => $request->jenis_surat_id,
            'tanggal_surat' => now(),
            'keperluan' => $request->keperluan,
            'keterangan' => 'Pengajuan Mandiri Online',
        ]);

        // WhatsApp Notification
        if ($penduduk->phone) {
            $message = "Halo {$penduduk->nama}, pengajuan surat {$jenisSurat->nama_surat} Anda telah kami terima. Silakan pantau statusnya atau tunggu informasi selanjutnya. Terima kasih.";
            \App\Services\WhatsAppService::send($penduduk->phone, $message);
        }

        return redirect()->back()->with('success', 'Pengajuan surat berhasil dikirim. Silakan hubungi kantor desa untuk pengambilan.');
    }

    public function pengaduanCreate()
    {
        return view('frontend.pengaduan_create');
    }

    public function pengaduanStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'phone' => 'required',
            'category' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $input = $request->all();
        $input['ticket_code'] = 'TKT-' . strtoupper(\Illuminate\Support\Str::random(8));

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/pengaduan', $filename);
            $input['image'] = 'pengaduan/' . $filename;
        }

        \App\Models\Pengaduan::create($input);

        // WhatsApp Notification
        if ($request->phone) {
            $message = "Halo {$request->name}, pengaduan Anda telah kami terima dengan Kode Tiket: {$input['ticket_code']}. Gunakan kode ini untuk melacak status aduan Anda di website kami. Terima kasih.";
            \App\Services\WhatsAppService::send($request->phone, $message);
        }

        return redirect()->route('public.pengaduan.track')->with('success', 'Pengaduan berhasil dikirim. Simpan Kode Tiket Anda: ' . $input['ticket_code']);
    }

    public function pengaduanTrack()
    {
        return view('frontend.pengaduan_track');
    }

    public function pengaduanStatus(Request $request)
    {
        $request->validate(['ticket_code' => 'required']);
        $pengaduan = \App\Models\Pengaduan::where('ticket_code', $request->ticket_code)->first();

        return view('frontend.pengaduan_track', compact('pengaduan'));
    }
}
