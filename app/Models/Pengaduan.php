<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pengaduan extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
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
