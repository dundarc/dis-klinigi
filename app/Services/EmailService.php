<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\EmailSetting;
use App\Models\EmailTemplate;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
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
        $settings = EmailSetting::first();

        if (!$settings) {
            throw new \Exception('Email settings not found');
        }

        // Override Laravel mail config at runtime
        config([
            'mail.default' => $settings->mailer,
            'mail.mailers.smtp.host' => $settings->host,
            'mail.mailers.smtp.port' => $settings->port,
            'mail.mailers.smtp.username' => $settings->username,
            'mail.mailers.smtp.password' => $settings->password,
            'mail.mailers.smtp.encryption' => $settings->encryption,
            'mail.from.address' => $settings->from_address,
            'mail.from.name' => $settings->from_name,
        ]);

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

        $subject = self::replacePlaceholders($template->subject, $data);
        $html = self::replacePlaceholders($template->body_html, $data);
        $text = $template->body_text ? self::replacePlaceholders($template->body_text, $data) : null;

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
     */
    public static function sendFromLog(EmailLog $log, array $attachments = []): string
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
        $settings = EmailSetting::first();

        // Create transport
        $transport = new EsmtpTransport(
            $settings->host,
            $settings->port,
            $settings->encryption === 'tls'
        );

        if ($settings->username && $settings->password) {
            $transport->setUsername($settings->username);
            $transport->setPassword($settings->password);
        }

        $mailer = new SymfonyMailer($transport);

        // Create email
        $email = (new Email())
            ->from(new Address($settings->from_address, $settings->from_name ?: ''))
            ->to(new Address($to, $toName ?: ''))
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

        return $email->getHeaders()->get('Message-ID')?->getBody();
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
        // Simple implementation - block if hard bounce in last 30 days
        return \App\Models\EmailBounce::where('email', $email)
            ->where('bounce_type', 'hard')
            ->where('occurred_at', '>=', now()->subDays(30))
            ->exists();
    }
}