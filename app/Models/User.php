<?php
namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <<---- EKSİK OLAN VE EKLENMESİ GEREKEN SATIR BUDUR
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // HasApiTokens'i buraya ekleyin
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'locale', 'dark_mode', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'dark_mode' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
    public function notifications()
{
    return $this->hasMany(\App\Models\Notification::class);
}
    // Relationships
    public function workingHours() { return $this->hasMany(WorkingHour::class); }
    public function unavailabilities() { return $this->hasMany(UserUnavailability::class); }
    public function appointments() { return $this->hasMany(Appointment::class, 'dentist_id'); }
    public function patientTreatments() { return $this->hasMany(PatientTreatment::class, 'dentist_id'); }
    public function prescriptions() { return $this->hasMany(Prescription::class, 'dentist_id'); }
}

