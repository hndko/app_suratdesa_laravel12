<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaduanAiSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengaduan_id',
        'ai_provider_id',
        'user_id',
        'summary',
        'recommended_category',
        'priority',
        'draft_reply',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];
}
