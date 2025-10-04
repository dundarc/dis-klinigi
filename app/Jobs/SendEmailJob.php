<?php

namespace App\Jobs;

use App\Models\EmailLog;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public array $backoff = [60, 300, 900]; // 1min, 5min, 15min

    public int $emailLogId;
    public array $attachments;

    /**
     * Create a new job instance.
     */
    public function __construct(int $emailLogId, array $attachments = [])
    {
        $this->emailLogId = $emailLogId;
        $this->attachments = $attachments;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $log = EmailLog::find($this->emailLogId);

        if (!$log) {
            Log::error("EmailLog not found: {$this->emailLogId}");
            throw new \Exception("EmailLog not found: {$this->emailLogId}");
        }

        try {
            // Configure mail settings from DB
            EmailService::configureFromDb();

            // Send the email
            $messageId = EmailService::sendFromLog($log, $this->attachments);

            // Update log as sent
            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
                'message_id' => $messageId,
            ]);

        } catch (\Exception $e) {
            // Update log as failed
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            // Log the error
            Log::error("Email send failed for log {$this->emailLogId}: " . $e->getMessage());

            // Re-throw to mark job as failed
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("SendEmailJob failed: " . $exception->getMessage());
    }
}
