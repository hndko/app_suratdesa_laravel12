<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider_type',
        'base_url',
        'api_key',
        'model',
        'temperature',
        'max_tokens',
        'timeout',
        'retry',
        'is_active',
        'is_fallback',
    ];

    protected $casts = [
        'api_key' => 'encrypted',
        'temperature' => 'decimal:2',
        'max_tokens' => 'integer',
        'timeout' => 'integer',
        'retry' => 'integer',
        'is_active' => 'boolean',
        'is_fallback' => 'boolean',
    ];

    public function usageLogs()
    {
        return $this->hasMany(AiUsageLog::class);
    }
}
