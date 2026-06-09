<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWhatsAppMessage implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 30;

    public function __construct(
        public string $target,
        public string $message,
        public array $options = [],
    ) {
    }

    public function handle(): void
    {
        WhatsAppService::send($this->target, $this->message, $this->options);
    }
}
