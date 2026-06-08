<?php

namespace App\Services\AI\Adapters;

use App\Models\AiProvider;
use App\Services\AI\AiProviderAdapterInterface;
use Illuminate\Support\Facades\Http;

class GeminiAdapter implements AiProviderAdapterInterface
{
    public function chat(AiProvider $provider, array $messages, array $options = []): array
    {
        $baseUrl = rtrim($provider->base_url ?: 'https://generativelanguage.googleapis.com/v1beta', '/');
        $prompt = collect($messages)->map(fn ($message) => strtoupper($message['role']) . ': ' . $message['content'])->implode("\n\n");

        $response = Http::timeout($provider->timeout)
            ->connectTimeout(min(10, $provider->timeout))
            ->retry($provider->retry, 500, throw: false)
            ->acceptJson()
            ->post($baseUrl . '/models/' . urlencode($provider->model) . ':generateContent?key=' . urlencode((string) $provider->api_key), [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => (float) ($options['temperature'] ?? $provider->temperature),
                    'maxOutputTokens' => (int) ($options['max_tokens'] ?? $provider->max_tokens),
                ],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException($response->body() ?: 'Gemini request failed.');
        }

        $json = $response->json();

        return [
            'content' => data_get($json, 'candidates.0.content.parts.0.text', ''),
            'usage' => [
                'prompt_tokens' => data_get($json, 'usageMetadata.promptTokenCount'),
                'completion_tokens' => data_get($json, 'usageMetadata.candidatesTokenCount'),
                'total_tokens' => data_get($json, 'usageMetadata.totalTokenCount'),
            ],
            'raw' => $json,
        ];
    }
}
