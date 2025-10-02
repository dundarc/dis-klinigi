<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailBounce extends Model
{
    /** @use HasFactory<\Database\Factories\EmailBounceFactory> */
    use HasFactory;

    protected $fillable = [
        'email',
        'bounce_type',
        'provider',
        'raw_payload',
        'occurred_at',
        'email_log_id',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'raw_payload' => 'array',
    ];

    /**
     * Relationship with EmailLog
     */
    public function emailLog()
    {
        return $this->belongsTo(EmailLog::class);
    }
}
