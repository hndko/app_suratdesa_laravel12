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

    public function validateNumber(Request $request)
    {
        $request->validate([
            'targets' => 'required|string|max:7000',
            'country_code' => 'nullable|string|max:5',
        ]);

        $targets = array_filter(array_map('trim', explode(',', $request->targets)));

        if (count($targets) > 500) {
            return back()
                ->withInput()
                ->with('error', 'Validasi Fonnte maksimal 500 nomor dalam satu request.');
        }

        $result = WhatsAppService::validateNumbers($targets, $request->country_code ?: '62');
        $isSuccess = $result && (bool) ($result['status'] ?? $result['Status'] ?? false);

        if ($isSuccess) {
            return back()
                ->withInput()
                ->with('success', 'Validasi nomor WhatsApp berhasil.')
                ->with('validationResult', [
                    'registered' => $result['registered'] ?? [],
                    'not_registered' => $result['not_registered'] ?? [],
                ]);
        }

        $error = $result['reason'] ?? $result['detail'] ?? 'Terjadi kesalahan saat validasi nomor. Pastikan token dan device Fonnte aktif.';

        return back()
            ->withInput()
            ->with('error', 'Gagal validasi nomor: ' . $error);
    }
}
