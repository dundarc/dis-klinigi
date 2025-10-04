<?php

namespace App\Services;

use App\Mail\Automatic\EmergencyPatientNotificationMail;
use App\Mail\Automatic\KvkkConsentNotificationMail;
use App\Mail\Automatic\PatientCheckinNotificationMail;
use App\Models\Appointment;
use App\Models\Consent;
use App\Models\EmailAutomationSetting;
use App\Models\EmailLog;
use App\Models\Encounter;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Exception\RfcComplianceException;
use Throwable;

class AutomaticEmailService
{
    private ?EmailAutomationSetting $settings = null;
    private ?string $clinicName = null;
    private ?string $clinicEmail = null;

    public function sendPatientCheckin(Appointment $appointment, bool $forceSend = false): ?EmailLog
    {
        if (!$forceSend && !$this->isEnabled('patient_checkin_to_dentist')) {
            return null;
        }

        $appointment->loadMissing(['patient', 'dentist']);

        $dentist = $appointment->dentist;
        $patient = $appointment->patient;

        if (!$dentist || !$dentist->email || !$patient) {
            return null;
        }

        $mail = new PatientCheckinNotificationMail(
            patientName: $this->formatPersonName($patient->first_name, $patient->last_name),
            dentistName: $dentist->name,
            appointmentTime: optional($appointment->start_at)->format('d.m.Y H:i') ?? now()->format('d.m.Y H:i'),
            clinicName: $this->getClinicName()
        );

        return $this->sendMail(
            mail: $mail,
            toEmail: $dentist->email,
            toName: $dentist->name,
            templateKey: 'automatic.patient_checkin'
        );
    }

    public function sendPatientCheckinTest(object $dentist): ?EmailLog
    {
        if (!$dentist->email) {
            return null;
        }

        $mail = new PatientCheckinNotificationMail(
            patientName: 'Test Hastası',
            dentistName: $dentist->name ?? 'Diş Hekimi',
            appointmentTime: now()->addMinutes(15)->format('d.m.Y H:i'),
            clinicName: $this->getClinicName()
        );

        return $this->sendMail(
            mail: $mail,
            toEmail: $dentist->email,
            toName: $dentist->name,
            templateKey: 'automatic.patient_checkin.test',
            forceSend: true
        );
    }

    public function sendEmergencyPatient(Encounter $encounter, bool $forceSend = false): ?EmailLog
    {
        if (!$forceSend && !$this->isEnabled('emergency_patient_to_dentist')) {
            return null;
        }

        $encounter->loadMissing(['patient', 'dentist']);

        $dentist = $encounter->dentist;
        $patient = $encounter->patient;

        if (!$dentist || !$dentist->email || !$patient) {
            return null;
        }

        $mail = new EmergencyPatientNotificationMail(
            patientName: $this->formatPersonName($patient->first_name, $patient->last_name),
            dentistName: $dentist->name,
            registrationTime: optional($encounter->arrived_at)->format('d.m.Y H:i') ?? now()->format('d.m.Y H:i'),
            clinicName: $this->getClinicName(),
            triageLevel: optional($encounter->triage_level)->label()
        );

        return $this->sendMail(
            mail: $mail,
            toEmail: $dentist->email,
            toName: $dentist->name,
            templateKey: 'automatic.emergency_patient'
        );
    }

    public function sendEmergencyPatientTest(object $dentist): ?EmailLog
    {
        if (!$dentist->email) {
            return null;
        }

        $mail = new EmergencyPatientNotificationMail(
            patientName: 'Test Hastası',
            dentistName: $dentist->name ?? 'Diş Hekimi',
            registrationTime: now()->format('d.m.Y H:i'),
            clinicName: $this->getClinicName(),
            triageLevel: 'Acil'
        );

        return $this->sendMail(
            mail: $mail,
            toEmail: $dentist->email,
            toName: $dentist->name,
            templateKey: 'automatic.emergency_patient.test',
            forceSend: true
        );
    }

    public function sendKvkkConsent(Consent $consent, bool $forceSend = false): ?EmailLog
    {
        if (!$forceSend && !$this->isEnabled('kvkk_consent_to_admin')) {
            return null;
        }

        $consent->loadMissing('patient');

        $patient = $consent->patient;
        if (!$patient) {
            return null;
        }

        $toEmail = $this->getClinicEmail();
        if (!$toEmail) {
            return null;
        }

        $mail = new KvkkConsentNotificationMail(
            patientName: $this->formatPersonName($patient->first_name, $patient->last_name),
            consentDate: optional($consent->accepted_at)->format('d.m.Y H:i') ?? now()->format('d.m.Y H:i'),
            clinicName: $this->getClinicName(),
            recipientName: null
        );

        return $this->sendMail(
            mail: $mail,
            toEmail: $toEmail,
            toName: null,
            templateKey: 'automatic.kvkk_consent'
        );
    }

    public function sendKvkkConsentTest(object $admin): ?EmailLog
    {
        if (!$admin->email) {
            return null;
        }

        $mail = new KvkkConsentNotificationMail(
            patientName: 'Test Hastası',
            consentDate: now()->format('d.m.Y H:i'),
            clinicName: $this->getClinicName(),
            recipientName: $admin->name
        );

        return $this->sendMail(
            mail: $mail,
            toEmail: $admin->email,
            toName: $admin->name,
            templateKey: 'automatic.kvkk_consent.test',
            forceSend: true
        );
    }
    private function sendMail(
        Mailable $mail,
        string $toEmail,
        ?string $toName,
        string $templateKey,
        bool $forceSend = false
    ): EmailLog {

        $bodyHtml = $mail->render();
        $bodyText = $this->normalizeWhitespace(strip_tags($bodyHtml));
        $subject = $mail->subject ?? config('app.name', 'Klinik Bildirimi');
        $snippet = Str::limit($bodyText, 200);
        $sanitizedToName = $this->sanitizeRecipientName($toName);

        if (!$forceSend) {
            try {
                EmailService::configureFromDb();
            } catch (Throwable $e) {
                Log::warning('Automatic email configuration could not be loaded', [
                    'template_key' => $templateKey,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $originalFromAddress = config('mail.from.address');
        $originalFromName = config('mail.from.name');
        [$fromAddress, $fromName, $usedFallbackFrom] = $this->resolveSenderDetails($originalFromAddress, $originalFromName);

        if ($usedFallbackFrom) {
            Log::warning('Automatic email falling back to default from address', [
                'template_key' => $templateKey,
                'configured_from' => $originalFromAddress,
            ]);
        }

        config([
            'mail.from.address' => $fromAddress,
            'mail.from.name' => $fromName,
        ]);

        app()->forgetInstance('mail.manager');
        app()->forgetInstance('mailer');

        $mail->from($fromAddress, $fromName);

        // Check if email should be blocked due to bounces
        if (EmailService::shouldBlock($toEmail)) {
            $status = 'blocked';
            $sentAt = null;
            $errorMessage = 'E-posta bounce nedeniyle engellendi: ' . $toEmail;

            Log::warning('Automatic email blocked due to bounce history', [
                'template_key' => $templateKey,
                'recipient' => $toEmail,
                'recipient_name' => $sanitizedToName,
            ]);

            return EmailLog::create([
                'template_key' => $templateKey,
                'to_email' => $toEmail,
                'to_name' => $sanitizedToName,
                'subject' => $subject,
                'body_html' => $bodyHtml,
                'body_text' => $bodyText,
                'body_snippet' => $snippet,
                'status' => $status,
                'error_message' => $errorMessage,
                'sent_at' => $sentAt,
                'queued_at' => now(),
            ]);
        }

        // NUCLEAR VALIDATION - Absolutely prevent invalid emails from being sent
        $originalToEmail = $toEmail;
        $trimmedEmail = trim($toEmail);

        // Check 1: Must have @ symbol
        if (strpos($trimmedEmail, '@') === false) {
            Log::error('CRITICAL: Email missing @ symbol', [
                'template_key' => $templateKey,
                'invalid_email' => $trimmedEmail,
                'recipient_name' => $sanitizedToName,
            ]);
            $toEmail = 'fallback@example.com';
        }

        // Check 2: Must not contain spaces
        elseif (preg_match('/\s/', $trimmedEmail)) {
            Log::error('CRITICAL: Email contains spaces (likely a name)', [
                'template_key' => $templateKey,
                'invalid_email' => $trimmedEmail,
                'recipient_name' => $sanitizedToName,
            ]);
            $toEmail = 'fallback@example.com';
        }

        // Check 3: Must pass PHP filter_var validation
        elseif (filter_var($trimmedEmail, FILTER_VALIDATE_EMAIL) === false) {
            Log::error('CRITICAL: Email fails PHP validation', [
                'template_key' => $templateKey,
                'invalid_email' => $trimmedEmail,
                'recipient_name' => $sanitizedToName,
            ]);
            $toEmail = 'fallback@example.com';
        }

        // Check 4: Reasonable length
        elseif (strlen($trimmedEmail) < 5 || strlen($trimmedEmail) > 254) {
            Log::error('CRITICAL: Email length unreasonable', [
                'template_key' => $templateKey,
                'invalid_email' => $trimmedEmail,
                'length' => strlen($trimmedEmail),
                'recipient_name' => $sanitizedToName,
            ]);
            $toEmail = 'fallback@example.com';
        }

        // If email was changed to fallback, log it
        if ($toEmail !== $originalToEmail && $toEmail === 'fallback@example.com') {
            Log::warning('Email changed to fallback due to validation failure', [
                'template_key' => $templateKey,
                'original_email' => $originalToEmail,
                'fallback_email' => $toEmail,
                'recipient_name' => $sanitizedToName,
            ]);
        }

        Log::info('Recipient email validation completed', [
            'template_key' => $templateKey,
            'original_recipient' => $originalToEmail,
            'final_recipient' => $toEmail,
            'recipient_name' => $sanitizedToName,
        ]);

        // ABSOLUTE FINAL SAFETY CHECK - Triple verification before sending
        $finalTrimmed = trim($toEmail);

        // Emergency validation - if ANY of these fail, use fallback
        if (strpos($finalTrimmed, '@') === false ||
            preg_match('/\s/', $finalTrimmed) ||
            filter_var($finalTrimmed, FILTER_VALIDATE_EMAIL) === false ||
            strlen($finalTrimmed) < 5 ||
            strlen($finalTrimmed) > 254) {

            Log::critical('EMERGENCY SAFETY CHECK ACTIVATED - Invalid email blocked', [
                'template_key' => $templateKey,
                'blocked_email' => $toEmail,
                'recipient_name' => $sanitizedToName,
                'validation_checks' => [
                    'has_at' => strpos($finalTrimmed, '@') !== false,
                    'no_spaces' => !preg_match('/\s/', $finalTrimmed),
                    'php_filter' => filter_var($finalTrimmed, FILTER_VALIDATE_EMAIL) !== false,
                    'length_ok' => strlen($finalTrimmed) >= 5 && strlen($finalTrimmed) <= 254,
                ],
                'using_fallback' => 'fallback@example.com'
            ]);

            // Force fallback email
            $toEmail = 'fallback@example.com';
        }

        $status = 'sent';
        $sentAt = now();
        $errorMessage = null;


        // FINAL ABSOLUTE CHECK - If email is still invalid, throw exception
        if (strpos($toEmail, '@') === false ||
            preg_match('/\s/', $toEmail) ||
            filter_var($toEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new \InvalidArgumentException('Email validation failed: ' . $toEmail . ' does not comply with RFC 2822');
        }

        Log::info('Dispatching automatic email', [
            'template_key' => $templateKey,
            'recipient' => $toEmail,
            'recipient_name' => $sanitizedToName,
        ]);

        try {
            $this->deliverMail($mail, $toEmail, $sanitizedToName);
        } catch (Throwable $e) {
            $status = 'failed';
            $sentAt = null;
            $errorMessage = $e->getMessage();

            Log::error('Automatic email dispatch failed', [
                'template_key' => $templateKey,
                'recipient' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }

        return EmailLog::create([
            'template_key' => $templateKey,
            'to_email' => $toEmail,
            'to_name' => $sanitizedToName,
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
            'body_snippet' => $snippet,
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => $sentAt,
            'queued_at' => now(),
        ]);
    }

    private function deliverMail(Mailable $mail, string $toEmail, ?string $recipientName): void
    {
        if ($recipientName !== null) {
            try {
                Mail::to(new Address($toEmail, $recipientName))->send($mail);

                return;
            } catch (RfcComplianceException $exception) {
                Log::warning('Recipient name failed RFC compliance, retrying without display name', [
                    'recipient' => $toEmail,
                    'recipient_name' => $recipientName,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        Mail::to($toEmail)->send($mail);
    }

    private function isEnabled(string $key): bool
    {
        $settings = $this->getSettings();
        return (bool) data_get($settings, $key, false);
    }

    private function getSettings(): EmailAutomationSetting
    {
        if ($this->settings === null) {
            $this->settings = EmailAutomationSetting::getSettings() ?? new EmailAutomationSetting([
                'patient_checkin_to_dentist' => false,
                'emergency_patient_to_dentist' => false,
                'kvkk_consent_to_admin' => false,
            ]);
        }

        return $this->settings;
    }

    private function getClinicName(): string
    {
        if ($this->clinicName !== null) {
            return $this->clinicName;
        }

        $raw = Setting::where('key', 'clinic_name')->value('value');
        $value = $this->normalizeSettingValue($raw);

        $this->clinicName = $value ?: (string) (config('app.name') ?? 'Klinik');
        return $this->clinicName;
    }

    private function getClinicEmail(): string
    {
        if ($this->clinicEmail !== null) {
            return $this->clinicEmail;
        }

        $raw = Setting::where('key', 'clinic_email')->value('value');
        $value = $this->normalizeSettingValue($raw);

            $value = trim($value, "\"' ");


        $this->clinicEmail = $value ?: 'admin@example.com';
        return $this->clinicEmail;
    }

    private function resolveSenderDetails(mixed $address, mixed $name): array
    {
        $fallbackUsed = false;

        $fromAddress = is_string($address) ? trim($address) : '';
        if ($fromAddress === '' || filter_var($fromAddress, FILTER_VALIDATE_EMAIL) === false) {
            $fallbackUsed = true;
            $fromAddress = 'noreply@localhost';
        }

        $sanitizedName = $this->sanitizeRecipientName(is_string($name) ? $name : null) ?? 'Klinik Sistemi';

        return [$fromAddress, $sanitizedName, $fallbackUsed];
    }

    private function sanitizeRecipientName(?string $name): ?string
    {
        if (!is_string($name)) {
            return null;
        }

        $normalized = preg_replace('/[\r\n]+/', ' ', $name);
        $normalized = trim((string) ($normalized ?? ''));

        return $normalized !== '' ? preg_replace('/\s+/', ' ', $normalized) : null;
    }

    private function normalizeSettingValue(mixed $raw): ?string
    {
        if (is_string($raw)) {
            $trimmed = trim($raw);
            return $trimmed !== '' ? $trimmed : null;
        }

        if (is_array($raw)) {
            $first = reset($raw);
            if (is_string($first)) {
                $trimmed = trim($first);
                return $trimmed !== '' ? $trimmed : null;
            }
        }

        if (is_object($raw)) {
            $arr = (array) $raw;
            $first = reset($arr);
            if (is_string($first)) {
                $trimmed = trim($first);
                return $trimmed !== '' ? $trimmed : null;
            }
        }

        return null;
    }

    private function normalizeWhitespace(string $text): string
    {
        $normalized = preg_replace('/\s+/', ' ', $text ?? '');
        return trim($normalized);
    }

    private function formatPersonName(?string $first, ?string $last): string
    {
        return trim(collect([$first, $last])->filter(fn($v) => filled($v))->implode(' ')) ?: 'Hasta';
    }
}
