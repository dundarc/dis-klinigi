<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    use HasFactory;

    /**
     * Modelin zaman damgalarını otomatik olarak yönetmesini engeller.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Toplu atama yapılabilecek alanlar.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'weekday',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'is_active',
    ];

    /**
     * Bu çalışma saatinin ait olduğu kullanıcı (hekim).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}