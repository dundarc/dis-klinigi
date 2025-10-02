<?php

namespace App\Facades;

use App\Services\EmailService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Models\EmailLog sendTemplate(string $key, array $params)
 * @method static \App\Models\EmailLog queue(string $to, ?string $toName, string $subject, string $html, ?string $text = null, ?string $templateKey = null, array $attachments = [])
 * @method static \App\Models\EmailLog sendNow(string $to, ?string $toName, string $subject, string $html, ?string $text = null, ?string $templateKey = null, array $attachments = [])
 * @method static array renderTemplate(string $key, array $data)
 * @method static void configureFromDb()
 * @method static bool shouldBlock(string $email)
 */
class EmailFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EmailService::class;
    }
}