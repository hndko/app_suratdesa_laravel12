<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_surat',
        'nama_surat',
        'kop_judul',
        'template_isi',
    ];

    // NOTE: Relation to Surat
    public function surats()
    {
        return $this->hasMany(Surat::class);
    }
}
