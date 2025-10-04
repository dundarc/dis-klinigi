<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAutomationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_checkin_to_dentist',
        'emergency_patient_to_dentist',
        'kvkk_consent_to_admin',
    ];

    protected $casts = [
        'patient_checkin_to_dentist' => 'boolean',
        'emergency_patient_to_dentist' => 'boolean',
        'kvkk_consent_to_admin' => 'boolean',
    ];

    /**
     * Get the first automation setting record (singleton pattern)
     */
    public static function getSettings(): self
    {
        return static::first() ?? new static([
            'patient_checkin_to_dentist' => false,
            'emergency_patient_to_dentist' => false,
            'kvkk_consent_to_admin' => false,
        ]);
    }

    /**
     * Update or create automation settings
     */
    public static function updateSettings(array $data): self
    {
        $setting = static::find(1);
        if ($setting) {
            $setting->update($data);
            return $setting;
        } else {
            return static::create(array_merge($data, ['id' => 1]));
        }
    }
}
