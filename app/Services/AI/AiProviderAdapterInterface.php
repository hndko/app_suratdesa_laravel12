<?php

namespace App\Services\AI;

use App\Models\AiProvider;

interface AiProviderAdapterInterface
{
    public function chat(AiProvider $provider, array $messages, array $options = []): array;
}
