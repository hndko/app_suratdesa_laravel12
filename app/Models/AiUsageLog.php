<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiUsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ai_provider_id',
        'user_id',
        'feature',
        'model',
        'status',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'latency_ms',
        'error_code',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
