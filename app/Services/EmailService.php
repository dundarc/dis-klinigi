<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\EmailSetting;
use App\Models\EmailTemplate;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;
use Symfony\Component\Mailer\Transport\Smtp\Auth\LoginAuthenticator;
use Symfony\Component\Mailer\Transport\Smtp\Auth\PlainAuthenticator;
use Symfony\Component\Mailer\Transport\Smtp\Auth\CramMd5Authenticator;
use Symfony\Component\Mailer\Transport\Smtp\Auth\XOAuth2Authenticator;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mime\Crypto\DkimSigner;

class EmailService
{
    /**
     * Configure mail settings from database
     */
    public static function configureFromDb(): void
    {
        $settings = EmailSetting::getSettings();

        if (!$settings) {
            throw new \Exception('Email settings not configured. Please configure email settings first.');
        }

        $fromAddress = self::resolveFromAddress($settings->from_address);
        $fromName = self::resolveFromName($settings->from_name);

        // Override Laravel mail config at runtime
        config([
            'mail.default' => $settings->mailer,
            'mail.mailers.smtp.host' => $settings->host,
            'mail.mailers.smtp.port' => $settings->port,
            'mail.mailers.smtp.username' => $settings->username,
            'mail.mailers.smtp.password' => $settings->password,
            'mail.mailers.smtp.encryption' => $settings->encryption ?: null,
            'mail.mailers.smtp.stream' => (app()->environment(['local', 'development']) && $settings->skip_ssl_verification) ? [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ] : null,
            'mail.from.address' => $fromAddress,
            'mail.from.name' => $fromName,
        ]);

        app()->forgetInstance('mail.manager');
        app()->forgetInstance('mailer');

        // Configure DKIM if available
        if ($settings->dkim_domain && $settings->dkim_selector && $settings->dkim_private_key) {
            // DKIM signing will be handled in the job
        }
    }

    /**
     * Render email template with data
     */
    public static function renderTemplate(string $key, array $data): array
    {
        $template = EmailTemplate::findByKey($key);

        if (!$template) {
            throw new \Exception("Email template '{$key}' not found or inactive");
        }

        // Validate required placeholders
        self::validateRequiredPlaceholders($template->subject, $data, $key, 'subject');
        self::validateRequiredPlaceholders($template->body_html, $data, $key, 'body_html');
        if ($template->body_text) {
            self::validateRequiredPlaceholders($template->body_text, $data, $key, 'body_text');
        }

        $subject = self::replacePlaceholders($template->subject, $data);
        $html = self::replacePlaceholders($template->body_html, $data);
        $text = $template->body_text ? self::replacePlaceholders($template->body_text, $data) : strip_tags($html);

        return [
            'subject' => $subject,
            'html' => $html,
            'text' => $text,
        ];
    }

    /**
     * Replace placeholders in template
     */
    private static function replacePlaceholders(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $content = str_replace("{{{$key}}}", $value, $content);
        }
        return $content;
    }

    /**
     * Validate required placeholders in template content
     */
    private static function validateRequiredPlaceholders(string $content, array $data, string $templateKey, string $field): void
    {
        preg_match_all('/\{\{(\w+)\}\}/', $content, $matches);

        if (!empty($matches[1])) {
            $requiredPlaceholders = array_unique($matches[1]);
            $missingPlaceholders = [];

            foreach ($requiredPlaceholders as $placeholder) {
                if (!array_key_exists($placeholder, $data)) {
                    $missingPlaceholders[] = $placeholder;
                }
            }

            if (!empty($missingPlaceholders)) {
                throw new \Exception(
                    "Template '{$templateKey}' {$field} is missing required placeholders: " .
                    implode(', ', $missingPlaceholders)
                );
            }
        }
    }

    /**
     * Queue email for sending
     */
    public static function queue(
        string $to,
        ?string $toName,
        string $subject,
        string $html,
        ?string $text = null,
        ?string $templateKey = null,
        array $attachments = []
    ): EmailLog {
        // Create log entry with queued status
        $log = EmailLog::create([
            'template_key' => $templateKey,
            'to_email' => $to,
            'to_name' => $toName,
            'subject' => $subject,
            'body_html' => $html,
            'body_text' => $text,
            'body_snippet' => Str::limit(strip_tags($html), 200),
            'status' => 'queued',
            'queued_at' => now(),
        ]);

        // Dispatch job
        SendEmailJob::dispatch($log->id, $attachments);

        return $log;
    }

    /**
     * Send email immediately (synchronous)
     */
    public static function sendNow(
        string $to,
        ?string $toName,
        string $subject,
        string $html,
        ?string $text = null,
        ?string $templateKey = null,
        array $attachments = []
    ): EmailLog {
        self::configureFromDb();

        $log = EmailLog::create([
            'template_key' => $templateKey,
            'to_email' => $to,
            'to_name' => $toName,
            'subject' => $subject,
            'body_html' => $html,
            'body_text' => $text,
            'body_snippet' => Str::limit(strip_tags($html), 200),
            'status' => 'queued',
            'queued_at' => now(),
        ]);

        try {
            $messageId = self::sendMail($to, $toName, $subject, $html, $text, $attachments);

            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
                'message_id' => $messageId,
            ]);
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }

        return $log;
    }

    /**
     * Send email from log entry
     *
     * @return string|null Message ID when provided by the transport
     */
    public static function sendFromLog(EmailLog $log, array $attachments = []): ?string
    {
        self::configureFromDb();

        return self::sendMail(
            $log->to_email,
            $log->to_name,
            $log->subject,
            $log->body_html,
            $log->body_text,
            $attachments
        );
    }

    /**
     * Send mail using Symfony Mailer with DKIM support
     */
    private static function sendMail(
        string $to,
        ?string $toName,
        string $subject,
        string $html,
        ?string $text,
        array $attachments
    ): ?string {
        $settings = EmailSetting::getSettings();

        if (!$settings) {
            throw new \Exception('Email settings not found');
        }

        // Create transport
        $encryption = strtolower((string) ($settings->encryption ?? ''));

        $useTls = in_array($encryption, ['tls', 'ssl'], true);

        $stream = null;
        if ($settings->skip_ssl_verification) {
            $stream = new SocketStream();
            $stream->setStreamOptions([
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
        }

        $transport = new EsmtpTransport(
            $settings->host,
            $settings->port,
            $useTls,
            null,
            null,
            $stream
        );

        if ($encryption === 'ssl') {
            $transport->setAutoTls(false);
            $transport->setRequireTls(true);
        } elseif ($encryption === 'tls') {
            $transport->setAutoTls(true);
            $transport->setRequireTls(true);
        } else {
            $transport->setAutoTls(false);
            $transport->setRequireTls(false);
        }

        if ($settings->username && $settings->password) {
            $transport->setUsername($settings->username);
            $transport->setPassword($settings->password);
        }

        $mailer = new SymfonyMailer($transport);

        $fromAddress = self::resolveFromAddress($settings->from_address);
        $fromName = self::resolveFromName($settings->from_name);
        $toAddress = self::sanitizeEmail($to);

        if ($toAddress === null) {
            throw new \InvalidArgumentException('Invalid recipient email address: ' . $to);
        }

        // Double-check that from address is valid
        if (!filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
            Log::error('Resolved from address is still invalid, using emergency fallback', [
                'original_from' => $settings->from_address,
                'resolved_from' => $fromAddress,
            ]);
            $fromAddress = 'noreply@localhost';
        }

        // Additional validation - ensure it's not a name
        if (preg_match('/\s/', $fromAddress) || !strpos($fromAddress, '@')) {
            Log::error('From address appears to be a name instead of email, forcing emergency fallback', [
                'suspect_from_address' => $fromAddress,
            ]);
            $fromAddress = 'noreply@localhost';
        }

        // Create email
        $email = (new Email())
            ->from(new Address($fromAddress, $fromName ?: ''))
            ->to(new Address($toAddress, $toName ?: ''))
            ->subject($subject)
            ->html($html);

        if ($text) {
            $email->text($text);
        }

        // Add attachments
        foreach ($attachments as $attachment) {
            if (is_string($attachment)) {
                $email->attachFromPath($attachment);
            }
        }

        // DKIM signing
        if ($settings->dkim_domain && $settings->dkim_selector && $settings->dkim_private_key) {
            $signer = new DkimSigner(
                $settings->dkim_private_key,
                $settings->dkim_domain,
                $settings->dkim_selector
            );
            $email = $signer->sign($email);
        }

        $mailer->send($email);

        $messageId = $email->getHeaders()->get('Message-ID')?->getBody();

        // Fallback Message-ID if not provided by transport
        if (!$messageId) {
            $messageId = '<' . uniqid('email_', true) . '@' . parse_url(config('app.url'), PHP_URL_HOST) . '>';
        }

        return $messageId;
    }

    private static function sanitizeEmail(?string $email): ?string
    {
        if (!is_string($email)) {
            return null;
        }

        $trimmed = trim($email);

        if ($trimmed === '') {
            return null;
        }

        return filter_var($trimmed, FILTER_VALIDATE_EMAIL) ? $trimmed : null;
    }

    public static function resolveFromAddress(?string $email, bool $logFallback = true): string
    {
        $sanitized = self::sanitizeEmail($email);

        if ($sanitized !== null) {
            return $sanitized;
        }

        if ($logFallback) {
            Log::warning('Invalid from address detected; using fallback.', [
                'from_address' => $email,
                'provided_value' => $email,
                'sanitized_result' => $sanitized,
            ]);
        }

        // Try config fallback first
        $configAddress = self::sanitizeEmail(config('mail.from.address'));
        if ($configAddress !== null) {
            return $configAddress;
        }

        // Last resort - generate a valid fallback based on app URL
        $domain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
        $fallback = "noreply@{$domain}";

        // Ensure the fallback is valid
        if (!filter_var($fallback, FILTER_VALIDATE_EMAIL)) {
            $fallback = 'noreply@localhost';
        }

        return $fallback;
    }

    public static function resolveFromName(?string $name): string
    {
        if (is_string($name)) {
            $trimmed = trim($name);

            if ($trimmed !== '') {
                return $trimmed;
            }
        }

        $fallback = config('mail.from.name');

        if (is_string($fallback)) {
            $fallback = trim($fallback);

            if ($fallback !== '') {
                return $fallback;
            }
        }

        $appName = config('app.name');

        if (is_string($appName)) {
            $appName = trim($appName);

            if ($appName !== '') {
                return $appName;
            }
        }

        return 'Klinik';
    }

    /**
     * Send template email
     */
    public static function sendTemplate(string $key, array $params): EmailLog
    {
        $data = $params['data'] ?? [];
        $attachments = $params['attachments'] ?? [];

        $rendered = self::renderTemplate($key, $data);

        return self::queue(
            $params['to'],
            $params['to_name'] ?? null,
            $rendered['subject'],
            $rendered['html'],
            $rendered['text'],
            $key,
            $attachments
        );
    }

    /**
     * Check if email should be blocked based on bounces
     */
    public static function shouldBlock(string $email): bool
    {
        // Block if hard or soft bounce in last 30 days
        return \App\Models\EmailBounce::where('email', $email)
            ->whereIn('bounce_type', ['hard', 'soft'])
            ->where('occurred_at', '>=', now()->subDays(30))
            ->exists();
    }
}