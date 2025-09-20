<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    // Bu modelin updated_at sütununu yönetmeye çalışmasını engelliyoruz.
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'ip',
        'user_agent',
        'old_values',
        'new_values',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }
    
    // İlişkiyi tanımlayalım
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}