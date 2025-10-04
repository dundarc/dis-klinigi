<?php

namespace App\Services\Kvkk;

use App\Enums\ConsentStatus;
use App\Models\Consent;
use App\Models\Patient;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ConsentService
{
    public static function hasActive(int|Patient $patient): bool
    {
        $patient = self::resolvePatient($patient);

        if (!$patient) {
            return false;
        }

        if ($patient->relationLoaded('consents')) {
            return $patient->consents->contains(fn ($consent) => $consent->status === ConsentStatus::ACTIVE || $consent->status === 'accepted');
        }

        return Consent::query()
            ->where('patient_id', $patient->id)
            ->whereIn('status', [ConsentStatus::ACTIVE->value, 'accepted'])
            ->exists();
    }

    public static function latest(int|Patient $patient): ?Consent
    {
        $patient = self::resolvePatient($patient);

        if (!$patient) {
            return null;
        }

        if ($patient->relationLoaded('consents')) {
            return $patient->consents->first();
        }

        return Consent::query()
            ->where('patient_id', $patient->id)
            ->orderByDesc('accepted_at')
            ->first();
    }

    public static function activeConsent(int|Patient $patient): ?Consent
    {
        $patient = self::resolvePatient($patient);

        if (!$patient) {
            return null;
        }

        if ($patient->relationLoaded('consents')) {
            return $patient->consents->first(fn ($consent) => $consent->status === ConsentStatus::ACTIVE || $consent->status === 'accepted');
        }

        return Consent::query()
            ->where('patient_id', $patient->id)
            ->whereIn('status', [ConsentStatus::ACTIVE->value, 'accepted'])
            ->orderByDesc('accepted_at')
            ->first();
    }

    public static function register(Patient $patient, array $data): Consent
    {
        return DB::transaction(function () use ($patient, $data) {
            $status = Arr::get($data, 'status', ConsentStatus::ACTIVE);

            // Only check for active consent if we're creating an active consent
            if ($status === ConsentStatus::ACTIVE) {
                $activeConsent = self::activeConsent($patient);
                if ($activeConsent) {
                    return $activeConsent;
                }
            }

            $snapshot = self::normalizeSnapshot(Arr::get($data, 'snapshot', []));
            // Use the actual request IP, not from meta data
            $ipAddress = Request::ip();
            $userAgent = Request::userAgent();
            $acceptedAt = $status === ConsentStatus::ACTIVE ? CarbonImmutable::now() : null;

            $consent = Consent::query()->create([
                'patient_id' => $patient->id,
                'type' => Arr::get($data, 'type', 'kvkk'),
                'title' => Arr::get($data, 'title', 'KVKK Aydınlatma Metni'),
                'content' => Arr::get($data, 'content', ''),
                'version' => Arr::get($data, 'version', '1.0'),
                'status' => $status,
                'consent_method' => Arr::get($data, 'consent_method'),
                'verification_token' => Arr::get($data, 'verification_token'),
                'accepted_at' => $acceptedAt,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'snapshot' => $snapshot,
                'hash' => self::generateSnapshotHash($snapshot),
                'signature_path' => Arr::get($data, 'signature_path'),
            ]);

            // Log the consent creation
            \App\Models\KvkkAuditLog::create([
                'patient_id' => $patient->id,
                'user_id' => Arr::get($data, 'user_id', auth()->id()),
                'action' => \App\Enums\KvkkAuditAction::CREATE_CONSENT,
                'ip_address' => $ipAddress,
                'meta' => [
                    'consent_id' => $consent->id,
                    'consent_version' => $consent->version,
                    'consent_method' => $consent->consent_method,
                    'status' => $consent->status->value,
                    'created_at' => $consent->created_at,
                ],
            ]);

            return $consent;
        });
    }

    public static function cancel(Consent $consent): Consent
    {
        if ($consent->status === ConsentStatus::CANCELED) {
            return $consent;
        }

        $consent->status = ConsentStatus::CANCELED;
        $consent->canceled_at = CarbonImmutable::now();
        $consent->save();

        return $consent->fresh();
    }

    public static function cancelActive(int|Patient $patient, $user = null): bool
    {
        $patient = self::resolvePatient($patient);

        if (!$patient) {
            return false;
        }

        $activeConsents = $patient->consents->where('status', ConsentStatus::ACTIVE);

        if ($activeConsents->isEmpty()) {
            return false;
        }

        foreach ($activeConsents as $consent) {
            self::cancel($consent);

            // Log the cancellation if user is provided
            if ($user) {
                \App\Models\KvkkAuditLog::create([
                    'patient_id' => $patient->id,
                    'user_id' => $user->id,
                    'action' => \App\Enums\KvkkAuditAction::CANCEL_CONSENT,
                    'ip_address' => request()->ip(),
                    'meta' => [
                        'consent_id' => $consent->id,
                        'consent_version' => $consent->version,
                        'canceled_at' => now(),
                    ],
                ]);
            }
        }

        return true;
    }

    public static function generateSnapshotHash(array $snapshot): string
    {
        return hash('sha256', json_encode($snapshot, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Send email verification for consent
     */
    public static function sendEmailVerification(Consent $consent): void
    {
        if (!$consent->patient->email) {
            throw new \InvalidArgumentException('Patient has no email address');
        }

        $consent->update([
            'email_sent_at' => now(),
        ]);

        $verificationUrl = route('kvkk.verify-consent', $consent->verification_token);

        // Send the actual email
        \Illuminate\Support\Facades\Mail::to($consent->patient->email)->send(
            new \App\Mail\KvkkConsentVerification($consent, $verificationUrl)
        );

        \Illuminate\Support\Facades\Log::info('KVKK consent verification email sent', [
            'consent_id' => $consent->id,
            'patient_email' => $consent->patient->email,
            'verification_url' => $verificationUrl,
        ]);
    }

    /**
     * Verify consent via email token
     */
    public static function verifyEmailConsent(string $token): Consent
    {
        $consent = Consent::where('verification_token', $token)
            ->where('consent_method', 'email_verification')
            ->where('status', ConsentStatus::PENDING)
            ->whereNull('email_verified_at')
            ->first();

        if (!$consent) {
            throw new \InvalidArgumentException('Invalid verification token');
        }

        $consent->update([
            'status' => ConsentStatus::ACTIVE,
            'accepted_at' => now(),
            'email_verified_at' => now(),
        ]);

        return $consent;
    }

    private static function normalizeSnapshot(array $snapshot): array
    {
        ksort($snapshot);

        foreach ($snapshot as $key => $value) {
            if (is_array($value)) {
                $snapshot[$key] = self::normalizeSnapshot($value);
            }
        }

        return $snapshot;
    }

    public static function initiateCancellation(Consent $consent): Consent
    {
        if ($consent->status !== ConsentStatus::ACTIVE) {
            throw new \InvalidArgumentException('Only active consents can be canceled.');
        }

        $consent->cancellation_pdf_generated_at = now();
        $consent->save();

        return $consent->fresh();
    }

    public static function markPdfDownloaded(Consent $consent): Consent
    {
        $consent->cancellation_pdf_downloaded_at = now();
        $consent->save();

        return $consent->fresh();
    }

    public static function cancelWithAudit(Consent $consent, $user): Consent
    {
        if (!$consent->cancellation_pdf_downloaded_at) {
            throw new \InvalidArgumentException('PDF must be downloaded before canceling consent.');
        }

        $canceledConsent = self::cancel($consent);

        // Log the cancellation
        \DB::table('kvkk_cancellation_logs')->insert([
            'consent_id' => $consent->id,
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'canceled_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $canceledConsent;
    }

    private static function resolvePatient(int|Patient $patient): ?Patient
    {
        if ($patient instanceof Patient) {
            if (!$patient->relationLoaded('consents')) {
                $patient->loadMissing(['consents' => fn ($query) => $query->orderByDesc('accepted_at')]);
            }

            return $patient;
        }

        return Patient::with(['consents' => fn ($query) => $query->orderByDesc('accepted_at')])->find($patient);
    }
}
