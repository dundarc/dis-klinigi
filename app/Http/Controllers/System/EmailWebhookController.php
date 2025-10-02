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
        // This is a generic implementation
        // In real-world, you'd verify signatures for specific providers

        $payload = $request->all();

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
}