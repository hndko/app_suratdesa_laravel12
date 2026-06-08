<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Penduduk extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'kartu_keluarga_id',
        'nik',
        'nama',
        'phone',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'rt',
        'rw',
        'agama',
        'pendidikan',
        'golongan_darah',
        'shdk',
        'status_perkawinan',
        'pekerjaan',
        'foto_ktp',
    ];

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }

    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluarga::class);
    }
}
