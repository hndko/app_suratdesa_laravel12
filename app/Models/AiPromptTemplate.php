<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiPromptTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'system_prompt',
        'user_prompt_template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
