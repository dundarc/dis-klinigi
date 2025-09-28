<?php

namespace App\Models;

use App\Enums\FileType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'encounter_id',
        'uploader_id',
        'type',
        'filename',
        'original_filename',
        'path',
        'mime_type',
        'size',
        'notes',
    ];

    protected $appends = ['download_url', 'display_name'];


    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => FileType::class,
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('patient-files.show', $this);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->original_filename ?? basename($this->path);
    }

}