<?php

namespace App\Services\AI\Adapters;

use App\Models\AiProvider;
use App\Services\AI\AiProviderAdapterInterface;
use Illuminate\Support\Facades\Http;

class ClaudeAdapter implements AiProviderAdapterInterface
{
    public function chat(AiProvider $provider, array $messages, array $options = []): array
    {
        $baseUrl = rtrim($provider->base_url ?: 'https://api.anthropic.com/v1', '/');
        $system = collect($messages)->firstWhere('role', 'system')['content'] ?? null;
        $chatMessages = collect($messages)
            ->reject(fn ($message) => $message['role'] === 'system')
            ->map(fn ($message) => [
                'role' => $message['role'] === 'assistant' ? 'assistant' : 'user',
                'content' => $message['content'],
            ])
            ->values()
            ->all();

        $payload = [
            'model' => $options['model'] ?? $provider->model,
            'messages' => $chatMessages,
            'temperature' => (float) ($options['temperature'] ?? $provider->temperature),
            'max_tokens' => (int) ($options['max_tokens'] ?? $provider->max_tokens),
        ];

        if ($system) {
            $payload['system'] = $system;
        }

        $response = Http::timeout($provider->timeout)
            ->connectTimeout(min(10, $provider->timeout))
            ->retry($provider->retry, 500, throw: false)
            ->withHeaders([
                'x-api-key' => (string) $provider->api_key,
                'anthropic-version' => '2023-06-01',
            ])
            ->acceptJson()
            ->post($baseUrl . '/messages', $payload);

        if ($response->failed()) {
            throw new \RuntimeException($response->body() ?: 'Claude request failed.');
        }

        $json = $response->json();

        return [
            'content' => data_get($json, 'content.0.text', ''),
            'usage' => [
                'prompt_tokens' => data_get($json, 'usage.input_tokens'),
                'completion_tokens' => data_get($json, 'usage.output_tokens'),
                'total_tokens' => (int) data_get($json, 'usage.input_tokens', 0) + (int) data_get($json, 'usage.output_tokens', 0),
            ],
            'raw' => $json,
        ];
    }
}
