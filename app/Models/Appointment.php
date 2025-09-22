<?php
namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'dentist_id', 'start_at', 'end_at', 'status', 'room',
        'notes', 'queue_number', 'checked_in_at', 'called_at',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'checked_in_at' => 'datetime',
            'called_at' => 'datetime',
            'status' => AppointmentStatus::class,
        ];
    }
    
    public function encounter()
    {
        return $this->hasOne(Encounter::class);
    }
    // Relationships
    public function patient() { return $this->belongsTo(Patient::class); }
    public function dentist() { return $this->belongsTo(User::class, 'dentist_id'); }
}