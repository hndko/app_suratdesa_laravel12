<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $fillable = [
        'ticket_code',
        'name',
        'nik',
        'phone',
        'category',
        'content',
        'image',
        'status',
        'reply',
        'replied_by',
        'replied_at',
    ];

    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}
