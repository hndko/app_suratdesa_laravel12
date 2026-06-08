<?php

namespace App\Services\AI\Adapters;

use App\Models\AiProvider;
use App\Services\AI\AiProviderAdapterInterface;
use Illuminate\Support\Facades\Http;

class OpenAiCompatibleAdapter implements AiProviderAdapterInterface
{
    public function chat(AiProvider $provider, array $messages, array $options = []): array
    {
        $baseUrl = rtrim($provider->base_url ?: $this->defaultBaseUrl($provider->provider_type), '/');
        $payload = [
            'model' => $options['model'] ?? $provider->model,
            'messages' => $messages,
            'temperature' => (float) ($options['temperature'] ?? $provider->temperature),
            'max_tokens' => (int) ($options['max_tokens'] ?? $provider->max_tokens),
        ];

        if (!empty($options['response_format'])) {
            $payload['response_format'] = $options['response_format'];
        }

        $response = Http::timeout($provider->timeout)
            ->connectTimeout(min(10, $provider->timeout))
            ->retry($provider->retry, 500, throw: false)
            ->withToken((string) $provider->api_key)
            ->acceptJson()
            ->post($baseUrl . '/chat/completions', $payload);

        if ($response->failed()) {
            throw new \RuntimeException($response->body() ?: 'AI provider request failed.');
        }

        $json = $response->json();

        return [
            'content' => data_get($json, 'choices.0.message.content', ''),
            'usage' => [
                'prompt_tokens' => data_get($json, 'usage.prompt_tokens'),
                'completion_tokens' => data_get($json, 'usage.completion_tokens'),
                'total_tokens' => data_get($json, 'usage.total_tokens'),
            ],
            'raw' => $json,
        ];
    }

    private function defaultBaseUrl(string $providerType): string
    {
        return match ($providerType) {
            'openrouter' => 'https://openrouter.ai/api/v1',
            'deepseek' => 'https://api.deepseek.com',
            default => 'https://api.openai.com/v1',
        };
    }
}
