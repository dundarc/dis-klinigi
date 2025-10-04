<?php

namespace App\Services;

use App\Models\EmailSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class DynamicMailService
{
    /**
     * Configure mail settings from database
     */
    public static function configureMail(): bool
    {
        $settings = EmailSetting::getSettings();

        if (!$settings) {
            return false;
        }

        // Override mail configuration
        Config::set('mail.default', $settings->mailer);

        $smtpConfig = [
            'transport' => 'smtp',
            'host' => $settings->host,
            'port' => $settings->port,
            'encryption' => $settings->encryption ?: null,
            'username' => $settings->username,
            'password' => $settings->password,
            'timeout' => null,
            'auth_mode' => null,
        ];

        if ($settings->skip_ssl_verification) {
            $smtpConfig['stream'] = [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ];
        }

        Config::set('mail.mailers.smtp', $smtpConfig);

        $fromAddress = EmailService::resolveFromAddress($settings->from_address);
        $fromName = EmailService::resolveFromName($settings->from_name);

        Config::set('mail.from', [
            'address' => $fromAddress,
            'name' => $fromName,
        ]);

        // Reinitialize mail manager with new config
        app()->forgetInstance('mail.manager');
        app()->forgetInstance('mailer');

        return true;
    }

    /**
     * Send email with dynamic configuration
     */
    public static function send($view, $data, $callback)
    {
        // Configure mail settings
        if (!self::configureMail()) {
            throw new \Exception('E-posta ayarları bulunamadı.');
        }

        // Send email
        return Mail::send($view, $data, $callback);
    }

    /**
     * Send raw email with dynamic configuration
     */
    public static function raw($text, $callback)
    {
        // Configure mail settings
        if (!self::configureMail()) {
            throw new \Exception('E-posta ayarları bulunamadı.');
        }

        // Send raw email
        return Mail::raw($text, $callback);
    }

    /**
     * Send email to specific recipient with dynamic configuration
     */
    public static function to($users, $view = null, $data = [], $callback = null)
    {
        // Configure mail settings
        if (!self::configureMail()) {
            throw new \Exception('E-posta ayarları bulunamadı.');
        }

        // Send email
        return Mail::to($users)->send($view, $data, $callback);
    }
}