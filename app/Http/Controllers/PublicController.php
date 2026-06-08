<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\SuratNumberService;
use App\Jobs\SendWhatsAppMessage;

class PublicController extends Controller
{
    public function home()
    {
        $posts = \App\Models\Post::where('status', 'published')->latest()->take(6)->get();

        $data = [
            'posts' => $posts,
        ];

        return view('frontend.home', $data);
    }

    public function suratCreate()
    {
        $jenisSurats = \App\Models\JenisSurat::all();

        $data = [
            'jenisSurats' => $jenisSurats,
        ];

        return view('frontend.surat_create', $data);
    }

    public function suratStore(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|exists:penduduks,nik',
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'keperluan' => 'required|string|max:2000',
        ]);

        $penduduk = \App\Models\Penduduk::where('nik', $request->nik)->first();

        // In public submission, status is usually 'pending' or similar if implemented.
        // Based on PRD FR-4.05: Admin can change status.
        // But our Surat model doesn't have status yet. Let's add it via migration if needed,
        // or just store it as is for now.
        // Let's assume for now we just create the record and admin will see it in Arsip.

        $jenisSurat = \App\Models\JenisSurat::findOrFail($request->jenis_surat_id);
        $no_surat = SuratNumberService::generate($jenisSurat, now());

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
            SendWhatsAppMessage::dispatch($penduduk->phone, $message);
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
            'phone' => 'required|string|max:20',
            'category' => 'required|in:infrastruktur,keamanan,pelayanan,sosial,lainnya',
            'content' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $input = $request->only(['name', 'nik', 'phone', 'category', 'content']);
        $input['ticket_code'] = $this->generateTicketCode();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->hashName();
            Storage::disk('public')->putFileAs('pengaduan', $file, $filename);
            $input['image'] = 'pengaduan/' . $filename;
        }

        \App\Models\Pengaduan::create($input);

        // WhatsApp Notification
        if ($request->phone) {
            $message = "Halo {$request->name}, pengaduan Anda telah kami terima dengan Kode Tiket: {$input['ticket_code']}. Gunakan kode ini untuk melacak status aduan Anda di website kami. Terima kasih.";
            SendWhatsAppMessage::dispatch($request->phone, $message);
        }

        return redirect()->route('public.pengaduan.track')->with('success', 'Pengaduan berhasil dikirim. Simpan Kode Tiket Anda: ' . $input['ticket_code']);
    }

    public function pengaduanTrack()
    {
        return view('frontend.pengaduan_track');
    }

    public function pengaduanStatus(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string|max:20',
            'nik' => 'required|digits:16',
        ]);

        $pengaduan = \App\Models\Pengaduan::where('ticket_code', strtoupper($request->ticket_code))
            ->where('nik', $request->nik)
            ->first();

        $data = [
            'pengaduan' => $pengaduan,
        ];

        return view('frontend.pengaduan_track', $data);
    }

    private function generateTicketCode(): string
    {
        do {
            $code = 'TKT-' . strtoupper(Str::random(10));
        } while (\App\Models\Pengaduan::where('ticket_code', $code)->exists());

        return $code;
    }
}
