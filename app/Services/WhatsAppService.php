<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public const ENDPOINT = 'https://api.fonnte.com/send';

    public static function send(string $target, ?string $message = null, array $options = []): array|false
    {
        $target = self::normalizeTarget($target);

        if ($target === '' && empty($options['data'])) {
            return false;
        }

        $token = config('services.fonnte.token');

        if (empty($token) || $token === 'your_token_here') {
            Log::info('WhatsApp message skipped because Fonnte token is not set.', [
                'target' => $target,
            ]);

            return [
                'status' => false,
                'reason' => 'Fonnte token is not set.',
            ];
        }

        try {
            $payload = self::buildPayload($target, $message, $options);
            $response = self::request($token, $payload, $options)
                ->post(self::ENDPOINT, self::payloadWithoutFile($payload));

            if ($response->failed()) {
                Log::warning('Failed to send WhatsApp message via Fonnte.', [
                    'target' => $target,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return self::handleResponse($response, $target);
        } catch (\Throwable $e) {
            Log::error('WhatsApp service error.', [
                'target' => $target,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private static function request(string $token, array $payload, array $options): PendingRequest
    {
        $request = Http::timeout((int) ($options['timeout'] ?? 10))
            ->connectTimeout((int) ($options['connect_timeout'] ?? 5))
            ->retry((int) ($options['retry'] ?? 2), (int) ($options['retry_delay'] ?? 500), throw: false)
            ->withHeaders([
                'Authorization' => $token,
            ]);

        if (!empty($payload['file'])) {
            return $request->attach(
                'file',
                fopen($payload['file'], 'r'),
                basename($payload['file'])
            );
        }

        return $request->asForm();
    }

    private static function buildPayload(string $target, ?string $message, array $options): array
    {
        $allowedOptions = [
            'url',
            'filename',
            'schedule',
            'typing',
            'delay',
            'countryCode',
            'location',
            'choices',
            'select',
            'pollname',
            'file',
            'connectOnly',
            'followup',
            'data',
            'sequence',
            'preview',
            'inboxid',
            'duration',
        ];

        $payload = [
            'message' => $message,
            'countryCode' => (string) ($options['countryCode'] ?? '62'),
        ];

        if ($target !== '') {
            $payload['target'] = $target;
        }

        foreach ($allowedOptions as $key) {
            if (array_key_exists($key, $options)) {
                $payload[$key] = self::formatPayloadValue($key, $options[$key]);
            }
        }

        return array_filter($payload, static fn ($value) => $value !== null && $value !== '');
    }

    private static function payloadWithoutFile(array $payload): array
    {
        unset($payload['file']);

        return $payload;
    }

    private static function formatPayloadValue(string $key, mixed $value): mixed
    {
        if ($key === 'countryCode' || $key === 'delay' || $key === 'data') {
            return (string) $value;
        }

        if ($key === 'file') {
            return is_string($value) && is_file($value) ? $value : null;
        }

        if (is_bool($value)) {
            return $value;
        }

        return $value;
    }

    private static function handleResponse(Response $response, string $target): array|false
    {
        $body = $response->json();

        if (!is_array($body)) {
            Log::warning('Fonnte returned non JSON response.', [
                'target' => $target,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        }

        $isSuccess = (bool) ($body['status'] ?? $body['Status'] ?? false);

        if (!$isSuccess) {
            Log::warning('Fonnte rejected WhatsApp message.', [
                'target' => $target,
                'reason' => $body['reason'] ?? $body['detail'] ?? 'Unknown provider error.',
                'requestid' => $body['requestid'] ?? null,
                'response' => $body,
            ]);
        }

        return $body;
    }

    private static function normalizeTarget(string $target): string
    {
        $targets = array_filter(array_map('trim', explode(',', $target)));

        $targets = array_map(static function (string $item): string {
            $parts = array_map('trim', explode('|', $item));
            $destination = array_shift($parts);

            if (!str_contains($destination, '@g.us')) {
                $destination = preg_replace('/[\s\-\(\)\.]/', '', $destination);
                $destination = ltrim((string) $destination, '+');
            }

            return implode('|', array_filter(array_merge([$destination], $parts), static fn ($part) => $part !== ''));
        }, $targets);

        return implode(',', array_filter($targets));
    }
}
