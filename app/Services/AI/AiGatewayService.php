<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use App\Models\AiUsageLog;
use App\Services\AI\Adapters\ClaudeAdapter;
use App\Services\AI\Adapters\GeminiAdapter;
use App\Services\AI\Adapters\OpenAiCompatibleAdapter;
use Illuminate\Support\Facades\Auth;

class AiGatewayService
{
    public function chat(array $messages, string $feature, array $options = []): array
    {
        $provider = $options['provider'] ?? AiProvider::where('is_active', true)->first();

        if (!$provider) {
            throw new \RuntimeException('Provider AI aktif belum dikonfigurasi.');
        }

        return $this->sendWithFallback($provider, $messages, $feature, $options);
    }

    private function sendWithFallback(AiProvider $provider, array $messages, string $feature, array $options): array
    {
        try {
            return $this->send($provider, $messages, $feature, $options);
        } catch (\Throwable $e) {
            $fallback = AiProvider::where('is_fallback', true)
                ->where('id', '!=', $provider->id)
                ->first();

            if (!$fallback) {
                throw $e;
            }

            return $this->send($fallback, $messages, $feature, $options);
        }
    }

    private function send(AiProvider $provider, array $messages, string $feature, array $options): array
    {
        $startedAt = microtime(true);

        try {
            $result = $this->adapterFor($provider)->chat($provider, $messages, $options);
            $latencyMs = (int) ((microtime(true) - $startedAt) * 1000);

            AiUsageLog::create([
                'ai_provider_id' => $provider->id,
                'user_id' => Auth::id(),
                'feature' => $feature,
                'model' => $provider->model,
                'status' => 'success',
                'prompt_tokens' => data_get($result, 'usage.prompt_tokens'),
                'completion_tokens' => data_get($result, 'usage.completion_tokens'),
                'total_tokens' => data_get($result, 'usage.total_tokens'),
                'latency_ms' => $latencyMs,
                'metadata' => ['provider_type' => $provider->provider_type],
            ]);

            $result['provider'] = $provider;

            return $result;
        } catch (\Throwable $e) {
            AiUsageLog::create([
                'ai_provider_id' => $provider->id,
                'user_id' => Auth::id(),
                'feature' => $feature,
                'model' => $provider->model,
                'status' => 'error',
                'latency_ms' => (int) ((microtime(true) - $startedAt) * 1000),
                'error_code' => class_basename($e),
                'error_message' => mb_substr($e->getMessage(), 0, 1000),
                'metadata' => ['provider_type' => $provider->provider_type],
            ]);

            throw $e;
        }
    }

    private function adapterFor(AiProvider $provider): AiProviderAdapterInterface
    {
        return match ($provider->provider_type) {
            'gemini' => app(GeminiAdapter::class),
            'claude' => app(ClaudeAdapter::class),
            default => app(OpenAiCompatibleAdapter::class),
        };
    }
}
