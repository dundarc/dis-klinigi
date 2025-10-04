<?php

namespace App\Models;

use App\Enums\ConsentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consent extends Model
{
    /** @use HasFactory<\\Database\\Factories\\ConsentFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'type',
        'title',
        'content',
        'version',
        'status',
        'consent_method',
        'verification_token',
        'accepted_at',
        'email_sent_at',
        'email_verified_at',
        'ip_address',
        'user_agent',
        'snapshot',
        'hash',
        'signature_path',
        'withdrawn_at',
        'cancellation_pdf_generated_at',
        'cancellation_pdf_downloaded_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ConsentStatus::class,
            'accepted_at' => 'datetime',
            'email_sent_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'snapshot' => 'array',
            'withdrawn_at' => 'datetime',
            'cancellation_pdf_generated_at' => 'datetime',
            'cancellation_pdf_downloaded_at' => 'datetime',
        ];
    }

    /**
     * Get the patient that owns the consent.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getCanceledAtAttribute()
    {
        return $this->attributes['withdrawn_at'] ?? null;
    }

    public function setCanceledAtAttribute($value): void
    {
        $this->attributes['withdrawn_at'] = $value;
    }
}
