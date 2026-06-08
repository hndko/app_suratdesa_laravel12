<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function send(string $target, string $message)
    {
        if (empty($target)) {
            return false;
        }

        $token = config('services.fonnte.token');

        if (empty($token) || $token === 'your_token_here') {
            Log::info("WhatsApp message to $target skipped because Fonnte token is not set.");
            return false;
        }

        try {
            $response = Http::timeout(5)
                ->retry(2, 500, throw: false)
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                ]);

            if ($response->failed()) {
                Log::warning('Failed to send WhatsApp message via Fonnte.', [
                    'target' => $target,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::error('WhatsApp service error.', [
                'target' => $target,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
