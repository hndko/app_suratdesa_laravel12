<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportBatchRow extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_batch_id',
        'row_number',
        'payload',
        'errors',
        'status',
    ];

    protected $casts = [
        'payload' => 'array',
        'errors' => 'array',
    ];
}
