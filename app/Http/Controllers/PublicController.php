<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Pengaduan;
use App\Models\Post;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\SuratNumberService;
use App\Jobs\SendWhatsAppMessage;

class PublicController extends Controller
{
    public function home()
    {
        $posts = Post::where('status', 'published')->latest()->take(6)->get();
        $siteName = \App\Facades\Setting::get('site_name', 'SIMADES');

        $data = [
            'title' => 'Selamat Datang di Portal Resmi ' . $siteName,
            'posts' => $posts,
            'siteName' => $siteName,
            'villageName' => \App\Facades\Setting::get('village_nama', 'Desa Kami'),
            'villageAddress' => \App\Facades\Setting::get('village_alamat', 'Alamat kantor desa belum diatur'),
            'contactWhatsapp' => \App\Facades\Setting::get('contact_whatsapp'),
            'totalJenisSurat' => JenisSurat::count(),
            'totalSuratSelesai' => Surat::where('status', 'done')->count(),
            'totalPengaduanSelesai' => Pengaduan::where('status', 'resolved')->count(),
            'totalPengumuman' => Post::where('status', 'published')->count(),
        ];

        return view('frontend.home', $data);
    }

    public function suratCreate()
    {
        $jenisSurats = JenisSurat::orderBy('nama_surat')->get();
        $siteName = \App\Facades\Setting::get('site_name', 'SIMADES');

        $data = [
            'title' => 'Pengajuan Surat Online - ' . $siteName,
            'jenisSurats' => $jenisSurats,
            'siteName' => $siteName,
            'villageName' => \App\Facades\Setting::get('village_nama', 'Desa Kami'),
            'contactWhatsapp' => \App\Facades\Setting::get('contact_whatsapp'),
        ];

        return view('frontend.pengajuan.surat.create', $data);
    }

    public function suratStore(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|exists:penduduks,nik',
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'keperluan' => 'required|string|max:2000',
        ]);

        $penduduk = \App\Models\Penduduk::where('nik', $request->nik)->first();

        $jenisSurat = \App\Models\JenisSurat::findOrFail($request->jenis_surat_id);
        $no_surat = SuratNumberService::generate($jenisSurat, now());
        $trackingCode = $this->generateSuratTrackingCode();

        \App\Models\Surat::create([
            'no_surat' => $no_surat,
            'tracking_code' => $trackingCode,
            'penduduk_id' => $penduduk->id,
            'jenis_surat_id' => $request->jenis_surat_id,
            'tanggal_surat' => now(),
            'keperluan' => $request->keperluan,
            'keterangan' => 'Pengajuan Mandiri Online',
        ]);

        // WhatsApp Notification
        if ($penduduk->phone) {
            $message = "Halo {$penduduk->nama}, pengajuan surat {$jenisSurat->nama_surat} Anda telah kami terima. Kode Tracking: {$trackingCode}. Gunakan kode ini untuk memantau status pengajuan. Terima kasih.";
            SendWhatsAppMessage::dispatch($penduduk->phone, $message);
        }

        return redirect()->route('public.surat.track')->with('success', 'Pengajuan surat berhasil dikirim. Simpan Kode Tracking Anda: ' . $trackingCode);
    }

    public function suratTrack()
    {
        $siteName = \App\Facades\Setting::get('site_name', 'SIMADES');

        $data = [
            'title' => 'Lacak Pengajuan Surat - ' . $siteName,
            'siteName' => $siteName,
            'villageName' => \App\Facades\Setting::get('village_nama', 'Desa Kami'),
        ];

        return view('frontend.pengajuan.surat.track', $data);
    }

    public function suratStatus(Request $request)
    {
        $request->validate([
            'tracking_code' => 'required|string|max:24',
            'nik' => 'required|digits:16',
        ]);

        $surat = \App\Models\Surat::with(['penduduk', 'jenisSurat'])
            ->where('tracking_code', strtoupper($request->tracking_code))
            ->whereHas('penduduk', function ($query) use ($request) {
                $query->where('nik', $request->nik);
            })
            ->first();

        $data = [
            'title' => 'Lacak Pengajuan Surat - ' . \App\Facades\Setting::get('site_name', 'SIMADES'),
            'surat' => $surat,
            'siteName' => \App\Facades\Setting::get('site_name', 'SIMADES'),
            'villageName' => \App\Facades\Setting::get('village_nama', 'Desa Kami'),
        ];

        return view('frontend.pengajuan.surat.track', $data);
    }

    public function pengaduanCreate()
    {
        $siteName = \App\Facades\Setting::get('site_name', 'SIMADES');

        $data = [
            'title' => 'Kirim Pengaduan Warga - ' . $siteName,
            'siteName' => $siteName,
            'villageName' => \App\Facades\Setting::get('village_nama', 'Desa Kami'),
        ];

        return view('frontend.pengajuan.pengaduan.create', $data);
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
        $siteName = \App\Facades\Setting::get('site_name', 'SIMADES');

        $data = [
            'title' => 'Lacak Status Pengaduan - ' . $siteName,
        ];

        return view('frontend.pengajuan.pengaduan.track', $data);
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
            'title' => 'Lacak Status Pengaduan - ' . \App\Facades\Setting::get('site_name', 'SIMADES'),
            'pengaduan' => $pengaduan,
        ];

        return view('frontend.pengajuan.pengaduan.track', $data);
    }

    public function verifikasiSurat(Request $request)
    {
        $verification = null;

        if ($request->filled('code')) {
            $verification = \App\Models\SuratVerification::with(['surat.penduduk', 'surat.jenisSurat'])
                ->where('verification_code', strtoupper($request->code))
                ->where('is_active', true)
                ->first();

            if ($verification) {
                $verification->update(['verified_at' => now()]);
            }
        }

        $data = [
            'title' => 'Verifikasi Keaslian Surat - ' . \App\Facades\Setting::get('site_name', 'SIMADES'),
            'verification' => $verification,
            'siteName' => \App\Facades\Setting::get('site_name', 'SIMADES'),
            'villageName' => \App\Facades\Setting::get('village_nama', 'Desa Kami'),
            'verificationCode' => $request->filled('code') ? strtoupper($request->code) : null,
        ];

        return view('frontend.verifikasi.surat', $data);
    }

    public function verifikasiSuratStatus(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|max:32',
        ]);

        $verification = \App\Models\SuratVerification::with(['surat.penduduk', 'surat.jenisSurat'])
            ->where('verification_code', strtoupper($request->verification_code))
            ->where('is_active', true)
            ->first();

        if ($verification) {
            $verification->update(['verified_at' => now()]);
        }

        $data = [
            'title' => 'Verifikasi Keaslian Surat - ' . \App\Facades\Setting::get('site_name', 'SIMADES'),
            'verification' => $verification,
            'siteName' => \App\Facades\Setting::get('site_name', 'SIMADES'),
            'villageName' => \App\Facades\Setting::get('village_nama', 'Desa Kami'),
            'verificationCode' => strtoupper($request->verification_code),
        ];

        return view('frontend.verifikasi.surat', $data);
    }

    private function generateTicketCode(): string
    {
        do {
            $code = 'TKT-' . strtoupper(Str::random(10));
        } while (\App\Models\Pengaduan::where('ticket_code', $code)->exists());

        return $code;
    }

    private function generateSuratTrackingCode(): string
    {
        do {
            $code = 'SRT-' . strtoupper(Str::random(10));
        } while (\App\Models\Surat::where('tracking_code', $code)->exists());

        return $code;
    }
}
