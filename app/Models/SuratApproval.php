<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'user_id',
        'action',
        'from_status',
        'to_status',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
