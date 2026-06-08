<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'file_name',
        'status',
        'total_rows',
        'valid_rows',
        'invalid_rows',
        'processed_rows',
    ];

    public function rows()
    {
        return $this->hasMany(ImportBatchRow::class);
    }
}
