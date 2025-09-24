<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'default_price',
        'default_vat',
        'default_duration_min',
        'description',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'default_vat' => 'decimal:2',
        'default_duration_min' => 'integer',
    ];
}
