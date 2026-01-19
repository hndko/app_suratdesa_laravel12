<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_surat',
        'penduduk_id',
        'jenis_surat_id',
        'user_id',
        'tanggal_surat',
        'keperluan',
        'keterangan',
        'file_arsip',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
