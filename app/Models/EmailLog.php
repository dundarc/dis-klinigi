<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    /** @use HasFactory<\Database\Factories\EmailLogFactory> */
    use HasFactory;

    protected $fillable = [
        'template_key',
        'to_email',
        'to_name',
        'subject',
        'body_html',
        'body_text',
        'body_snippet',
        'status',
        'error_message',
        'sent_at',
        'queued_at',
        'message_id',
        'mailer_alias',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'queued_at' => 'datetime',
    ];

    /**
     * Relationship with EmailBounce
     */
    public function bounces()
    {
        return $this->hasMany(EmailBounce::class);
    }
}
