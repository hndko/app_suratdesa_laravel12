<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Surat extends Model
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
        'no_surat',
        'tracking_code',
        'penduduk_id',
        'jenis_surat_id',
        'user_id',
        'tanggal_surat',
        'keperluan',
        'keterangan',
        'status',
        'verified_at',
        'approved_at',
        'rejected_at',
        'approval_note',
        'file_arsip',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
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

    public function approvals()
    {
        return $this->hasMany(SuratApproval::class);
    }

    public function verification()
    {
        return $this->hasOne(SuratVerification::class);
    }
}
