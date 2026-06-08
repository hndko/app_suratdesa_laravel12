<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'verification_code',
        'is_active',
        'verified_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }
}
