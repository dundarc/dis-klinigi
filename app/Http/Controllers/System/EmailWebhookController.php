<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\EmailBounce;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmailWebhookController extends Controller
{
    /**
     * Handle bounce webhook
     */
    public function bounce(Request $request): JsonResponse
    {
        $payload = $request->all();
        $provider = $payload['provider'] ?? null;

        // Verify webhook signature based on provider
        if (!$this->verifyWebhookSignature($request, $provider)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        try {
            // Parse bounce data (this would vary by provider)
            $email = $payload['email'] ?? null;
            $bounceType = $payload['bounce_type'] ?? 'other';
            $provider = $payload['provider'] ?? null;
            $rawPayload = json_encode($payload);
            $occurredAt = isset($payload['occurred_at']) ? carbon($payload['occurred_at']) : now();

            if (!$email) {
                return response()->json(['error' => 'Email not provided'], 400);
            }

            // Find related email log if possible
            $emailLogId = null;
            if (isset($payload['message_id'])) {
                $emailLog = EmailLog::where('message_id', $payload['message_id'])->first();
                $emailLogId = $emailLog?->id;
            }

            // Create bounce record
            EmailBounce::create([
                'email' => $email,
                'bounce_type' => $bounceType,
                'provider' => $provider,
                'raw_payload' => $rawPayload,
                'occurred_at' => $occurredAt,
                'email_log_id' => $emailLogId,
            ]);

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            // Log error but don't fail the webhook
            \Log::error('Bounce webhook processing failed: ' . $e->getMessage());

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Verify webhook signature based on provider
     */
    private function verifyWebhookSignature(Request $request, ?string $provider): bool
    {
        // Skip verification in development
        if (app()->environment(['local', 'development'])) {
            return true;
        }

        switch ($provider) {
            case 'mailgun':
                return $this->verifyMailgunSignature($request);
            case 'sendgrid':
                return $this->verifySendGridSignature($request);
            case 'ses':
                return $this->verifySESSignature($request);
            default:
                // For unknown providers, skip verification but log warning
                \Log::warning('Unknown webhook provider, skipping signature verification', [
                    'provider' => $provider,
                    'ip' => $request->ip(),
                ]);
                return true;
        }
    }

    private function verifyMailgunSignature(Request $request): bool
    {
        $signature = $request->header('X-Mailgun-Signature');
        $timestamp = $request->header('X-Mailgun-Timestamp');
        $token = $request->header('X-Mailgun-Token');

        if (!$signature || !$timestamp || !$token) {
            return false;
        }

        // Get webhook signing key from settings (you'd store this in email settings)
        $signingKey = config('services.mailgun.webhook_signing_key');

        if (!$signingKey) {
            \Log::error('Mailgun webhook signing key not configured');
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $timestamp . $token, $signingKey);

        return hash_equals($expectedSignature, $signature);
    }

    private function verifySendGridSignature(Request $request): bool
    {
        // SendGrid uses different verification methods
        // This is a simplified implementation
        $signature = $request->header('X-Twilio-Email-Event-Webhook-Signature');
        $timestamp = $request->header('X-Twilio-Email-Event-Webhook-Timestamp');

        if (!$signature || !$timestamp) {
            return false;
        }

        // Implementation would depend on your SendGrid setup
        return true; // Placeholder
    }

    private function verifySESSignature(Request $request): bool
    {
        // SES webhook verification
        // This would involve SNS message verification
        return true; // Placeholder - implement proper SNS verification
    }
}