<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratAiSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_surat_id',
        'surat_id',
        'ai_provider_id',
        'user_id',
        'suggestion_type',
        'original_text',
        'suggested_text',
        'placeholder_report',
        'raw_response',
    ];

    protected $casts = [
        'placeholder_report' => 'array',
        'raw_response' => 'array',
    ];
}
