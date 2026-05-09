<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public static function send($target, $message)
    {
        $token = config('services.fonnte.token');

        if (empty($token) || $token === 'your_token_here') {
            \Log::info("WhatsApp message to $target: $message (Fonnte token not set)");
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);

        return $response->json();
    }
}
