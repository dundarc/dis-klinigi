<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'skip_ssl_verification',
        'from_address',
        'from_name',
        'dkim_domain',
        'dkim_selector',
        'dkim_private_key',
        'spf_record',
    ];

    protected $casts = [
        'skip_ssl_verification' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'dkim_private_key',
    ];

    /**
     * Get the first email setting record (singleton pattern)
     */
    public static function getSettings(): ?self
    {
        return static::first();
    }

    /**
     * Update or create email settings
     */
    public static function updateSettings(array $data): self
    {
        $setting = static::find(1);

        if ($setting) {
            $setting->update($data);

            return $setting;
        }

        return static::create(array_merge($data, ['id' => 1]));
    }

    /**
     * Get mail configuration array for Laravel
     */
    public function getMailConfig(): array
    {
        return [
            'driver' => $this->mailer,
            'host' => $this->host,
            'port' => $this->port,
            'from' => [
                'address' => $this->from_address,
                'name' => $this->from_name,
            ],
            'encryption' => $this->encryption,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }

    /**
     * Set the DKIM private key (encrypted)
     */
    public function setDkimPrivateKeyAttribute($value): void
    {
        $this->attributes['dkim_private_key'] = $value ? encrypt($value) : null;
    }

    /**
     * Get the DKIM private key (decrypted)
     */
    public function getDkimPrivateKeyAttribute($value): ?string
    {
        return $value ? decrypt($value) : null;
    }
}
