<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppTestController extends Controller
{
    public function index()
    {
        return view('backend.whatsapp.test', [
            'title' => 'Uji Coba WhatsApp Gateway'
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'message' => 'required'
        ]);

        $result = WhatsAppService::send($request->phone, $request->message);

        if ($result && isset($result['status']) && $result['status'] == true) {
            return back()->with('success', 'Pesan WhatsApp berhasil dikirim!');
        }

        $error = isset($result['reason']) ? $result['reason'] : 'Terjadi kesalahan saat mengirim pesan. Pastikan token Fonnte sudah benar.';
        return back()->with('error', 'Gagal mengirim pesan: ' . $error);
    }
}
