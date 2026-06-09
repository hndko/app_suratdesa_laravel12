<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppTestController extends Controller
{
    public function index()
    {
        $token = config('services.fonnte.token');

        return view('backend.whatsapp.test', [
            'title' => 'Uji Coba WhatsApp Gateway',
            'hasToken' => filled($token) && $token !== 'your_token_here',
            'tokenPreview' => filled($token) && $token !== 'your_token_here'
                ? substr((string) $token, 0, 4) . str_repeat('*', 8)
                : null,
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        $result = WhatsAppService::send($request->phone, $request->message, [
            'countryCode' => '62',
            'typing' => true,
            'preview' => false,
        ]);

        $isSuccess = $result && (bool) ($result['status'] ?? $result['Status'] ?? false);

        if ($isSuccess) {
            $detail = $result['detail'] ?? 'Pesan WhatsApp berhasil dikirim!';

            return back()->with('success', $detail);
        }

        $error = $result['reason'] ?? $result['detail'] ?? 'Terjadi kesalahan saat mengirim pesan. Pastikan token Fonnte sudah benar.';

        return back()->with('error', 'Gagal mengirim pesan: ' . $error);
    }
}
