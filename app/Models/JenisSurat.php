<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JenisSurat extends Model
{
    use HasFactory, LogsActivity;

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

    public function aiSuggestions()
    {
        return $this->hasMany(SuratAiSuggestion::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
