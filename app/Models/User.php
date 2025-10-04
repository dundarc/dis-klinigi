<?php
namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <<---- EKSİK OLAN VE EKLENMESİ GEREKEN SATIR BUDUR
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // HasApiTokens
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'locale', 'dark_mode', 'is_active',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
        'dark_mode' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Validation rules for the model
     */
    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:' . implode(',', array_column(UserRole::cases(), 'value')),
            'locale' => 'nullable|string|max:10',
            'dark_mode' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Validate email format
     */
    public function isValidEmail(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false &&
               strpos($this->email, '@') !== false &&
               !preg_match('/\s/', $this->email) &&
               strlen($this->email) >= 5 &&
               strlen($this->email) <= 254;
    }

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

    public function hasRole(UserRole $role): bool
    {
        return $this->role === $role;
    }

    // Relationships
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isDentist(): bool
    {
        return $this->role === UserRole::DENTIST;
    }

    public function isReceptionist(): bool
    {
        return $this->role === UserRole::RECEPTIONIST;
    }

    public function isAccountant(): bool
    {
        return $this->role === UserRole::ACCOUNTANT;
    }

    public function isAssistant(): bool
    {
        return $this->role === UserRole::ASSISTANT;
    }

    public function workingHours() { return $this->hasMany(WorkingHour::class); }
    public function unavailabilities() { return $this->hasMany(UserUnavailability::class); }
    public function appointments() { return $this->hasMany(Appointment::class, 'dentist_id'); }
    public function patientTreatments() { return $this->hasMany(PatientTreatment::class, 'dentist_id'); }
    public function prescriptions() { return $this->hasMany(Prescription::class, 'dentist_id'); }
    public function treatmentPlans() { return $this->hasMany(TreatmentPlan::class, 'dentist_id'); }
    public function stockMovements() { return $this->hasMany(\App\Models\Stock\StockMovement::class, 'created_by'); }
}

